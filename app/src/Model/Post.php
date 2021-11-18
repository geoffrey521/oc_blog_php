<?php

namespace App\Model;

use App\Core\Text;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use DateTime;

class Post extends MainModel
{
    protected const TABLE_NAME = "post";

    protected int $id;
    protected $title;
    protected $image;
    protected $lead;
    protected $excerpt;
    protected $content;
    protected $author;
    protected $category;
    protected $authorId;
    protected $categoryId;
    protected $slug;
    protected $lastUpdate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        $this->slug = $this->slugify($title);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setImage($image)
    {
        if (is_array($image) && !empty($image)) {
            $this->image = $image['name'];
            return $this;
        }
        $this->image = $image;
        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setLead($lead)
    {
        $this->lead = $lead;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLead(): mixed
    {
        return $this->lead;
    }

    /**
     * @return mixed
     */
    public function getExcerpt()
    {
        if ($this->lead === null) {
            return null;
        }
        return Text::excerpt($this->lead, 60);
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setAuthorId($authorId)
    {
        $this->authorId = $authorId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutorId(): mixed
    {
        return $this->authorId;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    public function setCategoryId($category_id)
    {
        $this->categoryId = $category_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getCategoryId() :int
    {
        return $this->categoryId;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return array
     */
    public function getCategory()
    {
        return $this->category;
    }


    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function setLastUpdate()
    {
        $this->lastUpdate = date('d-F-Y');
        return $this;
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLastUpdate(): DateTime
    {
        return new DateTime($this->lastUpdate);
    }

    public function add()
    {
        $this->query(
            'INSERT INTO post(
                 title, image, lead, author_id, category_id, slug, content)
                 VALUES(:title, :image, :lead, :author_id, :category_id, :slug, :content)',
            [
                'title' => $this->title,
                'image' => $this->image,
                'lead' => $this->lead,
                'author_id' => $this->authorId,
                'category_id' => $this->categoryId,
                'slug' => $this->slug,
                'content' => $this->content
            ]
        );
    }

    public function edit($id)
    {
        $this->query(
            'UPDATE post SET
                title = :title,
                image = :image,
                lead = :lead,
                author_id = :author_id,
                category_id = :category_id,
                slug = :slug,
                content = :content
                WHERE id = :id',
            [
                'title' => $this->title,
                'image' => $this->image,
                'lead' => $this->lead,
                'author_id' => $this->authorId,
                'category_id' => $this->categoryId,
                'slug' => $this->slug,
                'content' => $this->content,
                'id' => $id
            ]
        );
    }

    public static function getTableName(): string
    {
        return self::TABLE_NAME;
    }

    public static function setPost($user): Post
    {
        $post = new self();
        $post->setTitle($_POST['title'])
            ->setLead($_POST['lead'])
            ->setAuthorId($user['id'])
            ->setCategoryId($_POST['category'])
            ->setContent($_POST['content']);
        if (!empty($_FILES['image']['name'])) {
            $post->setImage($_FILES['image']);
            PostRepository::uploadImage($_FILES['image']);
        }
        return $post;
    }
}
