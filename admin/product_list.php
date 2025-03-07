<?php
require_once 'include/productConf.php';
$product = new Product();

$products_per_page = 5;

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $products_per_page;
$products = $product->getAll($offset, $products_per_page);
$total_products = $product->countAll();
$total_pages = ceil($total_products / $products_per_page);

?>

<div class="container-fluid">
    <h1>Product List</h1>

    <div class="mb-4" style="text-align: right;">
        <a href="index.php?p=product_add" style="background-color: #28a745; color: white; border-radius: 4px; padding: 8px 12px; border: none; cursor: pointer;">+ New</a>
    </div>

    <div class="table-container">
        <div class="card p-3">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($products) > 0) {
                            foreach ($products as $row) {
                                $status_color = ($row['status'] == 1) ? 'color: rgb(3, 232, 95);' : 'color: rgb(172, 8, 17);';
                                $status_text = ($row['status'] == 1) ? 'Active' : 'Inactive';
                                echo "<tr>";
                                echo "<td>{$row['id']}</td>";
                                echo "<td><img src='{$row['main_image_url']}' alt='Product Image' style='width: 50px; height: 50px; object-fit: cover; border-radius: 5px;'/></td>";
                                echo "<td>{$row['product_name']}</td>";
                                echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                echo "<td>\${$row['price']}</td>";
                                echo "<td>{$row['stock_quantity']}</td>";
                                echo "<td><span style='" . $status_color . "'>" . $status_text . "</span></td>";
                                echo "<td>
                                        <div class='dropdown'>
                                            <button type='button' class='btn dropdown-toggle hide-arrow' data-bs-toggle='dropdown'>
                                                <i class='bx bx-dots-vertical-rounded'></i>
                                            </button>
                                            <div class='dropdown-menu'>
                                                <a class='dropdown-item' href='index.php?p=product_add&id={$row['id']}'><i class='bx bx-edit-alt'></i> Edit</a>
                                                <a class='dropdown-item' href='product_delete.php?id={$row['id']}' style='color: red;'><i class='bx bx-trash'></i> Delete</a>
                                            </div>
                                        </div>
                                      </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No products found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <nav aria-label="Brand Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?p=product_list&page=<?= $page - 1 ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="index.php?p=product_list&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="index.php?p=product_list&page=<?= $page + 1 ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>


