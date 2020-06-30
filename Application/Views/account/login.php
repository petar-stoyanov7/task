<div class="container main-container form-container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-xs-12 form-group">
            <h3>Login:</h3>
            <?php if (isset($errors)) : ?>
                <div class="error-message">
                   <?php if (is_array($errors)) : ?>
                        <?php foreach($errors as $error) : ?>
                            <span class="form-error"><?= $error; ?></span>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <span class="form-error"><?= $errors; ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php $View::renderForm($form); ?>
        </div>
    </div>
</div>
