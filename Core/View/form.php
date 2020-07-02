<?php

use Core\Form\Element;
use Core\Form\AbstractForm;
use Core\View;

$cache = [];

/** @var AbstractForm $form */
$fieldsetElements = $form->getAllFieldsetElements();
$nonFieldsetElements = $form->getNonFieldsetElements();
?>
<form
    method="<?= $form->getMethod(); ?>"
    <?php if (!empty($form->getName())) : ?>
        id="<?= $form->getName(); ?>"
    <?php endif; ?>
    action="<?= $form->getTarget(); ?>"
    <?php if (!empty($form->getClass())) : ?>
        class="<?= $form->getClass() ?>"
    <?php endif; ?>
    <?php if ($form->getUpload()) : ?>
        enctype="multipart/form-data"
    <?php endif; ?>
>
<?php if (!empty($fieldsetElements)) : ?>
    <?php foreach($fieldsetElements as $fieldsetName => $fieldSetGroup) : ?>
    <?php $fieldsetId = preg_replace('/[^a-z]/', '', strtolower($fieldsetName)); ?>
    <fieldset class="standard-form-fieldset" id="fieldset-<?= $fieldsetId; ?>">
        <legend><?= $fieldsetName; ?></legend>
            <?php View::renderElements($fieldSetGroup, $form); ?>
    </fieldset>
    <?php endforeach; ?>
<?php endif; ?>

<?php View::renderElements($nonFieldsetElements, $form); ?>
</form>
