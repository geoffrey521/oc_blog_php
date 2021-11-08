<?php

namespace App\Model;

use App\Model\MainModel;

class Database
{

    protected static $pdo = null;

    public static function getDatabase()
    {
        if (!self::$pdo) {
            self::$pdo = new \PDO("mysql:dbname=" . DB['name'] . ";host=" . DB['host'], DB['user'], DB['password']);
            self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_OBJ);
        }
        return self::$pdo;
    }
}
