<?php
namespace App;

use App\Model\Routes;
use Illuminate\Database\Capsule\Manager as Capsule;

class Router
{
    public $routes;

    public function __construct()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $this->initialize();
        $routes = Routes::all();
        foreach ($routes as $route) {
            $this->get($route->method, $route->route, $route->controller, json_decode($route->data, true));
        }
    }

    public function initialize()
    {
        $capsule = new Capsule;

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => Config::getInstance()->get('db.mysql.host'),
            'database'  => Config::getInstance()->get('db.mysql.dbname'),
            'username'  => Config::getInstance()->get('db.mysql.user'),
            'password'  => Config::getInstance()->get('db.mysql.password'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    public function get($method, $path, $callback, $data)
    {
        $this->routes[] = new Route($method, $path, $callback, $data);
    }

    public function dispatch()
    {
        foreach ($this->routes as $route)
        {
            $uri = $_SERVER['REQUEST_URI'];
            if ($route->match($_SERVER['REQUEST_METHOD'], $uri) === true)
            {
                return $route->run($uri);
            }
        }
        throw new NotFoundException('Router сказал - Страница не найдена.', 404);
    }
}

