<?php
namespace App\Widgets;

class PaginatorController extends WidgetController
{
    public function render($params)
    {
        $uri = explode('/', $_SERVER['REQUEST_URI'])[1];
        if ($uri == "main") {
            $params[2] = "";
        }
        return $this->twig->render('paginator.html.twig', ['postsLastPage' => $params[0], 'postsCurrentpage' => $params[1], 'urlPath' => $params[2]]);
    }
}