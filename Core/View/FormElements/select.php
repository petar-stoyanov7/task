<?php if (!empty($label)) : ?>
    <label for="<?= $name; ?>"><?= $label; ?></label>
<?php endif; ?>
<select
    id="<?= $name ?>"
    name="<?= $name?>"
    <?php if (!empty($class)) : ?>
        class="<?= $class; ?>"
    <?php endif; ?>
    <?= (bool)$Element->getDisabled() ? ' disabled' : '' ?>
    <?= $type === 'multiselect' ? 'multiple' : ''; ?>
>
    <?php foreach($Element->getOptions() as $index => $option) : ?>
        <?php if($Element->getValue() === $index) :?>
            <option value="<?= $index; ?>" selected><?= $option ?></option>
        <?php else : ?>
            <option value="<?= $index; ?>"><?= $option ?></option>
        <?php endif; ?>
    <?php endforeach; ?>
</select>