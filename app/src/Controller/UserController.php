<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Icontroller;
use App\Model\Comment;
use App\Model\Database;
use App\Model\Post;
use App\Model\Session;
use App\Model\User;
use App\Model\Validator;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\CustomPageRepository;
use App\Repository\PostRepository;

class UserController extends Controller implements Icontroller
{

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @throws \Exception
     */
    public function register()
    {
        if (!empty($_POST)) {
            $validator = new Validator($_POST);
            $validator->isAlpha(
                'username',
                "Pseudo non rempli ou non valide (caractères alphanumérique et traits d'union uniquement)"
            );
            if ($validator->isValid()) {
                $validator->isUniq('username', 'user', 'Ce pseudo est déjà utilisé');
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
                $validator->isUniq('email', 'user', "Un compte est déjà associé à cet email.");
            }
            $validator->areSamePasswords('password', "Mot de passe non valide");
            $validator->isAgree(
                'terms',
                "Vous devez accepter les conditions générales d'utilisation pour vous inscrire"
            );
            if ($validator->isValid()) {
                $user = new User();
                $user->setUsername($_POST['username'])
                    ->setLastname($_POST['lastname'])
                    ->setFirstname($_POST['firstname'])
                    ->setEmail($_POST['email'])
                    ->setPassword($_POST['password']);
                $user->setPassword($_POST['password']);

                $user->register();

                $this->session->setFlash(
                    'success',
                    'Un email de confirmation vous a été envoyé pour valider votre compte.'
                );
                $this->redirectTo('user', 'login');
            }
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
            $this->redirectTo('user', 'register');
        }
        echo $this->twig->render('/front/register.html.twig');
    }

    /**
     * User login
     */
    public function login()
    {
        $user = new User();
        if ($user->isLogged()) {
            if ($user->getIsAdmin()) {
                $this->redirectTo('user', 'admin');
            }
            $this->redirectTo('front', 'home');
        }
        if (!empty($_POST)) {
            $validator = new Validator($_POST);
            $validator->isNotEmpty('username', "Le Nom d'utilisateur ne peut pas être vide");
            $validator->isNotEmpty('password', "Le Mot de passe ne peut pas être vide");
            $validator->isExist('username', 'user', "Nom d'utilisateur ou mot de passe incorrecte");
            $validator->isCorrectPassword('password', "Nom d'utilisateur ou mot de passe incorrecte");
            if ($validator->isValid()) {
                $user = $user->login($_POST['username'], $_POST['password'], isset($_POST['remember']));
                if ($user === false) {
                    $this->session->setFlash('danger', 'Email ou mot de passe incorrecte');
                    $this->redirectTo('user', 'login');
                }
                $this->session->setFlash('success', 'Connexion réussie');
                if ($user->is_admin) {
                    $this->redirectTo('user', 'admin');
                }
                $this->redirectTo('front');
            }
            $errors = $validator->getErrors();
            foreach ($errors as $error) {
                $this->session->setFlash('danger', $error);
            }
        }

        echo $this->twig->render('/front/login.html.twig');
    }

    public function logout()
    {
        $user = new User();
        $user->logout();
        $this->session->setFlash('success', 'Vous avez été déconnecté');
        $this->redirectTo('front');
    }

    public function confirmAccount($id, $token)
    {
        $user = new User();
        if ($user->confirm($id, $token)) {
            $this->session->setFlash('success', 'Votre compte à bien été validé.');
            $this->redirectTo('user');
        }
        $this->session->setFlash('danger', 'Ce lien de confirmation n\'est plus valide');
        $this->redirectTo('user', 'login');
    }

    public function forget()
    {
        if (!empty($_POST) && !empty($_POST['email'])) {
            $user = new User();
            if ($user->resetPassword($_POST['email'])) {
                $this->session->setFlash('success', 'Un mail pour réinitialiser votre mot de passe vous a été envoyé');
                $this->redirectTo('user', 'login');
            }
            $this->session->setFlash('danger', 'Aucun compte n\'est associé à cet email');
        }

        echo $this->twig->render('/user/forget-password.html.twig');
    }

