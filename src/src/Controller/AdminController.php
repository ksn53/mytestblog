<?php
namespace App\Controller;

use App\Model\UserRoles;

class AdminController extends Controller
{
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
            if ($this->checkAccess()) {
                if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                    $data['roleid'] = $this->roleid;
                    $data['servername'] = $this->servername;
                    $this->render($data);
                }
            }
        } else {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/profile/login');
        }
    }
    public function ajaxData()
    {
        if (isset($_POST["mode"])) {
            if ($this->roleid <= $this->access) {
                $call="ajax" . $_POST["mode"];
                return json_encode($this->$call());
            }
            } else {
                return json_encode(array("error"=>true));
            }
    }
    public function checkAccess()
    {
        if ($this->roleid <= $this->access) {
            return true;
        } else {
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/profile/login');
        }
    }

}