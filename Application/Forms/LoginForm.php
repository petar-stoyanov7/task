<?php

namespace Application\Forms;

use Core\Form\AbstractForm;

class LoginForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setName('login-form');
        $this->setTarget('/account/login');
        $this->setOptions(
            [
                'classes' => [
                    'login-form',
                    'form-group'
                ]
            ]
        );

        $this->addElement(
            'text',
            'login-username',
            [
                'required' => true,
                'placeholder' => 'Username',
                'label' => 'Username',
                'classes' => ['form-control'],
            ]
        );

        $this->addElement(
            'password',
            'login-password',
            [
                'required' => true,
                'placeholder' => 'Password',
                'label' => 'Password',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'button',
            'login-submit',
            [
                'required' => false,
                'label' => 'Login',
                'classes' => [
                    'btn',
                    'btn-primary',
                ]
            ]
        );
    }

}