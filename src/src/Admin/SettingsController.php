<?php
namespace App\Admin;

use \App\View as View;
use \App\Controller\AdminController as Controller;

class SettingsController extends Controller
{
    public function render($data)
    {
        $this->view = new View('admin/settings.twig.html', $data);
    }
}