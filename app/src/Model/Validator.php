<?php

namespace App\Model;

class Validator extends MainModel
{
    private $errors = [];

    public function __construct(private $data)
    {
        parent::__construct();
    }

    private function getField($field, $key = null)
    {
        if (!isset($this->data[$field])) {
            return null;
        }
        if (!empty($key)) {
            return $this->data[$field][$key];
        }
        return $this->data[$field];
    }

    public function isAlpha($field, $errorMsg)
    {
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $this->getField($field))) {
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isNotEmpty($field, $errorMsg)
    {
        if (empty($this->getField($field))) {
            return $this->errors[$field] = $errorMsg;
        }
    }

    public function isUniq($field, $table, $errorMsg)
    {
        $record = $this->query("SELECT id FROM $table WHERE $field = ?", [$this->getField($field)])->fetch();
        if ($record) {
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isEmail($field, $errorMsg)
    {
        if (!filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)) {
            return $this->errors[$field] = $errorMsg;
        }
    }

    public function areSamePasswords($field, $errorMsg = '')
    {
        if (empty($this->getField($field)) || $this->getField($field) != $this->getField($field . '_confirm')) {
            $this->getField($field . '_confirm');
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isAgree($field, $errorMsg)
    {
        if (empty($this->getField($field))) {
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isExist($field, $table, $errorMsg)
    {
        $record = $this->query("SELECT $field FROM $table WHERE $field = ?", [$this->getField($field)]);
        if (!$record) {
            $this->errors[$field] = $errorMsg;
        }
    }

    public function isImageValid($field, $errorMsg)
    {
        if ($this->isImageOverSize($field, $errorMsg)) {
            return $this->errors[$field] = $this->isImageOverSize($field, $errorMsg);
        }
        if ($this->isNotAllowedExtension($field, $errorMsg)) {
            return $this->isNotAllowedExtension($field, $errorMsg);
        }
    }

    public function isImageOverSize($field, $errorMsg)
    {
        if ($this->getField($field, 'size') > MAX_IMAGE_POST_FILESIZE) {
            return $errorMsg . "l'image ne doit pas faire plus de " . MAX_IMAGE_POST_FILESIZE / 1000000 . "Mo";
        }
    }

    public function isNotAllowedExtension($field, $errorMsg)
    {
        $fileInfo = pathinfo($this->getField($field, 'name'));

        $extension = $fileInfo['extension'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        if (!in_array($extension, $allowedExtensions)) {
            return $errorMsg . "Seul les formats " . implode(', ', $allowedExtensions) . ", sont accept??s.";
        }
    }

    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function validatePost()
    {
        if (!empty($_FILES['image']['name'])) {
            $this->isImageValid('image', 'Image invalide: ');
        }
        $this->isNotEmpty('title', "Merci d'entrer un titre");
        $this->isNotEmpty('lead', "Merci d'entrer un chap??");
        $this->isNotEmpty('category', "Merci de s??lectionner une cat??gorie");
        $this->isNotEmpty('content', "L'article ne contient aucun contenu");
    }

    public function validateComment()
    {
        $this->isNotEmpty('content', 'Le commentaire ne doit pas ??tre vide');
    }
}
