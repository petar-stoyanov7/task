<div class="container main-container">

    <div class="row admin-container">
        <h3>Last five users</h3>
        <?php
            $View::displayPartial(
                    'user/list.php',
                    [
                        'showPaginator' => false,
                        'showActions'   => true,
                    ]
            );
        ?>
    </div>

    <div class="row admin-container">
        <h3>Last five pictures</h3>
        <?php
            $View::displayPartial(
                    'admin/pictures.php',
                    ['showPaginator' => false]
            );
        ?>
    </div>

    <div class="row admin-container">
        <h3>Last five comments</h3>
        <?php
            $View::displayPartial(
                    'admin/comments.php',
                    ['showPaginator' => false]
            );
        ?>
    </div>




</div>