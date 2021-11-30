<?php

namespace App\Repository;

use App\Model\Database;
use PDO;

abstract class BaseRepository
{
    protected static function getMany($request, $class, $params = [], $operator = 'AND')
    {
        $db = Database::getDatabase();
        if (!empty($params)) {
            $where = [];
            foreach ($params as $key => $value) {
                $where[] = $key . " = :" . $key;
            }
            $req = $db->prepare($request . " WHERE " . join(" $operator ", $where));
            $req->execute($params);
            $reqObjects = $req->fetchAll(PDO::FETCH_OBJ);
            return self::hydrateMultiple($reqObjects, $class);
        }
        $req = $db->query($request);
        $reqObjects = $req->fetchAll(PDO::FETCH_OBJ);
        return self::hydrateMultiple($reqObjects, $class);
    }

    protected static function getOne($tableName, $class, $params = [], $operator = 'AND')
    {
        $where = [];
        foreach ($params as $key => $value) {
            $where[] = $key . " = :" . $key;
        }
        $db = Database::getDatabase();
        $db->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
        $req = $db->prepare(sprintf("SELECT * FROM %s WHERE %s", $tableName, join(" $operator ", $where)));
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

    /**
     * Create an array of class objects with an array objects
     * @param $objects
     * @param $class
     * @return array
     */
    private static function hydrateMultiple($objects, $class)
    {
        $result = [];
        foreach ($objects as $object) {
            $object = new $class($object);
            $result[] = $object;
        }
        return $result;
    }
}
