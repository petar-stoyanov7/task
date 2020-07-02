<?php

namespace Application\Controllers;


use Application\Forms\ContactsForm;
use Application\Models\ContactModel;
use Core\View;

class Contacts
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

    public function indexAction()
    {
        header('Location: /contacts/new');
    }

    public function newAction()
    {
        if (!empty($_POST)) {
            $data = $_POST;
            $this->contactModel->addMessage($data);
//            $emailTo = 'demo@example.com';
//            $subject = 'picture site system message';
//            $message = $data['message'];
//            $header = 'From: '.$data['email'];
//            $header .= 'Reply-To: '.$data['email'];
//            mail($emailTo, $subject, $message, $header);
            header('location: /');
            return;
        }
        $userId = $_SESSION['user']['id'];
        $viewParams = [
            'title' => 'Contact us',
            'heading' => 'Contact us',
            'CSS' => ['form.css'],
            'JS' => ['contacts.js'],
            'form' => new ContactsForm($userId)
        ];

        View::render('form.php', $viewParams);
    }

    public function deleteMessageAction($params)
    {
        if (empty($params) || $_SESSION['user']['group'] !== 'admins') {
            header('location: /');
            return;
        }
        $messageId = $params['id'];
        $this->contactModel->deleteMessage($messageId);
        header('location: /');
    }
}