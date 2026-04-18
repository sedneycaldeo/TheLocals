<?php
session_start();
include 'conn.php';

// Only logged-in riders
if(!isset($_SESSION['rider_id'])){
    header("Location: rider_login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];
$rider_name = $_SESSION['rider_name'];

// Fetch untaken orders
$orders = $conn->query("SELECT * FROM orders WHERE status='Pending' AND (rider_id IS NULL OR rider_id=0) ORDER BY order_date ASC");

// Fetch untaken preorders
$preorders = $conn->query("SELECT * FROM preorders WHERE status='Pending' AND (rider_id IS NULL OR rider_id=0) ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Available Deliveries | Rider Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px;}
.container { max-width:1200px; margin:auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2 { text-align:center; margin-bottom:20px;}
a.logout { float:right; background:#dc3545; color:#fff; padding:8px 15px; text-decoration:none; border-radius:6px;}
a.logout:hover { background:#c82333;}
table { width:100%; border-collapse: collapse; margin-top:20px;}
th, td { border:1px solid #ccc; padding:10px; text-align:center; vertical-align:top;}
th { background:#F4C430;}
a.button { text-decoration:none; padding:6px 12px; background:#28a745; color:white; border-radius:6px;}
a.button:hover { background:#218838;}
</style>
</head>
<body>
<div class="container">
<h2>Available Deliveries</h2>
<a href="rider_logout.php" class="logout">Logout</a>
<a href="riders.php" class="button">Home</a>

<!-- Orders -->
<h3>Orders</h3>
<?php if($orders->num_rows>0): ?>
<table>
<thead>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php while($order = $orders->fetch_assoc()): ?>
<tr>
<td><?php echo $order['order_id']; ?></td>
<td><?php echo htmlspecialchars($order['fullname']); ?></td>
<td><?php echo htmlspecialchars($order['phone']); ?></td>
<td>₱<?php echo number_format($order['total'],2); ?></td>
<td>
    <a class="button" href="rider_delivery.php?order_type=order&id=<?php echo $order['order_id']; ?>">Accept Delivery</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>No available orders at the moment.</p>
<?php endif; ?>

<!-- Preorders -->
<h3>Preorders</h3>
<?php if($preorders->num_rows>0): ?>
<table>
<thead>
<tr>
<th>Preorder ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Total</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php while($preorder = $preorders->fetch_assoc()): ?>
<tr>
<td><?php echo $preorder['preorder_id']; ?></td>
<td><?php echo htmlspecialchars($preorder['fullname']); ?></td>
<td><?php echo htmlspecialchars($preorder['phone']); ?></td>
<td>₱<?php echo number_format($preorder['total'],2); ?></td>
<td>
    <a class="button" href="rider_delivery.php?order_type=preorder&id=<?php echo $preorder['preorder_id']; ?>">Accept Delivery</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>No available preorders at the moment.</p>
<?php endif; ?>

</div>
</body>
</html>
