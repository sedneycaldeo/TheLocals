<?php
session_start();
include 'conn.php';

// Only logged-in riders can access
if(!isset($_SESSION['rider_id'])){
    header("Location: rider_login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];
$rider_name = $_SESSION['rider_name'];

// Fetch delivered Orders
$delivered_orders = $conn->query("SELECT * FROM orders WHERE rider_id=$rider_id AND status='Delivered' ORDER BY order_date DESC");

// Fetch delivered Preorders
$delivered_preorders = $conn->query("SELECT * FROM preorders WHERE rider_id=$rider_id AND status='Delivered' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rider Delivery History</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px; }
.container { max-width:1200px; margin:auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
h2 { text-align:center; margin-bottom:20px; }
a.logout, a.home { padding:8px 15px; text-decoration:none; border-radius:6px; color:#fff; font-weight:bold; }
a.logout { background:#dc3545; float:right; }
a.logout:hover { background:#c82333; }
a.home { background:#17a2b8; float:left; }
a.home:hover { background:#138496; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { border:1px solid #ccc; padding:10px; text-align:center; vertical-align:top; }
th { background:#F4C430; }
</style>
</head>
<body>
<div class="container">
<h2>Rider Delivery History</h2>

<!-- Navigation buttons -->
<div style="overflow:auto; margin-bottom:20px;">
    <a href="riders.php" class="home">Home</a>
    <a href="rider_logout.php" class="logout">Logout</a>
</div>

<!-- Delivered Orders -->
<h3>Delivered Orders</h3>
<?php if($delivered_orders->num_rows > 0): ?>
<table>
<thead>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Address</th>
<th>Total</th>
<th>Order Date</th>
</tr>
</thead>
<tbody>
<?php while($order = $delivered_orders->fetch_assoc()): ?>
<tr>
<td><?php echo $order['order_id']; ?></td>
<td><?php echo htmlspecialchars($order['fullname']); ?></td>
<td><?php echo htmlspecialchars($order['phone']); ?></td>
<td><?php echo htmlspecialchars($order['address']); ?>, <?php echo htmlspecialchars($order['city']); ?></td>
<td>₱<?php echo number_format($order['total'],2); ?></td>
<td><?php echo htmlspecialchars($order['order_date']); ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>No delivered orders yet.</p>
<?php endif; ?>

<!-- Delivered Preorders -->
<h3>Delivered Preorders</h3>
<?php if($delivered_preorders->num_rows > 0): ?>
<table>
<thead>
<tr>
<th>Preorder ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Category</th>
<th>Total</th>
<th>Order Date</th>
</tr>
</thead>
<tbody>
<?php while($preorder = $delivered_preorders->fetch_assoc()): ?>
<tr>
<td><?php echo $preorder['preorder_id']; ?></td>
<td><?php echo htmlspecialchars($preorder['fullname']); ?></td>
<td><?php echo htmlspecialchars($preorder['phone']); ?></td>
<td><?php echo htmlspecialchars($preorder['category']); ?></td>
<td>₱<?php echo number_format($preorder['total'],2); ?></td>
<td><?php echo htmlspecialchars($preorder['created_at']); ?></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>No delivered preorders yet.</p>
<?php endif; ?>

</div>
</body>
</html>
