<?php

namespace App\Repository;

use App\Model\Category;
use App\Model\Database;
use App\Model\Post;
use App\Model\User;

class PostRepository extends BaseRepository implements RepositoryInterface
{
    public static function getLastFour()
    {
        $posts = self::getMany("SELECT * FROM " . Post::getTableName() .
            " ORDER BY last_update DESC LIMIT 4", Post::class);
        foreach ($posts as $post) {
            $category = self::getOne(Category::getTableName(), Category::class, ['id' => $post->category_id]);
            $post->setCategory($category->getName())
                ->setCategoryId($category->getId());
        }
        return $posts;
    }

    public static function findAll()
    {
        return self::getMany("SELECT * FROM " . Post::getTableName() . " ORDER BY last_update DESC", Post::class);
    }

    public static function findById(int $postId)
    {
        $post = self::getOne(Post::getTableName(), Post::class, ['id' => $postId]);
        $author = self::getOne('user', User::class, ['id' => $post->getAutorId()]);
        $category = self::getOne(Category::getTableName(), Category::class, ['id' => $post->getCategoryId()]);
        $post->setAuthor($author->getUsername())
            ->setCategory($category->getName())
            ->setCategoryId($category->getId());
        return $post;
    }

    public static function findBySlug(string $postSlug)
    {
        $post = self::getOne(Post::getTableName(), Post::class, ['slug' => $postSlug]);
        $author = self::getOne('user', User::class, ['id' => $post->getAutorId()]);
        $post->setAuthor($author->getUsername());

        return $post;
    }

    public static function deleteById(int $postId)
    {
        return self::delete(Post::getTableName(), ['id' => $postId]);
    }

    public static function deleteImageByPostId(int $id)
    {
        return self::deleteOneFieldValueById(Post::getTableName(), 'image', ['id' => $id]);
    }
}
