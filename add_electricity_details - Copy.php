<?php
// Start the session
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_username'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Details</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Welcome Admin</h1>
        <div id="admin-content">
            <div id="electricity-details">
                <h2>Electricity Details</h2>
                <form method="post" action="form_handler.php">
                    <label for="consumer-id">Consumer ID:</label>
                    <input type="text" id="consumer-id" name="consumer-id" required>
                    <label for="place">Place:</label>
                    <input type="text" id="place" name="place" required>
                    <label for="consumption-date">Date:</label>
                    <input type="date" id="consumption-date" name="consumption-date" required>
                    <label for="consumption-amount">Amount (kWh):</label>
                    <input type="number" id="consumption-amount" name="consumption-amount" step="0.01" required>

                    <button type="submit">Add Details</button>
                    <button type="button" onclick="clearForm()">Clear</button>
                </form>
            </div>
        </div>
    </header>
</body>
</html>
