<?php
namespace App\Widgets;

use App\Model\Posts;
use \Illuminate\Pagination\Paginator;

class PostRollController extends WidgetController
{
    public function showPaginatedPosts($postsPerPage, $currentPage)
    {
        Paginator::currentPageResolver(function() use ($currentPage) { return $currentPage; });
        $postData = Posts::orderBy('created_at', 'desc')->paginate($postsPerPage);
        return $postData;
    }

    public function showPaginatedCatPosts($postsPerPage, $currentPage, $categoryId)
    {
        Paginator::currentPageResolver(function() use ($currentPage) { return $currentPage; });
        $postData = Posts::where("category", $categoryId)->orderBy('created_at', 'desc')->paginate($postsPerPage);
        return $postData;
    }

    public function render($params)
    {
        $serverName= $_SERVER['HTTP_HOST'];
        if (isset($params[2])) {
            $posts = $this->showPaginatedCatPosts($params[0], $params[1], $params[2]);
        } else {
            $posts = $this->showPaginatedPosts($params[0], $params[1]);
        }
        foreach ($posts as $post) {
            echo $this->twig->render('postroll.html.twig',  ['title' => $post->title, 'content' => $post->content, 'postLink' => 'http://' . $serverName . "/post/" . $post->id, 'created_at' => $post->created_at, 'author' => $this->returnUserProperty($post->author, 'name')]);
        }
    }
}


