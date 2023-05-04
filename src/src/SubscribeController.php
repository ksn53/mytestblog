<?php
namespace App;

use App\Model\User;
use App\Model\Subscribtions;

class SubscribeController extends Controller\Controller
{

    public function render($data)
    {
        $data['email'] = '';
        $data['subscribed'] = 0;
        //$profileController = new ProfileController
        if (isset($this->id)) {
            $data['subscribed'] = ProfileController::returnSubscribedStatus($this->id);
            $data['email'] = ProfileController::returnEmail($this->id);
        }
        if (isset($data['uridata'][0])) {
            $data['postid'] = $data['uridata'][0];
        }
        $data['author'] = $this->showCurrentLoginId();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view = new View('subscribe.twig.html', $data);
        }
    }
//returnSubscribedStatus
    public function showCurrentLoginId()
    {
        $currentLogin = User::where('name', "guest")->value('id');
        if (isset($_SESSION["id"])) {
            $currentLogin = $_SESSION["id"];
        }
        return $currentLogin;
    }
    //добавление email подписки
    public function ajaxAddSubscribeEmail()
    {
        $validator = new ValidatorController();
        $email = $_POST['email'];
        if ($validator->emailValidate($email) == false) {
            $returnData = [false, 'Неверный формат адреса почты.'];
            return $returnData;
        }
        if ($this->emailExists($email) == true) {
            $returnData = [false, 'Такой адрес уже есть в списке рассылки.'];
            return $returnData;
        }
        if ($validator->emailValidate($email) == true) {
            if (isset($this->id)) {
                $returnData[0] = ProfileController::subscribeUser($this->id);
            } else {
                $returnData[0] = $this->addEmail($email);
            }

            if ($returnData[0] == 1) {
                $returnData[1] = 'Адрес добавлен в список рассылки. <br>Переадресация на основную страницу через 2 сек.';
                $returnData[2] = 'redirectTo';
                $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'];
                $returnData[4] = 2;
            } else {
                $returnData = [false, 'Ошибка добавления адреса рассылки.'];
            }
            return $returnData;
        }
        return false;
    }
    public function addEmail($email)
    {
        $dataToInsert = ['email' => $email, 'unsubscribe' => random_int(1, 10000)];
        return  Subscribtions::insert($dataToInsert);
    }
    public function emailExists($email)
    {
        if (count(Subscribtions::where('email', $email)->get()) != 0) {
            return true;
        }
        return false;
    }
}