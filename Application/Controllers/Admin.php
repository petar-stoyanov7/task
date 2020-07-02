<?php

namespace Application\Controllers;


use Application\Forms\ContactsForm;
use Core\View;

class Admin
{
    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('location: /');
        } else {
            if ($_SESSION['user']['group'] !== 'admins') {
                header('location: /');
            }
        }
    }

    public function indexAction()
    {
        header('Location: /admin/last-five');
    }

    public function lastFiveAction()
    {
        //
        $viewParams = [
            'title' => 'Last five',
            'CSS' => [
                'userlist.css',
                'admin.css'
            ],
        ];
        View::render('admin/last-five.php', $viewParams);
    }

    public function usersAction()
    {
        $viewParams = [
            'title' => 'Administer users',
            'showActions' => true,
            'showPaginator' => true,
            'CSS' => [
                'userlist.css',
                'admin.css'
            ]
        ];
        View::render('user/list.php', $viewParams);
    }

    public function picturesAction()
    {
        $viewParams = [
            'title' => 'Administer users',
            'showPaginator' => true,
            'CSS' => ['admin.css'],
        ];
        View::render('admin/pictures.php', $viewParams);
    }

    public function commentsAction()
    {
        $viewParams = [
            'title' => 'Administer users',
            'showPaginator' => true,
            'CSS' => ['admin.css'],
        ];
        View::render('admin/comments.php', $viewParams);
    }
}