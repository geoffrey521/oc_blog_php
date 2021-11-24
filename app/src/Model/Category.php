<?php

namespace App\Model;

use App\Repository\CategoryRepository;

class Category extends MainModel
{
    protected const TABLE_NAME = 'category';
    protected $id;
    protected $name;
    protected $slug;
    protected $parentId;

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
        $this->slug = $this->slugify($name);
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * @param mixed $parentId
     */
    public function setParentId($parentId): void
    {
        $this->parentId = $parentId;
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

    public function add()
    {
        $this->query(
            'INSERT INTO category (name, slug) VALUES (:name, :slug)',
            [
                'name' => $this->name,
                'slug' => $this->slug,
            ]
        );
    }
}
