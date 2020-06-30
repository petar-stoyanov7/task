<?php

namespace Application\Controllers;


use Application\Forms\LoginForm;
use Application\Forms\RegisterForm;
use Core\View;

class User
{
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
        View::render('form.php', $viewParams);
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
        View::render('form.php', $viewParams);
    }

    public function listAction()
    {
        $viewParams = [
            'title' => 'Users',
            'CSS' => 'userlist.css'
        ];
        View::render('user/list.php', $viewParams);
    }

    public function showAction()
    {
        $viewParams = [
            'title' => 'User profile',
            'heading' => 'username',
            'CSS' => 'user.css',

        ];
        View::render('user/show.php', $viewParams);
    }

}