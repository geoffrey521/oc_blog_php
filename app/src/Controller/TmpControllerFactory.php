<?php

namespace App\Controller;

use App\Model\Auth;
use App\Model\Session;

class TmpControllerFactory
{
    public function redirectTo($page) {
        header("Location: index.php?p=$page");
        exit();
    }

    static function getAuth() {
        return new Auth(Session::getInstance(), ['restriction_msg' => "TMP Vous devez être connecté pour acceder"]);
    }
}