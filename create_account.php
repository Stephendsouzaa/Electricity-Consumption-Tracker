<?php
// Start session
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else
echo"pass";

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch username and password from POST request
    $username = $_POST['new-username'];
    $password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    // Check if password and confirm password match
    if ($password != $confirm_password) {
        die("Password and confirm password do not match");
    }

    // Check if the username already exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        die("Username already exists");
    }

    // Hash the password for storage
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    // Execute the statement
    if ($stmt->execute() === TRUE) {
        echo "New account created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>
