<div class="container main-container">
    <div class="row user-list">
        <table id="user-list-table" class="table table-striped">
            <thead class="thead-dark">
                <th scope="col">Username</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Email</th>
                <th scope="col">Number of pictures</th>
                <?php if (isset($showActions) && $showActions) : ?>
                    <th scope="col">Actions</th>
                <?php endif; ?>
            </thead>
            <tbody>
            <?php foreach($users as $user) : ?>
                <tr user-id="<?= $user['id']; ?>">
                    <td><?= $user['username']; ?></td>
                    <td><?= $user['firstname']; ?></td>
                    <td><?= $user['lastname']; ?></td>
                    <td><?= $user['email']; ?></td>
                    <td><?= null !== $user['pictures_count'] ? $user['pictures_count'] : 0 ?></td>
                    <?php if ($showActions) : ?>
                        <td>
                            <a href="#" class="btn btn-danger btn-xs">delete</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($showPaginator) : ?>
    <div class="row">
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
    </div>
    <?php endif; ?>
</div>