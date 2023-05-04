<?php
namespace App\Admin;

use App\Model\Comments;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class EditCommentController extends Controller
{
    public function render($data)
    {
        $data['comment'] = [];
        $data['mode'] = 'AddComment';
        if (isset($data['uridata'][0])) {
            $data['comment'] = $this->showComment($data['uridata'][0]);
            $data['mode'] = 'UpdateComment';
        }
        $this->view = new View('admin/editcomment.twig.html', $data);
    }
    public function ajaxUpdateComment()
    {
        $id = $_POST['id'];
        $today = date("Y-m-d");
        $dataToUpdate = ['title' => $_POST['title'], 'content' => $_POST['content'],  'author' => $_SESSION['id']];
        $comment = Comments::where("id", $id)->first();
        $returnData = [false, "Ошибка обновления комментария."];
        if ($comment->update($dataToUpdate) == true) {
            $returnData = [true, "Комментарий обновлён."];
        }
        return $returnData;

    }
    //Добавление комментария
    public function ajaxAddComment()
    {
        $today = date("Y-m-d");
        $dataToInsert = ['title' => $_POST['title'], 'content' => $_POST['content'], 'author' => $_POST['author'], 'created_at' => $today, 'updated_at' => $today, 'parent' => $_POST['parent'], 'postid' => $_POST['postid']];
        $returnData[0] = Comments::insert($dataToInsert);
        $returnData[1] = $_POST['postid'];
        $returnData[2] = $_SERVER['HTTP_HOST'];
        return $returnData;
    }
    public function showComment($id)
    {
        $comment = Comments::where("id", $id)->first();
        $showComment = [$comment->id, $comment->title, $comment->content, $comment->created_at, $comment->author];
        return $showComment;
    }

}