<?php
namespace App\Admin;

use App\Model\Pages;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class EditPageController extends Controller
{
    public function render($data)
    {
        $data['page'] = [];
        $data['mode'] = 'AddPage';
        if (isset($data['uridata'][0])) {
            $data['page'] = $this->showPage($data['uridata'][0]);
            $data['mode'] = 'UpdatePage';
        }
        $this->view = new View('admin/editpage.twig.html', $data);
    }

    public function showPage($id)
    {
        $page = Pages::where("id", $id)->first();
        $showPage = [$page->id, $page->title, $page->content];
        return $showPage;
    }
    //удаление страницы
    public function ajaxDeletePage()
    {
        $id = $_POST['id'];
        if (Pages::where("id", $id)->delete() == 1) {
            return true;
        } else {
            return false;
        }
    }
    //Добавление страницы
    public function ajaxAddPage()
    {
        $today = date("Y-m-d");
        $dataToInsert = ['title' => $_POST['title'], 'content' => $_POST['content'], 'author' => $_SESSION['id'], 'created_at' => $today, 'updated_at' => $today];
        if (Pages::insert($dataToInsert)) {
            $returnData[0] = true;
            $returnData[1] = 'Страница добавлена.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/pagelist';
            $returnData[4] = 1;
        } else {
            $returnData = [false, 'Ошибка добавления страницы.'];
        }
        return $returnData;
    }
    //обновление страницы
    public function ajaxUpdatePage()
    {
        $id = $_POST['id'];
        $today = date("Y-m-d");
        $dataToUpdate = ['title' => $_POST['title'], 'content' => $_POST['content'], 'updated_at' => $today];
        $post = Pages::where("id", $id)->first();
        if ($post->update($dataToUpdate)) {
            $returnData[0] = true;
            $returnData[1] = 'Страница обновлена.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/pagelist';
            $returnData[4] = 1;
        } else {
            $returnData = [false, 'Ошибка обновления страницы.'];
        }
        return $returnData;
    }

}