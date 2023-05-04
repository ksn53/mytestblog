<?php
namespace App\Widgets;

use App\Model\User;

class WidgetController
{
    public $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(VIEW_DIR . '/layout/');
        $this->twig = new \Twig\Environment($loader);
        $this->twig->addExtension(new \Twig\Extensions\TextExtension());
    }

    public function returnUserProperty($userId, $property)
    {
        return User::where('id', $userId)->value($property);
    }
    public function showCurrentId()
    {
        $currentId = "";
        if (isset($_SESSION["id"])) {
            $currentId = $_SESSION["id"];
        }
        return $currentId;
    }
    public function showCurrentLogin()
    {
        $currentLogin = "";
        if (isset($_SESSION["currentLogin"])) {
            $currentLogin = $_SESSION["currentLogin"];
        }
        return $currentLogin;
    }
}