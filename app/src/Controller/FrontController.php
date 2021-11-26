<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Icontroller;
use App\Model\MainModel;
use App\Model\Post;
use App\Model\Session;
use App\Model\User;
use App\Model\Validator;
use App\Repository\PostRepository;

class FrontController extends Controller
{

    public function home()
    {
        echo $this->twig->render(
            '/front/home.html.twig',
            [
            'posts' => PostRepository::getLastFour(),
            ]
        );
    }

    public function contact()
    {
        if (!empty($_POST)) {
            $errors = [];
            $validator = new Validator($_POST);
            $errors[] = $validator->isEmail('email', 'Email non valide');
            $errors[] = $validator->isNotEmpty('message', 'Message vide');
            $validator->isAgree(
                'terms',
                "Vous devez accepter les conditions générales d'utilisation pour vous inscrire"
            );
            if ($validator->isValid()) {
                mail('admin@admin.com', 'formulaire de contact', $_POST['message']);
                $this->session->setFlash(
                    'success',
                    'Votre message a bien été envoyé.'
                );
                echo $this->twig->render('/front/contact.html.twig');
                return;
            }
            echo $this->twig->render('/front/contact.html.twig');
            return;
        }
        echo $this->twig->render(
            '/front/contact.html.twig',
            [
            'session' => $this->session
            ]
        );
    }

}
