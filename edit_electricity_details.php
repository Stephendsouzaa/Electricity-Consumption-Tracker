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

// Check if ID parameter is set in the URL
if (!isset($_GET['id'])) {
    // Redirect to the electricity details list page if ID is not provided
    header("Location: electricity_details_list.php");
    exit;
}

$id = $_GET['id'];

// Retrieve electricity details from the database based on ID
$sql = "SELECT * FROM electricity_details WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Redirect to the electricity details list page if no record found with the provided ID
    header("Location: electricity_details_list.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $consumer_id = $_POST['consumer-id'];
    $place = $_POST['place'];
    $consumption_date = $_POST['consumption-date'];
    $consumption_amount = $_POST['consumption-amount'];

    // Update data in the database
    $sql = "UPDATE electricity_details 
            SET consumer_id = '$consumer_id', place = '$place', consumption_date = '$consumption_date', consumption_amount = '$consumption_amount' 
            WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the electricity details list page after successful update
        header("Location: electricity_details_list.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
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
        <h1>Welcome Admin</h1>
        <div id="admin-content">
            <div id="edit-electricity-details">
                <h2>Edit Electricity Details</h2>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $id; ?>">
                    <label for="consumer-id">Consumer ID:</label>
                    <input type="text" id="consumer-id" name="consumer-id" value="<?php echo $row['consumer_id']; ?>" required>
                    <label for="place">Place:</label>
                    <input type="text" id="place" name="place" value="<?php echo $row['place']; ?>" required>
                    <label for="consumption-date">Date:</label>
                    <input type="date" id="consumption-date" name="consumption-date" value="<?php echo $row['consumption_date']; ?>" required>
                    <label for="consumption-amount">Amount (kWh):</label>
                    <input type="number" id="consumption-amount" name="consumption-amount" step="0.01" value="<?php echo $row['consumption_amount']; ?>" required>

                    <button type="submit">Update Details</button>
                    <button type="button" onclick="location.href='electricity_details_list.php'">Cancel</button>
                </form>
            </div>
        </div>
    </header>
</body>
</html>
