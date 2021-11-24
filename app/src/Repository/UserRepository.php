<?php

namespace App\Repository;

use App\Model\User;

class UserRepository extends BaseRepository
{
    public static function findUserById($id)
    {
        return self::getOne(User::getTableName(), User::class, ['id' => $id]);
    }

    public static function findUserByUsernameOrEmail($param)
    {
        return self::getOne(User::getTableName(), User::class, ['username' => $param, 'email' => $param], 'OR');
    }

    public static function initUser($id = null)
    {
        return $id !== null ? self::findUserById($id) : new User();
    }
}
