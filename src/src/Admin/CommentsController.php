<?php
namespace App\Admin;

use App\Model\Comments;
use App\Model\User;
use \Illuminate\Pagination\Paginator;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class CommentsController extends Controller
{
    public function render($data)
    {
        $currentPage = 1;
        if (isset($data['uridata'][0])) {
            $currentPage = $data['uridata'][0];
        }
        $data['commentperpage'] = 10;
        if (isset($data['uridata'][1])) {
            $data['commentperpage'] = $data['uridata'][1];
        }
        $data['commentslastpage'] = $this->commentsLastPage($data['commentperpage']);
        $data['currentpage'] = $currentPage;
        $data['urlpath'] = "";
        $data['commentlist'] = $this->showPaginatedComments($data['commentperpage'], $currentPage);
        $this->view = new View('admin/comments.twig.html', $data);
    }
    //удаление комментария
    public function ajaxDeleteComment()
    {
        $id = $_POST['id'];
        if (Comments::where("id", $id)->delete() == 1) {
            return true;
        } else {
            return false;
        }
    }
    //изменение одобрение комментария
    public function ajaxModerateComment()
    {
        $id = $_POST['id'];
        $moderatedState = Comments::where("id", $id)->first()->moderated;
        switch ($moderatedState) {
            case 0:
                $moderatedState = 1;
                $returnData[1] = 'да';
                break;
            case 1:
                $moderatedState = 0;
                $returnData[1] = 'нет';
                break;
            default:
                $moderatedState = 0;
                break;
        }
        if (Comments::where("id", $id)->update(['moderated' => $moderatedState])) {
            $returnData[0] = true;
            return $returnData;
        }
        return false;
    }
    public function returnName($userId)
    {
        return User::where('id', $userId)->value('name');
    }

    public function showPaginatedComments($commentsPerPage, $currentPage)
    {
        Paginator::currentPageResolver(function() use ($currentPage) { return $currentPage; });
        $commentData = Comments::orderBy('updated_at', 'desc')->paginate($commentsPerPage);
        $commentNames =[];
        foreach ($commentData as $comment) {
            $commentNames[] = [$comment->id, $comment->title, $this->returnName($comment->author), $comment->created_at, $comment->parent, $comment->postid, $comment->moderated];
        }
        return $commentNames;
    }
    public function commentsLastPage($commentsPerPage)
    {
        $commentData = Comments::paginate($commentsPerPage)->lastPage();
        return $commentData;
    }
}