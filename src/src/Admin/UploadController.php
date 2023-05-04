<?php
namespace App\Admin;

use \App\View as View;
use \App\Controller\AdminController as Controller;

class UploadController extends Controller
{
    public function render($data)
    {
        $this->view = new View('admin/upload.twig.html', $data);
    }

    public static function uploadAndScaleFile($file, $width)
    {
        if (is_uploaded_file($file['tmp_name'])) {
            $fileName = time()."_".basename($file['name']);
            $uploaddir = 'files/';
            $uploadfile = $uploaddir . $fileName;
            move_uploaded_file($file['tmp_name'], $uploadfile);
            $srcId = imagecreatefromjpeg($uploadfile);
            $dstId = imagescale($srcId, $width, -1, IMG_BILINEAR_FIXED);
            $fileNamePreview = "resized_" . basename($uploadfile);
            imagejpeg($dstId, $uploaddir . $fileNamePreview);
            imagedestroy($srcId);
            imagedestroy($dstId);
            $output = ['name' => $fileName, 'preview' => $fileNamePreview];
            return $output;
        } else {
            return false;
        }
    }

    public function ajaxUploadFile()
    {
        if ($_FILES['uploadfile']['tmp_name'] != "") {
            $validator = new \App\ValidatorController();
            if ($validator->fileValidate($_FILES['uploadfile']['tmp_name']) == true) {
                if ($this::uploadAndScaleFile($_FILES['uploadfile'], 270) != false) {
                    $returnData[0] = true;
                    $returnData[1] = 'Файл загружен.';
                    $returnData[2] = 'redirectTo';
                    $returnData[3] = 'http://' . $_SERVER['HTTP_HOST'] . '/admin/files';
                    $returnData[4] = 1;
                    return $returnData;
                } else {
                    $returnData = [false, "Ошибка загрузки."];
                    return $returnData;
                }
            } else {
                $returnData = [false, 'Файл не соответствует формату. Размер не более 2М и формат JPG.'];
                return $returnData;
            }
        }
    }
}