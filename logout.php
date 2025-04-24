<?php
session_start();
require_once('db.php');

// Destroy session and clear session variables
session_unset();
session_destroy();

// Redirect to login page with logout message flag
header("Location: login.php?logout=1");
exit();
?>
