<?php

namespace App\Repository;

use App\Model\Database;
use App\Model\Post;

class PostRepository extends BaseRepository implements RepositoryInterface
{
    public static function getLastFour()
    {
        return self::getMany("SELECT * FROM " . Post::TABLE_NAME . " ORDER BY last_update DESC LIMIT 4", Post::class);
    }

    public static function findAll()
    {
        return self::getMany("SELECT * FROM " . Post::TABLE_NAME . " ORDER BY last_update DESC", Post::class);
    }

    public static function findById(int $postId)
    {
        return self::getOne(Post::TABLE_NAME, Post::class, ['id' => $postId]);
    }

    public static function deleteById(int $postId)
    {
        return self::delete(Post::TABLE_NAME, ['id' => $postId]);
    }

    public static function upload(Post $post)
    {
        $image = [];
        if (!empty($post->getImage())) {
            $image = $post->getImage();
        }
        return move_uploaded_file(
            $image['tmp_name'],
            __DIR__ . '/../../public/assets/img/uploads/' . basename($image['name'])
        );
    }
}
