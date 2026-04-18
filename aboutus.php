<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gugma Lokal | About Us</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">

    <style>
        /* RESET */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #fdfaf6;
            color: #2f2f2f;
            line-height: 1.6;
        }

        h1, h2 {
            font-family: 'Playfair Display', serif;
        }

        /* NAVBAR */
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

        .top-nav .left,
        .top-nav .right {
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
            transition: all 0.3s ease;
            margin: 4px;
            display: inline-block;
        }

        .top-nav a:hover {
            background-color: #ff5a5f;
            color: white;
            transform: translateY(-2px);
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

        /* HERO */
        .about-hero {
            position: relative;
            text-align: center;
            padding: 120px 20px;
            color: white;
            background:
                linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                url('images/tourism_group.jpeg') center/cover no-repeat;
        }

        .about-hero h1 {
            font-size: 42px;
        }

        .about-hero p {
            margin-top: 10px;
            opacity: 0.9;
        }

        /* PURPOSE SECTION */
        .about-purpose {
            padding: 60px 20px;
        }

        .purpose-container {
            display: flex;
            align-items: center;
            gap: 40px;
            max-width: 1000px;
            margin: auto;
        }

        /* CAROUSEL */
.carousel {
    position: relative;
    flex: 1;
    overflow: hidden;
    border-radius: 12px;
    height: 300px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* TRACK */
.carousel-track {
    display: flex;
    height: 100%;
    transition: transform 0.5s ease-in-out;
}

/* IMAGES */
.carousel-track img {
    width: 100%;
    flex-shrink: 0;
    object-fit: cover;
}

/* BUTTONS */
.prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0,0,0,0.4);
    color: white;
    border: none;
    padding: 10px 14px;
    cursor: pointer;
    border-radius: 50%;
    font-size: 18px;
}

.prev { left: 10px; }
.next { right: 10px; }

.prev:hover, .next:hover {
    background: rgba(0,0,0,0.7);
}

        /* TEXT */
        .purpose-text {
            flex: 1;
        }

        .purpose-text h2 {
            color: #8C3B2A;
            margin-bottom: 15px;
        }

        /* GALLERY */
        .tourism-gallery {
            padding: 60px 10%;
            text-align: center;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }

        .gallery-grid img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            transition: 0.3s;
        }

        .gallery-grid img:hover {
            transform: scale(1.05);
        }

        .gallery-caption {
            color: #555;
        }

        /* IMPACT */
        .about-impact {
            max-width: 800px;
            margin: 60px auto;
            text-align: center;
            padding: 0 20px;
        }

        .about-impact h2 {
            color: #8C3B2A;
            margin-bottom: 15px;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .purpose-container {
                flex-direction: column;
                text-align: center;
            }

            .about-hero h1 {
                font-size: 32px;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<div class="top-nav">
    <div class="left">
        <a href="profile.php">Profile</a>
    </div>

    <div class="right">
        <a href="add_to_cart.php">Cart</a>
    </div>

    <div class="center-links">
        <a href="index.php">Home</a>
        <a href="product.php">Products</a>
        <a href="track_order.php">Track Order</a>
        <a href="aboutus.php" class="active">About Us</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php">Contact</a>
    </div>
</div>

<!-- HERO -->
<section class="about-hero">
    <h1>The Art of Locals</h1>
    <p>Connecting people to local culture, creativity, and community.</p>
</section>

<!-- PURPOSE + SLIDESHOW -->
<section class="about-purpose">
    <div class="purpose-container">

        <!-- SLIDESHOW -->
        <div class="carousel">

    <div class="carousel-track" id="track">
        <img src="images/tagatig.jpeg" alt="Tigbauan 1">
        <img src="images/tourism_group.jpeg" alt="Tigbauan 2">
        <img src="images/Solymar.webp" alt="Tigbauan 3">
        <img src="images/tigbauan_church.jpg" alt="Tigbauan 4">
    </div>

    <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
    <button class="next" onclick="moveSlide(1)">&#10095;</button>

</div>

        <!-- TEXT -->
        <div class="purpose-text">
            <h2>Our Purpose</h2>
            <p>
                Gugma Lokal was created to support and promote local businesses,
                artisans, and tourism in Tigbauan.
            </p>
            <p>
                We believe every handmade product tells a story of culture,
                passion, and identity while helping strengthen the community.
            </p>
        </div>

    </div>
</section>

<!-- GALLERY -->
<section class="tourism-gallery">
    <h2>Discover Tigbauan</h2>

    <div class="gallery-grid">
        <img src="images/Solymar.webp">
        <img src="images/tigbauan_church.jpg">
        <img src="images/Saludan_festival.jpg">
        <img src="images/sefdec.jpeg">
    </div>

    <p class="gallery-caption">
        From beaches to cultural landmarks, Tigbauan is rich in history and beauty.
    </p>
</section>

<!-- IMPACT -->
<section class="about-impact">
    <h2>Why It Matters</h2>
    <p>
        Supporting Gugma Lokal means supporting small businesses, preserving culture,
        and promoting local tourism in Tigbauan.
    </p>
</section>

<!-- SLIDESHOW SCRIPT -->
<script>
let currentIndex = 0;
const track = document.getElementById("track");
const totalSlides = track.children.length;

function updateSlide() {
    track.style.transform = `translateX(-${currentIndex * 100}%)`;
}

function moveSlide(direction) {
    currentIndex += direction;

    if (currentIndex < 0) {
        currentIndex = totalSlides - 1;
    } else if (currentIndex >= totalSlides) {
        currentIndex = 0;
    }

    updateSlide();
}

/* AUTO PLAY (optional but nice) */
setInterval(() => {
    moveSlide(1);
}, 4000);
</script>

</body>
</html>