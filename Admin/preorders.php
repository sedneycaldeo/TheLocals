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
    <title>Pending Preorders | Admin</title>

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

    <h1>Pending Preorders</h1>

    <a href="index.php" class="button">Back to Dashboard</a>

    <?php
    // Fetch all preorders with product details
    $sql = "SELECT * FROM preorders WHERE status != 'Delivered' ORDER BY created_at ASC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0):
    ?>

    <table>
        <thead>
            <tr>
                <th>Preorder ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Category</th>
                <th>Product</th>
                <th>Image</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Subtotal</th>
                <th>Total</th>
                <th>Status</th>
                <th>Created At</th>
            </tr>
        </thead>

        <tbody>
            <?php while ($preorder = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $preorder['preorder_id']; ?></td>

                    <td><?php echo htmlspecialchars($preorder['fullname']); ?></td>

                    <td><?php echo htmlspecialchars($preorder['email']); ?></td>

                    <td><?php echo htmlspecialchars($preorder['phone']); ?></td>

                    <td><?php echo htmlspecialchars($preorder['category']); ?></td>

                    <td><?php echo htmlspecialchars($preorder['product_name']); ?></td>

                    <td>
                        <?php if (!empty($preorder['image'])): ?>
                            <img src="<?php echo htmlspecialchars($preorder['image']); ?>" class="product-img" alt="Product">
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </td>

                    <td>₱<?php echo number_format($preorder['unit_price'], 2); ?></td>

                    <td><?php echo $preorder['quantity']; ?></td>

                    <td>₱<?php echo number_format($preorder['subtotal'], 2); ?></td>

                    <td>₱<?php echo number_format($preorder['total'], 2); ?></td>

                    <td><?php echo $preorder['status']; ?></td>

                    <td><?php echo $preorder['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <?php else: ?>
        <p>No pending preorders.</p>
    <?php endif; ?>

</div>
</body>
</html>