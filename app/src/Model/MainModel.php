<?php

namespace App\Model;

use PDO;

class MainModel
{

    protected $pdo;

    public function __construct()
    {
        $this->pdo = Database::getDatabase();
    }

    /**
     * @param $query
     * @param bool|array $params
     * @return false|\PDOStatement
     */
    public function query($request, $params = false)
    {
        if ($params) {
            $req = $this->pdo->prepare($request);
            $req->execute($params);
        } else {
            $req = $this->pdo->query($request);
        }
        return $req;
    }

    public function delete($request, $params = false)
    {
        $req = $this->pdo->prepare("DELETE FROM " . static::TABLE_NAME);
        $result = $req->execute($params);
        return $result;
    }

    /**
     * @return int
     */
    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}
