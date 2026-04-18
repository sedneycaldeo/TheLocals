<?php
session_start();
include 'conn.php';

if(!isset($_SESSION['rider_id'])){
    header("Location: rider_login.php");
    exit();
}

if(!isset($_GET['order_type']) || !isset($_GET['id'])){
    echo "<p>Invalid request.</p>";
    exit;
}

$order_type = $_GET['order_type'];
$id = intval($_GET['id']);
$table = ($order_type==='order') ? 'orders' : 'preorders';
$id_column = ($order_type==='order') ? 'order_id' : 'preorder_id';

// Update status to Delivered
$stmt = $conn->prepare("UPDATE $table SET status='Delivered' WHERE $id_column=? AND rider_id=?");
$stmt->bind_param("ii", $id, $_SESSION['rider_id']);
$stmt->execute();

$_SESSION['message'] = ucfirst($order_type)." #$id marked as Delivered.";
header("Location: available_riders.php");
exit();
