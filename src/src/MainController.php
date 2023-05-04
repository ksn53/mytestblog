<?php
namespace App;

use App\Model\Posts;
use \Illuminate\Pagination\Paginator;

class MainController extends Controller\Controller
{

    public function render($data)
    {
        $currentPage = 1;
        if (isset($data['uridata'][0])) {
            $currentPage = $data['uridata'][0];
        }
        $data['postperpage'] = 4;
        $data['postslastpage'] = $this->postsLastPage($data['postperpage']);
        $data['currentpage'] = $currentPage;
        $data['urlpath'] = $this->route . '/';
        $data['servername'] = $_SERVER['HTTP_HOST'];
        $this->view = new View('mainpage.html', $data);
    }
    public function postsLastPage($postsPerPage)
    {
        $postData = Posts::paginate($postsPerPage)->lastPage();
        return $postData;
    }
}