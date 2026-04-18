<?php
session_start();
include 'conn.php';

// Protect the page – only logged-in riders can access
if(!isset($_SESSION['rider_id'])){
    header("Location: rider_login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];
$rider_name = $_SESSION['rider_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rider Dashboard | The Fry Project</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:0; }
.container { max-width:800px; margin:50px auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); text-align:center; }
h1 { margin-bottom:30px; }
a.button { display:inline-block; margin:10px; padding:12px 20px; background:#28a745; color:#fff; border-radius:6px; text-decoration:none; font-weight:bold; }
a.button:hover { background:#218838; }
</style>
</head>
<body>

<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($rider_name); ?> 👋</h1>

    <!-- Available deliveries -->
    <a class="button" href="available_riders.php">Available Deliveries</a>

    <!-- Ongoing deliveries -->
    <a class="button" href="rider_ongoing.php">Ongoing Deliveries</a>

    <!-- Delivery history -->
    <a class="button" href="rider_history.php">Delivery History</a>

    <!-- Logout -->
    <a class="button" href="rider_logout.php" style="background:#dc3545;">Logout</a>
</div>

</body>
</html>
