<?php
namespace App\Widgets;

class CurrentLoginController extends WidgetController
{
    public function render($params = null)
    {
        $login = $this->showCurrentLogin();
        $userId = $this->showCurrentId();
        return $this->twig->render('currentLoginWidget.twig.html', ['currentLogin' => $login, 'servername' => $_SERVER['HTTP_HOST'], 'userid' => $userId]);
    }

}