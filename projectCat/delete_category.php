<?php
include('db.php');

//session
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // First, fetch the image filename to delete it from the uploads folder
    $sql = "SELECT image FROM category WHERE id = $id";
    $result = $conn->query($sql);
    $category = $result->fetch_assoc();

    // Delete category from the database
    $sql = "DELETE FROM category WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Delete the image file from the server
        unlink('uploads/' . $category['image']);
        
        // Redirect to index.php after deletion
        echo "<script>alert('Category deleted successfully!'); window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location.href = 'index.php';</script>";
    }
}
?>
