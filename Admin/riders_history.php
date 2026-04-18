<?php
session_start();
include 'conn.php';

// Protect page for admin only
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all riders
$riders = $conn->query("SELECT * FROM riders ORDER BY fullname ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riders History | Admin</title>

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

        a.button {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
            color: #fff;
            font-weight: bold;
            margin-bottom: 20px;
            display: inline-block;
        }

        a.home {
            background: #17a2b8;
        }

        a.home:hover {
            background: #138496;
        }

        a.profile {
            background: #6c757d;
            margin-left: 10px;
        }

        a.profile:hover {
            background: #5a6268;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Riders Delivery History</h2>

    <!-- Home button -->
    <a href="index.php" class="button home">Home</a>

    <?php if ($riders->num_rows > 0): ?>
        <?php while ($rider = $riders->fetch_assoc()): ?>
            <div style="margin-top:20px;">

                <h3>
                    <?php echo htmlspecialchars($rider['fullname']); ?>
                    (<?php echo htmlspecialchars($rider['vehicle_type']); ?>)
                </h3>

                <!-- View Profile button -->
                <a href="rider_profile.php?rider_id=<?php echo $rider['rider_id']; ?>" class="button profile">
                    View Profile
                </a>

                <!-- Delivered Orders -->
                <?php
                $rider_id = $rider['rider_id'];
                $delivered_orders = $conn->query(
                    "SELECT * FROM orders 
                     WHERE rider_id = $rider_id 
                     AND status = 'Delivered' 
                     ORDER BY order_date DESC"
                );

                if ($delivered_orders->num_rows > 0):
                ?>
                    <h4>Delivered Orders</h4>

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
                            <?php while ($order = $delivered_orders->fetch_assoc()): ?>
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
                                    <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                <?php else: ?>
                    <p>No delivered orders for this rider.</p>
                <?php endif; ?>

                <!-- Delivered Preorders -->
                <?php
                $delivered_preorders = $conn->query(
                    "SELECT * FROM preorders 
                     WHERE rider_id = $rider_id 
                     AND status = 'Delivered' 
                     ORDER BY created_at DESC"
                );

                if ($delivered_preorders->num_rows > 0):
                ?>
                    <h4>Delivered Preorders</h4>

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
                            <?php while ($preorder = $delivered_preorders->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $preorder['preorder_id']; ?></td>
                                    <td><?php echo htmlspecialchars($preorder['fullname']); ?></td>
                                    <td><?php echo htmlspecialchars($preorder['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($preorder['category']); ?></td>
                                    <td>₱<?php echo number_format($preorder['total'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($preorder['created_at']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                <?php else: ?>
                    <p>No delivered preorders for this rider.</p>
                <?php endif; ?>

                <hr style="margin:40px 0;">

            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No riders found.</p>
    <?php endif; ?>

</div>
</body>
</html>