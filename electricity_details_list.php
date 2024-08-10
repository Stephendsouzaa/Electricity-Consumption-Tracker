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

// Retrieve electricity details from the database
$sql = "SELECT * FROM electricity_details";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Details List</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <header>
        <h1>Welcome Admin</h1>
        <div id="admin-content">
            <div id="electricity-details-list">
                <h2>Electricity Details List</h2>
                <table>
                    <tr>
                        <th>Consumer ID</th>
                        <th>Place</th>
                        <th>Date</th>
                        <th>Amount (kWh)</th>
                        <th>Actions</th>
                    </tr>
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["consumer_id"] . "</td>";
                            echo "<td>" . $row["place"] . "</td>";
                            echo "<td>" . $row["consumption_date"] . "</td>";
                            echo "<td>" . $row["consumption_amount"] . "</td>";
                            echo "<td><a href='edit_electricity_details.php?id=" . $row["id"] . "'>Edit</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No electricity details found.</td></tr>";
                    }
                    ?>
                </table>
                <button onclick="location.href='form_electricity.php'">Add Electricity Details</button>
            </div>
        </div>
    </header>
</body>
</html>
