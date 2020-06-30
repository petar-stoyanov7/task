<?php

namespace Application\Forms;

use Core\Form\AbstractForm;

class ContactsForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $this->setMethod('post');
        $this->setName('contacts-form');
        $this->setTarget('/contacts/new');
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
            'name',
            [
                'required' => true,
                'label' => 'Name:',
                'placeholder' => 'your name',
                'classes' => ['form-control'],
            ]
        );

        $this->addElement(
            'email',
            'email',
            [
                'required' => true,
                'label' => 'Email',
                'placeholder' => 'your e-mail address',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'textarea',
            'message',
            [
                'required' => true,
                'label' => 'Message:',
                'placeholder' => 'Your message',
                'classes' => ['form-control'],
            ]
        );

        $this->addElement(
            'button',
            'login-submit',
            [
                'required' => false,
                'label' => 'Send',
                'classes' => [
                    'btn',
                    'btn-primary',
                ]
            ]
        );

    }
}