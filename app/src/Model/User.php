<?php

namespace App\Model;

use App\Repository\UserRepository;

class User extends MainModel
{
    protected const TABLE_NAME = "user";

    protected $id;
    protected $firstname;
    protected $lastname;
    protected $username;
    protected $email;
    protected $password;
    protected $createdAt;
    protected $agreedTermsDate;
    protected $isActive;
    protected $isAdmin;
    protected $confirmToken;
    protected $confirmedAt;
    protected $resetToken;
    protected $resetAt;
    protected $rememberToken;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = self::hashPassword($password);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAgreedTermsDate()
    {
        return $this->agreedTermsDate;
    }

    /**
     * @param mixed $agreedTermsDate
     */
    public function setAgreedTermsDate($agreedTermsDate)
    {
        $this->agreedTermsDate = $agreedTermsDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * @param mixed $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmToken()
    {
        return $this->confirmToken;
    }

    /**
     * @param mixed $confirmToken
     */
    public function setConfirmToken($confirmToken)
    {
        $this->confirmToken = $confirmToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmedAt()
    {
        return $this->confirmedAt;
    }

    /**
     * @param mixed $confirmedAt
     */
    public function setConfirmedAt($confirmedAt)
    {
        $this->confirmedAt = $confirmedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResetToken()
    {
        return $this->resetToken;
    }

    /**
     * @param mixed $resetToken
     */
    public function setResetToken($resetToken)
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResetAt()
    {
        return $this->resetAt;
    }

    /**
     * @param mixed $resetAt
     */
    public function setResetAt($resetAt)
    {
        $this->resetAt = $resetAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRememberToken()
    {
        return $this->rememberToken;
    }

    /**
     * @param mixed $rememberToken
     */
    public function setRememberToken($rememberToken)
    {
        $this->rememberToken = $rememberToken;
        return $this;
    }

    public static function getTableName()
    {
        return self::TABLE_NAME;
    }


    /**
     * Hash a password who has given and crypt with bcrypt
     *
     * @param  $password
     * @return string
     */
    public static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * Insert user datas to the database
     *
     * @param  $db
     * @param  $firstname
     * @param  $lastname
     * @param  $username
     * @param  $password
     * @param  $email
     * @throws \Exception
     */
    public function register()
    {
        $bytes = random_bytes(30);
        $token = (bin2hex($bytes));
        $this->query(
            'INSERT INTO user(
                    firstname, lastname, username, email, password, is_active, confirm_token)
                    VALUES(:firstname, :lastname, :username, :email, :password, :is_active, :confirm_token)',
            [
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'username' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'is_active' => true,
                'confirm_token' => $token
            ]
        );
        $userId = $this->lastInsertId();
        mail(
            $this->email,
            'Confirmation de votre compte',
            "Merci de cliquer sur le lien ci-dessous pour valider votre compte\n\n
            http://localhost/confirm_account/id/$userId/token/$token"
        );
    }

    /**
     * Confirm and activate a user account
     * @param  $user_id
     * @param  $token
     * @param  $session
     * @return bool
     */
    public function confirm($userId, $token)
    {
        $user = $this->query('SELECT * FROM user WHERE id = ?', [$userId])->fetch();
        if ($user && $user->confirm_token == $token) {
            $this->query('UPDATE user SET confirm_token = NULL, confirmed_at = NOW() WHERE id = ?', [$userId]);
            Session::write('auth', $user);
            return true;
        }
        return false;
    }

    /**
     * restrict page for connected user only
     */
    public function isLogged()
    {
        return Session::read('auth');
    }

    public function isAdmin()
    {
        return $this->isAdmin === 1;
    }

    public function connect()
    {
        Session::write('auth', [
            'id' => $this->id,
            'username' => $this->username,
            'isAdmin' => $this->isAdmin,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname
        ]);
    }

    public function connectFromCookie()
    {
        if (isset($_COOKIE['remember']) && !Session::read('auth')) {
            $remember_token = $_COOKIE['remember'];
            $parts = explode('==', $remember_token);
            $userId = $parts['0'];
            $user = $this->query('SELECT * FROM user WHERE id = ?', [$userId])->fetch();
            if ($user) {
                $expected = $userId . '==' . $user->remember_token . sha1($userId . 'memberwookies');
                if ($expected == $remember_token) {
                    $this->connect($user);
                    setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
                    return;
                }
            }
            setcookie('remember', null, -1);
        }
    }

    public static function login($username, $password, $remember)
    {
        if (isset($username) && isset($password)) {
            $user = UserRepository::findUserByUsernameOrEmail($username);
            if ($user && password_verify($password, $user->password)) {
                $user->connect();
                if ($remember) {
                    $user->remember();
                }
                return $user;
            }
            return false;
        }
    }

    public function remember()
    {
        $bytes = random_bytes(125);
        $remember_token = (bin2hex($bytes));
        $this->query('UPDATE user SET remember_token = ? WHERE id = ?', [$remember_token, $this->id]);
        setcookie(
            'remember',
            $this->id . '==' . $remember_token . sha1($this->id . 'memberwookies'),
            time() + 60 * 60 * 24 * 7
        );
    }

    public function logout()
    {
        setcookie('remember', null, -1);
        Session::delete('auth');
    }

    public function resetPassword($email)
    {
        $user = $this->query(
            'SELECT * FROM user WHERE (email = ?) AND confirmed_at IS NOT NULL',
            [$_POST['email']]
        )->fetch();
        if ($user) {
            $bytes = random_bytes(30);
            $reset_token = (bin2hex($bytes));
            $this->query(
                'UPDATE user SET reset_token = ?, reset_at = NOW() WHERE id = ?',
                [$reset_token, $user->getId()]
            );
            mail(
                $email,
                'Blog php: Réinitialisation de votre mot de passe',
                "Cliquer sur le lien ci-dessous pour Réinitialiser votre mot de passe\n\n
                http://localhost/reset/id/$user->getId()/token/$reset_token"
            );
            return $user;
        }
        return false;
    }

    public function checkResetToken($userId, $token)
    {
        return $this->query(
            'SELECT * FROM user WHERE id = ? 
                     AND reset_token IS NOT NULL 
                     AND reset_token = ? 
                     AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE
                         )',
            [$userId, $token]
        )->fetch();
    }

    public function updatePassword($password, $userId)
    {
        $this->query('UPDATE user SET password = ? WHERE id = ?', [$password, $userId]);
    }

    public function deleteResetToken($userId)
    {
        $this->query('UPDATE user SET reset_token = NULL WHERE id = ?', [$userId]);
    }

    public function edit($id)
    {
        $this->query(
            'UPDATE user SET
            username = :username,
            firstname = :firstname,
            lastname = :lastname
            WHERE id = :id',
            [
                'username' => $this->username,
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'id' => $id
            ]
        );
    }

    public static function fillWithPost()
    {
        $user = new self();
        $user->setUsername($_POST['username'])
            ->setFirstname($_POST['firstname'])
            ->setLastname($_POST['lastname']);
        return $user;
    }
}
