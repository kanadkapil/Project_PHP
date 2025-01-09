<?php
$servername = "localhost";  // Your server (usually localhost)
$username = "root";         // Database username (default is root)
$password = "";             // Database password (default is empty)
$dbname = "blog";           // The database you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
