<?php
include "../conn.php"; // go one folder up since this is in /admin/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);

    // ✅ Image upload directory
    $target_dir = "../uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . uniqid() . "_" . $file_name;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // ✅ Allow image files only
    $allowed = ["jpg", "jpeg", "png", "gif"];
    if (in_array($imageFileType, $allowed)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // ✅ Save to DB (use relative path so it displays correctly)
            $db_path = "uploads/" . basename($target_file);
            $sql = "INSERT INTO products (product_name, description, price, image)
                    VALUES ('$product_name', '$description', '$price', '$db_path')";
            if (mysqli_query($conn, $sql)) {
                $message = "<p style='color:green;'>✅ Product uploaded successfully!</p>";
            } else {
                $message = "<p style='color:red;'>❌ Database error: " . mysqli_error($conn) . "</p>";
            }
        } else {
            $message = "<p style='color:red;'>❌ Failed to upload image.</p>";
        }
    } else {
        $message = "<p style='color:red;'>❌ Only JPG, JPEG, PNG & GIF files are allowed.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin | Upload Product</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body style="font-family:'Poppins',sans-serif; background-color:#f8f9fa; text-align:center; margin:0;">

  <div style="background-color:#F4C430; padding:15px; text-align:center;">
    <h2 style="margin:0;">🧑‍💼 Admin Panel — Add Product</h2>
  </div>

  <div style="margin-top:40px;">
    <?php if (!empty($message)) echo $message; ?>
    <form action="" method="POST" enctype="multipart/form-data"
          style="display:inline-block; background:white; padding:25px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); width:350px; text-align:left;">
      
      <label>Product Name:</label><br>
      <input type="text" name="product_name" required style="width:100%; padding:10px; margin-bottom:10px;"><br>
      
      <label>Description:</label><br>
      <textarea name="description" required style="width:100%; padding:10px; height:80px; margin-bottom:10px;"></textarea><br>
      
      <label>Price (₱):</label><br>
      <input type="number" step="0.01" name="price" required style="width:100%; padding:10px; margin-bottom:10px;"><br>
      
      <label>Image:</label><br>
      <input type="file" name="image" accept="image/*" required style="margin-bottom:15px;"><br>

      <button type="submit" 
              style="background-color:#F4C430; border:none; color:black; padding:10px 20px; border-radius:6px; cursor:pointer; font-weight:500;">
        Upload Product
      </button>
    </form>
  </div>

  <p style="margin-top:20px;">
    <a href="index.php" style="text-decoration:none; color:#ff5a5f;">← Back to Admin Dashboard</a>
  </p>

</body>
</html>
