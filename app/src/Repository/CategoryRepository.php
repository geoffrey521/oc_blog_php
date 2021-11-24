<?php

namespace App\Repository;

use App\Model\Category;

class CategoryRepository extends BaseRepository implements RepositoryInterface
{

    public static function findAll()
    {
        return self::getMany("SELECT * FROM " . Category::getTableName(), Category::class);
    }

    public static function findById($id)
    {
        return self::getOne(Category::getTableName(), Category::class, ['id' => $id]);
    }

    public static function deleteById(int $id)
    {
        return self::delete(Category::getTableName(), ['id' => $id]);
    }
}
