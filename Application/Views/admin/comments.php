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
            <?php for ($i = 1; $i < 6; $i++) : ?>
                <tr>
                    <td>
                        <div class="admin-image-thumbnail">
                            <img src="/uploads/0/0<?= $i; ?>.jpg" alt="">
                        </div>
                    </td>
                    <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid asperiores, assumenda consequatur doloremque eius esse facilis incidunt ipsum, iste maxime odio perspiciatis sapiente sequi. Cupiditate facere id labore nemo odit!</td>
                    <td>admin</td>
                    <td>21.06.2020</td>
                    <td>
                        <a href="#" class="btn btn-danger btn-xs">delete</a>
                    </td>
                </tr>
            <?php endfor; ?>
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