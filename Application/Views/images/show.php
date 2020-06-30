<div class="container main-container">

    <div class="row picture-row">
        <h3>Picture title</h3>
        <div class="image-container show-image">
            <a href="/uploads/0/02.jpg" target="_blank">
                <img src="/uploads/0/02.jpg" alt="">
            </a>
        </div>
        <div>
            <a href="/user/show/id/3">admin</a>,
            <span>
                19.06.2020
            </span>
        </div>
    </div>

    <div class="row picture-row">

        <div class="col-lg-4 col-md-4 col-xs-12 mb-3">
        <?php if (isset($_SESSION['user'])) : ?>
                <?php $View::renderForm($form); ?>
        <?php endif; ?>
        </div>

        <div class="col-lg-8 col-md-8 col-xs-12 mb-3">
            <div id="comments-container">
                <article class="picture-comment">
                    <h5>Admin</h5>
                    <span class="picture-comment">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis doloremque eius excepturi exercitationem ipsa nam
                    </span>
                </article>
                <article class="picture-comment">
                    <h5>Admin</h5>
                    <span class="picture-comment">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis doloremque eius excepturi exercitationem ipsa nam
                    </span>
                </article>
                <article class="picture-comment">
                    <h5>Admin</h5>
                    <span class="picture-comment">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis doloremque eius excepturi exercitationem ipsa nam
                    </span>
                </article>
                <article class="picture-comment">
                    <h5>Admin</h5>
                    <span class="picture-comment">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis doloremque eius excepturi exercitationem ipsa nam
                    </span>
                </article>
                <article class="picture-comment">
                    <h5>Admin</h5>
                    <span class="picture-comment">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Debitis doloremque eius excepturi exercitationem ipsa nam
                    </span>
                </article>
            </div>
        </div>

    </div>


    <div class="row picture-row">
    </div>

</div>