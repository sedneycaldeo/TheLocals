<?php
session_start();
include 'conn.php';

if (!isset($_GET['order_id'])) {
    echo "<p>No Order ID provided.</p>";
    exit;
}

$order_id = $_GET['order_id'];

// Fetch order info (from orders table)
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->bind_param("s", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "<p>Order not found.</p>";
    exit;
}

$order = $order_result->fetch_assoc();

// Fetch order items (from order_items table)
$item_stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
$item_stmt->bind_param("s", $order_id);
$item_stmt->execute();
$items_result = $item_stmt->get_result();

$order_items = [];
$total_amount = 0;

while ($row = $items_result->fetch_assoc()) {
    $order_items[] = $row;
    $total_amount += $row['subtotal'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order Receipt | <?php echo htmlspecialchars($order_id); ?></title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px; }
.container { max-width: 700px; margin:auto; background:#fff; padding:2rem; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2 { text-align:center; margin-bottom:1rem;}
.info { margin-bottom:20px; }
.info p { margin: 0.3rem 0; }
table { width:100%; border-collapse: collapse; margin-top:1rem;}
th, td { padding:10px; border:1px solid #ccc; text-align:center;}
th { background:#f4c430; color:#000; }
img { max-width:80px; height:auto; border-radius:8px; }
.total { text-align:right; font-weight:bold; font-size:1.1rem; margin-top:1rem;}
.continue-btn { display:block; text-align:center; margin-top:20px; padding:10px; background:#28a745; color:white; text-decoration:none; border-radius:6px;}
@media(max-width:600px){
    table, th, td { font-size:12px; }
    img { max-width:50px; }
}
.profile { display:block; margin:auto; width:80px; height:80px; border-radius:50%; object-fit:cover; margin-bottom:15px; }
</style>
</head>
<body>
<div class="container">
<h2>Order Receipt</h2>

<?php if(!empty($order['image'])): ?>
    <img src="uploads/<?php echo htmlspecialchars($order['image']); ?>" alt="Profile" class="profile">
<?php endif; ?>

<div class="info">
    <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
    <p><strong>Customer Email:</strong> <?php echo htmlspecialchars($order['customer_email']); ?></p>
    <p><strong>Full Name:</strong> <?php echo htmlspecialchars($order['fullname']); ?></p>
    <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
    <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?>, 
       <?php echo htmlspecialchars($order['city']); ?>, 
       <?php echo htmlspecialchars($order['postal_code']); ?></p>
    <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
    <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['order_date']); ?></p>
</div>

<table>
<tr>
    <th>Product</th>
    <th>Image</th>
    <th>Unit Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
</tr>
<?php foreach($order_items as $item): ?>
<tr>
    <td><?php echo htmlspecialchars($item['name']); ?></td>
    <td>
        <?php if(!empty($item['image'])): ?>
            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product">
        <?php endif; ?>
    </td>
    <td>₱<?php echo number_format($item['price'],2); ?></td>
    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
    <td>₱<?php echo number_format($item['subtotal'],2); ?></td>
</tr>
<?php endforeach; ?>
</table>

<p class="total">Total Amount: ₱<?php echo number_format($total_amount, 2); ?></p>

<a href="index.php" class="continue-btn">Continue Shopping</a>
</div>
</body>
</html>
