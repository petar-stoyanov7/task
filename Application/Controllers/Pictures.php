<?php

namespace Application\Controllers;


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
}