<?php
$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = ''; // Modify this based on your database configuration

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
