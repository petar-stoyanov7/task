
<div class="container main-container">
    <? if (isset($heading)) : ?>
    <div class="row">
        <h1><?= $heading ?></h1>
    </div>
    <? endif; ?>
    <div class="row home-images-container">
        <?php foreach ($pictures as $picture) : ?>
            <div class="col-lg-6 col-md-6 col-xs-6 mb-3 gallery-item">
                <h4><?= $picture['title'] ?></h4>
                <div class="image-container">
                    <a href="/pictures/show/id/<?= $picture['id']; ?>">
                        <img
                                src="<?= $picture['path']; ?>"
                                alt="Another alt text"
                        >
                    </a>
                </div>
                <p class="author">
                    <a href="/user/show/id/<?= $picture['user_id']; ?>"><?= $picture['username']; ?></a>
                </p>
            </div>
        <?php endforeach; ?>

    </div>
    <div class="row">
        <?php if($showNav) : ?>
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item<?= $page === 1 ? ' disabled' : ''; ?>">
                    <a class="page-link" href="/pictures/list/page/<?= $page - 1; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i < $pages; $i++) : ?>
                    <?php if ($i < 3) : ?>
                    <li class="page-item<?= $i === $page ? ' active' : ''?>">
                        <a class="page-link" href="/pictures/list/page/<?= $i; ?>">
                            <?= $i; ?>
                        </a>
                    </li>
                    <?php endif; ?>
                <?php if ($pages > 4) : ?>
                    <?php if ($i > $pages-1) : ?>
                        <li class="page-item<?= $i === $page ? ' active' : ''?>">
                            <a class="page-link" href="/pictures/list/page/<?= $i; ?>">
                                <?= $i; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php endfor; ?>
                <li class="page-item<?= $page === $pages ? ' disabled' : ''; ?>">
                    <a class="page-link" href="/pictures/list/page/<?= $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>
