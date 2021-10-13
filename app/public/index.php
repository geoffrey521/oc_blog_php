<?php

use App\Controller\MainController;

require '../../vendor/autoload.php';

$frontController = new MainController();

/*$loader = new \Twig\Loader\ArrayLoader([
   'index' => 'hello {{ name }}!',
]);*/
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../src/Views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
]);

$page = '';

if (isset($_GET['p'])) {
    $page = $_GET['p'];
}

switch ($page) {
    case 'home':
        echo $twig->render('content/node/homepage.html.twig');
        break;
    case 'test':
        echo $twig->render('content/node/test.html.twig', ['person' => [
            'name' => 'George',
            'age' => 22
        ]]);
        break;
    default:
        echo $twig->render('pages/page-404.html.twig');
        break;
}
