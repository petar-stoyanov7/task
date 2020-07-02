<?php

namespace Application\Controllers;


use Application\Forms\CommentForm;
use Application\Forms\PictureForm;
use Application\Models\PictureModel;
use Application\Models\UserModel;
use Core\View;

class Pictures
{
    private $pictureModel;
    private $userModel;

    public function __construct()
    {
        $this->pictureModel = new PictureModel();
        $this->userModel = new UserModel();
    }

    public function indexAction()
    {
        header('location: /pictures/list');
    }

    public function listAction($params)
    {
        $page = !empty($params['page']) ? (int)$params['page'] : 1;
        $allPages = $this->pictureModel->getNumberOfPages(10);
        $pictures = $this->pictureModel->getPaginatedData($page);
        $viewParams = [
            'page'      => $page,
            'pages'  => $allPages,
            'pictures'  => $pictures,
            'title'     => 'Pictures',
            'heading'   => 'Gallery',
            'showNav'   => true
        ];
        View::render('gallery.php', $viewParams);
    }

    public function newAction()
    {
        if (!empty($_POST)) {
            $values = $_POST;
            $limit = $this->userModel->getUserLimit($values['user_id']);
            if ($limit >= 10) {
                header('location: /');
                return;
            }
            $file = $_FILES['picture-file'];
            $newFile = md5($file['name']);
            $newFile .= '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            $userId = $values['user_id'];
            $fullpath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $userId . '/';
            /** $path represents the relative path, stored in DB,
             * $fullPath is the file's location on the filesystem */
            $path = '/uploads/' . $userId . '/';
            $fullpath .= $newFile;
            if (move_uploaded_file($file['tmp_name'], $fullpath)) {
                $values['file'] = $path . $newFile;
                $this->pictureModel->add($values);
                $this->userModel->updatePictureCount($userId);
                header('location: /pictures/list');
            }
        }

        if (isset($_SESSION['user'])) {
            $limit = $this->userModel->getUserLimit($_SESSION['user']['id']);
            if ($limit >= 10) {
                header('location:/');
                return;
            }
            $form = new PictureForm($_SESSION['user']['id']);
            $form->setTarget('/pictures/new');
            $viewParams = [
                'title' => 'Upload a picture',
                'form' => $form,
                'CSS' => 'form.css',
                'heading' => 'Add new picture'
            ];
            View::render('form.php', $viewParams);
        } else {
            header('location: /');
            die();
        }
    }

    public function showAction($params)
    {
        if (empty($params) || empty($params['id'])) {
            header('location: /');
        }
        $pictureId = $params['id'];
        $picture = $this->pictureModel->getById($pictureId);
        $pictureEdit = new PictureForm();
        $pictureEdit->getElementByName('picture-file')->setRequired(false);
        $viewParams = [
            'title'         => 'Image details',
            'picture'      => $picture,
            'CSS'           => ['main.css', 'picture.css'],
            'JS'            => 'picture.js',
            'editForm'      => $pictureEdit,
            'commentForm'   => new CommentForm(),
        ];

        View::render('pictures/show.php', $viewParams);
    }
}