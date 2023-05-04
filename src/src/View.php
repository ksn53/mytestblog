<?php
namespace App;

class View implements Renderable
{
    public $data;
    public $mainTemplate;
    public $twig;
    //public $template;

    public function __construct($mainTemplate, array $data)
    {
        $this->data = $data;
        $this->mainTemplate = $mainTemplate;
        $this->initializeTwig();
        //$this->template = $template;
        //echo $this->twig->render($template, $array);
    }

    public function initializeTwig()
    {
        $loader = new \Twig\Loader\FilesystemLoader(VIEW_DIR . '/layout/');
        $this->twig = new \Twig\Environment($loader);
        $controllerFunction = new \Twig\TwigFunction('controller', function ($widgetController, $widgetMethod, $params) {
            $activeWidgetController = new $widgetController();
            echo $activeWidgetController->$widgetMethod($params);
        });
        $this->twig->addFunction($controllerFunction);
        $this->twig->addExtension(new \Twig\Extensions\TextExtension());
    }

    /*public function includeView($templateName, $data = null)
    {
        include VIEW_DIR . '/layout/' . $templateName . '.php';
    }*/

    public function includeTwigTemplate($template, $array)
    {
        echo $this->twig->render($template, $array);
    }

    public function render()
    {
        //$this->includeTwigTemplate('header.html', ['title' => $this->data['maindata']['title'], 'currentLogin' => $this->showCurrentLogin()]);
        echo $this->twig->render($this->mainTemplate, $this->data);
    }
}
