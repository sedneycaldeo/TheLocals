<?php
session_start();
include 'conn.php';

// Only logged-in riders
if(!isset($_SESSION['rider_id'])){
    header("Location: rider_login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];

if(!isset($_GET['order_type']) || !isset($_GET['id'])){
    echo "<p>Invalid request.</p>";
    exit;
}

$order_type = $_GET['order_type']; // 'order' or 'preorder'
$id = intval($_GET['id']);
$table = ($order_type==='order') ? 'orders' : 'preorders';
$id_column = ($order_type==='order') ? 'order_id' : 'preorder_id';

// Assign rider and mark as On Delivery
$stmt = $conn->prepare("UPDATE $table SET rider_id=?, status='On Delivery' WHERE $id_column=? AND (rider_id IS NULL OR rider_id=0)");
$stmt->bind_param("ii", $rider_id, $id);
$stmt->execute();

// Fetch details for display
$result = $conn->query("SELECT * FROM $table WHERE $id_column=$id");
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Delivery in Progress</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;}
.container { max-width:600px; margin:auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2 { text-align:center; margin-bottom:20px;}
a.button { display:block; text-align:center; padding:10px; background:#28a745; color:#fff; text-decoration:none; border-radius:5px; font-weight:bold; margin-top:20px;}
</style>
</head>
<body>
<div class="container">
<h2>Delivery Accepted</h2>

<p><strong><?php echo ucfirst($order_type); ?> ID:</strong> <?php echo $data[$id_column]; ?></p>
<p><strong>Customer:</strong> <?php echo htmlspecialchars($data['fullname']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($data['phone']); ?></p>
<p><strong>Status:</strong> <?php echo htmlspecialchars($data['status']); ?></p>

<!-- Mark as Delivered -->
<a class="button" href="mark_delivered.php?order_type=<?php echo $order_type; ?>&id=<?php echo $id; ?>">Mark as Delivered</a>
</div>
</body>
</html>
