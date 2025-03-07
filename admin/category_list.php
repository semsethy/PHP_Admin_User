<?php
    require_once 'include/categoryConf.php';
    $category = new Category();

    $categories_per_page = 5;
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $categories_per_page;
    $result = $category->getCategoriesPagination($offset, $categories_per_page);
    $total_row = $category->getTotal();
    $total_categories = $total_row['total'];
    $total_pages = ceil($total_categories / $categories_per_page);
?>

<div class="container-fluid">
    <h1>Category List</h1>
    <div class="mb-4" style="text-align: right;">
        <a href="index.php?p=category_add" style="background-color: #28a745; color: white; border-radius: 4px; padding: 8px 12px; border: none; cursor: pointer;">+ New</a>
    </div>
    <div class="table-container">
        <div class="card p-3">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th><strong>ID</strong></th>
                            <th><strong>Image</strong></th>
                            <th><strong>Name</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Actions</strong></th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        <?php
                        if (count($result) > 0) {
                            foreach ($result as $row) {
                                $image_path = '' . htmlspecialchars($row['category_image']);
                                if (file_exists($image_path)) {
                                    $image_html = "<img src='" . $image_path . "' alt='Category Image' class='product-img' style='width: 50px; height: 50px; object-fit: cover; border-radius: 5px; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);'/>";
                                } else {
                                    $image_html = "<img src='images/logos/placeholder2.jpg' alt='Brand Logo' class='product-img' style='width: 50px; height: 50px; object-fit: cover; border-radius: 5px; border: 1px solid lightgray; box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.3);'/>";
                                }
                                
                                $status_color = ($row['status'] == 1) ? 'color: rgb(3, 232, 95);' : 'color: rgb(172, 8, 17);';
                                $status_text = ($row['status'] == 1) ? 'Active' : 'Inactive';
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $image_html . "</td>";
                                echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                echo "<td><span style='" . $status_color . "'>" . $status_text . "</span></td>";
                                echo "<td>";
                                echo "<div class='dropdown'>";
                                echo "<button type='button' class='btn dropdown-toggle hide-arrow d-flex align-items-center justify-content-center' data-bs-toggle='dropdown'>";
                                echo "<i class='bx bx-dots-vertical-rounded p-0'></i>";
                                echo "</button>";
                                echo "<div class='dropdown-menu'>";
                                echo "<a class='dropdown-item' href='index.php?p=category_add&id=" . $row['id'] . "'><i class='bx bx-edit-alt me-1'></i> Edit</a>";
                                echo "<a class='dropdown-item' href='delete_category.php?id=" . $row['id'] . "' style='color: red !important;'><i class='bx bx-trash me-1'></i> Delete</a>";
                                echo "</div>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No categories found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <nav aria-label="Product Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?p=category_list&page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?p=category_list&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?p=category_list&page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php $conn = null; // Close the PDO connection ?>
