<?php

namespace App\Model;

use App\Model\MainModel;

Class ConnectDB {

    protected static $pdo = null;

    public static function getDatabase() {
        if(!self::$pdo) {
            self::$pdo = new mainModel(DB['user'], DB['password'], DB['host'], DB['name']);
            return self::$pdo;
        }
    }


} 