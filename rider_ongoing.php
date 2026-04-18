<?php
session_start();
include 'conn.php';

// Protect page – only logged-in riders
if(!isset($_SESSION['rider_id'])){
    header("Location: rider_login.php");
    exit();
}

$rider_id = $_SESSION['rider_id'];
$rider_name = $_SESSION['rider_name'];

// ===== Handle "Mark Delivered" action =====
if(isset($_GET['deliver_order'])){
    $order_id = intval($_GET['deliver_order']);
    $stmt = $conn->prepare("UPDATE orders SET status='Delivered' WHERE order_id=? AND rider_id=?");
    $stmt->bind_param("ii", $order_id, $rider_id);
    $stmt->execute();
    $_SESSION['message'] = "Order #$order_id marked as Delivered.";
    header("Location: rider_ongoing.php");
    exit();
}

if(isset($_GET['deliver_preorder'])){
    $preorder_id = intval($_GET['deliver_preorder']);
    $stmt = $conn->prepare("UPDATE preorders SET status='Delivered' WHERE preorder_id=? AND rider_id=?");
    $stmt->bind_param("ii", $preorder_id, $rider_id);
    $stmt->execute();
    $_SESSION['message'] = "Preorder #$preorder_id marked as Delivered.";
    header("Location: rider_ongoing.php");
    exit();
}

// ===== Fetch ongoing deliveries =====
$orders = $conn->query("SELECT * FROM orders WHERE rider_id=$rider_id AND status='On Delivery' ORDER BY order_date ASC");
$preorders = $conn->query("SELECT * FROM preorders WHERE rider_id=$rider_id AND status='On Delivery' ORDER BY created_at ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ongoing Deliveries | Rider Dashboard</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px;}
.container { max-width:1200px; margin:auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2 { text-align:center; margin-bottom:20px;}
a.logout { float:right; background:#dc3545; color:#fff; padding:8px 15px; text-decoration:none; border-radius:6px;}
a.logout:hover { background:#c82333;}
table { width:100%; border-collapse: collapse; margin-top:20px;}
th, td { border:1px solid #ccc; padding:10px; text-align:center; vertical-align:top;}
th { background:#F4C430;}
a.button { text-decoration:none; padding:6px 12px; background:#007bff; color:white; border-radius:6px;}
a.button:hover { background:#0069d9;}
.message { background:#d4edda; color:#155724; padding:10px; border-radius:6px; margin-bottom:20px; text-align:center;}
</style>
</head>
<body>
<div class="container">
<h2>Ongoing Deliveries</h2>
<a href="riders.php" class="button">Home</a>
<a href="rider_logout.php" class="logout">Logout</a>

<?php
if(isset($_SESSION['message'])) {
    echo '<div class="message">'.$_SESSION['message'].'</div>';
    unset($_SESSION['message']);
}
?>

<!-- Orders -->
<h3>Orders On Delivery</h3>
<?php if($orders->num_rows > 0): ?>
<table>
<thead>
<tr>
<th>Order ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Address</th>
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
<td><?php echo htmlspecialchars($order['address']); ?>, <?php echo htmlspecialchars($order['city']); ?></td>
<td>₱<?php echo number_format($order['total'],2); ?></td>
<td>
    <a class="button" href="rider_ongoing.php?deliver_order=<?php echo $order['order_id']; ?>">Mark Delivered</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>No ongoing orders.</p>
<?php endif; ?>

<!-- Preorders -->
<h3>Preorders On Delivery</h3>
<?php if($preorders->num_rows > 0): ?>
<table>
<thead>
<tr>
<th>Preorder ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Category</th>
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
<td><?php echo htmlspecialchars($preorder['category']); ?></td>
<td>₱<?php echo number_format($preorder['total'],2); ?></td>
<td>
    <a class="button" href="rider_ongoing.php?deliver_preorder=<?php echo $preorder['preorder_id']; ?>">Mark Delivered</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<p>No ongoing preorders.</p>
<?php endif; ?>

</div>
</body>
</html>
