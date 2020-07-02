<?php

namespace Application\Controllers;


use Application\Forms\ContactsForm;
use Application\Models\CommentModel;
use Application\Models\PictureModel;
use Application\Models\UserModel;
use Core\View;

class Admin
{
    private $userModel;
    private $pictureModel;
    private $commentModel;

    public function __construct()
    {
        if (!isset($_SESSION['user'])) {
            header('location: /');
        } else {
            if ($_SESSION['user']['group'] !== 'admins') {
                header('location: /');
            }
        }

        $this->userModel = new UserModel();
        $this->pictureModel = new PictureModel();
        $this->commentModel = new CommentModel();
    }

    public function indexAction()
    {
        header('Location: /admin/last-five');
    }

    public function lastFiveAction()
    {
        $users = $this->userModel->getLastX(5);
        $pictures = $this->pictureModel->getLastX(5);
        $comments = $this->commentModel->getLastX(5);
        $viewParams = [
            'users'     => $users,
            'pictures'  => $pictures,
            'comments'  => $comments,
            'title'     => 'Last five',
            'CSS'       => [
                            'userlist.css',
                            'admin.css'
                        ],
        ];
        View::render('admin/last-five.php', $viewParams);
    }

    public function usersAction($params)
    {
        $page = isset($params['page']) && !empty($params['page']) ? $params['page'] : 1;
        $users = $this->userModel->getPaginatedData($page, 10);
        $allPages = $this->userModel->getNumberOfPages(10);
        $url = '/admin/users/';
        $viewParams = [
            'users'         => $users,
            'page'          => $page,
            'pages'         => $allPages,
            'url'           => $url,
            'title'         => 'Administer users',
            'showActions'   => true,
            'showPaginator' => true,
             'CSS'          => [
                            'userlist.css',
                            'admin.css'
                        ]
        ];
        View::render('user/list.php', $viewParams);
    }

    public function picturesAction($params)
    {
        $page = isset($params['page']) && !empty($params['page']) ? $params['page'] : 1;
        $pictures = $this->pictureModel->getPaginatedData($page);
        $allPages = $this->pictureModel->getNumberOfPages(10);
        $viewParams = [
            'page'          => $page,
            'pages'         => $allPages,
            'pictures'      => $pictures,
            'url'           => '/admin/pictures/',
            'title'         => 'Administer pictures',
            'showPaginator' => true,
            'CSS'           => ['admin.css'],
        ];
        View::render('admin/pictures.php', $viewParams);
    }

    public function commentsAction($params)
    {
        $page = isset($params['page']) && !empty($params['page']) ? $params['page'] : 1;
        $comments = $this->commentModel->getPaginatedData($page);
        $allPages = $this->commentModel->getNumberOfPages(10);
        $viewParams = [
            'page'          => $page,
            'pages'         => $allPages,
            'comments'      => $comments,
            'url'           => '/admin/comments/',
            'title'         => 'Administer comments',
            'showPaginator' => true,
            'CSS'           => ['admin.css'],
        ];
        View::render('admin/comments.php', $viewParams);
    }
}