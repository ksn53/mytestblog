<?php
namespace App\Admin;

use App\Model\Posts;
use App\Model\User;
use Illuminate\Pagination\Paginator;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class PostListController extends Controller
{
    public function render($data)
    {
        $currentPage = 1;
        $postsPerPage = 14;
        if (isset($data['uridata'][0])) {
            $currentPage = $data['uridata'][0];
        }
        if (isset($data['uridata'][1])) {
            $postsPerPage = $data['uridata'][1];
        }
        $data['postslastpage'] = $this->postsLastPage($postsPerPage, $this->id, $this->roleid);
        $data['postperpage'] = 4;
        $data['currentpage'] = $currentPage;
        $data['urlpath'] = "";
        $data['postlist'] = $this->showPaginatedPosts($postsPerPage, $currentPage, $this->id, $this->roleid);
        $this->view = new View('admin/postlist.twig.html', $data);
    }

    public function returnName($userId)
    {
        return User::where('id', $userId)->value('name');
    }


    public function showPaginatedPosts($postsPerPage, $currentPage, $author, $roleid)
    {
        Paginator::currentPageResolver(function() use ($currentPage) { return $currentPage; });
        if ($roleid == 3) {
            $postData = Posts::where('author', $author)->orderBy('created_at', 'desc')->paginate($postsPerPage);
        } else {
            $postData = Posts::orderBy('created_at', 'desc')->paginate($postsPerPage);
        }
        $postNames =[];
        foreach ($postData as $post) {
            $postNames[] = [$post->id, $post->title, $this->returnName($post->author), $post->created_at];
        }
        return $postNames;
    }
    public function postsLastPage($postsPerPage, $author, $roleid)
    {
        if ($roleid == 3) {
            $postData = Posts::where('author', $author)->paginate($postsPerPage)->lastPage();
        } else {
            $postData = Posts::orderBy('created_at', 'desc')->paginate($postsPerPage)->lastPage();
        }
        return $postData;
    }
}