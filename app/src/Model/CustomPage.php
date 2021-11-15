<?php

namespace App\Model;

use Monolog\Handler\IFTTTHandler;

class CustomPage extends MainModel
{
    protected const TABLE_NAME = "page";

    private $id;
    private $name;
    private $title;
    private $catchPhrase;
    private $image;
    private $contentTitle;
    private $content;
    private $displayNavbar = null;
    private $displayFooter = null;
    private $slug;
    private $published;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
        $this->slug = $this->slugify($name);
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getCatchPhrase()
    {
        return $this->catchPhrase;
    }

    /**
     * @param string $catchPhrase
     */
    public function setCatchPhrase(string $catchPhrase)
    {
        $this->catchPhrase = $catchPhrase;
        return $this;
    }

    /**
     * @param  $image
     * @return $this
     */
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

    /**
     * @return mixed
     */
    public function getContentTitle()
    {
        return $this->contentTitle;
    }

    /**
     * @param string $contentTitle
     */
    public function setContentTitle(string $contentTitle)
    {
        $this->contentTitle = $contentTitle;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayNavbar()
    {
        return $this->displayNavbar;
    }

    /**
     * @param mixed|null $displayNavbar
     */
    public function setDisplayNavbar(bool $displayNavbar = null)
    {
        $this->displayNavbar = $displayNavbar;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayFooter()
    {
        return $this->displayFooter;
    }


    public function setDisplayFooter(bool $displayFooter = null)
    {
        $this->displayFooter = $displayFooter;
        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setPublished($published)
    {
        $this->published = $published;
    }

    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @return string
     */
    public static function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function add()
    {
        $this->query(
            'INSERT INTO page(
                 name, title, catch_phrase, background, content_title, 
                 content, display_navbar, display_footer, slug, published)
                 VALUES(
                        :name, :title, :catch_phrase, :background, 
                        :content_title, :content, :display_navbar, :display_footer, :slug, :published)',
            [
                'name' => $this->name,
                'title' => $this->title,
                'catch_phrase' => $this->catchPhrase,
                'background' => $this->image['name'],
                'content_title' => $this->contentTitle,
                'content' => $this->content,
                'display_navbar' => $this->displayNavbar,
                'display_footer' => $this->displayFooter,
                'slug' => $this->slug,
                'published' => $this->published
            ]
        );
    }

    public function edit()
    {
        $this->query(
            'UPDATE page SET (
                 title, catch_phrase, background, content_title, content, display_navbar, display_footer, published)
                 VALUES(
                 :title, :catch_phrase, :background, :content_title, 
                   :content, :display_navbar, :display_footer, :published)',
            [
                'title' => $this->title,
                'catch_phrase' => $this->catchPhrase,
                'background' => $this->image['name'],
                'content_title' => $this->contentTitle,
                'content' => $this->content,
                'display_navbar' => $this->displayNavbar,
                'display_footer' => $this->displayFooter,
                'published' => $this->published
            ]
        );
    }
}
