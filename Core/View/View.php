<?php

namespace Core;

use Application\Forms\LoginForm;
use Application\Forms\UserForm;
use Core\Form\AbstractForm;
use Core\Form\Element;

class View
{
    public static function render($view, $arguments = [])
    {
        $jsArray = [];
        $cssArray = [];
        if (!empty($arguments['title'])) {
            $title = $arguments['title'];
            unset($arguments['title']);
        }
        if (!empty($arguments['JS'])) {
            if (is_array($arguments['JS'])) {
                $jsArray = $arguments['JS'];
            } else {
                $jsArray = [$arguments['JS']];
            }
            unset($arguments['JS']);
        }
        if (!empty($arguments['CSS'])) {
            if (is_array($arguments['CSS'])) {
                $cssArray = $arguments['CSS'];
            } else {
                $cssArray = [$arguments['CSS']];
            }
            unset($arguments['CSS']);
        }
        $file = "../Application/Views/$view";
        $arguments['View'] = new View();
        if (is_readable($file)) {
            extract($arguments, EXTR_SKIP);
            require('../Application/Views/Layout/header.php');
            require($file);
            require('../Application/Views/Layout/footer.php');
        } else {
            echo "{$file} not found";
        }
    }

    public static function displayPartial($file, $arguments = [])
    {
        $file =  "../Application/Views/" . $file;
        $arguments['View'] = new View();
        if (is_readable($file)) {
            extract($arguments, EXTR_SKIP);
            require($file);
        } else {
            echo "{$file} not found";
        }
    }

    public static function renderForm(AbstractForm $renderedForm)
    {
        $form = $renderedForm;
        require('form.php');
    }

    public static function renderFormElement(Element $Element)
    {
        $name = $Element->getName();
        $label = $Element->getLabel();
        $type = $Element->getType();
        $class = $Element->getClass();
        $placeholder = $Element->getPlaceholder();
        $errors = $Element->getErrors();
        if($Element->isSelect()) {
            require(__DIR__. '/FormElements/select.php');
        } elseif($type === 'textarea') {
            require(__DIR__. '/FormElements/textarea.php');
        } elseif($type === 'button') {
            require(__DIR__. '/FormElements/button.php');
        } else {
            require(__DIR__ . '/FormElements/input.php');
        }
    }

    public static function renderElements(array $formElements, AbstractForm $form)
    {
        foreach($formElements as $name => $formElement) {
            /** @var Element $Element */
            $Element = $formElement['element'];
            $div = '<div class="form-wrapper';
            if (!empty($Element->getErrors())) {
                $div .= ' form-errors';
            }
            if (!empty($formElement['group'])) {
                $groupName = $formElement['group'];
                if (empty($cache[$groupName])) {
                    $group = $form->getGroupElements($groupName);
                    $cache[$groupName] = $group;
                } else {
                    $group = $cache[$groupName];
                }
                $group = array_keys($group);
                if ($name === $group[0]) {
                    $groupId = preg_replace('/[^a-z]/', '', strtolower($groupName));
                    $div .= "\" id=\"form-group-{$groupId}\">";
                    echo $div;
                    self::renderFormElement($Element);
                } elseif ($name === $group[count($group) - 1]) {
                    self::renderFormElement($Element);
                    echo '</div>';
                } else {
                    self::renderFormElement($Element);
                }
            } else {
                $div .= '">';
                echo $div;
                self::renderFormElement($Element);
                echo '</div>';
            }
        }
    }
}