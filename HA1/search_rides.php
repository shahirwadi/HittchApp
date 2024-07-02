<?php
session_start();
require 'config.php';

$search_performed = false;

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['start_location']) && isset($_GET['end_location'])) {
    $start_location = $_GET['start_location'];
    $end_location = $_GET['end_location'];
    $sort_by = $_GET['sort_by'] ?? 'date_time';

    $sql = "SELECT r.*, u.username AS driver_username 
            FROM rides r 
            INNER JOIN users u ON r.user_id = u.id 
            WHERE r.start_location LIKE ? 
            AND r.end_location LIKE ? 
            ORDER BY $sort_by";
    $stmt = $conn->prepare($sql);
    $start_location_param = "%{$start_location}%";
    $end_location_param = "%{$end_location}%";
    $stmt->bind_param("ss", $start_location_param, $end_location_param);
    $stmt->execute();
    $result = $stmt->get_result();

    $search_performed = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Rides - HitchApp</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Search for Rides</h1>
        <form method="get" action="search_rides.php">
            Start Location: <input type="text" name="start_location" required><br>
            End Location: <input type="text" name="end_location" required><br>
            Sort by:
            <select name="sort_by">
                <option value="date_time">Date and Time</option>
                <option value="available_seats">Available Seats</option>
            </select>
            <input type="submit" value="Search">
        </form>

        <?php if ($search_performed): ?>
            <h2>Search Results</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($ride = $result->fetch_assoc()): ?>
                    <div class="ride">
                        <p>Name: <?php echo htmlspecialchars($ride['name']); ?></p>
                        <p>Start Location: <?php echo htmlspecialchars($ride['start_location']); ?></p>
                        <p>End Location: <?php echo htmlspecialchars($ride['end_location']); ?></p>
                        <p>Date and Time: <?php echo htmlspecialchars($ride['date_time']); ?></p>
                        <p>Available Seats: <?php echo htmlspecialchars($ride['available_seats']); ?></p>
                        <p>Driver: <?php echo htmlspecialchars($ride['driver_username']); ?></p>
                        <?php if (isset($_SESSION['username']) && $_SESSION['username'] !== $ride['driver_username']): ?>
                            <form method="post" action="book_ride.php">
                                <input type="hidden" name="ride_id" value="<?php echo htmlspecialchars($ride['id']); ?>">
                                <input type="submit" value="Book Ride">
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No rides found.</p>
            <?php endif; ?>
        <?php endif; ?>

        <p><a href="index.php">Back to Home</a></p>
    </div>
</body>
</html>
