<?php

namespace Application\Controllers;


use Application\Forms\LoginForm;
use Core\View;

class Account
{
    public function indexAction()
    {
        header('Location: /');
    }

    public function loginAction()
    {
        $loginForm = new LoginForm();
        $viewParams = [
            'form' => $loginForm,
            'CSS' => 'form.css',
        ];
        View::render('account/login.php', $viewParams);
    }

}