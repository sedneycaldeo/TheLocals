<?php
session_start();
include 'conn.php';

// ====== Protect page for admin only ======
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ====== Determine period ======
$period = $_GET['period'] ?? 'weekly';
$period_sql = '';

switch ($period) {
    case 'monthly':
        $period_sql = "DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        break;

    case 'annually':
        $period_sql = "DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
        break;

    default: // weekly
        $period_sql = "DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        $period = 'weekly';
}

// ====== Total sales ======
$order_sales = $conn->query(
    "SELECT COUNT(*) AS total_orders, SUM(total) AS revenue_orders
     FROM orders
     WHERE status = 'Delivered'
     AND order_date >= $period_sql"
);
$order_data = $order_sales->fetch_assoc();

$preorder_sales = $conn->query(
    "SELECT COUNT(*) AS total_preorders, SUM(total) AS revenue_preorders
     FROM preorders
     WHERE status = 'Delivered'
     AND created_at >= $period_sql"
);
$preorder_data = $preorder_sales->fetch_assoc();

$total_revenue =
    ($order_data['revenue_orders'] ?? 0) +
    ($preorder_data['revenue_preorders'] ?? 0);

// ====== Product-level sales ======
$order_products = $conn->query(
    "SELECT oi.name AS product_name, SUM(oi.quantity) AS quantity
     FROM order_items oi
     JOIN orders o ON oi.order_id = o.order_id
     WHERE o.status = 'Delivered'
     AND o.order_date >= $period_sql
     GROUP BY oi.name
     ORDER BY quantity DESC"
);

$preorder_products = $conn->query(
    "SELECT product_name, SUM(quantity) AS quantity
     FROM preorders
     WHERE status = 'Delivered'
     AND created_at >= $period_sql
     GROUP BY product_name
     ORDER BY quantity DESC"
);

// ====== Chart data preparation ======
$colors_palette = [
    '#FF6384','#36A2EB','#FFCE56','#4BC0C0','#9966FF',
    '#FF9F40','#8AFF33','#FF33F6','#33FFF3','#FF3333'
];

function prepare_chart_data($products, $colors_palette) {
    $labels = [];
    $quantities = [];
    $colors = [];
    $i = 0;

    while ($row = $products->fetch_assoc()) {
        $labels[] = $row['product_name'];
        $quantities[] = intval($row['quantity']);
        $colors[] = $colors_palette[$i % count($colors_palette)];
        $i++;
    }

    return [$labels, $quantities, $colors];
}

list($order_labels, $order_quantities, $order_colors) =
    prepare_chart_data($order_products, $colors_palette);

