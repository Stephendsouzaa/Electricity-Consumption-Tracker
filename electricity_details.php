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
    
    // Check if consumer ID already exists in database
    $sql_check = "SELECT * FROM electricity_details WHERE consumer_id = '$consumer_id'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        // If consumer ID exists, update current month reading and set previous month reading to current month reading
        $row_check = $result_check->fetch_assoc();
        $prev_month_reading = $row_check['consumption_amount'];
        $current_month_reading = $amount;
        $amount_consumed_current_month = $current_month_reading - $prev_month_reading;
        $total_amount = $row_check['consumption_amount'] + $amount;
        $sql = "UPDATE electricity_details SET consumption_amount = '$total_amount', last_consumption_date = '$date', latest_updated_date = NOW(), prev_month_reading = '$prev_month_reading', current_month_reading = '$current_month_reading', amount_consumed_current_month = '$amount_consumed_current_month' WHERE consumer_id = '$consumer_id'";
    } else {
        // If consumer ID doesn't exist, insert new record with current month reading and previous month reading as 0
        $prev_month_reading = 0;
        $current_month_reading = $amount;
        $amount_consumed_current_month = $current_month_reading - $prev_month_reading;
        $sql = "INSERT INTO electricity_details (consumer_id, place, consumption_date, consumption_amount, last_consumption_date, latest_updated_date, prev_month_reading, current_month_reading, amount_consumed_current_month) VALUES ('$consumer_id', '$place', '$date', '$amount', '$date', NOW(), '$prev_month_reading', '$current_month_reading', '$amount_consumed_current_month')";
    }
    
    if ($conn->query($sql) === TRUE) {
        // Data inserted or updated successfully
        // You can redirect or show a success message here if needed
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch aggregated data from database
$sql = "SELECT * FROM electricity_details";
$result = $conn->query($sql);
$rows = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electricity Details</title>
    <link rel="stylesheet" href="ele.css">
    
</head>
<body>
    <header>
        <h1>Welcome Admin</h1>
        <div id="admin-content">
            <div id="electricity-details">
                <h2>Electricity Details</h2>
                <!-- Display form -->
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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
                
                <!-- Display data from database -->
                <?php if (!empty($rows)): ?>
                <h3>Entered Details:</h3>
                <table>
                    <tr>
                        <th>Consumer ID</th>
                        <th>Place</th>
                        <th>Last Consumption Date</th>
                        <th>Latest Updated Date</th>
                        <th>Total Amount (kWh)</th>
                        <th>Previous Month Reading (kWh)</th>
                        <th>Current Month Reading (kWh)</th>
                        <th>Amount Consumed This Month (kWh)</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?php echo $row['consumer_id']; ?></td>
                        <td><?php echo $row['place']; ?></td>
                        <td><?php echo $row['last_consumption_date']; ?></td>
                        <td><?php echo $row['latest_updated_date']; ?></td>
                        <td><?php echo $row['consumption_amount']; ?></td>
                        <td><?php echo $row['prev_month_reading']; ?></td>
                        <td><?php echo $row['current_month_reading']; ?></td>
                        <td><?php echo $row['amount_consumed_current_month']; ?></td>
                        <td><a href="edit.php?id=<?php echo $row['consumer_id']; ?>">Edit</a></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php else: ?>
                <p>No data available.</p>
                <?php endif; ?>
            </div>
        </div>
    </header>
</body>
</html>
