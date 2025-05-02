<?php
include '../../connection.php';  // Include your DB connection

// Query to fetch only paid orders
$sql = "SELECT * FROM orders WHERE order_status = 'paid'";
$result = $conn->query($sql);

// Array to hold the order data
$orders = [];

// Fetch all rows as associative arrays
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

// Return orders as JSON
echo json_encode($orders);
