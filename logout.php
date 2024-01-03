<?php
// Start or resume session
session_start();

// Destroy the session variables
session_unset();
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit();
?>
