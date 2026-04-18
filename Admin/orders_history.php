<?php
session_start();
include 'conn.php';

// Protect page – admin only
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders History | Admin</title>

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

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        a.button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
        }

        a.button:hover {
            background: #0069d9;
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
            vertical-align: middle;
        }

        th {
            background: #F4C430;
        }

        img.customer-img,
        img.product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Orders History</h1>

    <a href="index.php" class="button">Back to Dashboard</a>

    <?php
    // Fetch all delivered/completed orders
    $sql = "SELECT * FROM orders WHERE status='Delivered' ORDER BY order_date DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
    ?>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Total</th>
                <th>Payment Method</th>
                <th>Rider ID</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    <td>
                        <?php
                        echo htmlspecialchars($order['address']) . ', ' .
                             htmlspecialchars($order['city']) . ' ' .
                             htmlspecialchars($order['postal_code']);
                        ?>
                    </td>
                    <td>₱<?php echo number_format($order['total'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['payment_method']); ?></td>
                    <td><?php echo $order['rider_id'] ?? 'N/A'; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td><?php echo $order['status']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p>No previous orders found.</p>
    <?php endif; ?>

</div>
</body>
</html>