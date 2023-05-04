<?php
namespace App;

class NotFoundException extends HttpException implements Renderable
{
    public $data;

    public function render()
    {
        http_response_code(404);
        $this->data['title'] = 'не найдено';
        include VIEW_DIR . '/layout/header.php';
        echo $this->getMessage();
        include VIEW_DIR . '/layout/footer.php';
    }
}