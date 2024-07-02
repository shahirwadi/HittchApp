<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
$ride_id = $_POST['ride_id'] ?? null;

if (!$user_id || !$ride_id) {
    header("Location: login.php");
    exit;
}

// Check if the user has booked this ride
$sql = "SELECT * FROM bookings WHERE ride_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $ride_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Cancel booking: Increase available seats
    $update_sql = "UPDATE rides SET available_seats = available_seats + 1 WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $ride_id);

    if ($update_stmt->execute()) {
        // Delete booking record
        $delete_booking_sql = "DELETE FROM bookings WHERE ride_id = ? AND user_id = ?";
        $delete_booking_stmt = $conn->prepare($delete_booking_sql);
        $delete_booking_stmt->bind_param("ii", $ride_id, $user_id);

        if ($delete_booking_stmt->execute()) {
            echo "Booking canceled successfully.";
        } else {
            echo "Error canceling booking.";
        }

        $delete_booking_stmt->close();
    } else {
        echo "Error updating available seats.";
    }

    $update_stmt->close();
} else {
    echo "Unauthorized access or booking not found.";
}

$conn->close();
?>
