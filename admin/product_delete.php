<?php
require_once 'include/productConf.php';
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$product = new Product();
if ($product_id > 0) {
    if ($product->delete($product_id)) {
        header("Location: index.php?p=product_list");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error deleting product: " . $stmt->error . "</div>";
    }
}
?>



