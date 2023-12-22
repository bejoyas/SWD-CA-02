<?php
$servername = "localhost";
$username = "swd";
$password = getenv('DB_PASSWORD');
$dbname = "swd";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
