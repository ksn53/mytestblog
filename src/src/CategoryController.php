<?php
namespace App;

use App\Model\Posts;
use \Illuminate\Pagination\Paginator;
use App\Model\Categorys;

class CategoryController extends Controller\Controller
{
    public function __construct($route, $data)
    {
        $activePage = 1;
        if (isset($data['uridata'][1])) {
            $activePage = $data['uridata'][1];
        }
        $categoryId = 1;
        if (isset($data['uridata'][0])) {
            $categoryId = $data['uridata'][0];
        }
        $postsPerPage = 4;
        $data['postslastpage'] = $this->postsLastPage($postsPerPage, $categoryId);
        $data['postperpage'] = 4;
        $data['currentpage'] = $activePage;
        $data['categoryid'] = $categoryId;
        $data['urlpath'] = '';
        $data['servername'] = $_SERVER['HTTP_HOST'];
        $this->view = new View('categorypage.html', $data);
    }

    public function currentCategory($id)
    {
        return Categorys::where("id", $id)->first();
    }

    public function postsLastPage($postsPerPage, $categoryId)
    {
        $postData = Posts::where("category", $categoryId)->paginate($postsPerPage)->lastPage();
        return $postData;
    }

}