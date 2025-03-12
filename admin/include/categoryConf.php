<?php
require_once 'Database.php';

class Category {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    public function create($category_name, $category_status, $category_image) {
        $query = "INSERT INTO categories (category_name, status, category_image) 
                  VALUES (:category_name, :category_status, :category_image)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category_name", $category_name);
        $stmt->bindParam(":category_status", $category_status);
        $stmt->bindParam(":category_image", $category_image);
        return $stmt;
    }
    public function update($category_name, $category_status, $category_image, $category_id){
        $query = "UPDATE categories SET category_name = :category_name, status = :category_status, category_image = :category_image WHERE id = :category_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category_name', $category_name);
            $stmt->bindParam(':category_status', $category_status, PDO::PARAM_INT);
            $stmt->bindParam(':category_image', $category_image);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            return $stmt;
    }

    public function getTotal(){
        $total_categories_sql = "SELECT COUNT(*) as total FROM categories";
        $total_stmt = $this->conn->prepare($total_categories_sql);
        $total_stmt->execute();
        return $total_stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getCategoriesPagination($offset, $categories_per_page){
        $sql = "SELECT * FROM categories ORDER BY id ASC LIMIT :offset, :categories_per_page";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':categories_per_page', $categories_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll($offset, $limit) {
        $query = "SELECT *
                  FROM categories
                  WHERE status = 1
                  ORDER BY id ASC
                  LIMIT :offset, :limit";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCategories() {
        $query = "SELECT * FROM categories WHERE status = 1"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCategoriesByID($category_id) {
        $sql = "SELECT * FROM categories WHERE id = :category_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function read() {
        $query = "SELECT *
                  FROM categories WHERE status = 1
                  ORDER BY id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "SELECT category_image FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($category && !empty($category['category_image']) && file_exists($category['category_image'])) {
            unlink($category['category_image']);  
        }
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();  
    }
    
}
?>


