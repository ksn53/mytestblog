<?php
namespace App;

use App\Model\User;
use App\Model\Subscribtions;

class UnsubscribeController extends Controller\Controller
{

    public function __construct($route, $data)
    {
        $key = 0;
        $data['message'] = "Нет такой подписки.";
        if (isset($data['uridata'][0])) {
            $key = $data['uridata'][0];
        }
        if ($this->unsubscribe($key) == true) {
            $data['message'] = "Подписка удалена.";
        }
        $this->view = new View('unsubscribe.twig.html', $data);
    }
    public function unsubscribe($key)
    {
        if (count(Subscribtions::where('unsubscribe', $key)->get()) == 1) {
            if (Subscribtions::where('unsubscribe', $key)->delete() == 1) {
                return true;
            }
        }
        return false;
    }
}