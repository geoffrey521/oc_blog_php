<?php

namespace App\Core;

use App\Model\Session;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;
use App\Core\Router;

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
        $this->twig->addGlobal('debug_time', round(1000 * (microtime(true) - DEBUG_TIME)));
        $this->twig->addFunction(new TwigFunction('generate_route', function ($name, $params = []) {
            return Router::generateRoute($name, $params);
        }));
    }

    public function redirectTo($class, $action = "", array $params = [])
    {
        $attributes = "";
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $attributes = $attributes . "&" . $key . "=" . $value;
            }
        }
        header("Location: /$action" . $attributes);
        exit();
    }
}
