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
        $registerForm = new RegisterForm();
        $viewParams = [
            'title' => 'Register',
            'form' => $registerForm,
            'CSS' => 'form.css',
            'JS' => 'register.js',
            'heading' => 'Register'
        ];
        if (!empty($_POST)) {
            $values = $_POST;
            $users = $this->userModel->listUsers();
            $isValid = true;
            $errors = [];
            foreach ($users as $user) {
                if ($user['username'] === $values['username']) {
                    $isValid = false;
                    $errors[] = "Username [{$values['username']}] exists";
                }
                if ($user['email'] === $values['email']) {
                    $isValid = false;
                    $errors[] = "Email address [{$values['email']}] is already used";
                }
            }
            $validation = $this->_validateRegister($values);
            $isValid = $isValid && $validation['isValid'];
            $errors = array_merge($errors, $validation['errors']);
            if ($isValid) {
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
                $errors = array_unique($errors);
                $viewParams['errors'] = $errors;
            }
        }
        if (isset($_SESSION['user'])) {
            header('location: /');
        }



        View::render('form.php', $viewParams);
    }

    public function listAction($params)
    {
        $page = isset($params['page']) && !empty($params['page']) ? $params['page'] : 1;
        $allPages = $this->userModel->getNumberOfPages(10);
        $users = $this->userModel->getPaginatedData($page);
        $viewParams = [
            'page'          => $page,
            'pages'         => $allPages,
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
            'CSS'       => ['user.css','form.css'],
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

    public function editProfileAction()
    {
        if (empty($_POST)) {
            header('location: /');
        } else {
            $postData = $_POST;
            $this->userModel->updateUser($postData['user_id'], $postData);
            echo json_encode(['success' => 1]);
            die();
        }
    }

    public function deleteAction($params)
    {
        if (empty($params) || empty($params['id'])) {
            header('location: /');
        } else {
            $userId = $params['id'];
            $this->userModel->deleteUser($userId);
            $userPath = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $userId;
            $this->_deleteDir($userPath);
            $this->logoutAction();
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
            empty($values['password']) ||
            ($values['password'] !== $values['password2'])
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

    private function _deleteDir($path) {
        $files = glob($path . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::_deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($path);
    }


}