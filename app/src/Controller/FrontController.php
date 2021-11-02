<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Icontroller;
use App\Model\Session;
use App\Model\Validator;

class FrontController extends Controller implements Icontroller
{
    public function index()
    {
        if(isset($_SESSION['auth'])) {
            $username = $_SESSION['auth']->username;
            echo $this->twig->render('/front/home.html.twig', [
                'session' => $this->session,
                'flashes' => $this->session->getFlashes(),
                'username' => $username
            ]);
        } else {
            echo $this->twig->render('/front/home.html.twig', [
                'session' => $this->session,
                'flashes' => $this->session->getFlashes(),
            ]);
        }
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
                 echo $this->twig->render('/front/contact.html.twig', [
                    'session' => $this->session,
                ]);
            } else {
                echo $this->twig->render('/front/contact.html.twig', [
                    'session' => $this->session,
                    'errors' => $errors
                ]);
            }
        }
        $username = '';
        if (isset($_SESSION['auth']->firstname))
        {
            $username = $_SESSION['auth']->firstname;
        }
        echo $this->twig->render('/front/contact.html.twig', [
            'session' => $this->session,
            'username' => $username
        ]);
    }
}
