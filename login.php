<?php
// Start session
session_start();

// Check if user is already logged in, redirect to admin page if logged in
if (isset($_SESSION['admin-username'])) {
    header("Location: admin1.php");
    exit();
}
else
echo"pass";

// Database connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch username and password from POST request
    $username = $_POST['admin-username'];
    $password = $_POST['admin-password'];

    // Prepare SQL statement to fetch user data
    $stmt = $conn->prepare("SELECT username, password FROM users WHERE username = ?");
    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        // Check if username exists
        if ($stmt->num_rows == 1) {
            $stmt->bind_result($db_username, $db_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $db_password)) {
                // Password is correct, set session variable and redirect to admin page
                $_SESSION['admin-username'] = $db_username;
                header("Location: admin1.php");
                exit();
            } else {
                echo "Invalid password";
            }
        } else {
            echo "Username not found";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error in preparing SQL statement: " . $conn->error;
    }
}

// Close database connection
$conn->close();
?>
