<?php
include '../../connection.php'; // Change this to your actual DB connection file

// Get JSON data from the frontend
$data = json_decode(file_get_contents("php://input"), true);

// Check if data is received
if ($data) {
    $product_name = $data['product_name'];
    $size = $data['size'];
    $price = $data['price'];
    $quantity = $data['quantity'];

    // Check if there is an existing order (you might want to track this for a specific user)
    // If no order exists, create a new order with 'pending' status
    $sql = "INSERT INTO orders (total_amount, order_status) VALUES (0, 'pending')";
    $conn->query($sql);
    $order_id = $conn->insert_id; // Get the newly created order ID

    // Calculate the total amount for the order (adjust based on actual cart data)
    $total_amount = $price * $quantity;

    // Update the order's total amount
    $update_order_sql = "UPDATE orders SET total_amount = total_amount + ? WHERE order_id = ?";
    $stmt = $conn->prepare($update_order_sql);
    $stmt->bind_param("di", $total_amount, $order_id);
    $stmt->execute();

    // Prepare SQL query to insert cart item
    $stmt = $conn->prepare("INSERT INTO cart_items (order_id, product_name, size, price, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issdi", $order_id, $product_name, $size, $price, $quantity);

    // Execute the query and check for success
    if ($stmt->execute()) {
        echo "Added to cart"; // Return success message
    } else {
        echo "Error: " . $conn->error; // Return error message
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid data"; // If data is not valid
}
