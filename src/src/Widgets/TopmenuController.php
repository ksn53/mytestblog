<?php
namespace App\Widgets;

use App\Model\Topmenu;

class TopmenuController extends WidgetController
{
    public static function allTopmenu()
    {
        $links = Topmenu::All();
        $linkNames =[];
        foreach ($links as $link) {
            $linkNames[] = [$link->id, $link->title, $link->url];
        }
        return $linkNames;
    }

    public function render($params = null)
    {
        $links = self::allTopmenu();
        return $this->twig->render('topmenu.html.twig', ['links' => $links]);
    }

}