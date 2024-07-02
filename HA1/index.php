<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to HitchApp</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to HitchApp</h1>
        <?php if (isset($_SESSION['username'])): ?>
            <p>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <p><a href="publish_ride.php">Publish a Ride</a></p>
            <p><a href="search_rides.php">Search for Rides</a></p>
            <p><a href="activity.php">My Activity</a></p>
            <p><a href="logout.php">Logout</a></p>
        <?php else: ?>
            <p><a href="login.php">Login</a></p>
            <p><a href="register.php">Register</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
