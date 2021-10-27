<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\ConnectDB;
use App\Model\Session;
use App\Model\User;
use App\Model\Validator;

class UserController extends Controller {

    /**
     * Show admin dashboard if user is admin
     */
    public function index() {
        $session = Session::getInstance();
        //Need to be checked
        User::getAuth();

    }

    public function register() {
        if(!empty($_POST)) {

            $errors = [];
            $db = ConnectDB::getDatabase();
            $validator = new Validator($_POST);
            $validator->isAlpha('username', "Pseudo non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)");
            if ($validator->isValid()) {
                $validator->isUniq('username', $db, 'user', 'Ce pseudo est déjà utilisé');
            }

            $validator->isAlpha('lastname', "Nom non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)");
            $validator->isAlpha('firstname', "Prénom non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)");
            $validator->isEmail('email', 'Email non valide');

            if ($validator->isValid()) {
                $validator->isUniq('email', $db, 'user', "Un compte est déjà associé à cet email.");

            }
            $validator->isConfirmed('password', "Mot de passe non valide");
            $validator->isAgree('terms', "Vous devez accepter les conditions générales d'utilisation pour vous inscrire");

            if($validator->isValid())
            {
                $user = new User(Session::getInstance());
                 $user->register(
                     $db,
                     $_POST['firstname'],
                     $_POST['lastname'],
                     $_POST['username'],
                     $_POST['password'],
                     $_POST['email']
                 );
                Session::getInstance()->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte.');
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
    public function login() {
        $auth = TmpControllerFactory::getAuth();
        $db = \App\Model\ConnectDB::getDatabase();

        if ($auth->getUser()) {
            $this->redirectTo('user', 'account');
        }
        if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])) {
        $user = $auth->login($db, $_POST['username'], $_POST['password'], isset($_POST['remember']));
        $session = Session::getInstance();
        if($user) {
            $session->setFlash('success', 'Connexion réussie');
            $this->redirectTo('user', 'account');
        } else {
            $session->setFlash('danger', 'identifiant ou mot de passe incorrecte');
        }


        }
        echo $this->twig->render('/front/login.html.twig');
    }

    public function logout() {

    }

    public function changePassword() {

    }

    public function resetPassword() {

    }

    public function account() {
        Session::getInstance();
        $username = $_SESSION['auth']->username;

        echo $this->twig->render('/user/account.html.twig', [
            'session' => $_SESSION,
            'username' => $username
        ]);
    }

    /**
     * restrict page for connected user only
     */
//    public function restrict() {
//        if(!$this->session->read('auth')) {
//            $this->session->setFlash('danger', $this->options['restriction_msg']);
//            header('Location: index.php?c=login');
//            exit();
//        }
//    }


}