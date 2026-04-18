<?php
$host = "localhost";
$user = "u507727118_usr_PxmXNBGW";
$pass = ">|GC0C|:b8U";
$db   = "u507727118_db_PxmXNBGW";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>