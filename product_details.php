<?php
include "conn.php";

// ✅ Check if the product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
  die("<p style='text-align:center; font-family:Poppins, sans-serif;'>Invalid product ID.</p>");
}

$product_id = intval($_GET['id']);

// ✅ Fetch product details
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
  die("<p style='text-align:center; font-family:Poppins, sans-serif;'>Product not found.</p>");
}

$product = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($product['product_name']); ?> | The Fry Project</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body style="margin:0; font-family:'Poppins',sans-serif; background-color:#f8f9fa;">

  <!-- 🔹 Navigation Bar -->
  <div style="background-color:#F4C430; display:flex; justify-content:center; align-items:center; padding:12px 0;">
    <div style="display:flex; gap:10px;">
      <a href="index.php" style="background-color:white; color:#000; padding:10px 20px; border-radius:6px; text-decoration:none;">Home</a>
      <a href="menu.php" style="background-color:#ff5a5f; color:white; padding:10px 20px; border-radius:6px; text-decoration:none;">Menu</a>
      <a href="promo.php" style="background-color:white; color:#000; padding:10px 20px; border-radius:6px; text-decoration:none;">Promos</a>
      <a href="order.php" style="background-color:white; color:#000; padding:10px 20px; border-radius:6px; text-decoration:none;">Order Now</a>
      <a href="aboutus.php" style="background-color:white; color:#000; padding:10px 20px; border-radius:6px; text-decoration:none;">About Us</a>
      <a href="gallery.php" style="background-color:white; color:#000; padding:10px 20px; border-radius:6px; text-decoration:none;">Gallery</a>
      <a href="contact.php" style="background-color:white; color:#000; padding:10px 20px; border-radius:6px; text-decoration:none;">Contact</a>
    </div>
  </div>

  <!-- 🍟 Product Details -->
  <div style="max-width:800px; margin:50px auto; background-color:white; border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,0.1); overflow:hidden;">
    <div style="width:100%; height:400px; background-color:#f4f4f4;">
      <?php if (!empty($product['image'])): ?>
        <img src="<?php echo htmlspecialchars($product['image']); ?>" style="width:100%; height:100%; object-fit:cover;">
      <?php else: ?>
        <span style="display:block; text-align:center; line-height:400px; color:#aaa;">No Image Available</span>
      <?php endif; ?>
    </div>

    <div style="padding:25px;">
      <h1 style="margin-top:0; color:#ff5a5f;"><?php echo htmlspecialchars($product['product_name']); ?></h1>
      <p style="color:#555; line-height:1.6; font-size:15px;"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
      <p style="font-size:20px; font-weight:600; color:#ff5a5f;">₱<?php echo number_format($product['price'], 2); ?></p>

      <form action="add_to_cart.php" method="POST" style="margin-top:20px;">
        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
        <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>">
        <input type="hidden" name="price" value="<?php echo $product['price']; ?>">
        <button type="submit" style="background-color:#F4C430; border:none; color:black; padding:10px 20px; border-radius:8px; font-weight:600; cursor:pointer; transition:0.3s;"
          onmouseover="this.style.backgroundColor='#ff5a5f'; this.style.color='white';"
          onmouseout="this.style.backgroundColor='#F4C430'; this.style.color='black';">
          Add to Cart
        </button>
      </form>

      <div style="margin-top:30px;">
        <a href="product.php" style="text-decoration:none; background-color:#6c757d; color:white; padding:10px 20px; border-radius:8px; transition:0.3s;"
          onmouseover="this.style.backgroundColor='#495057';"
          onmouseout="this.style.backgroundColor='#6c757d';">
          ← Back to Menu
        </a>
      </div>
    </div>
  </div>

</body>
</html>
