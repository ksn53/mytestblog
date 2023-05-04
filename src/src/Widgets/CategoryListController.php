<?php
namespace App\Widgets;

use App\Model\Categorys;

class CategoryListController extends WidgetController
{
    public static function allCategorysNames()
    {
        $categorys = Categorys::All();
        $categorysNames =[];
        foreach ($categorys as $category) {
            $categorysNames[$category->id] = $category->name;
        }
        return $categorysNames;
    }

    public function render($params = null)
    {
        $articles = self::allCategorysNames();
        $serverName= $_SERVER['HTTP_HOST'];
        return $this->twig->render('categorylist.html.twig', ['articles' => $articles, 'servername' => $serverName]);
    }

}