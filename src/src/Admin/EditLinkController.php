<?php
namespace App\Admin;

use App\Model\Links;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class EditLinkController extends Controller
{
    public function render($data)
    {
        $data['link'] = [];
        $data['mode'] = 'AddLink';
        if (isset($data['uridata'][0])) {
            $data['mode'] = 'UpdateLink';
            $data['link'] = $this->showLink($data['uridata'][0]);
        }
        $this->view = new View('admin/editlink.twig.html', $data);
    }

    public function showLink($id)
    {
        $link = Links::where("id", $id)->first();
        $showLink = [$link->id, $link->title, $link->url];
        return $showLink;
    }

    public function ajaxAddLink()
    {
        $today = date("Y-m-d");
        $dataToInsert = ['title' => $_POST['title'], 'url' => $_POST['url']];
        if (Links::insert($dataToInsert)) {
            $returnData[0] = true;
            $returnData[1] = 'Ссылка добавлена.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/linklist';
            $returnData[4] = 1;
            return $returnData;
        } else {
            $returnData = [false, 'Ошибка добавления ссылки.'];
        }
    }
    public function ajaxUpdateLink()
    {
        $id = $_POST['id'];
        $dataToUpdate = ['title' => $_POST['title'], 'url' => $_POST['url']];
        $link = Links::where("id", $id)->first();
        if ($link->update($dataToUpdate)) {
            $returnData[0] = true;
            $returnData[1] = 'Ссылка обновлена.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/linklist';
            $returnData[4] = 1;
            return $returnData;
        } else {
            $returnData = [false, 'Ошибка обновления ссылки.'];
        }
    }
    public function ajaxDeleteLink()
    {
        $id = $_POST['id'];
        if (Links::where("id", $id)->delete() == 1) {
            return true;
        } else {
            return false;
        }
    }

}