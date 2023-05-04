<?php
namespace App\Admin;

use App\Model\Subscribtions;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class SubscribtionsListController extends Controller
{
    public function render($data)
    {
        $data['subscribtions'] = $this->allSubscribtions();
        $this->view = new View('admin/subscribtionslist.twig.html', $data);
    }

    public function allSubscribtions()
    {
        $subscribtions = Subscribtions::All();
        $subscribtionFiels =[];
        foreach ($subscribtions as $subscribtion) {
            $subscribtionFiels[] = [$subscribtion->id, $subscribtion->email];
        }
        return $subscribtionFiels;
    }
    //удаление подписки
    public function ajaxDeleteSub()
    {
        $id = $_POST['id'];
        if (Subscribtions::where("id", $id)->delete() == 1) {
            return true;
        } else {
            return false;
        }
    }

}