<?php
include "../conn.php";

$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin | View Products</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body style="font-family:'Poppins',sans-serif; background-color:#f8f9fa; margin:0;">

  <div style="background-color:#F4C430; padding:15px; text-align:center;">
    <h2 style="margin:0;">🧑‍💼 Admin — Manage Products</h2>
  </div>

  <div style="text-align:center; margin-top:20px;">
    <a href="upload_product.php" style="background-color:#ff5a5f; color:white; padding:8px 16px; border-radius:6px; text-decoration:none;">+ Add New Product</a>
    <a href="index.php" style="margin-left:10px; text-decoration:none; color:#333;">🏠 Dashboard</a>
  </div>

  <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:15px; padding:30px;">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div style="background-color:white; width:260px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.1); text-align:center; overflow:hidden;">
          <div style="height:180px; background-color:#f4f4f4;">
            <?php if (!empty($row['image'])): ?>
              <img src="../<?php echo $row['image']; ?>" style="width:100%; height:100%; object-fit:cover;">
            <?php else: ?>
              <span style="color:#aaa; line-height:180px;">No Image</span>
            <?php endif; ?>
          </div>
          <div style="padding:15px;">
            <h3 style="margin:0;"><?php echo htmlspecialchars($row['product_name']); ?></h3>
            <p style="font-size:14px; color:#666;"><?php echo htmlspecialchars($row['description']); ?></p>
            <p style="font-weight:600; color:#ff5a5f;">₱<?php echo number_format($row['price'], 2); ?></p>
            
            <a href="edit_product.php?id=<?php echo $row['product_id']; ?>" style="background-color:#F4C430; color:black; padding:6px 12px; border-radius:6px; text-decoration:none; margin-right:5px;">✏️ Edit</a>
            <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');" style="background-color:#ff5a5f; color:white; padding:6px 12px; border-radius:6px; text-decoration:none;">🗑️ Delete</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p style="color:#6c757d;">No products found.</p>
    <?php endif; ?>
  </div>

</body>
</html>
