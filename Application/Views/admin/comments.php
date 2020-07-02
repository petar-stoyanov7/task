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
                        <?= $comment['author']; ?>
                    </td>
                    <td>
                        <?= $comment['date_created']; ?>
                    </td>
                    <td>
                        <a
                                href="#"
                                class="btn btn-danger btn-xs"
                                onclick="deleteComment('<?= $comment['id']; ?>')"
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
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="/user/list/page/1">1</a></li>
                        <li class="page-item"><a class="page-link" href="/user/list/page/2">2</a></li>
                        <li class="page-item"><a class="page-link" href="/user/list/page/3">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="/user/list/page/2">Next</a>
                        </li>
                    </ul>
                </nav>
        <?php endif; ?>
    </div>
</div>