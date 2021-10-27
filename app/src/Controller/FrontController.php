<?php

namespace App\Controller;

use App\Core\Controller;
use App\Core\Icontroller;

class FrontController extends Controller implements Icontroller {

    public function index()
    {
        $this->render("front/home");
    }

}