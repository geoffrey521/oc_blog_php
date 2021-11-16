<?php

namespace App\Model;

use App\Core\Text;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use DateTime;

class Post extends MainModel
{
    protected const TABLE_NAME = "post";

    private $id;
    private $title;
    private $image;
    private $lead;
    private $excerpt;
    private $content;
    private $author;
    private $category;
    private $authorId;
    private $categoryId;
    private $slug;
    private $last_update;

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
            $this->image = $image;
        }
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
     * @return array
     */
    public function getCategoryId()
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
        $this->last_update = date('d-F-Y');
        return $this;
    }


    /**
     * @return mixed
     * @throws \Exception
     */
    public function getLastUpdate(): DateTime
    {
        return new DateTime($this->last_update);
    }

    public function add()
    {
        $this->query(
            'INSERT INTO post(
                 title, image, lead, author_id, category_id, slug, content)
                 VALUES(:title, :image, :lead, :author_id, :category_id, :slug, :content)',
            [
                'title' => $this->title,
                'image' => $this->image['name'],
                'lead' => $this->lead,
                'author_id' => $this->authorId,
                'category_id' => $this->categoryId,
                'slug' => $this->slug,
                'content' => $this->content
            ]
        );
    }

    public function edit()
    {
        $this->query(
            'UPDATE post SET (
                 title, image, lead, author_id, category_id, slug, content)
                 VALUES(:title, :image, :lead, :author_id, :category_id, :slug, :content)',
            [
                'title' => $this->title,
                'image' => $this->image['name'],
                'lead' => $this->lead,
                'author_id' => $this->authorId,
                'category_id' => $this->categoryId,
                'slug' => $this->slug,
                'content' => $this->content
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
            ->setImage($_FILES['image'])
            ->setAuthorId($user['id'])
            ->setCategoryId($_POST['category'])
            ->setSlug($_POST['slug'])
            ->setContent($_POST['content']);
        PostRepository::uploadImage($post);

        return $post;
    }
}
