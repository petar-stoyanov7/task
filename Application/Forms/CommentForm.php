<?php

namespace Application\Forms;

use Core\Form\AbstractForm;

class CommentForm extends AbstractForm
{
    private $userId;
    private $pictureId;

    public function __construct($userId,$pictureId)
    {
        $this->userId = $userId;
        $this->pictureId = $pictureId;

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
            'comment_user_id',
            [
                'required' => true
            ],
            $this->userId
        );

        $this->addElement(
            'hidden',
            'comment_picture_id',
            [
                'required' => true
            ],
            $this->pictureId
        );

        $this->addElement(
            'textarea',
            'comment_body',
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