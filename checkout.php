<?php
session_start();
include "conn.php";

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Get user info
$stmt = $conn->prepare("SELECT fullname, email, profile_pic, address, city, postal_code, phone FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Make sure cart isn’t empty
if (empty($_SESSION['cart'])) {
    header("Location: add_to_cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Checkout | The Locals</title>
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: #f8f9fa;
    padding: 40px;
}
.container {
    max-width: 750px;
    margin: auto;
    background: #fff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
h2 {
    color: #F4C430;
    text-align: center;
}
input, select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}
button {
    width: 100%;
    background: #28a745;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}
button:hover {
    background: #218838;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}
th, td {
    border: 1px solid #ddd;
    text-align: center;
    padding: 8px;
}
th {
    background: #F4C430;
}
img.profile {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: block;
    margin: 10px auto;
}
</style>
</head>
<body>
<div class="container">
    <h2>Checkout</h2>
    <img src="uploads/<?php echo htmlspecialchars($user['profile_pic']); ?>" alt="Profile" class="profile">

    <form method="POST" action="place_order.php">
        <h3>Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $item):
                $subtotal = $item['price'] * $item['quantity'];
                $total += $subtotal;
            ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']); ?></td>
                    <td><?= $item['quantity']; ?></td>
                    <td>₱<?= number_format($item['price'], 2); ?></td>
                    <td>₱<?= number_format($subtotal, 2); ?></td>
                </tr>
            <?php endforeach; ?>
                <tr>
                    <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
                    <td><strong>₱<?= number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <h3>Customer Information</h3>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']); ?>" placeholder="Full Name" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" placeholder="Email" required>
        <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']); ?>" placeholder="Phone" required>
        <input type="text" name="address" value="<?= htmlspecialchars($user['address']); ?>" placeholder="Address" required>
        <input type="text" name="city" value="<?= htmlspecialchars($user['city']); ?>" placeholder="City" required>
        <input type="text" name="postal_code" value="<?= htmlspecialchars($user['postal_code']); ?>" placeholder="Postal Code" required>

        <h3>Payment Method</h3>
        <select name="payment_method" required>
            <option value="COD">Cash on Delivery</option>
            <option value="GCash">GCash</option>
        </select>

        <input type="hidden" name="profile_pic" value="<?= htmlspecialchars($user['profile_pic']); ?>">

        <button type="submit" name="place_order">Place Order</button>
    </form>
</div>
</body>
</html>
