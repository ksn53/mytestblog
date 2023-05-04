<?php
namespace App\Admin;

use App\Model\Categorys;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class CatListController extends Controller
{
    public function render($data)
    {
        $currentPage = 1;
        if (isset($data['uridata'][0])) {
            $currentPage = $data['uridata'][0];
        }
        $data['catlist'] = $this->allCategoryNames();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view = new View('admin/catlist.twig.html', $data);
        }
    }

    public static function allCategoryNames()
    {
        $categorys = Categorys::All();
        $categoryNames =[];
        foreach ($categorys as $category) {
            $categoryNames[] = [$category->id, $category->name];
        }
        return $categoryNames;
    }
}