<?php

use App\Controller\ExceptionController;
use App\Controller\FrontController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Controller\UserController;

require '../../vendor/autoload.php';

$whoops = new \Whoops\Run();
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
$whoops->register();

$controller = \App\Core\Router::match();
call_user_func_array([$controller['controller'], $controller['action']], $controller['params']);
