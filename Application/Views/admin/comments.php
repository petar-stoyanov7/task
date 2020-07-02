<div class="container main-container">
    <div class="row page-content-row">
        <table class="table table-striped">
            <thead class="thead-dark">
            <th scope="col">Thumb</th>
            <th scope="col">Comment</th>
            <th scope="col">Author</th>
            <th scope="col">Date</th>
            <th scope="col">Actions</th>
            </thead>
            <tbody>
            <?php foreach ($comments as $comment) : ?>
                <tr comment-id="<?= $comment['id']?>">
                    <td>
                        <div class="admin-image-thumbnail">
                            <a href="/pictures/show/id/<?= $comment['picture_id']; ?>">
                                <img src="<?= $comment['picture_path']; ?>" alt="">
                            </a>
                        </div>
                    </td>
                    <td>
                        <?= $comment['text']; ?>
                    </td>
                    <td>
                        <?= $comment['username']; ?>
                    </td>
                    <td>
                        <?= $comment['date_created']; ?>
                    </td>
                    <td>
                        <a
                                href="/pictures/delete-comment/id/<?= $comment['id']; ?>"
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
    <div class="row page-content-row">
        <?php if ($showPaginator) : ?>
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
        <?php endif; ?>
    </div>
</div>