<?php
require_once 'Database.php';
class Product {
    private $conn;
    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }
    public function create($product_name, $description, $price, $stock, $status, $main_image_url, $collection_image_url, $category_id) {
        $query = "INSERT INTO products (product_name, description, price, stock_quantity, status, main_image_url, collection_image_url, category_id) 
                  VALUES (:product_name, :description, :price, :stock, :status, :main_image_url, :collection_image_url, :category_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_name", $product_name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":stock", $stock);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":main_image_url", $main_image_url);
        $stmt->bindParam(":collection_image_url", $collection_image_url);
        $stmt->bindParam(":category_id", $category_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function update($product_name, $description, $price, $stock, $status, $main_image_url, $collection_image_url, $category_id, $product_id) {
        $query = "UPDATE products SET product_name = :product_name, description = :description, price = :price, stock_quantity = :stock, status = :status, main_image_url = :main_image_url, collection_image_url = :collection_image_url, category_id = :category_id WHERE id = :product_id"; // Removed extra closing parenthesis
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_name", $product_name);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":price", $price);
        $stmt->bindParam(":stock", $stock);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":main_image_url", $main_image_url);
        $stmt->bindParam(":collection_image_url", $collection_image_url);
        $stmt->bindParam(":category_id", $category_id);
        $stmt->bindParam(":product_id", $product_id);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getProductDetails($product_id) {
        $query = "
            SELECT products.*, categories.category_name
            FROM products
            JOIN categories ON products.category_id = categories.id
            WHERE products.id = :product_id
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getRelatedProducts($category_id, $product_id) {
        $query = "
            SELECT products.*, categories.category_name
            FROM products
            JOIN categories ON products.category_id = categories.id
            WHERE products.category_id = :category_id AND products.id != :product_id AND products.status = 1
            
        ";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductsByCategory($category_id) {
        $query = "SELECT products.*, categories.category_name
                  FROM products
                  JOIN categories ON products.category_id = categories.id
                  WHERE products.category_id = :category_id AND products.status = 1
                  ORDER BY products.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_id", $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductsByCategory1($category_id, $min_price, $max_price, $color, $size) {
        $query = "SELECT * FROM products WHERE category_id = :category_id AND price BETWEEN :min_price AND :max_price";
        if ($color) {
            $query .= " AND color = :color";
        }
        if ($size) {
            $query .= " AND size = :size";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':min_price', $min_price);
        $stmt->bindParam(':max_price', $max_price);
        if ($color) {
            $stmt->bindParam(':color', $color);
        }
        if ($size) {
            $stmt->bindParam(':size', $size);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getProductsByFilters($min_price, $max_price, $color, $size) {
        $query = "SELECT * FROM products WHERE price BETWEEN :min_price AND :max_price";
        if ($color) {
            $query .= " AND color = :color";
        }
        if ($size) {
            $query .= " AND size = :size";
        }
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':min_price', $min_price);
        $stmt->bindParam(':max_price', $max_price);
        if ($color) {
            $stmt->bindParam(':color', $color);
        }
        if ($size) {
            $stmt->bindParam(':size', $size);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAll($offset, $limit) {
        $query = "SELECT products.*, categories.category_name
                  FROM products
                  JOIN categories ON products.category_id = categories.id
                  WHERE products.status = 1
                  ORDER BY products.id ASC
                  LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function read() {
        $query = "SELECT products.*, categories.category_name
                  FROM products
                  JOIN categories ON products.category_id = categories.id
                  WHERE products.status = 1
                  ORDER BY products.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $query = "SELECT * FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM products";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public function delete($id) {
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>


