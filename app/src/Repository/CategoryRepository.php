<?php

namespace App\Repository;

use App\Model\Category;

class CategoryRepository extends BaseRepository implements RepositoryInterface
{

    public static function findAll()
    {
        return self::getMany("SELECT * FROM " . Category::getTableName(), Category::class);
    }

    public static function findById(int $categoryId)
    {
        return self::getOne(Category::getTableName(), Category::class, ['id' => $categoryId]);
    }

    //    public static function findByPostId(int $postId)
    //    {
    //        return self::getOne(Category::getTableName(), Category::class, ['id' => $])
    //    }

    public static function deleteById(int $id)
    {
        // TODO: Implement delete() method.
    }
}
