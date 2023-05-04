<?php
namespace App\Widgets;

use Symfony\Component\Finder\Finder;

class FileListController extends WidgetController
{
    public static function fileList()
    {
        $finder = new Finder();
        $finder->files()->in(APP_DIR . "/files")->name(['*.jpg', '*.jpeg'])->sortByChangedTime()->reverseSorting();
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                if (mb_strlen($file->getRelativePathname()) > 50) {
                    $fileNames[] = substr($file->getRelativePathname(), 0, 50) . '...';
                } else {
                    $fileNames[] = $file->getRelativePathname();
                }
            }
        return $fileNames;
        }
    }

    public function render($params = null)
    {
        $files = self::fileList();
        $selectFunction = $params[0];
        $serverName= $_SERVER['HTTP_HOST'];
        return $this->twig->render('filelist.twig.html', ['filenames' => $files, 'servername' => $serverName, 'selectFunction' => $selectFunction]);
    }

}