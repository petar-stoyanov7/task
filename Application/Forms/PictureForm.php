<?php

namespace Application\Forms;

use Core\Form\AbstractForm;

class PictureForm extends AbstractForm
{
    private $userId;
    private $pictureId;

    public function __construct($userId = null, $pictureId = null)
    {
        $this->userId = $userId;
        $this->pictureId = $pictureId;


        parent::__construct();
    }

    public function init()
    {
        $this->setName('picture-form');
        $this->setMethod('post');
        $this->setOptions(
            [
                'classes' => [
                    'custom-form',
                    'form-group'
                ]
            ]
        );
        $this->setUpload(true);

        if (!empty($this->pictureId)) {
            $this->addElement(
                'hidden',
                'picture_id',
                [
                    'required' => true
                ],
                $this->pictureId
            );
        }

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
            'picture_title',
            [
                'required' => true,
                'label' => 'Title',
                'placeholder' => 'The title of the picture',
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'file',
            'picture-file',
            [
                'required' => true,
                'classes' => ['form-control']
            ]
        );

        $this->addElement(
            'button',
            'picture-submit',
            [
                'classes' => ['form-control'],
                'label' => 'Send'
            ]
        );


    }
}