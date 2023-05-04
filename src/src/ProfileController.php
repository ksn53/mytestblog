<?php
namespace App;

use App\Model\User;
use App\Model\Roles;
use App\Model\UserRoles;
use App\Model\Comments;
use App\Model\Subscribtions;
use App\Admin\UploadController as UploadController;

class ProfileController extends Controller\Controller
{
    public function render($data)
    {
        if (isset($data['uridata'][0])) {
            switch ($data['uridata'][0]) {
                case 'edit':
                    if ($this->roleid == 1) {
                        $this->showDefaultProfilePage($data['uridata'][1]);
                    }
                    break;
                case 'delete':
                    if (($this->roleid == 1) && (isset($data['uridata'][1]))) {
                        if ($this->deleteUser($data['uridata'][1]) == true) {
                            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/admin/userlist');
                        }
                    }
                    break;
                case 'register':
                    $this->view = new View('register.twig.html', $data);
                    break;
                case 'login':
                    $this->view = new View('login.twig.html', $data);
                    break;
                case 'logout':
                    if ($this->logout()) { header('Location: http://' . $_SERVER['HTTP_HOST']); }
                    break;
                default:
                    $this->showDefaultProfilePage();
                    break;
            }
        } else {
            $this->showDefaultProfilePage();
        }
    }
    public function showDefaultProfilePage($id = null)
    {
        if (isset($_SESSION['id'])) {
            if ($this->roleid == 1) {
                $data['roles'] = $this->allRolesNames();
            }
            $requestedId = $this->id;
            if (isset($id)) {
                $requestedId = $id;
            }
            $data['roleid'] = $this->roleid;
            $data['mode'] = 'UpdateUser';
            $data['user'] = $this->showUser($requestedId);
            $this->view = new View('profile.twig.html', $data);
        }
    }
    //удаление роли пользователя
    public function deleteUserRole($userid)
    {
        if (UserRoles::where("user_id", $userid)->delete()) {
            return true;
        } else {
            return false;
        }
    }
    //удаление комментария пользователя
    public function deleteUserComment($userid)
    {
        $userComments = Comments::where("author", $userid)->count();
        if ($userComments != 0) {
            if (Comments::where("author", $userid)->delete()) {
                return true;
            }
        } else {
            return false;
        }
    }
    //редактирование пользователя
    public function ajaxUpdateUser()
    {
        if (($this->roleid != 1) && ($this->id != $_POST['id'])) {
            return false;
        }
        $validator = new ValidatorController();
        $id = $_POST['id'];
        unset($_POST['mode']);
        unset($_POST['id']);
        if (isset($_POST['subscribed']) && ($_POST['subscribed'] == 'on')) {
            $_POST['subscribed'] = (int) 1;
        } else {
            $_POST['subscribed'] = (int) 0;
        }
        if (isset($_POST['password']) && ($_POST['password'] == 'erase-it')) {
            unset($_POST['password']);
        }
        if (isset($_POST['password']) && ($_POST['password'] == 'erase-it')) {
            unset($_POST['password']);
        }
        if (isset($_POST['role'])) {
            $newRole = $_POST['role'];
            unset($_POST['role']);
        }
        foreach ($_POST as $key => $value) {
            switch ($key) {
                case 'name':
                    if ($validator->namepasswdValidate($value) == false) {
                        $returnData = [false, 'Неверный формат имени.<br>Допустимо: от 3 до 15 символов, кир. или лат. буквы, цифры.'];
                        return $returnData;
                    }
                    if ($this->userExists($value, $id) == true) {
                        $returnData = [false, 'Пользователь с таким именем уже существует.'];
                        return $returnData;
                    }
                break;
                case 'password':
                    if ($validator->namepasswdValidate($value) == false) {
                        $returnData = [false, 'Неверный формат пароля.<br>Допустимо: от 3 до 15 символов, кир. или лат. буквы, цифры.'];
                        return $returnData;
                    } else {
                        $postedData['password'] = password_hash($value, PASSWORD_DEFAULT);
                    }
                break;
                case 'email':
                    if ($validator->emailValidate($value) == false) {
                        $returnData = [false, 'Неверный формат адреса почты.'];
                        return $returnData;
                    }
                break;
            }
        }
            $dataToUpdate = $_POST;

        if ($_FILES['uploadUserpicfile']['tmp_name'] != "") {
            if ($validator->fileValidate($_FILES['uploadUserpicfile']['tmp_name']) == true) {
                $dataToUpdate['userpic'] = UploadController::uploadAndScaleFile($_FILES['uploadUserpicfile'], 150)['preview'];
            } else {
                $returnData = [false, 'Неверный формат загруженного файла.<br>Не более 2mb файл в формате JPG.'];
                return $returnData;
            }
        }
        $returnData[1] = 'Ошибка обновления данных.';
        $returnData[0] = User::where("id", $id)->update($dataToUpdate);
        if (isset($newRole)) {
            $returnData[0] = $this->updateRole($id, $newRole);
        }
        if (isset($returnData[0])) {
            $returnData[1] = 'Данные пользователя успешно обновлены.';
        }
        return $returnData;
    }
    //обновление роли пользователя
    public function updateRole($id, $newRole)
    {
        if ($this->roleid == 1) {
            return UserRoles::where("user_id", $id)->update(['role_id' => $newRole]);
        }
        return false;
    }
    /**
     * удаление пользователя
    **/
    public function deleteUser($id)
    {
        if ($this->roleid != 1) {
            return false;
        }
        $this->deleteUserComment($id);
        $this->deleteUserRole($id);
            if (User::where("id", $id)->delete() == 1) {
                return true;
            } else {
                return false;
            }
    }
    /**
     * Закрытие сессии и логаут
    **/
    public function logout()
    {
        if ( session_id() ) {
            setcookie(session_name(), session_id(), time()-60*60*24);
            session_unset();
            session_destroy();
            return true;
        }
        return false;
    }
    public static function returnSubscribedStatus($userid)
    {
        return User::where("id", $userid)->value('subscribed');
    }
    public static function subscribeUser($userid)
    {
        return User::where("id", $userid)->update(['subscribed' => 1]);
    }
    public static function returnEmail($userid)
    {
        return User::where("id", $userid)->value('email');
    }
    public function returnRoleName($userid)
    {
        return UserRoles::where("user_id", $userid)->value('role_id');
    }
    public static function allRolesNames()
    {
        $roles = Roles::All();
        $rolesNames =[];
        foreach ($roles as $role) {
            $rolesNames[] = [$role->id, $role->name];
        }
        return $rolesNames;
    }
    public function showUser($id)
    {
        $user = User::where("id", $id)->first();
        $showUser = ['id' => $user->id, 'name' => $user->name, 'role' => $this->returnRoleName($user->id), 'email' => $user->email, 'subscribed' => $user->subscribed, 'userpic' => $user->userpic, 'about' => $user->about];
        return $showUser;
    }
    public function userExists($username, $id = null)
    {
        if (isset($id)) {
            $testuserid = User::where('name', $username)->value('id');
            if (isset($testuserid)) {
                if ($testuserid != $id) {
                    return true;
                }
            }
        } elseif (count(User::where('name', $username)->get()) != 0) {
            return true;
        }
        return false;
    }
    //вход по учётной записи
    public function ajaxLogin()
    {
        $validator = new ValidatorController();
        if (($validator->namepasswdValidate($_POST['login']) == false) && ($validator->namepasswdValidate($_POST['password']) == false)) {
            $returnData = [false, 'Неверный формат имени или пароля.<br>Допустимо: от 3 до 15 символов, кир. или лат. буквы, цифры.'];
            return $returnData;
        }
        $userName = $_POST['login'];
        $hash = User::where('name', $userName)->value('password');
        if (password_verify($_POST['password'], $hash)) {
            $_SESSION["loginStatus"] = true;
            $_SESSION["currentLogin"] = $userName;
            $_SESSION["id"] = User::where('name', $userName)->value('id');
            $returnData[0] = true;
            $returnData[1] = 'Успешный вход. <br>Переадресация на основную страницу через 2 сек.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'];
            $returnData[4] = 2;
            return $returnData;
        }
        $returnData = [false, 'Неверое имя или пароль.'];
        return $returnData;
    }
    //Добавление регистрация нового пользователя
    public function ajaxRegister()
    {
        $validator = new ValidatorController();

        if (($validator->namepasswdValidate($_POST['name']) == false) && ($validator->namepasswdValidate($_POST['password']) == false)) {
            $returnData = [false, 'Неверный формат имени или пароля.<br>Допустимо: от 3 до 15 символов, кир. или лат. буквы, цифры.'];
            return $returnData;
        }
        if ($validator->emailValidate($_POST['email']) == false) {
            $returnData = [false, 'Неверный формат адреса почты.'];
            return $returnData;
        }
        if ($this->userExists($_POST['name']) == true) {
            $returnData = [false, 'Пользователь с таким именем уже зарегистрирован.'];
            return $returnData;
        }
        $id = $this->addUser($_POST['name'], password_hash($_POST['password'], PASSWORD_DEFAULT), $_POST['email']);
        if ($id) {
            $dataToInsert = ['user_id' => $id, 'role_id' => 3];
            $returnData[0] = UserRoles::insert($dataToInsert);
            $returnData[1] = 'Успешная регистрация. <br>Переадресация на основную страницу через 2 сек.';
            $returnData[2] = 'redirectTo';
            $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'];
            $returnData[4] = 2;
            return $returnData;
        } else {
            return false;
        }
    }

    //Добавление нового пользователя и возврат его ID
    public function addUser($name, $password, $email)
    {
        $dataToInsert = ['name' => $name, 'password' => $password, 'email' => $email];
        $id = User::insertGetId($dataToInsert);
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }
}