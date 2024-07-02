<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
$ride_id = $_POST['ride_id'] ?? null;

if (!$user_id || !$ride_id) {
    header("Location: login.php");
    exit;
}

// Check if the user has already booked this ride
$check_booking_sql = "SELECT * FROM bookings WHERE ride_id = ? AND user_id = ?";
$check_booking_stmt = $conn->prepare($check_booking_sql);
$check_booking_stmt->bind_param("ii", $ride_id, $user_id);
$check_booking_stmt->execute();
$check_booking_result = $check_booking_stmt->get_result();

if ($check_booking_result->num_rows > 0) {
    echo "You have already booked this ride.";
} else {
    // Attempt to book the ride: Decrease available seats
    $conn->autocommit(FALSE);

    // Decrease available seats
    $update_sql = "UPDATE rides SET available_seats = available_seats - 1 WHERE id = ? AND available_seats > 0";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $ride_id);

    if ($update_stmt->execute() && $update_stmt->affected_rows > 0) {
        // Book the ride
        $book_ride_sql = "INSERT INTO bookings (ride_id, user_id) VALUES (?, ?)";
        $book_ride_stmt = $conn->prepare($book_ride_sql);
        $book_ride_stmt->bind_param("ii", $ride_id, $user_id);

        if ($book_ride_stmt->execute()) {
            echo "Ride booked successfully.";
            $conn->commit();
        } else {
            echo "Error booking ride.";
            $conn->rollback();
        }

        $book_ride_stmt->close();
    } else {
        echo "No available seats left.";
    }

    $update_stmt->close();
}

$conn->autocommit(TRUE);
$conn->close();
?>
