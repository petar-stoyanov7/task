<?php

namespace Application\Forms;

use Core\Form\AbstractForm;

class CommentForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        $this->setName('comments-form');
        $this->setOptions(
            [
                'classes' => [
                    'comment-form',
                    'form-group'
                ]
            ]
        );

        $this->addElement(
            'hidden',
            'user_id',
            [
                'required' => true
            ]
        );

        $this->addElement(
            'textarea',
            'comment-body',
            [
                'required' => true,
                'classes' => ['form-control'],
                'placeholder' => 'write a comment'
            ]
        );

        $this->addElement(
            'button',
            'submit',
            [
                'classes' => ['form-control'],
                'label' => 'Send'
            ]
        );


    }
}