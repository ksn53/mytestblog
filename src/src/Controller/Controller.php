<?php
namespace App\Controller;

use App\Model\UserRoles;

class Controller
{
    public $view;
    //public $currentLogin;
    public $roleid;
    public $servername;
    public $id;
    public $route;
    public $access;

    public function __construct($route, $data)
    {
        $this->route = $route;
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["id"]))
        {
            $this->roleid = $this->returnUserRole($_SESSION['id']);
            $this->servername = $_SERVER['HTTP_HOST'];
            $this->id = $_SESSION['id'];
            $this->access = (array_key_exists("access", $data['maindata'])) ? $data['maindata']['access'] : 0;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->render($data);
        }
    }
    public function ajaxData()
    {
        if (isset($_POST["mode"])) {
            $call="ajax" . $_POST["mode"];
            return json_encode($this->$call());
        } else {
            return json_encode(array("error"=>true));
        }
    }
    public function returnUserRole($id)
    {
        $role = UserRoles::where('user_id', $id)->value('role_id');
        return $role;
    }

}