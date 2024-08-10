<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the feedback message is set and not empty
    if (isset($_POST["feedbackMsg"]) && !empty($_POST["feedbackMsg"])) {
        // Get the feedback message from the form
        $feedbackMsg = $_POST["feedbackMsg"];
        
        // Connect to your MySQL database (replace placeholders with your actual database credentials)
        $conn = new mysqli('localhost', 'root', '', 'admin_db');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute SQL statement to insert feedback into the database
        $stmt = $conn->prepare("INSERT INTO feedback (message) VALUES (?)");
        $stmt->bind_param("s", $feedbackMsg);
        $stmt->execute();

        // Close statement and database connection
        $stmt->close();
        $conn->close();

        // Redirect back to the feedback form page after submission
        header("Location: index.html");
        exit();
    }
}
?>
