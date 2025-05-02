<?php
include '../../connection.php'; // Change this to your actual DB connection file

// Check if the action is checkout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'checkout') {
    // Update the order status to 'paid' for all items belonging to the current order
    // Assuming the order_id is passed or identified by a user/cart session

    // For example, we will update all orders marked as pending
    $sql = "UPDATE orders SET order_status = 'paid' WHERE order_status = 'pending'";  // Update this condition as needed (e.g., by user or order_id)

    if ($conn->query($sql) === TRUE) {
        // Successfully updated, respond with success
        echo "Checkout Successful!";
    } else {
        // Error while updating
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
