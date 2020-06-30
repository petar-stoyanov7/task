<textarea
id="<?= $name; ?>"
name="<?= $name; ?>"
<?php if (!empty($class)) : ?>
    class="<?= $class; ?>"
<?php endif; ?>
<?php if (!empty($placeholder)) : ?>
    placeholder="<?=$placeholder; ?>"
<?php endif; ?>
<?= (bool)$Element->getDisabled() ? 'disabled' : '' ?>
><?= $Element->getValue(); ?>
</textarea>