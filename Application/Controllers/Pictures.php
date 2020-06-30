<?php

namespace Application\Controllers;


use Application\Forms\CommentForm;
use Core\View;

class Pictures
{
    public function indexAction()
    {
        $viewParams = [
            'title' => 'Pictures',
            'heading' => 'Gallery',
//            'CSS' => ['style.css'],
//            'JS' => ['script.js']
        ];
        View::render('gallery.php', $viewParams);
    }

    public function showAction()
    {
        $viewParams = [
            'title' => 'Image details',
            'CSS' => ['main.css', 'picture.css'],
            'JS' => 'picture.js',
            'form' => new CommentForm()
        ];

        View::render('images/show.php', $viewParams);
    }
}