<?php
require_once 'Database.php';

class Slideshow{
    private $conn;
    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }
    public function getSlideshow(){
        $query = "SELECT * FROM slideshow"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAll($offset, $limit) {
        $query = "SELECT slideshow.*, categories.category_name
                  FROM slideshow
                  JOIN categories ON slideshow.category_id = categories.id
                  ORDER BY slideshow.id ASC
                  LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getSlideshowByID($slideshow_id) {
        $sql = "SELECT * FROM slideshow WHERE id = :slideshow_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':slideshow_id', $slideshow_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }
    public function countAll() {
        $query = "SELECT COUNT(*) as total FROM slideshow";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
    public function create($title, $image_path, $caption, $description, $link, $status, $category_id) {
        $query = "INSERT INTO slideshow (title, image, caption, description, link, status, category_id)
                  VALUES (:title, :image, :caption, :description, :link, , :status, :category_id)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":title", $title);
        $stmt->bindParam(":image", $image_path);
        $stmt->bindParam(":caption", $caption);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":link", $link);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":category_id", $category_id);
        return $stmt;
    }
    public function update($title, $image_path, $caption, $description, $link, $status, $category_id, $slideshow_id){
        $query = "UPDATE slideshow SET title = :title, image = :image, caption = :caption, description = :description, link = :link, status = :status, category_id = :category_id WHERE id = :slideshow_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':image', $image_path);
            $stmt->bindParam(':caption', $caption);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':link', $link);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
            $stmt->bindParam(':slideshow_id', $slideshow_id, PDO::PARAM_INT);
            return $stmt;
    }
    public function read() {
        $query = "SELECT slideshow.*, categories.category_name
                  FROM slideshow
                  JOIN categories ON slideshow.category_id = categories.id
                  WHERE slideshow.status = 1
                  ORDER BY slideshow.id ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function delete($id) {
        $query = "DELETE FROM slideshow WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>