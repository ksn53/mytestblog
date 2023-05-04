<?php
namespace App\Admin;

use App\Model\Pages;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class PageListController extends Controller
{
    public function render($data)
    {
        $data['pagelist'] = $this->allPagesNames();
        $this->view = new View('admin/pagelist.twig.html', $data);
    }

    public function allPagesNames()
    {
        $pages = Pages::All();
        $pagesNames =[];
        foreach ($pages as $page) {
            $pagesNames[] = [$page->id, $page->title];
        }
        return $pagesNames;
    }

}