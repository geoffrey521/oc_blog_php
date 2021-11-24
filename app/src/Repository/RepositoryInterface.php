<?php

namespace App\Repository;

interface RepositoryInterface
{
    public static function findAll();

    public static function findById(int $id);

    public static function deleteById(int $id);
}
