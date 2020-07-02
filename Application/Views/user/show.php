<div class="container main-container">
    <div class="row user-details-row">
        <div class="user-title d-flex flex-row justify-content-between w-100">
            <h1><?= $heading; ?></h1>
            <div class="user-controls">
                <?php if ($_SESSION['user']['id'] === $user['id'] || $_SESSION['user']['group'] === 'admins') : ?>
                    <a href="#" class="btn btn-success btn-xs" onclick="showEditUser('<?= $user['id']; ?>')">
                        edit
                    </a>
                    <a href="#" class="btn btn-danger btn-xs" onclick="deleteUser('<?= $user['id']; ?>')">
                        delete
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="row user-details-row">
        <div class="col-lg-4 col-md-4 col-xs-12 mb-3">
            <div class="image-container profile-picture">
                <img id="profile-picture-img" src="<?= $user['profile_picture'] ?>" alt="">
            </div>
        </div>
        <div class="col-lg-8 col-md-8 col-xs-12 mb-3 user-details">
            <div class="row">
                <span class="user-heading">
                    Username:
                </span>
                <span id="user-details-username" class="content">
                    <?= $user['username']; ?>
                </span>
            </div>
            <div class="row">
                <span class="user-heading">
                    First name:
                </span>
                <span id="user-details-firstname" class="content">
                    <?= $user['firstname']  ?>
                </span>
            </div>
            <div class="row">
                <span class="user-heading">
                    Last name:
                </span>
                <span id="user-details-lastname" class="content">
                    <?= $user['lastname'] ?>
                </span>
            </div>
            <div class="row">
                <span class="user-heading">
                    Email:
                </span>
                <span id="user-details-email" class="content">
                    <?php
                    if ($_SESSION['user']['id'] === $user['id']
                    || $_SESSION['user']['group'] === 'admins') : ?>
                        <?= $user['email'] ?>
                    <?php else : ?>
                        hidden
                    <?php endif; ?>

                </span>
            </div>
            <div class="row">
                <span id="user-details-date" class="user-heading">
                    Date Joined:
                </span>
                <span class="content">
                    <?= $user['date_created']; ?>
                </span>
            </div>
            <div class="row">
                <span id="user-details-number" class="user-heading">
                    number of pictures:
                </span>
                <span class="content">
                    <?= !empty($user['pictures_count']) ? $user['pictures_count'] : 0 ?>
                </span>
            </div>
        </div>
    </div>
    <div class="row user-details-row">
        <h2>User images:</h2>
        <div class="d-flex flex-row flex-wrap">
            <?php foreach ($pictures as $picture) : ?>
            <div class="user-image-preview">
                <?php if ($picture['user_id'] === $_SESSION['user']['id']) : ?>
                <div class="user-profile-set">
                    <button
                            class="btn btn-xs btn-info"
                            onclick="setProfilePicture('<?= $picture['user_id']; ?>','<?= $picture['path']; ?>')">
                        set profile
                    </button>
                </div>
                 <?php endif; ?>
                <a href="/pictures/show/id/<?= $picture['id'] ?>">
                    <img src="<?= $picture['path']; ?>" alt="">
                </a>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>

<div id="user-form-container" class="cover-lvl-1">
    <h3>Edit user:</h3>
    <?php $View::renderForm($form); ?>
</div>