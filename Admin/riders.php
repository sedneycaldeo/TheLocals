<?php
session_start();
include 'conn.php';

// Only logged-in riders can access
if (!isset($_SESSION['rider_id'])) {
    header("Location: rider_login.php");
    exit();
}

$rider_id   = $_SESSION['rider_id'];
$rider_name = $_SESSION['rider_name'];

// Fetch ongoing Orders (not delivered)
$ongoing_orders = $conn->query(
    "SELECT * FROM orders 
     WHERE rider_id = $rider_id 
     AND status != 'Delivered' 
     ORDER BY order_date ASC"
);

// Fetch ongoing Preorders (not delivered)
$ongoing_preorders = $conn->query(
    "SELECT * FROM preorders 
     WHERE rider_id = $rider_id 
     AND status != 'Delivered' 
     ORDER BY created_at ASC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Ongoing Deliveries</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        a.logout,
        a.home {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
            color: #fff;
            font-weight: bold;
        }

        a.logout {
            background: #dc3545;
            float: right;
        }

        a.logout:hover {
            background: #c82333;
        }

        a.home {
            background: #17a2b8;
            float: left;
        }

        a.home:hover {
            background: #138496;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
            vertical-align: top;
        }

        th {
            background: #F4C430;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>
        Ongoing Deliveries for <?php echo htmlspecialchars($rider_name); ?>
    </h2>

    <!-- Navigation buttons -->
    <div style="overflow:auto; margin-bottom:20px;">
        <a href="index.php" class="home">Home</a>
        <a href="rider_logout.php" class="logout">Logout</a>
    </div>

    <!-- Ongoing Orders -->
    <h3>Ongoing Orders</h3>

    <?php if ($ongoing_orders->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($order = $ongoing_orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($order['phone']); ?></td>
                        <td>
                            <?php
                            echo htmlspecialchars($order['address']) . ', ' .
                                 htmlspecialchars($order['city']);
                            ?>
                        </td>
                        <td>₱<?php echo number_format($order['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($order['status']); ?></td>
                        <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No ongoing orders assigned to you.</p>
    <?php endif; ?>

    <!-- Ongoing Preorders -->
    <h3>Ongoing Preorders</h3>

    <?php if ($ongoing_preorders->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Preorder ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Category</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Order Date</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($preorder = $ongoing_preorders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $preorder['preorder_id']; ?></td>
                        <td><?php echo htmlspecialchars($preorder['fullname']); ?></td>
                        <td><?php echo htmlspecialchars($preorder['phone']); ?></td>
                        <td><?php echo htmlspecialchars($preorder['category']); ?></td>
                        <td><?php echo htmlspecialchars($preorder['product_name']); ?></td>
                        <td><?php echo $preorder['quantity']; ?></td>
                        <td>₱<?php echo number_format($preorder['total'], 2); ?></td>
                        <td><?php echo htmlspecialchars($preorder['status']); ?></td>
                        <td><?php echo htmlspecialchars($preorder['created_at']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No ongoing preorders assigned to you.</p>
    <?php endif; ?>

</div>
</body>
</html>