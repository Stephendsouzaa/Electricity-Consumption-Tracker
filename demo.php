<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'stephen');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connection successful"; // Output a message indicating successful connection
}
?>
