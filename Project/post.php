<?php
session_start();
include('db.php');

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php"); // Redirect to admin login
}

$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<div>
            <h2>" . $row['title'] . "</h2>
            <p>" . $row['description'] . "</p>
            <p>Visibility: " . $row['visibility'] . "</p>
            <a href='edit_post.php?id=" . $row['id'] . "'>Edit</a>
            <a href='delete_post.php?id=" . $row['id'] . "'>Delete</a>
          </div>";
}
?>
