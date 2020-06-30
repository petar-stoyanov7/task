<?php

namespace Application\Forms;

use Core\Form\AbstractForm;

class RegisterForm extends AbstractForm
{
    public function __construct()
    {
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

        $this->addElement(
            'text',
            'register-username',
            [
                'required' => true,
                'placeholder' => 'Your username',
                'label' => 'Username',
                'classes' => ['form-control'],
            ]
        );

        $this->addElement(
            'password',
            'register-password1',
            [
                'required' => true,
                'placeholder' => 'Password',
                'label' => 'Password',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'password',
            'register-password2',
            [
                'required' => true,
                'placeholder' => 'Repeat password',
                'label' => 'Repeat password',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'email',
            'email-address1',
            [
                'required' => true,
                'placeholder' => 'Email address:',
                'label' => 'Email address:',
                'classes' => ['form-control'],
            ]
        );

        $this->addElement(
            'email',
            'email-address2',
            [
                'required' => true,
                'placeholder' => 'Repeat email address:',
                'label' => 'Repeat email address:',
                'classes' => ['form-control'],
            ]
        );

        $this->addElement(
            'text',
            'first_name',
            [
                'required' => true,
                'label' => 'First name',
                'placeholder' => 'first name',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'text',
            'last_name',
            [
                'required' => true,
                'label' => 'Last name',
                'placeholder' => 'last name',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'button',
            'register-submit',
            [
                'required' => false,
                'label' => 'Register',
                'classes' => [
                    'btn',
                    'btn-primary',
                ]
            ]
        );
    }
}