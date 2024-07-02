<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $start_location = $_POST['start_location'];
    $end_location = $_POST['end_location'];
    $date_time = $_POST['date_time'];
    $available_seats = $_POST['available_seats'];

    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO rides (name, start_location, end_location, date_time, available_seats, user_id) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $name, $start_location, $end_location, $date_time, $available_seats, $user_id);

    if ($stmt->execute()) {
        echo "Ride published successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publish Ride - HitchApp</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Publish a Ride</h1>
        <form method="post" action="publish_ride.php">
            <label for="name">Name:</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="start_location">Start Location:</label><br>
            <input type="text" id="start_location" name="start_location" required><br>
            <label for="end_location">End Location:</label><br>
            <input type="text" id="end_location" name="end_location" required><br>
            <label for="date_time">Date and Time:</label><br>
            <input type="datetime-local" id="date_time" name="date_time" required><br>
            <label for="available_seats">Available Seats:</label><br>
            <input type="number" id="available_seats" name="available_seats" required><br>
            <input type="submit" value="Publish Ride">
        </form>
        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>
