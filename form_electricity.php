<?php
// Create connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the electricity details form was submitted
    if (isset($_POST['consumer-id']) && isset($_POST['place']) && isset($_POST['consumption-date']) && isset($_POST['consumption-amount'])) {
        // Extract data from the form
        $consumerId = $_POST['consumer-id'];
        $place = $_POST['place'];
        $consumptionDate = $_POST['consumption-date'];
        $consumptionAmount = $_POST['consumption-amount'];

        // Extract admin username from session
        session_start();
        $adminUsername = $_SESSION['admin_username'];

        // Prepare SQL statement to insert data into electricity_details table
        $sql = "INSERT INTO electricity_details (consumer_id, place, consumption_date, consumption_amount, admin_username) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Bind parameters and execute the statement
        $stmt->bind_param("sssss", $consumerId, $place, $consumptionDate, $consumptionAmount, $adminUsername);
        
        if ($stmt->execute()) {
            // Insert successful
            echo "Electricity details added successfully!";
        } else {
            // Insert failed
            echo "Error adding electricity details: " . $stmt->error;
        }
    } else {
        // Invalid form submission
        echo "Error: Invalid form submission.";
    }
} else {
    // Form not submitted
    echo "Error: Form not submitted.";
}

// Close database connection
$conn->close();
?>
