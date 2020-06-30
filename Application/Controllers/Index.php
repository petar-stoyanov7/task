<?php

namespace Application\Controllers;


use Core\View;

class Index
{
    public function indexAction()
    {
        $viewParams = [
            'title' => 'Home',
            'heading' => 'Last 10 pictures',
//            'CSS' => ['style.css'],
//            'JS' => ['script.js']
        ];
        View::render('gallery.php', $viewParams);
    }
}