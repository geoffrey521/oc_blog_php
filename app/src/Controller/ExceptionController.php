<?php

namespace App\Controller;

use App\Core\Controller;

class ExceptionController extends Controller
{
    public function error404()
    {
        print_r('error 404');
        die();
    }
}
