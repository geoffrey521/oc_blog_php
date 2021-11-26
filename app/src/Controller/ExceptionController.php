<?php

namespace App\Controller;

use App\Core\Controller;

class ExceptionController extends Controller
{
    public function error404()
    {
        echo $this->twig->render('/pages/page-404.html.twig');
    }
}
