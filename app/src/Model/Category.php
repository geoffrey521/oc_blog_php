<?php

namespace App\Model;

use App\Repository\CategoryRepository;

class Category
{
    private const TABLE_NAME = 'category';
    private $id;
    private $name;
    private $slug;
    private $parent_id;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parent_id): void
    {
        $this->parent_id = $parent_id;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getParent()
    {
        return CategoryRepository::findById($this->getParent());
    }

    public static function getTableName()
    {
        return self::TABLE_NAME;
    }
}
