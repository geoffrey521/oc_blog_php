<?php

namespace App\Model;

class Validator
{

    private $errors = [];

    public function __construct(private $data)
    {
        $this->data = $data;
    }

    private function getField($field)
    {
        if (!isset($this->data[$field])) {
            return null;
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

    public function isUniq($field, $db, $table, $errorMsg)
    {
        $record = $db->query("SELECT id FROM $table WHERE username = ?", [$this->getField($field)])->fetch();
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

    public function isConfirmed($field, $errorMsg = '')
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



    public function isValid()
    {
        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
