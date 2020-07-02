<?php

namespace Application\Controllers;


use Application\Models\PictureModel;
use Core\View;

class Index
{
    private $picturesModel;

    public function __construct()
    {
        $this->picturesModel = new PictureModel();
    }

    public function indexAction()
    {
        $pictures = $this->picturesModel->getLast(10);
        $viewParams = [
            'title'     => 'Home',
            'heading'   => 'Last 10 pictures',
            'showNav'   => false,
            'pictures'  => $pictures
        ];
        View::render('gallery.php', $viewParams);
    }
}