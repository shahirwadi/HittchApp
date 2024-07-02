<?php
require 'config.php';

session_start();
if (!isset($_SESSION['id'])) {
    echo "You need to be logged in to publish a ride.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $start_location = $_POST['start_location'];
    $end_location = $_POST['end_location'];
    $date_time = $_POST['date_time'];

    $sql = "INSERT INTO rides (user_id, name, start_location, end_location, date_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $name, $start_location, $end_location, $date_time);

    if ($stmt->execute()) {
        echo "Ride published successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<form method="post" action="publish_ride.php">
    Name: <input type="text" name="name" required><br>
    Start Location: <input type="text" name="start_location" required><br>
    End Location: <input type="text" name="end_location" required><br>
    Date and Time: <input type="datetime-local" name="date_time" required><br>
    <input type="submit" value="Publish Ride">
</form>