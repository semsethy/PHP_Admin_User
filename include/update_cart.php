
<?php
session_start();
require_once '../admin/include/shopping_cartConf.php';
$shopping_cart = new ShoppingCart();

if (!isset($_SESSION['user_id'])) {
    echo 'Please log in firstb.';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'update_cart') {
        $user_id = $_SESSION['user_id'];

        $product_ids = $_POST['product_id']; 
        $new_quantities = $_POST['new_quantity']; 

        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = $product_ids[$i];
            $new_quantity = $new_quantities[$i];
            
            if (!is_numeric($new_quantity) || $new_quantity <= 0) {
                echo 'Invalid quantity for product ID ' . $product_id;
                exit();
            }
            $shopping_cart->updateProductQuantity($new_quantity, $user_id, $product_id);
        }
        echo 'Cart updated successfully.';
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'delete_item') {
        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id'];

        $shopping_cart->delete($user_id,$product_id);
    }
}
?>
