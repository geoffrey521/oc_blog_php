<?php

namespace App\Model;

use PDO;
use PDOStatement;

class MainModel
{

    protected $pdo;

    public function __construct($params = [])
    {
        $this->pdo = Database::getDatabase();
        foreach ($params as $key => $value) {
            $camelKey = underscoreToCamelCase($key);
            $this->$camelKey = $value;
        }
    }

    /**
     * @param $request
     * @param bool|array $params
     * @return false|PDOStatement
     */
    public function query($request, array $params)
    {
        if ($params) {
            $req = $this->pdo->prepare($request);
            ;
            $req->execute($params);
            return $req;
        }
        return $this->pdo->query($request);
    }

    /**
     * @return int
     */
    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * @param $string
     * @param string $delimiter
     * @return string
     */
    public function slugify($string, $delimiter = '-')
    {
        $oldLocale = setlocale(LC_ALL, '0');
        setlocale(LC_ALL, 'en_US.UTF-8');
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower($clean);
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        $clean = trim($clean, $delimiter);
        setlocale(LC_ALL, $oldLocale);
        return $clean;
    }
}
