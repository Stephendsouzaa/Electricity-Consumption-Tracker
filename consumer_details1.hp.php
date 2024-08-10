<?php
$conn = new mysqli('localhost', 'root', '', 'admin_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$consumptionId = $_GET['id'];

$sql = "SELECT * FROM electricity_details WHERE consumer_id = '$consumptionId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch and encode consumer details as JSON
    $consumer = $result->fetch_assoc();
    echo json_encode($consumer);
} else {
    echo json_encode(array("error" => "Consumer not found"));
}

$conn->close();
?>
