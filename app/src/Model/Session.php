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
        error_log(json_encode($_SESSION));
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
        return self::read('auth');
    }

    /**
     * restrict page for connected user only
     */
    public function isLogged()
    {
        return self::read('auth') !== null;
    }

    public function updateAuth($user)
    {
        $_SESSION['auth']['username'] = $user->getUsername();
        $_SESSION['auth']['firstname'] = $user->getFirstname();
        $_SESSION['auth']['lastname'] = $user->getLastname();
    }
}
