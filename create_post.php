<?php
session_start();
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $image = ''; // Handle image upload
    $visibility = $_POST['visibility'];

    $sql = "INSERT INTO posts (user_id, title, description, category_id, image, visibility)
            VALUES ('$user_id', '$title', '$description', '$category', '$image', '$visibility')";

    if ($conn->query($sql) === TRUE) {
        echo "Post created successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!-- HTML form -->
<form method="POST">
    <input type="text" name="title" placeholder="Title" required>
    <textarea name="description" placeholder="Description" required></textarea>
    <select name="category" required>
        <option value="">Select Category</option>
        <!-- Dynamically load categories -->
    </select>
    <input type="file" name="image" required>
    <label for="visibility">Visibility</label>
    <select name="visibility">
        <option value="public">Public</option>
        <option value="private">Private</option>
    </select>
    <button type="submit">Create Post</button>
</form>
