<?php

namespace App\Controller;

use App\Core\Controller;

class ExceptionController extends Controller
{
    public function error404()
    {
        header('HTTP/1.0 404 Not Found');
        exit;
    }
}
