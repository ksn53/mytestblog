<?php
namespace App\Admin;

use App\Model\Links;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class LinkListController extends Controller
{
    public function render($data)
    {
        $data['linklist'] = $this->allCLinkNames(10, 1);
        $this->view = new View('admin/linklist.twig.html', $data);
    }

    public function allCLinkNames()
    {
        $links = Links::All();
        $linkNames =[];
        foreach ($links as $link) {
            $linkNames[] = [$link->id, $link->title, $link->url];
        }
        return $linkNames;
    }

}