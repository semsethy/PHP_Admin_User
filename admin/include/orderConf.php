<?php
require_once 'Database.php';
class Orders {
    private $conn;

    public function __construct() {
        // Establish a connection to the database
        $this->conn = (new Database())->getConnection();
    }

    // Insert a new product into the cart
    public function addOrder($order_number, $user_id, $first_name, $last_name, $shipping_address, $total) {
        $query = "INSERT INTO orders (order_number, user_id, first_name, last_name, shipping_address, total_amount, order_status)
            VALUES (?, ?, ?, ?, ?, ?, 'Completed')";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$order_number, $user_id, $first_name, $last_name, $shipping_address, $total]);
    }
    public function getOrder(){
        $query = "SELECT o.order_number, o.first_name, o.last_name, o.order_date, o.shipping_address, o.order_status, o.total_amount
          FROM orders o
          ORDER BY o.order_date DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
?>