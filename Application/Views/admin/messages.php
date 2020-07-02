<div class="container main-container">
    <div class="row">
        <table class="table table-striped">
            <thead class="thead-dark">
            <th scope="col">Name</th>
            <th scope="col">Reg username</th>
            <th scope="col">E-mail</th>
            <th scope="col">Reg e-mail</th>
            <th scope="col">Message</th>
            <th scope="col">date</th>
            <th scope="col">actions</th>
            </thead>
            <tbody>
            <?php foreach ($messages as $message) : ?>
            <tr message-id="<?= $message['id']; ?>">
                    <td><?= $message['name']; ?></td>
                    <td><?= $message['username']; ?></td>
                    <td><?= $message['email']; ?></td>
                    <td><?= $message['real_email']; ?></td>
                    <td><?= $message['message']; ?></td>
                    <td><?= $message['date']; ?></td>
                    <td>
                        <a href="/contacts/delete-message/id/<?= $message['id']; ?>"
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