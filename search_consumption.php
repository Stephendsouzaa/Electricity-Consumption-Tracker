<?php
$conn = new mysqli('localhost', 'root', '', 'admin_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$consumptionId = $_GET['id'];

$sql = "SELECT * FROM electricity_details WHERE consumer_id = '$consumptionId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<p><strong>Consumer ID:</strong> " . $row["consumer_id"]. "</p>";
        echo "<p><strong>Consumption Amount:</strong> " . $row["consumption_amount"]. "</p>";
        echo "<p><strong>Admin Username:</strong> " . $row["admin_username"]. "</p>";
        echo "<p><strong>Updated At:</strong> " . $row["updated_at"]. "</p>";
        echo "<p><strong>Place:</strong> " . $row["place"]. "</p>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
