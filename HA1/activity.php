<?php
session_start();
require 'config.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header("Location: login.php");
    exit;
}

// Function to fetch user's published rides
function getPublishedRides($conn, $user_id) {
    $sql = "SELECT * FROM rides WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

// Function to fetch user's booked rides
function getBookedRides($conn, $user_id) {
    $sql = "SELECT r.* FROM rides r INNER JOIN bookings b ON r.id = b.ride_id WHERE b.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

$published_rides = getPublishedRides($conn, $user_id);
$booked_rides = getBookedRides($conn, $user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Activity - HitchApp</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>My Activity</h1>

        <!-- Published Rides -->
        <h2>Published Rides</h2>
        <div class="rides">
            <?php if ($published_rides->num_rows > 0): ?>
                <?php while ($ride = $published_rides->fetch_assoc()): ?>
                    <div class="ride">
                        <p>Name: <?php echo htmlspecialchars($ride['name']); ?></p>
                        <p>Start Location: <?php echo htmlspecialchars($ride['start_location']); ?></p>
                        <p>End Location: <?php echo htmlspecialchars($ride['end_location']); ?></p>
                        <p>Date and Time: <?php echo htmlspecialchars($ride['date_time']); ?></p>
                        <form method="post" action="delete_ride.php">
                            <input type="hidden" name="ride_id" value="<?php echo htmlspecialchars($ride['id']); ?>">
                            <input type="submit" value="Delete Ride">
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No published rides.</p>
            <?php endif; ?>
        </div>

        <!-- Booked Rides -->
        <h2>Booked Rides</h2>
        <div class="rides">
            <?php if ($booked_rides->num_rows > 0): ?>
                <?php while ($ride = $booked_rides->fetch_assoc()): ?>
                    <div class="ride">
                        <p>Name: <?php echo htmlspecialchars($ride['name']); ?></p>
                        <p>Start Location: <?php echo htmlspecialchars($ride['start_location']); ?></p>
                        <p>End Location: <?php echo htmlspecialchars($ride['end_location']); ?></p>
                        <p>Date and Time: <?php echo htmlspecialchars($ride['date_time']); ?></p>
                        <form method="post" action="cancel_booking.php">
                            <input type="hidden" name="ride_id" value="<?php echo htmlspecialchars($ride['id']); ?>">
                            <input type="submit" value="Cancel Booking">
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No booked rides.</p>
            <?php endif; ?>
        </div>

        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>
