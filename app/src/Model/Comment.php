<?php

namespace App\Model;

class Comment extends MainModel
{
    protected const TABLE_NAME = 'comment';

    protected $id;
    protected $createdAt;
    protected $content;
    protected $authorId;
    protected $author;
    protected $postId;
    protected $validateAt;
    protected $status;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->authorId;
    }

    /**
     * @param mixed $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $autgorName
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @param mixed $postId
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * @param mixed $validate
     */
    public function setValidateAt($validateAt)
    {
        $this->validateAt = $validateAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }



    public static function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function add()
    {
        $this->query(
            'INSERT INTO comment(content, author_id, post_id)
                     VALUES(:content, :author_id, :post_id)',
            [
                'content' => $this->content,
                'author_id' => $this->authorId,
                'post_id' => $this->postId
            ]
        );
    }

    public function editStatus($commentId)
    {
        $this->query(
            'UPDATE comment SET status = :status, validate = NOW() WHERE id = :id',
            [
                'status' => $this->status,
                'id' => $commentId
            ]
        );
    }
}