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
            echo"<p>Booking canceled successfully</p>";
            echo '<button><a id="popup" href="activity.php">OK</a></button>'; 
        } else {
            echo"<p>Error canceling booking.</p>";
            echo '<button><a id="popup" href="activity.php">OK</a></button>'; 
        }

        $delete_booking_stmt->close();
    } else {
        echo"<p>Error updating available seats.</p>";
        echo '<button><a id="popup" href="activity.php">OK</a></button>'; 
    }

    $update_stmt->close();
} else {
    echo"<p>Unauthorized access or booking not found.</p>";
    echo '<button><a id="popup" href="activity.php">OK</a></button>'; 
}

$conn->close();
?>
