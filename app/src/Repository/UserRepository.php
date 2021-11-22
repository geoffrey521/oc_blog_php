<?php

namespace App\Repository;

use App\Model\User;

class UserRepository extends BaseRepository
{
    public static function findUserById($id)
    {
        return self::getOne(User::getTableName(), User::class, ['id' => $id]);
    }
}