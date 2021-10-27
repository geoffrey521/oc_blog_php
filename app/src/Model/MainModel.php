<?php

namespace App\Model;

use PDO;

class MainModel {

    protected $pdo;

    public function __construct($user, $password, $host, $dbname)
    {
        $this->pdo = new \PDO("mysql:dbname=$dbname;host=$host", $user, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    /**
     * @param $query
     * @param bool|array $params
     * @return false|\PDOStatement
     */
    public function query($query, $params = false) {
        if($params) {
            $req = $this->pdo->prepare($query);
            $req->execute($params);
        } else {
            $req = $this->pdo->query($query);
        }
        return $req;
    }

    /**
     * @return int
     */
    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

}