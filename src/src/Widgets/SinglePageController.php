<?php
namespace App\Widgets;

use App\Model\Pages;

class SinglePageController extends WidgetController
{
    public function showPage($id)
    {
        return Pages::where("id", $id)->first();
    }

    public function render($params)
    {
        $page = $this->showPage($params[0]);
        echo $this->twig->render('singlepage.html.twig',  ['title' => $page->title, 'content' => $page->content, 'created_at' => substr($page->created_at, 0, -9), 'author' => $this->returnUserProperty($page->author, 'name')]);
    }
}


