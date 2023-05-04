<?php
namespace App\Widgets;

use App\Model\Posts;

class SmallfeaturedpostController extends WidgetController
{
    public function showPost($id)
    {
        $selectedPost = Posts::where("id", $id)->first();
        return $selectedPost;
    }

    public function render($params)
    {
        $post = $this->showPost($params[0]);
        return $this->twig->render('smallfeaturedpostWidget.twig.html', ['title' => $post->title, 'content' => $post->content, 'urlPath' => "post/" . $post->id, 'created_at' => $post->created_at, 'teaserpic' => $post->teaserpic]);
    }
}