<?php
session_start(); // Start the session

if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to the homepage if already logged in
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Database connection
    $conn = new mysqli('localhost', 'your_db_username', 'your_db_password', 'blog');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to check user credentials
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];  // Store the user ID in the session
            $_SESSION['username'] = $user['username'];
            header("Location: index.php"); // Redirect to the homepage after login
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
</body>
</html>
