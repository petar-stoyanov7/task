<button
    id="<?= $name; ?>"
    <?php if (!empty($Element->getButtonType())) : ?>
        type="<?= $Element->getButtonType() ?>"
    <?php else : ?>
        type="submit"
    <?php endif; ?>
    <?php if (!empty($Element->getOnClick())) : ?>
        onclick="<?=$Element->getOnClick() ?>"
    <?php endif; ?>
    <?php if (!empty($class)) : ?>
        class="<?= $class; ?>"
    <?php endif; ?>
    <?= (bool)$Element->getDisabled() ? 'disabled' : '' ?>
>
    <?= empty($label) ? $name : $label; ?>
</button>