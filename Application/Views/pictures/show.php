<div class="container main-container">

    <div class="row picture-row">
        <div class="image-title d-flex flex-row justify-content-between w-100">
            <h3 id="picture-display-title">Picture title</h3>
            <div class="image-controls">
                <?php if (
                        isset($_SESSION['user']) &&
                        (
                                ($_SESSION['user']['id']) === $picture['user_id'] ||
                                $_SESSION['user']['group'] === 'admin'
                        )
                ) : ?>
                <a id="edit-image-button" href="#" class="btn btn-sm btn-success">edit</a>
                <a id="delete-image-button" href="#" class="btn btn-sm btn-danger">delete</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="image-container show-image">
            <a href="<?= $picture['path']; ?>" target="_blank">
                <img id="main-picture" src="<?= $picture['path']; ?>" alt="" picture-id="<?= $picture['id']; ?>">
            </a>
        </div>
        <div class="row w-100">
            <div class="d-flex justify-content-between w-100">
                <div class="image-author">
                    <a href="/user/show/id/<?= $picture['user_id']; ?>"><?= $picture['username']; ?></a>,
                    <span>
                        <?= $picture['date_created']; ?>
                    </span>
                </div>
                <div class="image-tags">

                </div>
            </div>
        </div>
    </div>

    <div class="row picture-row">

        <div class="col-lg-4 col-md-4 col-xs-12 mb-3">
        <?php if (isset($_SESSION['user'])) : ?>
                <?php $View::renderForm($commentForm); ?>
        <?php endif; ?>
        </div>

        <div class="col-lg-8 col-md-8 col-xs-12 mb-3">
            <div id="comments-container">
                <?php //TODO add real comments; ?>
                <article id="3" class="picture-comment">
                    <div class="d-flex flex-row justify-content-between w-100 comment-heading">
                        <h5>Admin</h5>
                        <button type="button" class="btn btn-danger btn-xs comment-delete" onclick="_deleteComment('3')">x</button>
                    </div>
                    <span class="picture-comment">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis doloremque eius excepturi exercitationem ipsa nam
                    </span>
                </article>


            </div>
        </div>
    </div>
</div>

<div id="picture-edit-modal" class="cover-lvl-1">
    <?php $View::renderForm($editForm); ?>
</div>