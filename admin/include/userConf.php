<?php
require_once 'Database.php';
class User {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Check if user exists by email
    public function getUsersByEmail($email) {
        $query = "SELECT * FROM `users` WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // returns an array of users with the given email
    }

    // Register a new user
    public function registerUser($username, $email, $password) {
        $query = "INSERT INTO `users` (username, email, password, created_at) 
                    VALUES (:username, :email, :password, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        return $stmt->execute();
    }

    // Update last login time
    public function updateLastLogin($userId) {
        $query = "UPDATE `users` SET last_login_at = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    // Fetch total number of users
    public function getTotalUsers() {
        $query = "SELECT COUNT(*) FROM `users`";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    // Fetch users for pagination
    public function getUsers($start_from, $results_per_page) {
        $query = "SELECT 
                      id, 
                      username, 
                      email, 
                      DATE_FORMAT(created_at, '%e %b %Y') AS formatted_created_at, 
                      DATE_FORMAT(last_login_at, '%e %b %Y') AS formatted_last_login_at
                  FROM `users` 
                  LIMIT :start_from, :results_per_page";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
        $stmt->bindParam(':results_per_page', $results_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Delete user by ID
    public function deleteUser($id) {
        $query = "DELETE FROM `users` WHERE `id` = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
?>