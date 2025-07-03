<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: welcome.php");
    exit;
}
$conn = new mysqli("localhost", "root", "", "estrada_db");
$user_id = $_SESSION['user_id'] ?? 1;
$cart_count = 0;
$count_result = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
if ($count_result && $row = $count_result->fetch_assoc()) {
    $cart_count = $row['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerin's Creations</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <header>
        <nav class="navbar">
            <div class="navbar-left">
                <div class="header-text">
                    <img src="logo1.png" alt="Logo" class="logo-icon">
                <p>Kerin's Creations</p>
                </div>
            </div>

            <div class="nav-center">
                <ul class="nav-tabs">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about_us.php">About us</a></li>
                    <li><a href="what_we_offer.php">What We Offer</a></li>
                </ul>
            </div>

            <div class="navbar-right">
                <a href="login.php"><button class="nav-button">Account</button></a>
                <?php if (isset($_SESSION['username'])): ?>
                    <a href="cart.php">
                        <button class="nav-button">
                            Cart
                            <?php if ($cart_count > 0): ?>
                                <span style="background:#fff;color:#941a34;border-radius:50%;padding:2px 8px;font-weight:bold;margin-left:6px;">
                                    <?= $cart_count ?>
                                </span>
                            <?php endif; ?>
                        </button>
                    </a>
                    <a href="logout.php"><button class="nav-button" style="background-color:#ff99cc;color:#fff;margin-left:10px;">Logout</button></a>
                <?php else: ?>
                    <a href="login.php"><button class="nav-button">Cart</button></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Main Hero Section -->
    <main class="hero-section">
        <div class="hero-container">
            <div class="hero-image">
                <img src="https://img.freepik.com/premium-photo/lipstick-cosmetics-skincare-model_910054-52265.jpg" alt="Model with Lipstick">
            </div>
            <div class="hero-text">
                <h1>50% off Lipstick</h1>
                <p class="subheading">moisturizing matte lippie in</p>
                <p class="tagline">obsessed</p>
                <p class="description">iconic red. universally flattering.<br> ultra-matte. moisturizing.</p>
            </div>
        </div>
    </main>

        <!-- Featured Product Section Left -->
    <section class="featured-product">
        <div class="featured-text">
            <h2>Powder Blush</h2>
            <p>SPF Meets Makeup. Color-Adapt Second Skin Finish.<br>Powerfully Protective. Made for Lazy Days.</p>
            <button class="shop-btn" onclick="window.location.href='what_we_offer.php'">shop now</button>
        </div>
        <div class="featured-img">
            <img src="https://i.pinimg.com/originals/3a/ea/79/3aea79e8862424731bc5306ca60b340b.jpg" alt="Smart Shade Foundation">
        </div>
    </section>

    <!-- Featured Product Section Right -->
    <section class="featured-product reverse">
        <div class="featured-img">
            <img src="https://scale.coolshop-cdn.com/product-media.coolshop-cdn.com/23S4YG/c6426e9948644b15b47e850c84c04be6.jpeg/f/max-factor-2000-calorie-lip-glaze-lip-gloss-060-favorite-song.jpeg" alt="Smart Shade Foundation">
        </div>
        <div class="featured-text">
            <h2>Lip Gloss</h2>
            <p>SPF Meets Makeup. Color-Adapt Second Skin Finish.<br>Powerfully Protective. Made for Lazy Days.</p>
            <button class="shop-btn" onclick="window.location.href='what_we_offer.php'">shop now</button>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
    </footer>
</body>
</html>

