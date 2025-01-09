<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_picture = ''; // Handle file upload separately
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $cover_photo = ''; // Handle file upload separately

    // Form validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
    } else {
        $sql = "INSERT INTO users (username, email, password, profile_picture, description, cover_photo) 
                VALUES ('$username', '$email', '$password', '$profile_picture', '$description', '$cover_photo')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful.";
            header("Location: login.php"); // Redirect to login page
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!-- HTML form -->
<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Register</button>
</form>
