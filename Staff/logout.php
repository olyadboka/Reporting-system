<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to the login page
header("Location: stafflogin.php");
exit();
?>