    public function reset($id, $token)
    {
        if (isset($id) && isset($token)) {
            $user = new User();
            $user_properties = $user->checkResetToken($id, $token);
            if ($user_properties) {
                if (!empty($_POST)) {
                    $validator = new Validator($_POST);
                    $validator->areSamePasswords('password');
                    if ($validator->isValid()) {
                        $password = $user->hashPassword($_POST['password']);
                        $user->updatePassword($password, $id);
                        $user->connect($user_properties);
                        $this->session->setFlash('success', 'Votre mot de passe a été réinitialisée');
                        $this->redirectTo('user', 'login');
                    }
                }
                echo $this->twig->render('/user/reset-password.html.twig');
                return;
            }
            $this->session->setFlash('danger', 'Lien invalide');
            $this->redirectTo('user', 'login');
        }
        $this->redirectTo('user', 'login');
    }

    public function admin()
    {
        $posts = PostRepository::findAll();

        if (!array_key_exists('auth', $_SESSION)) {
            $this->session->setFlash('danger', "Vous n'êtes pas connecté");
            $this->redirectTo('front', 'home');
        }
        $user = new User($_SESSION['auth']);
        if (!$user->isLogged() || !$user->isAdmin()) {
            $this->session->setFlash('danger', "Vous n'avez pas accès à cette page");
            $this->redirectTo('front', 'home');
        }
        if (!empty($_POST)) {
            if ($_POST['password'] != $_POST['password_confirm']) {
                $this->session->setFlash('danger', 'Les mots de passes ne sont pas identiques');
                $this->redirectTo('user', 'admin');
            }
            if (empty($_POST['password'])) {
                $this->session->setFlash('danger', 'Le mot de passe ne peut pas être vide');
                $this->redirectTo('user', 'admin');
            }
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $user->updatePassword($password, $user->getId());
            $this->session->setFlash('success', 'Le mot de passe à bien été modifié');
            $this->redirectTo('user', 'admin');
        }

        echo $this->twig->render(
            '/user/admin.html.twig',
            [
                'session' => $this->session,
                'posts' => $posts
            ]
        );
    }

    public function managePosts()
    {
        $posts = PostRepository::findAll();
        $user = new User();
        if (!$user->isLogged() || !$user->isAdmin()) {
            $this->session->setFlash('danger', "Vous n'avez pas accès à cette page");
            $this->redirectTo('front', 'home');
        }
        echo $this->twig->render(
            '/admin/post/manage.html.twig',
            [
                'posts' => $posts
            ]
        );
    }

    public function managePages()
    {
        $pages = CustomPageRepository::findAll();
        $user = new User();
        if (!$user->isLogged() || !$user->isAdmin()) {
            $this->session->setFlash('danger', "Vous n'avez pas accès à cette page");
            $this->redirectTo('front', 'home');
        }
        echo $this->twig->render(
            '/admin/page/manage.html.twig',
            [
                'pages' => $pages
            ]
        );
    }

    public function manageCategories()
    {
        $categories = CategoryRepository::findAll();
        $user = new User();
        if (!$user->isLogged() || !$user->isAdmin()) {
            $this->session->setFlash('danger', "Vous n'avez pas accès à cette page");
            $this->redirectTo('front', 'home');
        }
        echo $this->twig->render(
            '/admin/category/manage.html.twig',
            [
                'categories' => $categories
            ]
        );
    }

    public function manageComments()
    {
        $user = new User();
        if (!$user->isLogged() || !$user->isAdmin()) {
            $this->session->setFlash('danger', "Vous n'avez pas accès à cette page");
            $this->redirectTo('front', 'home');
        }
        $comments = CommentRepository::findWaitingForValidationComments();

        echo $this->twig->render(
            '/admin/comment/manage.html.twig',
            [
                'comments' => $comments
            ]
        );
    }
}
