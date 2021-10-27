<?php

use App\Controller\TmpControllerFactory;
use App\Controller\FrontController;


require '../../vendor/autoload.php';

$frontController = new TmpControllerFactory();

/*$loader = new \Twig\Loader\ArrayLoader([
   'index' => 'hello {{ name }}!',
]);*/
//$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../src/Views');
//$twig = new \Twig\Environment($loader, [
//    'cache' => false,
//]);

//if(isset($_GET['c']))
//{
//    if(!empty($_GET['c']))
//    {
//        $class = ucfirst((strtolower($_GET['c']))) . "Controller";
//        try {
//            $c = new $class();
//        } catch (Exception $e) {
//            die($e->getMessage());
//        }
//
//        if(isset($_GET['a']))
//        {
//            if (!empty($_GET)) {
//                //todo
//            }
//        }
//    }
//} else {
//    $c = new \App\Controller\HomeController();
//    $c->index();
//}

if(isset($_GET['c']))
{
    if(!empty($_GET['c']))
    {
        $class = strtolower($_GET['c']);
        if (isset($_GET['a']))
        {
            $action = $_GET['a'];
        }

        switch ($class)
        {
            case 'home':
                $c = new \App\Controller\HomeController();
                $c->index();
            break;
            case 'user':
                $c = new \App\Controller\UserController();
                if(isset($action)) {
                    try {
                        $c->$action();
                    } catch (Exception $e) {
                        die($e->getMessage());
                    }

                }

        }
    }
} else {
    $c = new \App\Controller\HomeController();
    $c->index();
}

/*********** WAS FOR TMP FILES ******************/
//$page = null;
//if (isset($_GET['p'])) {
//    $page = $_GET['p'];
//} else {
//    $page = 'home';
//}
//switch ($page) {
//    case 'home':
//        echo $twig->render('front/home.html.twig');
//        break;
//    case 'login':
//        require __DIR__ . '/../src/Views/Tmpcontent/node/tmp-login.php';
//        //echo $twig->render('Tmpcontent/node/node-login.html.twig');
//        break;
//    case 'register':
//        require __DIR__ . '/../src/Views/Tmpcontent/node/tmp-register.php';
//        //echo $twig->render('Tmpcontent/node/node-register.html.twig');
//        break;
//    case 'confirm':
//        require __DIR__ . '/../src/Views/Tmpcontent/node/tmp-confirm.php';
//        break;
//    case 'account':
//        require __DIR__ . '/../src/Views/Tmpcontent/node/tmp-account.php';
//        //echo $twig->render('Tmpcontent/node/tmp-account.php');
//        break;
//    case 'logout':
//        require __DIR__ . '/../src/Views/Tmpcontent/node/tmp-logout.php';
//        break;
//    case 'forget':
//        require __DIR__ . '/../src/Views/Tmpcontent/node/tmp-forgetPassword.php';
//        break;
//    case 'reset':
//        require __DIR__ . '/../src/Views/Tmpcontent/node/tmp-resetPassword.php';
//        break;
//    default:
//        echo $twig->render('pages/page-404.html.twig');
//        break;
//}
