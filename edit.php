<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input data
function sanitizeData($data) {
    // Remove whitespace from the beginning and end of string
    $data = trim($data);
    // Convert special characters to HTML entities
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form data
    $consumer_id = sanitizeData($_POST['consumer-id']);
    $place = sanitizeData($_POST['place']);
    $date = sanitizeData($_POST['consumption-date']);
    $amount = sanitizeData($_POST['consumption-amount']);
    
    // Update data in database
    $sql = "UPDATE electricity_details SET place='$place', consumption_date='$date', consumption_amount='$amount' WHERE consumer_id='$consumer_id'";
    if ($conn->query($sql) === TRUE) {
        // Data updated successfully
        // Redirect to admin.php after updating
        header("Location: electricity_details.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Retrieve consumer ID from URL parameter
if (isset($_GET['id'])) {
    $consumer_id = sanitizeData($_GET['id']);
    
    // Fetch data for the selected consumer ID
    $sql = "SELECT * FROM electricity_details WHERE consumer_id='$consumer_id'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $place = $row['place'];
        $date = $row['consumption_date'];
        $amount = $row['consumption_amount'];
    } else {
        // Consumer ID not found
        echo "Consumer ID not found.";
        exit;
    }
} else {
    // No consumer ID provided
    echo "No consumer ID provided.";
    exit;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Electricity Details</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Edit Electricity Details</h1>
        <div id="admin-content">
            <div id="electricity-details">
                <!-- Display edit form -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo htmlspecialchars($_GET['id']); ?>">
                    <input type="hidden" name="consumer-id" value="<?php echo $consumer_id; ?>">
                    <label for="place">Place:</label>
                    <input type="text" id="place" name="place" value="<?php echo $place; ?>" required>
                    <label for="consumption-date">Date:</label>
                    <input type="date" id="consumption-date" name="consumption-date" value="<?php echo $date; ?>" required>
                    <label for="consumption-amount">Amount (kWh):</label>
                    <input type="number" id="consumption-amount" name="consumption-amount" step="0.01" value="<?php echo $amount; ?>" required>

                    <button type="submit">Update Details</button>
                    <a href="electricity_details.php">Cancel</a>
                </form>
            </div>
        </div>
    </header>
</body>
</html>
