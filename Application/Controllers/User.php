<?php

namespace Application\Controllers;


use Application\Forms\LoginForm;
use Application\Forms\RegisterForm;
use Application\Models\PictureModel;
use Application\Models\UserModel;
use Core\View;

class User
{
    private $userModel;
    private $picturesModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->picturesModel = new PictureModel();
    }

    public function indexAction()
    {
        header('Location: /');
    }

    public function loginAction()
    {
        $loginForm = new LoginForm();
        $viewParams = [
            'title' => 'Login',
            'form' => $loginForm,
            'CSS' => 'form.css',
            'JS' => 'login.js',
            'heading' => 'Login:'
        ];
        if (isset($_POST) &&
            !empty($_POST['login-username']) &&
            !empty($_POST['login-password'])
        ) {
            $values = $_POST;
            if (
                $this->userModel
                    ->login($values['login-username'], $values['login-password'])
            ) {
                header("refresh:1;url=/");
            } else {
                $viewParams['errors'] = 'Invalid username or password!';
            }
        }

        View::render('form.php', $viewParams);
    }

    public function logoutAction()
    {
        session_start();
        session_unset();
        session_destroy();
        header("Location: /");
    }

    public function registerAction()
    {
        if (!empty($_POST)) {
            $values = $_POST;
            $validation = $this->_validateRegister($values);
            if ($validation['isValid']) {
                $directory = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
                $newUserId = $this->userModel->register($values);
                if ($newUserId) {
                    mkdir($directory . $newUserId);
                    $this->userModel->login(
                        $values['username'],
                        $values['password']
                    );
                    header('location: /');
                }
            } else {
                $viewParams['errors'] = $validation['errors'];
            }
        }
        if (isset($_SESSION['user'])) {
            header('location: /');
        }
        $registerForm = new RegisterForm();
        $viewParams = [
            'title' => 'Register',
            'form' => $registerForm,
            'CSS' => 'form.css',
            'JS' => 'register.js',
            'heading' => 'Register'
        ];


        View::render('form.php', $viewParams);
    }

    public function listAction($params)
    {
        $page = isset($params['page']) && !empty($params['page']) ? $params['page'] : 1;

        $users = $this->userModel->listUsers();
        $viewParams = [
            'users'         => $users,
            'title'         => 'Users',
            'showPaginator' => true,
            'showActions'   => false,
            'CSS'           => 'userlist.css',
            'JS'            => 'users.js'
        ];
        View::render('user/list.php', $viewParams);
    }

    public function showAction($params)
    {
        if (isset($params) && !empty($params['id'])) {
            $userId = $params['id'];
        } else if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id'];
        } else {
            header('location: /');
            return;
        }
        $userForm = new RegisterForm($userId);
        $userForm->disableElement('username');
        $user = $this->userModel->getById($userId);
        $userPics = $this->picturesModel->getUserPictures($userId);
        $viewParams = [
            'form'      => $userForm,
            'user'      => $user,
            'pictures'  => $userPics,
            'title'     => $user['firstname'].'\'s profile',
            'heading'   => $user['username'],
            'CSS'       => 'user.css',
            'JS'        => 'users.js'

        ];
        View::render('user/show.php', $viewParams);
    }

    public function setProfileAction()
    {
        if (empty($_POST)) {
            header('location: /');
        } else {
            $post = $_POST;
            $this->userModel->setProfilePicture($post['user-id'],$post['path']);
            echo json_encode(['success' => true]);
            die();
        }
    }

    private function _validateRegister($values)
    {
        $errors = [];
        $valid = true;
        if (empty($values['username'])) {
            $errors[] = 'Username is empty';
            $valid = false;
        }
        if (
            empty($values['password1']) ||
            ($values['password1'] !== $values['password2'])
        ) {
            $errors[] = 'Invalid password';
            $valid = false;
        }
        if (
            empty($values['email']) ||
            ($values['email'] !== $values['email2'])
        ) {
            $errors[] = 'Invalid e-mail address';
            $valid = false;
        }
        if (empty($values['firstname'])) {
            $errors[] = 'Invalid first name';
            $valid = false;
        }
        if (empty($values['lastname'])) {
            $errors[] = 'Invalid last name';
            $valid = false;
        }

        return [
            'isValid' => $valid,
            'errors' => $errors
        ];
    }

    private function _getPublicPath()
    {
        $currentDir = __DIR__;
    }

}