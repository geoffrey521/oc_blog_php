<?php

namespace App\Core;

use App\Controller\CategoryController;
use App\Controller\CommentController;
use App\Controller\ExceptionController;
use App\Controller\FrontController;
use App\Controller\CustomPageController;
use App\Controller\PostController;
use App\Controller\UserController;
use Exception;

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
            'forget' => [
                'controller' => new UserController(),
                'action' => 'forget',
                'path' => 'forget'
            ],
            'reset' => [
                'controller' => new UserController(),
                'action' => 'reset',
                'path' => 'reset',
                'params' => ''
            ],
            'register' => [
                'controller' => new UserController(),
                'action' => 'register',
                'path' => 'register'
            ],
            'confirmAccount' => [
                'controller' => new UserController(),
                'action' => 'confirmAccount',
                'path' => 'confirm_account',
                'params' => ''
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
                'path' => 'manage_posts'
            ],
            'createComment' => [
                'controller' => new CommentController(),
                'action' => 'createComment',
                'path' => 'create_comment',
                'params' => ''
            ],
            'validateComment' => [
                'controller' => new CommentController(),
                'action' => 'validateComment',
                'path' => 'validate_comment',
                'params' => ''
            ],
            'deleteComment' => [
                'controller' => new CommentController(),
                'action' => 'deleteComment',
                'path' => 'delete_comment',
                'params' => ''
            ],
            'manageComments' => [
                'controller' => new UserController(),
                'action' => 'manageComments',
                'path' => 'manage_comments',
                'params' => ''
            ],
            'showCommentsList' => [
                'controller' => new CommentController(),
                'action' => 'showCommentsList',
                'path' => 'show_comments_list'
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
            'deletePostImage' => [
                'controller' => new PostController(),
                'action' => 'deletePostImage',
                'path' => 'delete_post_image',
                'params' => ''
            ],
            'singlePost' => [
                'controller' => new PostController(),
                'action' => 'singlePost',
                'path' => 'singlePost',
                'params' => ''
            ],
            'showCustomPage' => [
                'controller' => new CustomPageController(),
                'action' => 'showCustomPage',
                'path' => 'page',
                'params' => ''
            ],
            'managePages' => [
                'controller' => new UserController(),
                'action' => 'managePages',
                'path' => 'manage_pages'
            ],
            'createPage' => [
                'controller' => new CustomPageController(),
                'action' => 'createPage',
                'path' => 'create_page'
            ],
            'editPage' => [
                'controller' => new CustomPageController(),
                'action' => 'editPage',
                'path' => 'edit_page',
                'params' => ''
            ],
            'deletePage' => [
                'controller' => new CustomPageController(),
                'action' => 'deletePage',
                'path' => 'delete_page',
                'params' => ''
            ],
            'deletePageImage' => [
                'controller' => new CustomPageController(),
                'action' => 'deletePageImage',
                'path' => 'delete_page_image',
                'params' => ''
            ],
            'manageCategories' => [
                'controller' => new UserController(),
                'action' => 'manageCategories',
                'path' => 'manage_categories'
            ],
            'createCategory' => [
                'controller' => new CategoryController(),
                'action' => 'createCategory',
                'path' => 'create_category'
            ],
            'deleteCategory' => [
                'controller' => new CategoryController(),
                'action' => 'deleteCategory',
                'path' => 'delete_category',
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
            if ($i % 2 == 1) {
                continue;
            }
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

        return (!empty($httpBuild)) ? $route . '/' . $httpBuild : $route;
    }

    public function getPath($name, $params = [])
    {
        if (!isset($this->routes[$name])) {
            throw new Exception('route not define');
        }
        return $this->routes[$name]['path'];
    }

    public function findByPath($path)
    {
        return array_filter(
            $this->routes,
            function ($route) use ($path) {
                return $route['path'] === $path;
            }
        );
    }
}
