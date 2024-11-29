<?php
session_start();
require '../signin&signout/config.php'; // Include your database connection

// Clear the session
session_unset();
session_destroy();

// Delete the remember token cookie
setcookie('remember_token', '', time() - 3600, "/");

// Redirect to login page
header("Location: ../signin&signout/LoginPage.php");
exit();
?>
