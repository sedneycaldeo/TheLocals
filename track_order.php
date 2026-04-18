<?php
session_start();
include "conn.php";

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all regular orders
$stmt = $conn->prepare("SELECT * FROM orders WHERE user_id=? ORDER BY order_date DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();

// Fetch all pre-orders
$stmt2 = $conn->prepare("SELECT * FROM preorders WHERE user_id=? ORDER BY created_at DESC");
$stmt2->bind_param("i", $user_id);
$stmt2->execute();
$preorders = $stmt2->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Track Orders | The Fry Project</title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; padding:20px; }
.container { max-width:900px; margin:auto; background:#fff; padding:2rem; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1); margin-bottom:40px; }
h2 { text-align:center; margin-bottom:1rem; }
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { border:1px solid #ccc; padding:10px; text-align:center; }
th { background:#F4C430; }
.status { font-weight:bold; color:#ff5a5f; }
a.button { text-decoration:none; background:#28a745; color:white; padding:6px 12px; border-radius:6px; }
a.button:hover { background:#218838; }
</style>
</head>
<body>

<div class="container">
<h2>Your Orders</h2>

<?php if($orders->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Total</th>
                <th>Payment Method</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($order = $orders->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                <td><?php echo date("M d, Y", strtotime($order['order_date'])); ?></td>
                <td>₱<?php echo number_format($order['total'],2); ?></td>
                <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                <td class="status"><?php echo htmlspecialchars($order['status']); ?></td>
                <td><a class="button" href="order_receipt.php?order_id=<?php echo urlencode($order['order_id']); ?>">View</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center; color:#666;">You have no orders yet.</p>
<?php endif; ?>
</div>

<div class="container">
<h2>Your Pre-Orders</h2>

<?php if($preorders->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Preorder ID</th>
                <th>Date</th>
                <th>Product Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Notes</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php while($preorder = $preorders->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($preorder['preorder_id']); ?></td>
                <td><?php echo date("M d, Y", strtotime($preorder['created_at'])); ?></td>
                <td><?php echo htmlspecialchars($preorder['product_name']); ?></td>
                <td><?php echo htmlspecialchars($preorder['category']); ?></td>
                <td><?php echo intval($preorder['quantity']); ?></td>
                <td>₱<?php echo number_format($preorder['unit_price'],2); ?></td>
                <td>₱<?php echo number_format($preorder['subtotal'],2); ?></td>
                <td>₱<?php echo number_format($preorder['total'],2); ?></td>
                <td><?php echo htmlspecialchars($preorder['notes']); ?></td>
                <td><a class="button" href="preorder_receipt.php?preorder_id=<?php echo urlencode($preorder['preorder_id']); ?>">View</a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p style="text-align:center; color:#666;">You have no pre-orders yet.</p>
<?php endif; ?>
</div>

</body>
</html>
