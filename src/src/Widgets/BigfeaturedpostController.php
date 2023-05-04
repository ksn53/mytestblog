<?php
namespace App\Widgets;

use App\Model\Posts;

class BigfeaturedpostController extends WidgetController
{
    public function showPost($id)
    {
        $selectedPost = Posts::where("id", $id)->first();
        return $selectedPost;
    }

    public function render($params)
    {
        $post = $this->showPost($params[0]);
        return $this->twig->render('bigfeaturedpostWidget.html.twig', ['title' => $post->title, 'content' => $post->content, 'urlPath' => "post/" . $post->id, 'teaserpic' => $post->teaserpic]);
    }
}