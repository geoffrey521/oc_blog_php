<?php

namespace App\Core;

use App\Model\Session;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

class Controller
{

    protected $twig = null;
    protected Session $session;

    public function __construct()
    {
        $this->twig = new Environment(new FilesystemLoader(__DIR__ . '/../Views'), [
            'cache' => false,
            'debug' => true,
        ]);
        $this->twig->addExtension(new DebugExtension());
        $this->session = Session::getInstance();
        $this->twig->addGlobal('session', $this->session);
    }

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
