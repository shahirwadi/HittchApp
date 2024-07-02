<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'] ?? null;
$ride_id = $_POST['ride_id'] ?? null;

if (!$user_id || !$ride_id) {
    header("Location: login.php");
    exit;
}

// Check if the ride belongs to the user
$sql = "SELECT * FROM rides WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $ride_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Delete the ride
    $delete_sql = "DELETE FROM rides WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $ride_id);

    if ($delete_stmt->execute()) {
        echo "Ride deleted successfully.";
    } else {
        echo "Error deleting ride.";
    }

    $delete_stmt->close();
} else {
    echo "Unauthorized access or ride not found.";
}

$conn->close();
?>
