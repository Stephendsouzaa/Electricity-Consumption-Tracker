<?php
$conn = new mysqli('localhost', 'root', '', 'admin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Simulating fetching consumer details based on ID
$consumerId = $_GET['id'];

// Fetch consumer details from the database
$sql = "SELECT `Previous Month Reading`, `Current Month Reading`, `Amount Consumed` FROM your_table WHERE consumer_id = '$consumerId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output consumer details as JSON
    $consumer = $result->fetch_assoc();
    echo json_encode($consumer);
} else {
    echo "Error: Consumer ID not found";
}

$conn->close();
?>
