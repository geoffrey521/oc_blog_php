<?php

namespace App\Repository;

use App\Model\CustomPage;

class CustomPageRepository extends BaseRepository implements RepositoryInterface
{

    public static function findAll()
    {
        return self::getMany("SELECT * FROM " . CustomPage::getTableName() . " ORDER BY id DESC", CustomPage::class);
    }

    public static function findById(int $id)
    {
        return self::getOne(CustomPage::getTableName(), CustomPage::class, ['id' => $id]);
    }

    public static function findBySlug(string $slug)
    {
        return self::getOne(CustomPage::getTableName(), CustomPage::class, ['slug' => $slug]);
    }

    public static function deleteById(int $id)
    {
        return self::delete(CustomPage::getTableName(), ['id' => $id]);
    }

    public static function deleteImageByPageId(int $id)
    {
        return self::deleteOneFieldValueById(CustomPage::getTableName(), 'image', ['id' => $id]);
    }

    public static function findDisplayedMenuPages()
    {
        return self::getMany("SELECT * FROM " . CustomPage::getTableName(), CustomPage::class, ['display_navbar' => 1]);
    }

    public static function findDisplayedFooterPages()
    {
        return self::getMany("SELECT * FROM " . CustomPage::getTableName(), CustomPage::class, ['display_footer' => 1]);
    }
}
