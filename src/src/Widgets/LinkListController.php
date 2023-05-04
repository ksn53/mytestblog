<?php
namespace App\Widgets;

use App\Model\Links;

class LinkListController extends WidgetController
{
    public static function allCLinkNames()
    {
        $links = Links::All();
        $linkNames =[];
        foreach ($links as $link) {
            $linkNames[] = [$link->id, $link->title, $link->url];
        }
        return $linkNames;
    }

    public function render($params = null)
    {
        $links = self::allCLinkNames();
        return $this->twig->render('linklist.html.twig', ['links' => $links]);
    }

}