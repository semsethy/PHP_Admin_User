<?php
require_once 'Database.php';
class ShoppingCart {
    private $conn;
    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }
    // Get the shopping cart items for the user
    public function getShoppingCart($user_id) {
        $query = "
            SELECT sc.product_id, sc.quantity, p.product_name, p.price, p.main_image_url 
            FROM shopping_cart sc
            JOIN products p ON sc.product_id = p.id
            WHERE sc.user_id = :user_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function checkProductInCart($user_id, $product_id) {
        $query = "SELECT quantity FROM shopping_cart WHERE user_id = :user_id AND product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    // Update the quantity of an existing product in the cart
    public function updateProductQuantity($new_quantity, $user_id, $product_id) {
        $updateQuery = "UPDATE shopping_cart SET quantity = :new_quantity WHERE user_id = :user_id AND product_id = :product_id";
       $updateStmt = $this->conn->prepare($updateQuery);
        $updateStmt->bindParam(':new_quantity', $new_quantity, PDO::PARAM_INT);
        $updateStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $updateStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
       $updateStmt->execute();
       return $updateStmt;
    }

    // Insert a new product into the cart
    public function addProductToCart($user_id, $product_id, $quantity) {
        $insertQuery = "INSERT INTO shopping_cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)";
        $insertStmt = $this->conn->prepare($insertQuery);
        $insertStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $insertStmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $insertStmt->execute();
        return $insertStmt;
    }
}
?>
