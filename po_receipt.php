<?php
session_start();
include 'conn.php';

if(!isset($_GET['preorder_id'])){
    echo "<p>No Pre-order ID provided.</p>";
    exit;
}

$preorder_id = $_GET['preorder_id'];

// Fetch all products in this preorder
$stmt = $conn->prepare("SELECT * FROM preorders WHERE preorder_id = ?");
$stmt->bind_param("s", $preorder_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows === 0){
    echo "<p>Pre-order not found.</p>";
    exit;
}

$preorders = [];
$total_amount = 0;

while($row = $result->fetch_assoc()){
    $preorders[] = $row;
    $total_amount += $row['subtotal'] ?? 0;
}

// Use first row for customer info
$customer = $preorders[0];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pre-Order Receipt | <?php echo htmlspecialchars($preorder_id); ?></title>
<style>
body { font-family: Arial, sans-serif; background:#f4f4f4; margin:0; padding:20px; }
.container { max-width: 700px; margin:auto; background:#fff; padding:2rem; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.1);}
h2 { text-align:center; margin-bottom:1rem;}
.info { margin-bottom:20px; }
.info p { margin: 0.3rem 0; }
table { width:100%; border-collapse: collapse; margin-top:1rem;}
th, td { padding:10px; border:1px solid #ccc; text-align:center;}
th { background:#f4c430; color:#000; }
img { max-width:80px; height:auto; }
.total { text-align:right; font-weight:bold; font-size:1.1rem; margin-top:1rem;}
.continue-btn { display:block; text-align:center; margin-top:20px; padding:10px; background:#28a745; color:white; text-decoration:none; border-radius:6px;}
@media(max-width:600px){
    table, th, td { font-size:12px; }
    img { max-width:50px; }
}
</style>
</head>
<body>
<div class="container">
<h2>Pre-Order Receipt</h2>

<div class="info">
<p><strong>Order ID:</strong> <?php echo htmlspecialchars($customer['preorder_id']); ?></p>
<p><strong>Customer Email:</strong> <?php echo htmlspecialchars($customer['email']); ?></p>
<p><strong>Full Name:</strong> <?php echo htmlspecialchars($customer['fullname']); ?></p>
<p><strong>Phone:</strong> <?php echo htmlspecialchars($customer['phone']); ?></p>
<p><strong>Category:</strong> <?php echo htmlspecialchars($customer['category']); ?></p>
<p><strong>Order Date:</strong> <?php echo htmlspecialchars($customer['created_at']); ?></p>
</div>

<table>
<tr>
    <th>Product</th>
    <th>Image</th>
    <th>Unit Price</th>
    <th>Quantity</th>
    <th>Subtotal</th>
</tr>
<?php foreach($preorders as $item): ?>
<tr>
    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
    <td><?php if(!empty($item['image'])): ?><img src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product"><?php endif; ?></td>
    <td>₱<?php echo number_format($item['unit_price'],2); ?></td>
    <td><?php echo $item['quantity']; ?></td>
    <td>₱<?php echo number_format($item['subtotal'],2); ?></td>
</tr>
<?php endforeach; ?>
</table>A

<p class="total">Total Amount: ₱<?php echo number_format($total_amount,2); ?></p>

<a href="index.php" class="continue-btn">Continue Shopping</a>
</div>
</body>
</html>
