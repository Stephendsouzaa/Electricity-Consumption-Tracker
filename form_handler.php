<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which form was submitted
    if (isset($_POST['admin-username']) && isset($_POST['admin-password'])) {
        // This is the login form
        $username = $_POST['admin-username'];
        $password = $_POST['admin-password'];

        // Process login...
        // Example:
        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Start session
                session_start();
                // Store admin username in session for later use
                $_SESSION['admin_username'] = $username;
                // Redirect to the electricity details page
                header('Location: electricity_details.php');
                exit;
            } else {
                echo "fail"; // Login failed
            }
        } else {
            echo "fail"; // Login failed
        }
    } elseif (isset($_POST['new-username']) && isset($_POST['new-password']) && isset($_POST['confirm-password'])) {
        // This is the signup form
        $newUsername = $_POST['new-username'];
        $newPassword = $_POST['new-password'];
        $confirmPassword = $_POST['confirm-password'];

        if ($newPassword === $confirmPassword) {
            // Hash the password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Process signup...
            // Example:
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $newUsername, $hashedPassword);
            if ($stmt->execute()) {
                echo "pass"; // Signup successful
                // Redirect or set session, etc.
            } else {
                echo "fail"; // Signup failed
            }
        } else {
            echo "fail"; // Passwords do not match
        }
    } elseif (isset($_POST['consumer-id']) && isset($_POST['place']) && isset($_POST['consumption-date']) && isset($_POST['consumption-amount'])) {
        // This is the electricity details form
        $consumerId = $_POST['consumer-id'];
        $place = $_POST['place'];
        $consumptionDate = $_POST['consumption-date'];
        $consumptionAmount = $_POST['consumption-amount'];

        // Process electricity details...
        // Example:
        $sql = "INSERT INTO electricity_details (consumer_id, place, consumption_date, consumption_amount) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $consumerId, $place, $consumptionDate, $consumptionAmount);
        if ($stmt->execute()) {
            // Electricity details added successfully
            // Redirect or show success message, etc.
        } else {
            // Error adding electricity details
        }
    } else {
        // Form submission received, but no recognizable form fields found
        echo "Error: Invalid form submission.";
    }
} else {
    // Form not submitted
    echo "Error: Form not submitted.";
}

// Close database connection
$conn->close();
?>
