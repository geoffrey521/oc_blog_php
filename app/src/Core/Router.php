<?php

namespace App\Core;

use App\Controller\CategoryController;
use App\Controller\ExceptionController;
use App\Controller\FrontController;
use App\Controller\PostController;
use App\Controller\UserController;

class Router
{
    protected $routes;

    public function __construct()
    {
        $this->routes = [
            'home' => [
                'controller' => new FrontController(),
                'action' => 'home',
                'path' => ''
            ],
            'contact' => [
                'controller' => new FrontController(),
                'action' => 'contact',
                'path' => 'contact'
            ],
            'login' => [
                'controller' => new UserController(),
                'action' => 'login',
                'path' => 'login'
            ],
            'register' => [
                'controller' => new UserController(),
                'action' => 'register',
                'path' => 'register'
            ],
            'confirm' => [
                'controller' => new UserController(),
                'action' => 'confirm',
                'path' => 'confirm'
            ],
            'logout' => [
                'controller' => new UserController(),
                'action' => 'logout',
                'path' => 'logout'
            ],
            'admin' => [
                'controller' => new UserController(),
                'action' => 'admin',
                'path' => 'admin'
            ],
            'category' => [
                'controller' => new CategoryController(),
                'action' => 'category',
                'path' => 'category'
            ],
            'showPosts' => [
                'controller' => new PostController(),
                'action' => 'showPosts',
                'path' => 'posts'
            ],
            'managePosts' => [
                'controller' => new UserController(),
                'action' => 'managePosts',
                'path' => 'manage_post'
            ],
            'create' => [
                'controller' => new PostController(),
                'action' => 'create',
                'path' => 'admin_create'
            ],
            'edit' => [
                'controller' => new PostController(),
                'action' => 'edit',
                'path' => 'edit',
                'params' => ''
            ],
            'delete' => [
                'controller' => new PostController(),
                'action' => 'delete',
                'path' => 'delete',
                'params' => ''
            ],
            'singlePost' => [
                'controller' => new PostController(),
                'action' => 'singlePost',
                'path' => 'singlePost',
                'params' => ''
            ]

        ];
    }

    public static function match()
    {
        $uri = array_slice(explode('/', $_SERVER['REQUEST_URI']), 1);
        $path = array_shift($uri);

        $self = new self();
        $controller = $self->findByPath($path ?? '');
        if (empty($controller)) {
            return [
                'controller' => new ExceptionController(),
                'action' => 'error404',
                'params' => []
                ];
        }

        $controller = array_pop($controller);
        $controller['params'] = self::generateParams($uri);
        return $controller;
    }

    private static function generateParams($params = [])
    {
        $newParams = [];
        for ($i = 0; $i < count($params) - 1; $i++) {
            $newParams[$params[$i]] = $params[$i + 1];
        }
        return $newParams;
    }

    public static function generateRoute($name, $params = [])
    {
        $self = new self();
        $route = '/' . $self->getPath($name);
        if (!is_array($params)) {
            return $route;
        }
        $httpBuild = str_replace('=', '/', http_build_query($params, null, '/'));
        return $route . '/' . $httpBuild;
    }

    public function getPath($name, $params = [])
    {
        if (!isset($this->routes[$name])) {
            throw new \Exception('route not define');
        }
        return $this->routes[$name]['path'];
    }

    public function findByPath($path)
    {
        return array_filter($this->routes, function ($route) use ($path) {
            return $route['path'] === $path;
        });
    }
}
