<?php
// Database credentials
$servername = "localhost";  // Replace with your MySQL server hostname or IP address
$username = "root";
$password = "";
$database = "hitchapp";  // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
