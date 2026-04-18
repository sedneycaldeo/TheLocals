<?php
include "../conn.php";

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $id");
$product = mysqli_fetch_assoc($query);

if ($product) {
    // Remove image file from server
    if (!empty($product['image']) && file_exists("../" . $product['image'])) {
        unlink("../" . $product['image']);
    }

    // Delete record
    mysqli_query($conn, "DELETE FROM products WHERE product_id = $id");
}

header("Location: view_products.php");
exit;
?>
