<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gugma Lokal | Contact Us</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #fdfaf6;
            color: #2f2f2f;
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
            background: white;
            color: #333;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            margin: 4px;
            display: inline-block;
            transition: 0.3s ease;
        }

        .top-nav a:hover {
            background: #ff5a5f;
            color: white;
        }

        .top-nav .center-links {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .top-nav .center-links a.active {
            background: #ff5a5f;
            color: white;
        }

        /* HERO */
        .contact-hero {
            text-align: center;
            padding: 90px 20px;
            background:
                linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)),
                url('images/tourism_group.jpeg') center/cover no-repeat;
            color: white;
        }

        .contact-hero h1 {
            font-size: 42px;
        }

        .contact-hero p {
            margin-top: 10px;
            opacity: 0.9;
        }

        /* CONTAINER */
        .contact-container {
            display: flex;
            gap: 40px;
            max-width: 1100px;
            margin: 60px auto;
            padding: 0 20px;
        }

        /* FORM */
        .contact-form {
            flex: 1;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .contact-form h2 {
            margin-bottom: 20px;
            color: #8C3B2A;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
        }

        .contact-form button {
            width: 100%;
            padding: 12px;
            background: #8C3B2A;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .contact-form button:hover {
            background: #6f2d20;
        }

        /* INFO */
        .contact-info {
            flex: 1;
        }

        .info-box {
            background: white;
            padding: 20px;
            margin-bottom: 15px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .info-box h3 {
            color: #8C3B2A;
            margin-bottom: 5px;
        }

        /* MAP */
        .map {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .map iframe {
            width: 100%;
            height: 300px;
            border-radius: 12px;
            border: none;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .contact-container {
                flex-direction: column;
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
        <a href="aboutus.php">About Us</a>
        <a href="gallery.php">Gallery</a>
        <a href="contact.php" class="active">Contact</a>
    </div>
</div>

<!-- HERO -->
<section class="contact-hero">
    <h1>Contact Us</h1>
    <p>We’d love to hear from you. Reach out anytime.</p>
</section>

<!-- CONTACT SECTION -->
<section class="contact-container">

    <!-- FORM -->
    <div class="contact-form">
        <h2>Send Message</h2>

        <form>
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <input type="text" placeholder="Subject">
            <textarea rows="5" placeholder="Your Message"></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>

    <!-- INFO -->
    <div class="contact-info">

        <div class="info-box">
            <h3>📍 Location</h3>
            <p>Tigbauan, Iloilo, Philippines</p>
        </div>

        <div class="info-box">
            <h3>📞 Phone</h3>
            <p>+63 9XX XXX XXXX</p>
        </div>

        <div class="info-box">
            <h3>📧 Email</h3>
            <p>support@gugmalokal.com</p>
        </div>

    </div>

</section>

<!-- MAP -->
<section class="map">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62723.71631641037!2d122.37695785000001!3d10.71656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33aef7c9f554fbc3%3A0xb54e70862e22e0f6!2sTigbauan%2C%20Iloilo!5e0!3m2!1sen!2sph!4v1776503799058!5m2!1sen!2sph" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>

</body>
</html>