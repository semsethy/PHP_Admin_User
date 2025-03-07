<?php
session_start();

require_once '../admin/include/shopping_cartConf.php';
$shop = new ShoppingCart();

if (!isset($_SESSION['user_id'])) {
    echo 'Please log in first.';
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['user_id'], $_POST['product_id'], $_POST['quantity'])) {
        $user_id = (int) $_POST['user_id']; 
        $product_id = (int) $_POST['product_id'];
        $quantity = (int) $_POST['quantity'];
        if ($quantity <= 0) {
            echo 'Invalid quantity.';
            exit();
        }
        $stmt = $shop->checkProductInCart($user_id, $product_id);

        if ($stmt->rowCount() > 0) {
            $existing_quantity = $stmt->fetchColumn(); 
            $new_quantity = $existing_quantity + $quantity; 
            $shop->updateProductQuantity($new_quantity, $user_id, $product_id);
        } else {
            $shop->addProductToCart($user_id, $product_id, $quantity);  
        }
        echo 'success'; 
    } else {
        echo 'Missing required data.';
    }
} else {
    echo 'Invalid request method';
}
?>
