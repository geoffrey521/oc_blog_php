<?php

namespace App\Repository;

use App\Model\Comment;
use App\Model\User;

class CommentRepository extends BaseRepository implements RepositoryInterface
{
    public static function findAllCommentsByPostId($postId)
    {
        return self::getMany('SELECT * FROM ' . Comment::getTableName(), Comment::class, ['post_id' => $postId]);
    }

    public static function findWaitingForValidationComments()
    {
        $comments =  self::getMany('SELECT * FROM ' . Comment::getTableName(), Comment::class, ['status' => 1]);
        foreach ($comments as $comment) {
            $user = self::getOne(User::getTableName(), User::class, ['id' => $comment->author_id]);
            $comment->setAuthor($user->getUsername());
        }
        return $comments;
    }

    public static function findAll()
    {
        return self::getMany("SELECT * FROM " . Comment::getTableName(), Comment::class);
    }

    public static function findById(int $id)
    {
        return self::getOne(Comment::getTableName(), Comment::class, ['id' => $id]);
    }

    public static function deleteById(int $id)
    {
        return self::delete(Comment::getTableName(), ['id' => $id]);
    }
}
