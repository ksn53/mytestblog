<?php
namespace App;
use App\Model\Routes;

class Routes extends Controller
{
    public function __construct()
    {
        //$this->view = new BooksView($route, $data, self::show_books());
    }

    public static function createRoute($method, $route, $controller, $data)
    {
        $arr = ["method" => $method, "route" => $route, "controller" => $controller, "data" => $data];
        $route = Routes::create($arr);
        return $route;
    }
}