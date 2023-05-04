<?php
namespace App\Admin;

use App\Model\User;
use App\Model\UserRoles;
use App\Model\Roles;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class UserListController extends Controller
{
    public function render($data)
    {
        $currentPage = 1;
        if (isset($data['uridata'][0])) {
            $currentPage = $data['uridata'][0];
        }
        $data['userlist'] = $this->userList();
        $this->view = new View('admin/userlist.twig.html', $data);
    }

    public function returnRoleName($id)
    {
        return Roles::where("id", $id)->value('name');
    }
    public function userList()
    {
        $users = User::All();
        $usersProperties =[];
        foreach ($users as $user) {
            $usersProperties[] = [$user->id, $user->name, $this->returnRoleName(UserRoles::where("user_id", $user->id)->value('role_id')), $user->email, $user->subscribed];
        }
        return $usersProperties;
    }

}