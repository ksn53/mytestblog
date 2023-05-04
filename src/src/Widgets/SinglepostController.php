<?php
namespace App\Widgets;

use App\Model\Posts;

class SinglepostController extends WidgetController
{

    public function printSubComment($comment, $shift)
    {
        foreach ($comment->subcomments as $subcomment) {
            echo $this->twig->render('comment.twig.html',  ['title' => $subcomment->title, 'id' => $subcomment->id, 'content' => $subcomment->content, 'parent' => $subcomment->parent, 'created_at' => substr($subcomment->created_at, 0, -9), 'userpic' => $this->returnUserProperty($comment->author, 'userpic'), 'author' => $this->returnUserProperty($subcomment->author, 'name'), 'shift' => $shift]);
            if ($subcomment->subcomments->count()) {
                $subShift = $shift + 1;
                $this->printSubComment($subcomment, $subShift);
            }
        }
    }

    public function render($params)
    {
        $postId = $params[0];
        $post = Posts::with('comments.subcomments')->find($postId);
        echo $this->twig->render('singlepost.html.twig',  ['title' => $post->title, 'content' => $post->content, 'postLink' => "post/" . $post->id, 'created_at' => $post->created_at, 'author' => $this->returnUserProperty($post->author, 'name')]);

        if ($post->comments->count()) {
            foreach ($post->comments as $comment) {
                echo $this->twig->render('comment.twig.html',  ['title' => $comment->title, 'id' => $comment->id, 'content' => $comment->content, 'parent' => $comment->parent, 'created_at' => $comment->created_at, 'userpic' => $this->returnUserProperty($comment->author, 'userpic'), 'author' => $this->returnUserProperty($comment->author, 'name'), 'shift' => 0]);
                if ($comment->subcomments->count()) {
                    $this->printSubComment($comment, 1);
                }
            }
        }
    }
}


