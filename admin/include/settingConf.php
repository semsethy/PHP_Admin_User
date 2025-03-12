<?php
// Include database connection (adjust the path if needed)
require_once 'Database.php';

class Setting {
    private $conn;

    // Constructor to initialize the database connection
    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // Method to retrieve the settings from the database
    public function getSettings() {
        try {
            // Query to select the settings (assuming there is only one row for settings)
            $query = "SELECT * FROM settings WHERE id = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle the error gracefully
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    // Method to save or update the settings in the database
    public function saveSettings($title, $icon, $email, $phone_number, $facebook_link, $instagram_link, $twitter_link, $logo) {
        try {
            // Check if settings already exist
            $query = "SELECT * FROM settings WHERE id = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            // If settings exist, update the record
            if ($stmt->rowCount() > 0) {
                $query = "UPDATE settings 
                          SET title = :title, email = :email, phone_number = :phone_number, 
                              facebook_link = :facebook_link, instagram_link = :instagram_link, 
                              twitter_link = :twitter_link, logo = :logo, icon = :icon
                          WHERE id = 1";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone_number', $phone_number);
                $stmt->bindParam(':facebook_link', $facebook_link);
                $stmt->bindParam(':instagram_link', $instagram_link);
                $stmt->bindParam(':twitter_link', $twitter_link);
                $stmt->bindParam(':logo', $logo);
                $stmt->bindParam(':icon', $icon);
            } else {
                // If no settings exist, insert a new row
                $query = "INSERT INTO settings (title, email, phone_number, facebook_link, instagram_link, twitter_link, logo, icon) 
                          VALUES (:title, :email, :phone_number, :facebook_link, :instagram_link, :twitter_link, :logo, :icon)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':phone_number', $phone_number);
                $stmt->bindParam(':facebook_link', $facebook_link);
                $stmt->bindParam(':instagram_link', $instagram_link);
                $stmt->bindParam(':twitter_link', $twitter_link);
                $stmt->bindParam(':logo', $logo);
                $stmt->bindParam(':icon', $icon);
            }

            // Execute the statement and return the result
            return $stmt;
        } catch (PDOException $e) {
            // Handle the error gracefully
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}
?>
