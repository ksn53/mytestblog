<?php
namespace App\Widgets;

use App\Model\Pages;

class PagesListController extends WidgetController
{
    public static function allPagesNames()
    {
        $pages = Pages::All();
        $pagesNames =[];
        foreach ($pages as $page) {
            $pagesNames[] = [$page->id, $page->title];
        }
        return $pagesNames;
    }

    public function render($params = null)
    {
        $pages = self::allPagesNames();
        $serverName= $_SERVER['HTTP_HOST'];
        return $this->twig->render('pageslist.html.twig', ['pages' => $pages, 'servername' => $serverName]);
    }

}