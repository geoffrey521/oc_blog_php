<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Auth;
use App\Model\ConnectDB;
use App\Model\Session;
use App\Model\User;
use App\Model\Validator;

class UserController extends Controller
{
    /**
     * Show admin dashboard if user is admin
     */
    public function index()
    {
        $isLogged = $this->getAuth()->isLogged();
        if(!$isLogged)
        {
            $this->session->setFlash('danger', 'restriction_msg');
            $this->redirectTo('front');
        } else {
            $username = $_SESSION['auth']->username;
            if (!empty($_POST)) {
                $user = new User(Session::getInstance());
                if ($_POST['password'] != $_POST['password_confirm']) {
                    $this->session->setFlash('danger', 'Les mots de passes ne sont pas identiques');
                } elseif (empty($_POST['password'])) {
                    $this->session->setFlash('danger', 'Le mot de passe ne peut pas être vide');
                } else {
                    $user_id = $_SESSION['auth']->id;
                    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
                    $db = ConnectDB::getDatabase();
                    $user->updatePassword($db, $password, $user_id);
                    $this->session->setFlash('success', 'Le mot de passe à bien été modifié');
                }
            }

            echo $this->twig->render('/user/account.html.twig', [
                'session' => $this->session,
                'username' => $username,
            ]);
        }
    }

    public function register()
    {
        if (!empty($_POST)) {
            $errors = [];
            $db = ConnectDB::getDatabase();
            $validator = new Validator($_POST);
            $validator->isAlpha(
                'username',
                "Pseudo non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)"
            );
            if ($validator->isValid()) {
                $validator->isUniq('username', $db, 'user', 'Ce pseudo est déjà utilisé');
            }
            $validator->isAlpha(
                'lastname',
                "Nom non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)"
            );
            $validator->isAlpha(
                'firstname',
                "Prénom non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)"
            );
            $validator->isEmail('email', 'Email non valide');

            if ($validator->isValid()) {
                $validator->isUniq('email', $db, 'user', "Un compte est déjà associé à cet email.");
            }
            $validator->isConfirmed('password', "Mot de passe non valide");
            $validator->isAgree(
                'terms',
                "Vous devez accepter les conditions générales d'utilisation pour vous inscrire"
            );

            if ($validator->isValid()) {
                $user = new User(Session::getInstance());
                 $user->register(
                     $db,
                     $_POST['firstname'],
                     $_POST['lastname'],
                     $_POST['username'],
                     $_POST['password'],
                     $_POST['email']
                 );
                $this->session->setFlash(
                    'success',
                    'Un email de confirmation vous a été envoyé pour valider votre compte.'
                );
                $this->redirectTo('user', 'login');
            } else {
                /** need to throw errors */
              //  $this->redirectTo('user', 'register');
                $this->twig->render('/front/register.html.twig', [
                    'errors' => [$errors]
                ]);
            }
        } else {
            echo $this->twig->render('/front/register.html.twig');
        }
    }

    /**
     * User login
     */
    public function login()
    {
        $auth = $this->getAuth();
        $db = ConnectDB::getDatabase();
        if ($auth->getUser()) {
            $this->redirectTo('user');
        }
        if (!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
            $user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
            if ($user) {
                $this->session->setFlash('success', 'Connexion réussie');
                $this->redirectTo('user');
            } else {
                $this->session->setFlash('danger', 'identifiant ou mot de passe incorrecte');
            }
        }
        echo $this->twig->render('/front/login.html.twig');
    }

    public function logout()
    {
        $user = new User();
        $user->logout();
        $this->redirectTo('front');
    }

    public function account()
    {
        die('yes problem');
    }

    public function confirm() {
        $db = ConnectDB::getDatabase();
        if ($this->getAuth()->confirm($db, $_GET['id'], $_GET['token'])) {
            $this->session->setFlash('success', 'Votre compte à bien été validé.');
            $this->redirectTo('user');
        } else {
            $this->session->setFlash('danger', 'Ce lien de confirmation n\'est plus valide');
            $this->redirectTo('user', 'login');
        }
    }

    public function getAuth() {
        return new User($this->session::getInstance(), ['restriction_msg' => "TMP Vous devez être connecté pour acceder"]);
    }

    public function forget()
    {
        if (!empty($_POST) && !empty($_POST['email'])) {
            $db = ConnectDB::getDatabase();
            if ($this->getAuth()->resetPassword($db, $_POST['email'])) {
                $this->session->setFlash('success', 'Un mail pour réinitialiser votre mot de passe vous a été envoyé');
                $this->redirectTo('user', 'login');
            } else {
                $this->session->setFlash('danger', 'Aucun compte n\'est associé à cet email');
            }
        }

        echo $this->twig->render('/user/forget-password.html.twig', [
            'username' => 'truc'
        ]);
    }

    public function reset()
    {
        if (isset($_GET['id']) && isset($_GET['token'])) {
            $db = ConnectDB::getDatabase();
            $user = $this->getAuth();
            $user_properties = $user->checkResetToken($db, $_GET['id'], $_GET['token']);
            if ($user_properties) {
                if (!empty($_POST)) {
                    $validator = new Validator($_POST);
                    $validator->isConfirmed('password');
                    if ($validator->isValid()) {
                        $password = $user->hashPassword($_POST['password']);
                        $user->updatePassword($db, $password, $_GET['id']);
                        $user->connect($user_properties);
                        $this->session->setFlash('success', 'Votre mot de passe a été réinitialisée');
                        $this->redirectTo('user');
                    }
                }
                echo $this->twig->render('/user/reset-password.html.twig');
            } else {
                $this->session->setFlash('danger', 'Lien invalide');
                $this->redirectTo('user', 'login');
            }
        } else {
            $this->redirectTo('user', 'login');
        }

    }
}
