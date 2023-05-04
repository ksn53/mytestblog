<?php
namespace App\Admin;

use App\Model\Categorys;
use App\Model\Posts;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class EditCatController extends Controller
{
    public function render($data)
    {
        $data['category'] = [];
        $data['mode'] = 'AddCategory';
        if (isset($data['uridata'][0])) {
            $data['category'] = $this->showCategory($data['uridata'][0]);
            $data['mode'] = 'UpdateCategory';
        }
        $this->view = new View('admin/editcat.twig.html', $data);
    }
    //удаление рубрики
    public function ajaxDeleteCat()
    {
        $id = $_POST['id'];
        if (!Posts::where("category", $id)->first()) {
            if (Categorys::where("id", $id)->delete() == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    //обновление рубрики
    public function ajaxUpdateCategory()
    {
        $id = $_POST['id'];
        $dataToUpdate = ['name' => $_POST['name']];
        $post = Categorys::where("id", $id)->first();
        if ($post->update($dataToUpdate)) {
            $returnData[0] = true;
            $returnData[1] = 'Данные успешно обновлены.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/catlist';
            $returnData[4] = 1;
            return $returnData;
        }
        return false;
    }
    public function ajaxAddCategory()
    {
        $dataToInsert = ['name' => $_POST['name']];
        if (Categorys::insert($dataToInsert)) {
            $returnData[0] = true;
            $returnData[1] = 'Рубрика добавлена.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/catlist';
            $returnData[4] = 1;
            return $returnData;
        }
        return false;
    }
    public function showCategory($id)
    {
        $category = Categorys::where("id", $id)->first();
        $showCategory = [$category->id, $category->name];
        return $showCategory;
    }

}