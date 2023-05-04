<?php
namespace App;

use Exception;

class Application
{
    public $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function renderException($e)
    {
        if ($e instanceof Exception) {
            //$e->render();
            var_dump($e);
        } else {
            throw new Exception('Ошибка сервера', 500);
            echo $e->getMessage();
        }
    }

    public function run()
    {
        try {
            $dispatchRoute = $this->router->dispatch();
            if ($dispatchRoute instanceof Renderable) {
                $dispatchRoute->render();
            } elseif (method_exists($dispatchRoute, 'ajaxData')) {
                echo $dispatchRoute->ajaxData();
            } else {
                echo json_encode($dispatchRoute);
            }
        } catch (Exception $e) {
            $this->renderException($e);
        }

    }
}
