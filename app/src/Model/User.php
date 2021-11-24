<?php

namespace App\Model;

class User
{

    private array $options = [
        'restriction_msg' => "Vous devez être connecté pour accéder à cette page"
    ];
    private $session;

        public function __construct($session = null, $options = [])
    {
        $this->options = array_merge($this->options, $options);
        $this->session = $session;
    }

    /**
     * Hash a password who has given and crypt with bcrypt
     * @param $password
     * @return string
     */
    public function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Insert user datas to the database
     * @param $db
     * @param $firstname
     * @param $lastname
     * @param $username
     * @param $password
     * @param $email
     * @throws \Exception
     */
    public function register($db, $firstname, $lastname, $username, $password, $email)
    {
        $password = $this->hashPassword($password);
        $bytes = random_bytes(30);
        $token = (bin2hex($bytes));
        $db->query(
            'INSERT INTO user(
                    firstname, lastname, username, email, password, is_active, confirm_token)
                    VALUES(:firstname, :lastname, :username, :email, :password, :is_active, :confirm_token)',
            [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'is_active' => true,
            'confirm_token' => $token
            ]
        );
        $user_id = $db->lastInsertId();
        mail(
            $email,
            'Confirmation de votre compte',
            "Merci de cliquer sur le lien ci-dessous pour valider votre compte\n\n
            http://localhost/index.php?c=user&a=confirm&id=$user_id&token=$token"
        );
    }

    /**
     * @param $db
     * @param $user_id
     * @param $token
     * @param $session
     * @return bool
     */
    public function confirm($db, $user_id, $token)
    {
        $user = $db->query('SELECT * FROM user WHERE id = ?', [$user_id])->fetch();
        if ($user && $user->confirm_token == $token) {
            $db->query('UPDATE user SET confirm_token = NULL, confirmed_at = NOW() WHERE id = ?', [$user_id]);
            $this->session->write('auth', $user);
            return true;
        } else {
            return false;
        }
    }

    /**
     * restrict page for connected user only
     */
    public function isLogged()
    {
        if ($this->session->read('auth')) {
            return true;
        }
        return false;
    }

    public function getUser()
    {
        if (!$this->session->read('auth')) {
            return false;
        }
        return $this->session->read('auth');
    }

    public function connect($user)
    {
        $this->session->write('auth', $user);
    }

    public function connectFromCookie($db)
    {
        if (isset($_COOKIE['remember']) && !$this->getUser()) {
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token);
            $user_id = $parts['0'];
            $user = $db->query('SELECT * FROM user WHERE id = ?', [$user_id])->fetch();
            if ($user) {
                $expected = $user_id . '==' . $user->remember_token . sha1($user_id . 'memberwookies');
                if ($expected == $remember_token) {
                    $this->connect($user);
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                }
            } else {
                setcookie('remember', null, -1);
            }
        }
    }

    public function login($db, $username, $password, $remember = false)
    {
        if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
            $user = $db->query(
                'SELECT * FROM user WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL',
                ['username' => $username]
            )->fetch();

            if (password_verify($password, $user->password)) {
                $this->connect($user);
                if ($remember) {
                    $this->remember($db, $user->id);
                }
                return $user;
            } else {
                return false;
            }
        }
    }

    public function remember($db, $user_id)
    {
        $bytes = random_bytes(125);
        $remember_token = (bin2hex($bytes));
        $db->query('UPDATE user SET remember_token = ?', [$remember_token]);
        setcookie(
            'remember',
            $user_id . '==' . $remember_token . sha1($user_id . 'memberwookies'),
            time() + 60 * 60 * 24 * 7
        );
    }

    public function logout()
    {
        setcookie('remember', null, -1);
        $this->session = Session::getInstance();
        $this->session->delete('auth');
        $this->session->setFlash('success', 'Vous avez été déconnecté');
    }

    public function resetPassword($db, $email)
    {
        $user = $db->query(
            'SELECT * FROM user WHERE (email = ?) AND confirmed_at IS NOT NULL',
            [$_POST['email']]
        )->fetch();
        if ($user) {
            $bytes = random_bytes(30);
            $reset_token = (bin2hex($bytes));
            $db->query('UPDATE user SET reset_token = ?, reset_at = NOW() WHERE id = ?', [$reset_token, $user->id]);
            mail(
                $email,
                'Blog php: Réinitialisation de votre mot de passe',
                "Cliquer sur le lien ci-dessous pour Réinitialiser votre mot de passe\n\n
                http://localhost/index.php?c=user&a=reset&id={$user->id}&token=$reset_token"
            );
            return $user;
        }
        return false;
    }

    public function checkResetToken($db, $user_id, $token)
    {
        return $db->query(
            'SELECT * FROM user WHERE id = ? 
                     AND reset_token IS NOT NULL 
                     AND reset_token = ? 
                     AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)',
            [$user_id, $token]
        )->fetch();

    }

    public function updatePassword($db, $password, $user_id)
    {
        $db->query('UPDATE user SET password = ? WHERE id = ?', [$password, $user_id]);
    }

    public function deleteResetToken($db, $user_id)
    {
        $db->query('UPDATE user SET reset_token = NULL WHERE id = ?', [$user_id]);
    }
}
