<?php

namespace App\Core;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class Controller
{

    protected $twig = null;

    public function __construct()
    {
        $this->twig = new Environment(new FilesystemLoader(__DIR__ . '/../Views'), [
            'cache' => false,
            'debug' => true,
        ]);
        $this->twig->addExtension(new DebugExtension());
    }

//    function renderer($view, array $params = [])
//    {
//        if (!empty($params)) {
//            foreach ($params as $key => $value) {
//                ${$key} = $value;
//            }
//        }
//        echo $this->twig->render('/' . $view . '.html.twig', [$params]);
//    }

//    /**
//     * @throws \Twig\Error\RuntimeError
//     * @throws \Twig\Error\SyntaxError
//     * @throws \Twig\Error\LoaderError
//     */
//    public function render($twig, array $params = [])
//    {
//        return $this->twig->render($twig, $params);
//    }

    public function redirectTo($class, $action = "index", array $params = [])
    {
        $attributes = "";
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $attributes = $attributes . "&" . $key . "=" . $value;
            }
        }
        header("Location: index.php?c=$class&a=$action" . $attributes);
        exit();
    }
}
