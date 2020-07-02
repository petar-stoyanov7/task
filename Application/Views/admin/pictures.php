<div class="container main-container">
    <div class="row">
        <table class="table table-striped">
            <thead class="thead-dark">
            <th scope="col">Thumb</th>
            <th scope="col">Title</th>
            <th scope="col">Author</th>
            <th scope="col">Date</th>
            <th scope="col">Actions</th>
            </thead>
            <tbody>
            <?php foreach ($pictures as $picture) : ?>
                <tr picture-id="<?= $picture['id']; ?>">
                    <td>
                        <div class="admin-image-thumbnail">
                            <a href="/pictures/show/id/<?= $picture['id']; ?>">
                                <img src="<?= $picture['path']; ?>" alt="">
                            </a>
                        </div>
                    </td>
                    <td><?= $picture['title']; ?></td>
                    <td><?= $picture['username']; ?></td>
                    <td><?= $picture['date_updated']; ?></td>
                    <td>
                        <a href="/pictures/delete-picture/id/<?= $picture['id']; ?>"
                           class="btn btn-danger btn-xs"
                        >
                            delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($showPaginator) : ?>
        <div class="row">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item<?= (int)$page === 1 ? ' disabled' : ''; ?>">
                        <a class="page-link" href="<?= $url; ?>page/<?= $page - 1; ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i < $pages; $i++) : ?>
                        <?php if ($i < 3) : ?>
                            <li class="page-item<?= $i === (int)$page ? ' active' : ''?>">
                                <a class="page-link" href="<?= $url; ?>page/<?= $i; ?>">
                                    <?= $i; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if ($pages > 4) : ?>
                            <?php if ($i > $pages-1) : ?>
                                <li class="page-item<?= $i === (int)$page ? ' active' : ''?>">
                                    <a class="page-link" href="<?= $url; ?>page/<?= $i; ?>">
                                        <?= $i; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endfor; ?>
                    <li class="page-item<?= (int)$page === $pages ? ' disabled' : ''; ?>">
                        <a class="page-link" href="<?= $url; ?>page/<?= $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>