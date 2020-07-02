<?php

namespace Application\Forms;

use Core\Form\AbstractForm;

class RegisterForm extends AbstractForm
{
    private $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setName('register-form');
        $this->setTarget('/user/register');
        $this->setOptions(
            [
                'classes' => [
                    'custom-form',
                    'form-group'
                ]
            ]
        );

        if (!empty($this->userId)) {

            $this->addElement(
                'hidden',
                'user_id',
                [
                    'required' => true
                ],
                $this->userId
            );

        }

        $this->addElement(
            'text',
            'username',
            [
                'required' => true,
                'placeholder' => 'Your username',
                'label' => 'Username',
                'classes' => ['form-control'],
            ]
        );

        $this->addElement(
            'password',
            'password',
            [
                'required' => true,
                'placeholder' => 'Password',
                'label' => 'Password',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'password',
            'password2',
            [
                'required' => true,
                'placeholder' => 'Repeat password',
                'label' => 'Repeat password',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'email',
            'email',
            [
                'required'      => true,
                'placeholder'   => 'Email address:',
                'label'         => 'Email address:',
                'classes'       => ['form-control'],
            ]
        );

        $this->addElement(
            'email',
            'email2',
            [
                'required'      => true,
                'placeholder'   => 'Repeat email address:',
                'label'         => 'Repeat email address:',
                'classes'       => ['form-control'],
            ]
        );

        $this->addElement(
            'text',
            'firstname',
            [
                'required'      => true,
                'label'         => 'First name',
                'placeholder'   => 'first name',
                'classes'       => ['form-control']
            ]
        );

        $this->addElement(
            'text',
            'lastname',
            [
                'required'      => true,
                'label'         => 'Last name',
                'placeholder'   => 'last name',
                'classes'       => ['form-control']
            ]
        );

        $this->addElement(
            'button',
            'register-submit',
            [
                'required'  => false,
                'label'     => 'Register',
                'classes'   => [
                    'btn',
                    'btn-primary',
                ]
            ]
        );
    }
}