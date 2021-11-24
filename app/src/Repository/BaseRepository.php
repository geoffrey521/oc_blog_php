<?php

namespace App\Repository;

use App\Model\Database;
use PDO;

abstract class BaseRepository
{
    protected static function getMany($request, $class, $params = [])
    {
        $db = Database::getDatabase();
        if (!empty($params)) {
            $where = [];
            foreach ($params as $key => $value) {
                $where[] = $key . " = :" . $key;
            }
            $req = $db->prepare($request . " WHERE " . join(' AND ', $where));
            $req->execute($params);
            return $req->fetchAll(PDO::FETCH_CLASS, $class);
        }
        $req = $db->query($request);
        return $req->fetchAll(PDO::FETCH_CLASS, $class);
    }

    protected static function getOne($tableName, $class, $params = [])
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = $key . " = :" . $key;
        }
        $db = Database::getDatabase();
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $req = $db->prepare(sprintf("SELECT * FROM %s WHERE %s", $tableName, join(' AND ', $where)));
        $req->execute($params);
        $req->setFetchMode(PDO::FETCH_OBJ);
        $reqObject = $req->fetchObject();
        return new $class($reqObject);
    }

    public static function delete($tableName, $params = [])
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = $key . " = :" . $key;
        }
        $db = Database::getDatabase();
        $req = $db->prepare(sprintf("DELETE FROM %s WHERE %s", $tableName, join(' AND ', $where)));
        return $req->execute($params);
    }

    public static function deleteOneFieldValueById($tableName, $field, $params)
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = $key . " = :" . $key;
        }
        $db = Database::getDatabase();
        $req = $db->prepare(sprintf("UPDATE %s SET %s = %s WHERE %s", $tableName, $field, "''", join(' AND ', $where)));
        return $req->execute($params);
    }

    public static function uploadImage($image)
    {
        return move_uploaded_file(
            $image['tmp_name'],
            __DIR__ . '/../../public/assets/img/uploads/' . basename($image['name'])
        );
    }
}
