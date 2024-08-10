<?php
// Check if GD extension is enabled
if (!function_exists('imagecreatetruecolor')) {
    die('GD extension is not enabled.');
}

// Establish database connection
$conn = new mysqli('localhost', 'root', '', 'admin_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if(isset($_POST['consumer_id'])) {
    $consumer_id = $_POST['consumer_id'];

    // Fetch data from database
    $sql = "SELECT prev_month_reading, current_month_reading FROM electricity_details WHERE consumer_id = $consumer_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $prev_month_reading = $row['prev_month_reading'];
        $current_month_reading = $row['current_month_reading'];

        // Generate graph
        $width = 400;
        $height = 200;
        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $blue = imagecolorallocate($image, 0, 0, 255);

        // Previous month reading
        $prev_x = 50;
        $prev_y = $height - $prev_month_reading * 2;
        imagefilledrectangle($image, $prev_x, $prev_y, $prev_x + 50, $height, $blue);
        imagestring($image, 3, $prev_x, $prev_y - 20, $prev_month_reading, $blue);

        // Current month reading
        $current_x = 200;
        $current_y = $height - $current_month_reading * 2;
        imagefilledrectangle($image, $current_x, $current_y, $current_x + 50, $height, $blue);
        imagestring($image, 3, $current_x, $current_y - 20, $current_month_reading, $blue);

        header('Content-type: image/png');
        imagepng($image);
        imagedestroy($image);
    } else {
        echo "No data found for the given consumer ID.";
    }
}
?>
