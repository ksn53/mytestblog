<?php
namespace App;

class PageController extends Controller\Controller
{
    public function render($data)
    {
        $data['pageid'] = 1;
        $data['servername'] = $this->servername;
        if (isset($data['uridata'][0])) {
            $data['pageid'] = $data['uridata'][0];
        }
        $this->view = new View('pagepage.twig.html', $data);
    }
}
