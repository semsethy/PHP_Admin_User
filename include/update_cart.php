<?php
session_start();
require_once '../admin/include/Database.php';
$conn = (new Database())->getConnection();
// require_once 'admin/inlcude/shopping_cartConf.php';
// $shopping_cart = new ShoppingCart();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo 'Please log in firstb.';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle update cart
    if (isset($_POST['action']) && $_POST['action'] == 'update_cart') {
        $user_id = $_SESSION['user_id'];

        // Loop through the posted product IDs and quantities
        $product_ids = $_POST['product_id']; // Array of product IDs
        $new_quantities = $_POST['new_quantity']; // Array of new quantities

        // Loop through each product ID and quantity to update the cart
        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = $product_ids[$i];
            $new_quantity = $new_quantities[$i];
            
            // Validate new quantity (should be a positive integer)
            if (!is_numeric($new_quantity) || $new_quantity <= 0) {
                echo 'Invalid quantity for product ID ' . $product_id;
                exit();
            }

            // Update the quantity in the database
            $query = "UPDATE shopping_cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            // $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
            if (!$stmt->execute()) {
                echo 'Failed to update quantity for product ID ' . $product_id;
                exit();
            }
        }

        echo 'Cart updated successfully.';
    }
    
    // Handle item deletion (existing functionality)
    if (isset($_POST['action']) && $_POST['action'] == 'delete_item') {
        $user_id = $_SESSION['user_id'];
        $product_id = $_POST['product_id'];

        // Delete item from cart
        $query = "DELETE FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        // $stmt->bind_param("ii", $user_id, $product_id);
        if ($stmt->execute()) {
            echo 'Item deleted successfully.';
        } else {
            echo 'Failed to delete item.';
        }
    }
}
?>
