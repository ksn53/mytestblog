<?php
namespace App\Admin;

use App\Model\Posts;
use App\Model\Categorys;
use App\Model\Comments;
use \App\View as View;
use \App\Controller\AdminController as Controller;
use \App\MailerController as MailerController;

class EditPostController extends Controller
{
    public function render($data)
    {
        $data['post'] = [];
        $data['mode'] = 'AddPost';
        if (isset($data['uridata'][0])) {
            $data['post'] = $this->showPost($data['uridata'][0]);
            $data['mode'] = 'UpdatePost';
        }
        $data['categorys'] = $this->allCategoryNames();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view = new View('admin/editpost.twig.html', $data);
        }
    }
    //Обновление статьи
    public function ajaxUpdatePost()
    {
        $id = $_POST['id'];
        $today = date("Y-m-d");
        $dataToUpdate = ['title' => $_POST['title'], 'content' => $_POST['content'], 'updated_at' => $today, 'category' => $_POST['category'], 'teaserpic' => $_POST['teaserpic']];
        $result = Posts::where("id", $id)->update($dataToUpdate);
        switch ($result) {
            case 1:
                $mailer = new MailerController();
                $mailSubj = 'Обновлена статья ' . $_POST['title'];
                $textToSend = 'Ссылка на обновлённую статью. <br><a href="http://' . $_SERVER['HTTP_HOST'] . '/post/' . $_POST['id'] . '">' . $_POST['title'] . '</a>';
                $mailer->sendToSubs($mailSubj, $textToSend);
                $mailer->sendToUsers($mailSubj, $textToSend);
                $returnData[0] = true;
                $returnData[1] = 'Данные статьи успешно обновлены.';
                $returnData[2] = 'redirectTo';
                $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/editpost/' . $id;
                $returnData[4] = 1;
                return $returnData;
                break;
            case 0:
                return [true, 'Обновлять нечего.'];
                break;
            default:
                return [false, 'Ошибка обновления данных'];
                break;
        }
    }
    //Добавление статьи
    public function ajaxAddPost()
    {
        $today = date("Y-m-d");
        $dataToInsert = ['title' => $_POST['title'], 'content' => $_POST['content'], 'author' => $_SESSION['id'], 'created_at' => $today, 'updated_at' => $today,'category' => $_POST['category'] ];
        $post=Posts::insertGetId($dataToInsert);
        if ($post) {
            $mailer = new MailerController();
            $mailSubj = 'Добавлена статья ' . $_POST['title'];
            $textToSend = 'Ссылка на обновлённую статью. <br><a href="http://' . $_SERVER['HTTP_HOST'] . '/post/' . $post . '">' . $_POST['title'] . '</a>';
            $mailer->sendToSubs($mailSubj, $textToSend);
            $mailer->sendToUsers($mailSubj, $textToSend);
            $returnData[0] = true;
            $returnData[1] = 'Статья успешно добавлена.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/postlist/1';
            $returnData[4] = 1;
            return $returnData;
        } else {
            return false;
        }
    }
    public function hasComments($postid)
    {
        if (Comments::where("postid", $postid)->first()) {
            return true;
        }
        return false;
    }
    public static function allCategoryNames()
    {
        $categorys = Categorys::All();
        $categoryNames =[];
        foreach ($categorys as $category) {
            $categoryNames[] = [$category->id, $category->name];
        }
        return $categoryNames;
    }

    public function showPost($id)
    {
        $post = Posts::where("id", $id)->first();
        $showPost = [$post->id, $post->title, $post->content, $post->created_at, $post->author, $post->category, $post->teaserpic];
        return $showPost;
    }
    //удаление статьи
    public function ajaxDeletePost()
    {
        $id = $_POST['postid'];
        if ($this->hasComments($id) == true) {
            Comments::where('postid', $id)->delete();
        }
        if (Posts::where("id", $id)->delete() == 1) {
            return true;
        }
        return false;
    }

}