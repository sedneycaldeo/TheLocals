<?php
session_start();
include 'conn.php';

// Protect page – admin only
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Orders | Admin</title>

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

        .product-list {
            text-align: left;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Pending Orders</h1>

    <a href="index.php" class="button">Back to Dashboard</a>

    <?php
    // Fetch all orders with their items using JOIN
    $sql = "SELECT o.*, oi.product_id, oi.name AS product_name, oi.image AS product_image, oi.price, oi.quantity, oi.subtotal
            FROM orders o
            LEFT JOIN order_items oi ON o.order_id = oi.order_id
            WHERE o.status != 'Delivered'
            ORDER BY o.order_date ASC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0):
        $orders = [];

        while ($row = $result->fetch_assoc()) {
            $oid = $row['order_id'];

            if (!isset($orders[$oid])) {
                $orders[$oid] = $row;
                $orders[$oid]['items'] = [];
            }

            if ($row['product_id'] != null) {
                $orders[$oid]['items'][] = [
                    'name'     => $row['product_name'],
                    'image'    => $row['product_image'],
                    'price'    => $row['price'],
                    'quantity' => $row['quantity'],
                    'subtotal' => $row['subtotal']
                ];
            }
        }
    ?>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Image</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Products</th>
                <th>Total</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>

                    <td><?php echo htmlspecialchars($order['fullname']); ?></td>

                    <td>
                        <?php if (!empty($order['image'])): ?>
                            <img src="<?php echo htmlspecialchars($order['image']); ?>" class="customer-img" alt="Customer Image">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>

                    <td><?php echo htmlspecialchars($order['customer_email']); ?></td>

                    <td><?php echo htmlspecialchars($order['phone']); ?></td>

                    <td>
                        <?php
                        echo htmlspecialchars($order['address']) . ', ' .
                             htmlspecialchars($order['city']);
                        ?>
                    </td>

                    <td class="product-list">
                        <?php
                        if (count($order['items']) > 0) {
                            echo "<ul style='padding-left:15px; margin:0;'>";

                            foreach ($order['items'] as $item) {
                                echo "<li>";

                                if (!empty($item['image'])) {
                                    echo "<img src='" . htmlspecialchars($item['image']) . "' class='product-img' alt='Product'> ";
                                }

                                echo htmlspecialchars($item['name']) . " × " .
                                     $item['quantity'] . " (₱" .
                                     number_format($item['subtotal'], 2) . ")";

                                echo "</li>";
                            }

                            echo "</ul>";
                        } else {
                            echo "No products";
                        }
                        ?>
                    </td>

                    <td>₱<?php echo number_format($order['total'], 2); ?></td>

                    <td><?php echo $order['status']; ?></td>

                    <td><?php echo $order['order_date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p>No pending orders.</p>
    <?php endif; ?>

</div>
</body>
</html>