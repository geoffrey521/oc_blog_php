<?php

const DB = [
    'host' => 'db',
    'name' => 'blogphp',
    'user' => 'root',
    'password' => 'root'
];

const MAX_IMAGE_POST_FILESIZE = 1000000;

define("DEBUG_TIME", microtime(true));

function underscoreToCamelCase($string, $capitalizeFirstCharacter = false)
{

    $str = str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));

    if (!$capitalizeFirstCharacter) {
        $str[0] = strtolower($str[0]);
    }

    return $str;
}

