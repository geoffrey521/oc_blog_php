<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Icontroller;
use App\Model\Session;

class HomeController extends Controller implements Icontroller
{

    public function index()
    {
        Session::getInstance();
        echo $this->twig->render('/front/home.html.twig', [
            'session' => $_SESSION
        ]);
    }
}
