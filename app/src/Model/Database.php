<?php

namespace App\Model;

use App\Model\MainModel;
use PDO;

class Database
{

    protected static $pdo = null;

    /**
     * Get PDO
     * @return PDO|null
     */
    public static function getDatabase()
    {
        if (!self::$pdo) {
            self::$pdo = new PDO("mysql:dbname=" . DB['name'] . ";host=" . DB['host'], DB['user'], DB['password']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self::$pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        }
        return self::$pdo;
    }

    /**
     * @param $request
     * @param bool|array $params
     * @return false|PDOStatement
     */
    public function query($request, array $params)
    {
        if ($params) {
            $req = self::$pdo->prepare($request);
            $req->execute($params);
            return $req;
        }
        return self::$pdo->query($request);
    }
}
