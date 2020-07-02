<?php

namespace Application\Controllers;


use Application\Forms\CommentForm;
use Application\Forms\PictureForm;
use Application\Models\CommentModel;
use Application\Models\PictureModel;
use Application\Models\UserModel;
use Core\View;

class Pictures
{
    private $pictureModel;
    private $userModel;
    private $commentModel;

    public function __construct()
    {
        $this->pictureModel = new PictureModel();
        $this->userModel = new UserModel();
        $this->commentModel = new CommentModel();
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
            'pages'     => $allPages,
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

    public function editPictureAction()
    {
        if (empty($_POST)) {
            headeR('location: /');
        } else {
            $values = $_POST;
            $currentPicture = $this->pictureModel->getById($values['picture_id']);
            if (!empty($_FILES['picture-file']['name'])) {
                $currentFile = $_SERVER['DOCUMENT_ROOT'] . $currentPicture['path'];
                unlink($currentFile);
                $file = $_FILES['picture-file'];
                $newFile = md5($file['name']);
                $newFile .= '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                $userId = $currentPicture['user_id'];
                $fullpath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $userId;
                $fullpath .= $newFile;
                /** $path represents the relative path, stored in DB,
                 * $fullPath is the file's location on the filesystem */
                $path = '/uploads/' . $userId;
                if (move_uploaded_file($file['tmp_name'], $fullpath)) {
                    $values['new_path'] = $path . $newFile;
                }
            }
            $this->pictureModel->editPicture($values);
            header('location: /pictures/show/id/'.$values['picture_id']);
        }
    }

    public function deletePictureAction($params)
    {
        if (empty($params)) {
            header('location: /');
            return;
        }
        $picture = $this->pictureModel->getById($params['id']);
        $this->commentModel->deletePictureComments($params['id']);
        $this->pictureModel->deletePicture($params['id']);
        $this->userModel->updatePictureCount($picture['user_id']);
        header('Location: /pictures/show');
    }

    public function showAction($params)
    {
        if (empty($params) || empty($params['id'])) {
            header('location: /');
        }
        $pictureId = $params['id'];
        $userId = $_SESSION['user']['id'];
        $picture = $this->pictureModel->getById($pictureId);
        $pictureEditForm = new PictureForm(null, $pictureId);
        $pictureEditForm->setTarget('/pictures/edit-picture');
        $commentForm = new CommentForm($userId,$pictureId);
        $comments = $this->commentModel->getCommentsForPicture($pictureId);
        $commentsLimit = $this->commentModel->getNumberOfPictureComments($pictureId);
        if ($commentsLimit >= 10) {
            $commentForm->disableElements(
                ['comment_body', 'submit']
            );
        }
        $pictureEditForm
            ->getElementByName('picture-file')
            ->setRequired(false);
        $viewParams = [
            'title'         => 'Image details',
            'picture'       => $picture,
            'comments'      => $comments,
            'CSS'           => ['main.css', 'picture.css'],
            'JS'            => 'picture.js',
            'editForm'      => $pictureEditForm,
            'commentForm'   => $commentForm,
        ];

        View::render('pictures/show.php', $viewParams);
    }

    public function addCommentAction()
    {
        if (empty($_POST)) {
            header('location: /');
        } else {
            $data = $_POST;
            $commentId = $this->commentModel->addComment($data);
            if ($commentId) {
                echo json_encode([
                    'success'   => 1,
                    'comment_id' => $commentId,
                    'username'  => $_SESSION['user']['username']
                ]);
            } else {
                echo json_encode(['success' => 0]);
            }
            die();
        }
    }

    public function removeCommentAction()
    {
        if (empty($_POST)) {
            header('location: /');
        } else {
            $comment = $this->commentModel->getById($_POST['comment_id']);
            if (
                $_SESSION['user']['group'] === 'admins' ||
                $_SESSION['user']['id'] === $comment['user_id'] ||
                $_SESSION['user']['id'] === $comment['picture_owner_id']
            ) {
                $this->commentModel->deleteById($comment['id']);
                echo json_encode(['success' => 1]);
            } else {
                echo json_encode(['success' => 0]);
            }
        }
        die();
    }
}