list($preorder_labels, $preorder_quantities, $preorder_colors) =
    prepare_chart_data($preorder_products, $colors_palette);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard | Admin</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .summary {
            display: flex;
            justify-content: space-around;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .card {
            background: #F4C430;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            width: 30%;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            margin-bottom: 10px;
        }

        .card h2 {
            margin: 10px 0;
            font-size: 16px;
        }

        a.button {
            display: inline-block;
            margin: 5px;
            padding: 6px 12px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
        }

        a.button.active {
            background: #28a745;
        }

        a.button:hover {
            background: #0069d9;
        }

        .charts-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .chart-container {
            width: 350px;
            margin: 10px;
            text-align: center;
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
        }

        th {
            background: #007bff;
            color: white;
        }
    </style>
</head>

<body>
<div class="container">

    <h1>Sales Dashboard</h1>

    <!-- Period buttons -->
    <div style="text-align:center; margin-bottom:20px;">
        <a href="?period=weekly" class="button <?php if ($period == 'weekly') echo 'active'; ?>">Weekly</a>
        <a href="?period=monthly" class="button <?php if ($period == 'monthly') echo 'active'; ?>">Monthly</a>
        <a href="?period=annually" class="button <?php if ($period == 'annually') echo 'active'; ?>">Annually</a>
    </div>

    <!-- Summary cards -->
    <div class="summary">
        <div class="card">
            <h2>Total Orders</h2>
            <p><?php echo $order_data['total_orders'] ?? 0; ?></p>
            <p>Revenue: ₱<?php echo number_format($order_data['revenue_orders'] ?? 0, 2); ?></p>
        </div>

        <div class="card">
            <h2>Total Preorders</h2>
            <p><?php echo $preorder_data['total_preorders'] ?? 0; ?></p>
            <p>Revenue: ₱<?php echo number_format($preorder_data['revenue_preorders'] ?? 0, 2); ?></p>
        </div>

        <div class="card">
            <h2>Total Revenue</h2>
            <p>₱<?php echo number_format($total_revenue, 2); ?></p>
        </div>
    </div>

    <!-- Charts -->
    <div class="charts-row">
        <div class="chart-container">
            <h3>Orders Product Sales</h3>
            <canvas id="ordersPie"></canvas>
        </div>

        <div class="chart-container">
            <h3>Preorders Product Sales</h3>
            <canvas id="preordersPie"></canvas>
        </div>
    </div>

    <!-- Orders Table -->
    <h3>Orders Details</h3>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Total</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $orders = $conn->query("SELECT * FROM orders ORDER BY order_date DESC");
            while ($order = $orders->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer_email']); ?></td>
                    <td><?php echo htmlspecialchars($order['phone']); ?></td>
                    <td>₱<?php echo number_format($order['total'], 2); ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Preorders Table -->
    <h3>Preorders Details</h3>
    <table>
        <thead>
            <tr>
                <th>Preorder ID</th>
                <th>Customer</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Product</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th>Order Date</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $preorders = $conn->query("SELECT * FROM preorders ORDER BY created_at DESC");
            while ($preorder = $preorders->fetch_assoc()):
            ?>
                <tr>
                    <td><?php echo $preorder['preorder_id']; ?></td>
                    <td><?php echo htmlspecialchars($preorder['fullname']); ?></td>
                    <td><?php echo htmlspecialchars($preorder['email']); ?></td>
                    <td><?php echo htmlspecialchars($preorder['phone']); ?></td>
                    <td><?php echo htmlspecialchars($preorder['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($preorder['category']); ?></td>
                    <td><?php echo $preorder['quantity']; ?></td>
                    <td>₱<?php echo number_format($preorder['total'], 2); ?></td>
                    <td><?php echo $preorder['status']; ?></td>
                    <td><?php echo $preorder['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="index.php" class="button" style="background:#28a745; margin-top:10px;">
        Back to Dashboard
    </a>

    <script>
        const ordersData = {
            labels: <?php echo json_encode($order_labels); ?>,
            datasets: [{
                label: 'Quantity Sold',
                data: <?php echo json_encode($order_quantities); ?>,
                backgroundColor: <?php echo json_encode($order_colors); ?>
            }]
        };

        const preordersData = {
            labels: <?php echo json_encode($preorder_labels); ?>,
            datasets: [{
                label: 'Quantity Sold',
                data: <?php echo json_encode($preorder_quantities); ?>,
                backgroundColor: <?php echo json_encode($preorder_colors); ?>
            }]
        };

        function createPieChart(ctx, data) {
            new Chart(ctx, {
                type: 'pie',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: { enabled: true }
                    },
                    onClick: (evt, item) => {
                        if (item.length > 0) {
                            const index = item[0].index;
                            alert(
                                "Product: " + data.labels[index] +
                                "\nQuantity Sold: " + data.datasets[0].data[index]
                            );
                        }
                    }
                }
            });
        }

        createPieChart(
            document.getElementById('ordersPie').getContext('2d'),
            ordersData
        );

        createPieChart(
            document.getElementById('preordersPie').getContext('2d'),
            preordersData
        );
    </script>

</div>
</body>
</html>