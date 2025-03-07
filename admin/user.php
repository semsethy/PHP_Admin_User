<?php
    include 'include/userConf.php';
    $userClass = new User();
    $results_per_page = 8;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start_from = ($page - 1) * $results_per_page;
    $total_users = $userClass->getTotalUsers();
    $total_pages = ceil($total_users / $results_per_page);
    $users = $userClass->getUsers($start_from, $results_per_page);
    if (isset($_GET['delete_id'])) {
        $delete_id = (int)$_GET['delete_id'];
        if ($userClass->deleteUser($delete_id)) {
            echo "<script> window.location.href = 'index.php?p=user';</script>";
        } else {
            echo "<script>alert('Error deleting user account');</script>";
        }
    }
?>

<div class="container-fluid">
    <h1>User List</h1>
    <div class="mb-4" style="text-align: right;">
        <a href="index.php?p=category_add" style="background-color: #28a745; color: white; border-radius: 4px; padding: 8px 12px; border: none; cursor: pointer;">Add user</a>
    </div>
    <div class="table-container">
        <div class="card p-3">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th><strong>ID</strong></th>
                            <th><strong>Username</strong></th>
                            <th><strong>Identifier</strong></th>
                            <th><strong>Created</strong></th>
                            <th><strong>Signed in</strong></th>
                            <th><strong>Action</strong></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php if (empty($users)): ?>
                            <tr><td colspan='6' class='text-center'>No User Account Yet.</td></tr>
                        <?php else: ?>
                        <?php foreach ($users as $row): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['formatted_created_at']; ?></td>
                                <td><?php echo $row['formatted_last_login_at'] ? $row['formatted_last_login_at'] : 'Never'; ?></td>
                                <td>
                                    <div class='dropdown'>
                                        <button type='button' class='btn dropdown-toggle hide-arrow d-flex align-items-center justify-content-center' data-bs-toggle='dropdown'>
                                            <i class='bx bx-dots-vertical-rounded p-0'></i>
                                        </button>
                                        <div class='dropdown-menu'>
                                            <a class='dropdown-item' href='edit_category.php?id=<?php echo $row['id'];?>'><i class='bx bx-edit-alt me-1'></i> Reset Password</a>
                                            <a class='dropdown-item' href='edit_category.php?id=<?php echo $row['id'];?>'><i class='bx bx-edit-alt me-1'></i> Disable Account</a>
                                            <a class='dropdown-item' href='index.php?p=user&delete_id=<?php echo $row['id'];?>' style='color: red !important;'><i class='bx bx-trash me-1'></i> Delete Account</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav aria-label="User Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?p=user&page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?p=user&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?p=user&page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php $conn = null; ?>