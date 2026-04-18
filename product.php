<?php
include "conn.php"; // ✅ Make sure this connects to your database

$search_query = '';
if(isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM products WHERE product_name LIKE '%$search_query%' OR description LIKE '%$search_query%' ORDER BY created_at DESC";
} else {
    $sql = "SELECT * FROM products ORDER BY created_at DESC";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Menu | The Fry Project</title>
  <style>
    /* Global box-sizing */
    /* Global Reset & Typography */
/* Reset & Base Styles */
* { box-sizing: border-box; margin: 0; padding: 0; }
body { 
    font-family: 'Poppins', sans-serif; 
    background-color: #f8f9fa; 
    color: #333;
    line-height: 1.6;
}

/* 🔹 Top Navigation (Consistent with Page 1) */
.top-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #F4C430;
    padding: 15px 5%;
    position: sticky; 
    top: 0;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.top-nav .left, .top-nav .right {
    position: absolute;
}
.top-nav .left { left: 20px; }
.top-nav .right { right: 20px; }

.top-nav a {
    text-decoration: none;
    background-color: white;
    color: #333;
    padding: 10px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin: 4px;
    display: inline-block;
}

.top-nav a:hover { 
    background-color: #ff5a5f; 
    color: white; 
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 90, 95, 0.3);
}

.top-nav .center-links {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    justify-content: center;
}

.top-nav .center-links a.active {
    background-color: #ff5a5f;
    color: white;
}

/* 🔍 Search Section (Consistent Gradient Header) */
.search-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 40px 20px;
    background: linear-gradient(180deg, #F4C430 0%, #f8f9fa 100%);
}

form.search-form {
    display: flex;
    gap: 0; 
    justify-content: center;
    width: 100%;
    max-width: 500px;
    filter: drop-shadow(0 4px 10px rgba(0,0,0,0.08));
}

form.search-form input[type="text"] {
    flex: 1;
    padding: 15px 20px;
    border: 2px solid #fff;
    border-radius: 12px 0 0 12px;
    font-size: 15px;
    outline: none;
}

form.search-form button {
    background-color: #ff5a5f;
    color: white;
    border: none;
    padding: 0 25px;
    border-radius: 0 12px 12px 0;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

form.search-form button:hover { background-color: #e0484d; }

/* 🍟 Menu Header */
.menu-header {
    text-align: center;
    margin: 20px 0;
}
.menu-header h1 {
    font-size: 2.2rem;
    color: #222;
    font-weight: 800;
}

/* 🧊 Product Grid (Consistent Spacing) */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 25px;
    padding: 20px 5% 60px;
    max-width: 1200px;
    margin: 0 auto;
}

/* 💳 Product Card (Consistent Design) */
.product-card {
    background-color: white;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #eee;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.product-image {
    width: 100%;
    height: 200px;
    background-color: #f4f4f4;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-info {
    padding: 20px;
    text-align: center;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.product-info h3 { 
    margin-bottom: 8px; 
    font-size: 18px; 
    color: #222; 
}

.product-info p.description { 
    font-size: 14px; 
    color: #777; 
    margin-bottom: 15px;
    min-height: 42px;
}

.product-info p.price { 
    font-size: 1.2rem;
    font-weight: 700; 
    color: #ff5a5f; 
    margin-bottom: 15px;
}

/* 🛒 Add to Cart Button (Consistent Style) */
form.add-to-cart button {
    background-color: #F4C430;
    color: #000;
    border: none;
    padding: 12px;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
    width: 100%;
}

form.add-to-cart button:hover {
    background-color: #ff5a5f;
    color: white;
}

/* Mobile Adjustments */
@media screen and (max-width: 768px) {
    .top-nav {
        padding: 15px 10px;
        flex-direction: column;
        gap: 15px;
    }
    .top-nav .left, .top-nav .right {
        position: static;
        width: 100%;
        text-align: center;
    }
    .top-nav a { width: 100%; margin: 2px 0; }
    
    form.search-form { flex-direction: column; gap: 10px; border: none; background: none; }
    form.search-form input[type="text"], form.search-form button {
        border-radius: 10px;
        width: 100%;
        padding: 12px;
    }
}
  </style>
</head>
<body>

 <!-- 🔹 Top Navigation -->
<div class="top-nav" role="navigation" aria-label="Main Navigation">
    <div class="left">
        <a href="profile.php">Profile</a>
    </div>
    <div class="right">
        <a href="add_to_cart.php">Cart</a>
    </div>
    <div class="center-links">
        <a href="index.php">Home</a>
        <a href="product.php">Products</a>
        <a href="track_order.php">Track Order</a> <!-- Added button -->
        <a href="aboutus.php">About Us</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php">Contact</a>
    </div>
</div>

<!-- 🔍 Responsive Search Bar -->
<div class="search-container">
    <form method="GET" class="search-form" role="search" aria-label="Product Search">
        <input type="text" name="search" placeholder="Search your favorite fries..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
               aria-label="Search products"
               />
        <button type="submit">Search</button>
    </form>
</div>


  <!-- 🍟 Menu Header -->
  <div class="menu-header">
    <h1>The Locals Production</h1>
    <?php if(!empty($search_query)): ?>
      <p>Showing results for: "<strong><?php echo htmlspecialchars($search_query); ?></strong>"</p>
    <?php else: ?>
      <p></p>
    <?php endif; ?>
  </div>

  <!-- 🧊 Product Grid -->
  <div class="product-grid">
    <?php if (mysqli_num_rows($result) > 0): ?>
      <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <div class="product-card">
          <a href="product_details.php?id=<?php echo $row['product_id']; ?>" aria-label="View details for <?php echo htmlspecialchars($row['product_name']); ?>">
            <div class="product-image">
              <?php if (!empty($row['image'])): ?>
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" />
              <?php else: ?>
                <span style="color:#aaa; line-height:160px;">No Image</span>
              <?php endif; ?>
            </div>
          </a>
          <div class="product-info">
            <h3><?php echo htmlspecialchars($row['product_name']); ?></h3>
            <p class="description"><?php echo htmlspecialchars($row['description']); ?></p>
            <p class="price">₱<?php echo number_format($row['price'], 2); ?></p>
            <form action="add_to_cart.php" method="POST" class="add-to-cart">
              <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>">
              <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($row['product_name']); ?>">
              <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
              <button type="submit">Add to Cart</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="no-products">No products found<?php echo (!empty($search_query)) ? " for \"".htmlspecialchars($search_query)."\"" : ""; ?>. Please check back later!</p>
    <?php endif; ?>
  </div>

</body>
</html>
