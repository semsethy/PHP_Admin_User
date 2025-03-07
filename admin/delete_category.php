<?php
require_once 'include/categoryConf.php';
$category = new Category();
$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($category_id > 0) {
    $stmt = $category->delete($category_id);
    if ($stmt) {
        header("Location: index.php?p=category_list");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
} else {
    echo "<div class='alert alert-danger'>Invalid category ID.</div>";
}
?>
