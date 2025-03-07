
<?php
session_start(); // Start the session
include_once 'admin/include/orderConf.php';
$Orders = new Orders();
// Check if the necessary POST data is available
if (isset($_POST['orderID']) && isset($_POST['total']) && isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['shippingAddress'])) {

    // Get the parameters from the POST data
    $order_id = $_POST['orderID'];
    $total = $_POST['total'];
    $first_name = $_POST['firstName'];
    $last_name = $_POST['lastName'];
    $shipping_address = $_POST['shippingAddress'];
    
    // Check if user is logged in
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id']; // Get the logged-in user's ID

        // Generate a unique order number (you can customize this as needed)
        $order_number = "#" . str_pad($order_id, 6, '1', STR_PAD_LEFT); // Example order number like #000198

        $success = $Orders->addOrder($order_number,$user_id, $first_name, $last_name, $shipping_address, $total);
        if ($success) {
            // If the order is successfully inserted, display success message
            // echo "Order successfully saved to the database!";
            header("Location: index.php?p=checkout");
            exit();
        } else {
            // If there is an issue with the query, display an error message
            echo "Failed to save the order. Please try again.";
        }
    } else {
        echo "User not logged in.";
    }

    // Close the database connection
    // $conn->close();
} else {
    echo "Missing order details.";
}
?>
