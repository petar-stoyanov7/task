<?php

namespace Application\Controllers;


use Application\Forms\ContactsForm;
use Core\View;

class Contacts
{
    public function indexAction()
    {
        header('Location: /contacts/new');
    }
    public function newAction()
    {
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
}