<?php

namespace App\Model;

class Session
{

    protected static $instance;

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Session();
        }
        return self::$instance;
    }

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    public function hasFlashes()
    {
        return isset($_SESSION['flash']);
    }

    public function getFlashes()
    {
        $flashes = [];
        if (isset($_SESSION['flash'])) {
            $flashes = $_SESSION['flash'];
            unset($_SESSION['flash']);
        }
        return $flashes;
    }

    public static function write($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function read($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public function getUser()
    {
        if (isset($_SESSION['auth'])) {
            $auth = [];
            $auth['id'] = $_SESSION['auth']->id;
            $auth['username'] = $_SESSION['auth']->username;
            $auth['isAdmin'] = $_SESSION['auth']->is_admin;
            return $auth;
        }
        return false;
    }

    /**
     * restrict page for connected user only
     */
    public function isLogged()
    {
        return self::read('auth');
    }
}
