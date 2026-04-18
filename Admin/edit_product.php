<?php
include "../conn.php";

$id = $_GET['id'] ?? 0;
$query = mysqli_query($conn, "SELECT * FROM products WHERE product_id = $id");
$product = mysqli_fetch_assoc($query);

if (!$product) {
    die("<p style='text-align:center; margin-top:50px; color:red;'>Product not found!</p>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $update_image = "";

    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $file_name = uniqid() . "_" . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $update_image = ", image='uploads/$file_name'";
        }
    }

    $sql = "UPDATE products SET product_name='$name', description='$desc', price='$price' $update_image WHERE product_id=$id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('✅ Product updated successfully!'); window.location='view_products.php';</script>";
    } else {
        echo "<p style='color:red;'>Database error: " . mysqli_error($conn) . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body style="font-family:'Poppins',sans-serif; background-color:#f8f9fa; text-align:center; margin:0;">

  <div style="background-color:#F4C430; padding:15px;">
    <h2 style="margin:0;">✏️ Edit Product</h2>
  </div>

  <div style="margin-top:40px;">
    <form action="" method="POST" enctype="multipart/form-data" style="display:inline-block; background:white; padding:25px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); width:350px; text-align:left;">
      
      <label>Product Name:</label><br>
      <input type="text" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required style="width:100%; padding:10px; margin-bottom:10px;"><br>
      
      <label>Description:</label><br>
      <textarea name="description" required style="width:100%; padding:10px; height:80px; margin-bottom:10px;"><?php echo htmlspecialchars($product['description']); ?></textarea><br>
      
      <label>Price (₱):</label><br>
      <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required style="width:100%; padding:10px; margin-bottom:10px;"><br>
      
      <label>Image:</label><br>
      <?php if (!empty($product['image'])): ?>
        <img src="../<?php echo $product['image']; ?>" width="100" style="border-radius:6px; margin-bottom:5px;"><br>
      <?php endif; ?>
      <input type="file" name="image" accept="image/*" style="margin-bottom:15px;"><br>

      <button type="submit" style="background-color:#F4C430; border:none; color:black; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:500;">
        Update Product
      </button>
    </form>
  </div>

  <p style="margin-top:20px;">
    <a href="view_products.php" style="text-decoration:none; color:#ff5a5f;">← Back to Products</a>
  </p>

</body>
</html>
