<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['rider_id'])) {
    header("Location: riders_history.php");
    exit();
}

$rider_id = intval($_GET['rider_id']);
$rider = $conn->query("SELECT * FROM riders WHERE rider_id = $rider_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rider Profile</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
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

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            width: 40%;
        }

        a.button {
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 6px;
            color: #fff;
            font-weight: bold;
            background: #17a2b8;
            display: inline-block;
            margin-bottom: 20px;
        }

        a.button:hover {
            background: #138496;
        }
    </style>
</head>

<body>
<div class="container">

    <h2>
        Rider Profile: <?php echo htmlspecialchars($rider['fullname']); ?>
    </h2>

    <a href="riders_history.php" class="button">Back</a>

    <table>
        <tr>
            <th>Full Name</th>
            <td><?php echo htmlspecialchars($rider['fullname']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($rider['email']); ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo htmlspecialchars($rider['phone']); ?></td>
        </tr>
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($rider['username']); ?></td>
        </tr>
        <tr>
            <th>Vehicle Type</th>
            <td><?php echo htmlspecialchars($rider['vehicle_type']); ?></td>
        </tr>
        <tr>
            <th>Vehicle Number</th>
            <td><?php echo htmlspecialchars($rider['vehicle_number']); ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo htmlspecialchars($rider['status']); ?></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td><?php echo htmlspecialchars($rider['created_at']); ?></td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td><?php echo htmlspecialchars($rider['updated_at']); ?></td>
        </tr>
    </table>

</div>
</body>
</html>