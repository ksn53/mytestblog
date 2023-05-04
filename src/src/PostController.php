<?php
namespace App;

use App\Model\User;
use App\Model\Comments;

class PostController extends Controller\Controller
{

    public function __construct($route, $data)
    {
        $data['postid'] = 1;
        $data['servername'] = $_SERVER['HTTP_HOST'];
        if (isset($data['uridata'][0])) {
            $data['postid'] = $data['uridata'][0];
        }
        $data['author'] = $this->showCurrentLoginId();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view = new View('postpage.twig.html', $data);
        }
    }

    public function showCurrentLoginId()
    {
        $currentLoginId = 0;
        if (isset($_SESSION["id"])) {
            $currentLoginId = $_SESSION["id"];
        }
        return $currentLoginId;
    }
    //Добавление комментария
    public function ajaxAddComment()
    {
        $today = date("Y-m-d");
        $returnData = [false, 'Ошибка отправки комментария.'];
        $dataToInsert = ['title' => $_POST['title'], 'content' => $_POST['content'], 'author' => $_POST['author'], 'created_at' => $today, 'updated_at' => $today, 'parent' => $_POST['parent'], 'postid' => $_POST['postid']];
        if (Comments::insert($dataToInsert)) {
            $returnData[0] = true;
            $returnData[1] = 'Комментарий добавлен. <br>Он будет виден после модерации.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/post/' . $_POST['postid'];
            $returnData[4] = 2;
        }
        return $returnData;
    }
}