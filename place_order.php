<?php
session_start();
include "conn.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: add_to_cart.php");
    exit;
}

if (isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $postal_code = $_POST['postal_code'];
    $payment_method = $_POST['payment_method'];
    $profile_pic = $_POST['profile_pic'];
    $status = "Pending";

    // Calculate total
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Generate unique order_id
    $order_id = 'ORD-' . strtoupper(uniqid());

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders 
        (order_id, customer_email, user_id, fullname, image, phone, address, city, postal_code, payment_method, total, order_date, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("ssisssssssss", 
    $order_id,      // s
    $email,         // s
    $user_id,       // i
    $fullname,      // s
    $profile_pic,   // s
    $phone,         // s
    $address,       // s
    $city,          // s
    $postal_code,   // s
    $payment_method,// s ✅ string
    $total,         // d
    $status         // s
);

    $stmt->execute();

    // Insert each item into order_items
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, name, image, price, quantity, subtotal) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $stmt_item->bind_param("sissdii", 
            $order_id, $product_id, $item['product_name'], $item['image'], $item['price'], $item['quantity'], $subtotal);
        $stmt_item->execute();
    }

    unset($_SESSION['cart']);
    header("Location: order_receipt.php?order_id=" . urlencode($order_id));
    exit;
}
?>
