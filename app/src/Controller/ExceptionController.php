<?php

namespace App\Controller;

use App\Core\Controller;

class ExceptionController extends Controller
{
    /**
     * Redirect to page 404
     */
    public function error404()
    {
        echo $this->twig->render('/pages/page-404.html.twig');
    }
}
