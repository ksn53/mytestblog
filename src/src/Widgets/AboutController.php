<?php
namespace App\Widgets;

use App\Model\About;

class AboutController extends WidgetController
{
    public static function aboutText()
    {
        $about = About::All();
        $outputText =[];
        foreach ($about as $aboutText) {
            $outputText[] = $aboutText->text;
        }
        return $outputText[0];
    }

    public function render($params = null)
    {
        $about = self::aboutText();
        return $this->twig->render('about.html.twig', ['text' => $about]);
    }

}