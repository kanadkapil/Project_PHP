<?php
session_start();  // Start the session

// Destroy all session data
session_unset();
session_destroy();

header("Location: login.php"); // Redirect to the login page after logging out
exit();
?>
