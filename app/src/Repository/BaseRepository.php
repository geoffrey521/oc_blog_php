<?php

namespace App\Repository;

use App\Model\Database;

abstract class BaseRepository
{
    protected static function getMany($request, $class, $params = false)
    {
        $db = Database::getDatabase();
        if ($params) {
            $req = $db->prepare($request);
            $req->execute($params);
            return $req->fetchAll(\PDO::FETCH_CLASS, $class);
        }
        $req = $db->query($request);
        return $req->fetchAll(\PDO::FETCH_CLASS, $class);
    }

    protected static function getOne($tableName, $class, $params = [])
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = $key . " = :" . $key;
        }
        $db = Database::getDatabase();
        $req = $db->prepare("SELECT * from " . $tableName . " WHERE " . join(' AND ', $where));
        $req->execute($params);
        $req->setFetchMode(\PDO::FETCH_CLASS, $class);
        return $req->fetch();
    }

    public static function delete($tableName, $params = [])
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = $key . " = :" . $key;
        }
        $db = Database::getDatabase();
        $req = $db->prepare("DELETE FROM " . $tableName . " WHERE " . join(' AND ', $where));
        return $req->execute($params);
    }
}
