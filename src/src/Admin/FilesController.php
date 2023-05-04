<?php
namespace App\Admin;

use Symfony\Component\Finder\Finder;
use Knp\Component\Pager\Paginator;
use Symfony\Component\Filesystem\Filesystem;
use \App\View as View;
use \App\Controller\AdminController as Controller;

class FilesController extends Controller
{
    public function render($data)
    {
        $pagination = null;
        $itemsPerPage = 7;
        $currentPage = 1;
        if (isset($data['uridata'][0])) {
            $currentPage = $data['uridata'][0];
        }
        $filenames = $this->fileList();
        if (!is_null($filenames)) {
            foreach ($filenames as $key => $file) {
                if (mb_strlen($file["filename"]) > 60) {
                    $filenames[$key]['filename'] = substr($filenames[$key]['filename'], 0, 60) . '...';
                }
            }
            $paginator = new Paginator;
            $pagination = $paginator->paginate($filenames, $currentPage, $itemsPerPage);
        }

        if ($pagination) {
            $data['filenames'] = $pagination->getItems();
            $data['lastpage'] = (int)ceil($pagination->getTotalItemCount()/$itemsPerPage);
            $data['currentpage'] = $pagination->getCurrentPageNumber();
        }

        $data['urlpath'] = '';
        $this->view = new View('admin/files.twig.html', $data);
    }

    //file delete
    public function ajaxDeleteFile()
    {
        $filename = $_POST['fileName'];
        $fileSystem = new Filesystem();
        try {
            $fileSystem->remove(APP_DIR . '/files/' . $filename);
            return true;
        } catch (IOExceptionInterface $exception) {
            echo "Ошибка при создании вашего каталога ".$exception->getPath();
        }
    }
    public function fileList()
    {
        $fileNames = [];
        $finder = new Finder();
        $filesystem = new Filesystem();
        $finder->files()->in(APP_DIR . "/files")->name(['*.jpg', '*.jpeg'])->notName(['resized*', 'nopicture.jpg'])->sortByChangedTime()->reverseSorting();
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $filename = $file->getRelativePathname();
                if ($filesystem->exists(APP_DIR . '/files/resized_' . $filename) == true) {
                    $fileNames[] = ['filename' => $filename, 'preview' => 'resized_' . $filename];
                } else {
                    $fileNames[] = ['filename' => $filename, 'preview' => 'nopicture.jpg'];
                }
            }
        return $fileNames;
        }
    }
}