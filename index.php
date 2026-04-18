<?php
include "conn.php"; // Database connection

// Fetch all products
$sql = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$productsArr = [];
while($row = mysqli_fetch_assoc($result)) {
    $productsArr[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gugma Lokal</title>

<!-- Artistic Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">

<style>
/* RESET */
* { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --primary: #8C3B2A;
    --secondary: #F4C430;
    --accent: #6A994E;
    --bg: #fdfaf6;
    --text: #2f2f2f;
}

body { 
    font-family: 'Poppins', sans-serif; 
    background-color: var(--bg);
    color: var(--text);
    line-height: 1.6;
    background-image: url('https://www.transparenttextures.com/patterns/paper-fibers.png');
}

h1, h2, h3 {
    font-family: 'Playfair Display', serif;
}

/* KEEP NAVBAR (UNCHANGED STYLE BASE, only subtle polish) */
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


/* HERO (ARTISTIC UPGRADE) */
/* Update your HERO section */
.search-section {
    text-align: center;
    padding: 100px 20px 150px 20px; /* Increased bottom padding */
    background: 
        linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.4)),
        url('images/bg_tigbau.jpg') center/cover no-repeat;
    color: white;
    position: relative;
    margin-bottom: -50px; /* Creates overlap */
}

/* ADD THIS - Creates fade transition to next section */
.search-section::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 150px;
    background: linear-gradient(to bottom, 
        transparent 0%,
        rgba(160, 120, 80, 0.25) 30%,
        rgba(120, 80, 60, 0.15) 60%,
        #fdfaf6 100%);
    pointer-events: none;
}

.hero-title {
    font-size: 42px;
    margin-bottom: 10px;
}

.hero-subtitle {
    margin-bottom: 25px;
    color: #eee;
}

.search-box {
    display: flex;
    max-width: 520px;
    margin: auto;
    border-radius: 14px;
    overflow: hidden;
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(10px);
}

.search-box input {
    flex: 1;
    padding: 15px;
    border: none;
    outline: none;
}

.search-box button {
    background: var(--primary);
    color: white;
    border: none;
    padding: 0 25px;
}

#exit-search {
    display: none;
    margin-top: 15px;
}

/* PRODUCTS (ART GALLERY STYLE) */
#products-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 30px;
    padding: 30px 5% 50px 5%;
    max-width: 1200px;
    margin: auto;
    position: relative;
    background: var(--bg); /* Matches your background color */
    z-index: 2;
}

.product-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: 0.4s ease;
    position: relative;
}

.product-card:hover {
    transform: translateY(-10px) scale(1.02);
}

.product-card img {
    width: 100%;
    height: 220px;
    object-fit: cover;
    filter: contrast(1.05) saturate(1.1);
}

.product-card::after {
    content: "View Piece";
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: rgba(0,0,0,0.6);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    opacity: 0;
    transition: 0.3s;
}

.product-card:hover::after {
    opacity: 1;
}

.product-card h3 { 
    padding: 12px; 
}

.product-card p { 
    padding: 0 12px 12px; 
    color: #666;
}

.price { 
    color: var(--primary);
    font-weight: bold;
}

/* STORY SECTION */
.frames-section {
    padding: 60px 20px;
    display: flex;
    flex-direction: column;
    gap: 30px;
    background: var(--bg);
    position: relative;
    margin-top: -30px;
}

.frame {
    display: flex;
    gap: 25px;
    background: #fffdf9;
    padding: 20px;
    border-left: 6px solid var(--primary);
    max-width: 900px;
    margin: auto;
}

.frame img {
    width: 200px;
    height: 160px;
    object-fit: cover;
    border-radius: 10px;
}

.frame h3 {
    margin-bottom: 8px;
}

/* MOBILE */
@media screen and (max-width: 768px) {
    .frame {
        flex-direction: column;
        text-align: center;
    }

    .frame img {
        width: 100%;
    }
}
</style>
</head>
<body>

<!-- NAVBAR (UNCHANGED STRUCTURE) -->
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

<!-- HERO -->
<div class="search-section">
    <h1 class="hero-title">Discover Local Artistry</h1>
    <p class="hero-subtitle">Handcrafted pieces made with culture, passion, and identity</p>

    <div class="search-box">
        <input id="search-input" type="text" placeholder="Search handcrafted items...">
        <button id="search-button">Search</button>
    </div>
    <button id="exit-search" onclick="exitSearch()">Exit Search</button>
</div>

<!-- PRODUCTS -->
<div id="search-results">
  <div id="products-container"></div>
</div>

<!-- STORY SECTION -->
<div class="frames-section">
  <div class="frame">
    <img src="images/p1.jpg">
    <div>
      <h3>Handcrafted with Love</h3>
      <p>Each product is created by local artisans using traditional Filipino techniques.</p>
    </div>
  </div>

  <div class="frame">
    <img src="images/p2.jpg">
    <div>
      <h3>Culture in Every Detail</h3>
      <p>Every design reflects heritage, identity, and creativity of local communities.</p>
    </div>
  </div>

  <div class="frame">
    <img src="images/p3.jpg">
    <div>
      <h3>Support Local Makers</h3>
      <p>Your purchase directly empowers small artists and local businesses.</p>
    </div>
  </div>
</div>

<script>
const allProducts = <?php echo json_encode($productsArr); ?>;

const searchInput = document.getElementById('search-input');
const searchButton = document.getElementById('search-button');
const productsContainer = document.getElementById('products-container');
const exitButton = document.getElementById('exit-search');

function performSearch() {
    const query = searchInput.value.toLowerCase().trim();
    productsContainer.innerHTML = '';

    const filtered = allProducts.filter(p => p.product_name.toLowerCase().includes(query));

    if(filtered.length === 0){
        productsContainer.innerHTML = '<p style="text-align:center;">No products found.</p>';
    } else {
        filtered.forEach(p => {
            productsContainer.innerHTML += `
                <div class="product-card">
                    <a href="product_details.php?id=${p.product_id}" style="text-decoration:none; color:inherit;">
                        ${p.image ? `<img src="${p.image}">` : ''}
                    </a>
                    <h3>${p.product_name}</h3>
                    <p>${p.description}</p>
                    <p class="price">₱${parseFloat(p.price).toFixed(2)}</p>
                </div>
            `;
        });
    }

    exitButton.style.display = 'inline-block';
}

searchButton.addEventListener('click', performSearch);
searchInput.addEventListener('keydown', e => { if(e.key === 'Enter') performSearch(); });

function exitSearch() {
    searchInput.value = '';
    productsContainer.innerHTML = '';
    exitButton.style.display = 'none';
}
</script>

</body>
</html>