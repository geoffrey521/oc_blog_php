<?php

namespace App\Repository;

use App\Model\Database;

abstract class BaseRepository
{
    protected static function getMany($request, $class, $params = false)
    {
        $db = Database::getDatabase();
        if ($params) {
            $where = [];
            foreach ($params as $key => $value) {
                $where[] = $key . " = :" . $key;
            }
            $req = $db->prepare($request . " WHERE " . join(' AND ', $where));
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
        $req = $db->prepare("SELECT * FROM " . $tableName . " WHERE " . join(' AND ', $where));
        $req->execute($params);
        $req->setFetchMode(\PDO::FETCH_CLASS, $class);

        // TODO need to change property name's underscores by camelCase
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

    public static function uploadImage($entity)
    {
        $image = [];
        if (!empty($entity->getImage())) {
            $image = $entity->getImage();
        }
        return move_uploaded_file(
            $image['tmp_name'],
            __DIR__ . '/../../public/assets/img/uploads/' . basename($image['name'])
        );
    }
}
