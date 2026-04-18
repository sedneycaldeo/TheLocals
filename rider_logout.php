<?php
session_start();

// Unset all session variables related to the rider
unset($_SESSION['rider_id']);
unset($_SESSION['rider_name']);

// Destroy the session
session_destroy();

// Redirect to rider login page
header("Location: rider_login.php");
exit();
