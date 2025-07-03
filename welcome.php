<?php
session_start();
$conn = new mysqli("localhost", "root", "", "estrada_db");

$cart_count = 0;
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    // Get user id from users table
    $user_result = $conn->query("SELECT id FROM users WHERE username='$username'");
    if ($user_result && $user_row = $user_result->fetch_assoc()) {
        $user_id = $user_row['id'];
        // Now get cart count using user_id
        $result = $conn->query("SELECT SUM(quantity) AS total FROM cart WHERE user_id=$user_id");
        if ($result && $row = $result->fetch_assoc()) {
            $cart_count = (int)$row['total'];
        }
    }
}
    if (!isset($_SESSION['username']) || !isset($_SESSION['user_data'])) {
        header("Location: login.php");
        exit;
    }

    $username = htmlspecialchars($_SESSION['username']);
    $user = $_SESSION['user_data'];

    $fields = [
        'fullname', 'gender', 'dob', 'phone', 'email',
        'street', 'city', 'province', 'zip', 'country', 'username' 
    ];

    foreach ($fields as $field) {
        if (!isset($user[$field])) {
            $user[$field] = '';
        }
    }
?>

<!--header & footer template-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Kerin's Creations</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(120deg, #fce4ec 0%, #f8bbd0 100%);
            min-height: 100vh;
            margin: 0;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }
        .hero-section {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* move content to the left */
            background: #fff0f5;
            padding: 40px 0 30px 40px; /* add left padding */
            box-shadow: 0 4px 24px rgba(228, 125, 192, 0.08);
            flex-direction: row;
            gap: 0;
        }
        .hero-image {
            flex: 1;
            text-align: right;
            margin-right: 10px;
            max-width: 350px;
        }
        .hero-image img {
            max-width: 100%;
            border-radius: 30px;
            box-shadow: 0 4px 24px rgba(228, 125, 192, 0.15);
        }
        .hero-text {
            flex: 1;
            padding-left: 10px;
            max-width: none;
        }
        .hero-text h1 {
            font-size: 2.8rem;
            color: #941a34;
            margin-bottom: 10px;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.08);
        }
        .hero-text .welcome {
            font-size: 3.5rem;
            color: #7a1c33;
            margin-bottom: 18px;
            font-weight: bold;
        }
        .profile-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 24px rgba(228, 125, 192, 0.10);
            width: 500px;      /* widened from 800px */
            margin: 40px auto 60px auto;
            padding: 32px 36px;
            text-align: left;
        }
        .profile-card h2 {
            color: #941a34;
            margin-bottom: 18px;
            font-size: 2rem;
            letter-spacing: 1px;
        }
        .profile-row {
            margin-bottom: 12px;
            font-size: 1.1rem;
        }
        .profile-row strong {
            color: #7a1c33;
            width: 140px;
            display: inline-block;
        }
        footer {
            background-color: rgb(228, 125, 192);
            color: white;
            text-align: center;
            padding: 40px;
            font-size: 20px;
            margin-top: 60px;
        }
        @media (max-width: 900px) {
            .hero-section { flex-direction: column; }
            .hero-image, .hero-text { padding: 0; text-align: center !important; }
            .hero-image img { max-width: 220px; margin-bottom: 20px; }
            .profile-card { padding: 18px 8px; }
        }
    </style>
</head>
<body>
    <!--header-->
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
                    <!-- Removed the logout button here -->
                <?php else: ?>
                    <a href="login.php"><button class="nav-button">Cart</button></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-image">
            <img src="https://img.freepik.com/premium-photo/lipstick-cosmetics-skincare-model_910054-52265.jpg" alt="Model with Lipstick">
        </div>
        <div class="hero-text">
            <div class="welcome">
                HELLO, WELCOME <?= htmlspecialchars(strToUpper(substr($user[0], 0, strpos($user[0], " ")))) ?>
            </div>
            <h1>Your Profile Dashboard</h1>
            <p>Thank you for being part of Kerin's Creations! Here you can view and manage your profile details.</p>
        </div>
    </section>

    <!-- Profile Card -->
    <div class="profile-card">
        <h2>User Profile</h2>
        <div class="profile-row"><strong>Full Name:</strong> <span><?= htmlspecialchars($user[0] ?? '') ?></span></div>
        <div class="profile-row"><strong>Gender:</strong> <span><?= htmlspecialchars($user[1] ?? '') ?></span></div>
        <div class="profile-row"><strong>Date of Birth:</strong> <span><?= htmlspecialchars($user[2] ?? '') ?></span></div>
        <div class="profile-row"><strong>Phone:</strong> <span><?= htmlspecialchars($user[3] ?? '') ?></span></div>
        <div class="profile-row"><strong>Email:</strong> <span><?= htmlspecialchars($user[4] ?? '') ?></span></div>
        <div class="profile-row"><strong>Street:</strong> <span><?= htmlspecialchars($user[5] ?? '') ?></span></div>
        <div class="profile-row"><strong>City:</strong> <span><?= htmlspecialchars($user[6] ?? '') ?></span></div>
        <div class="profile-row"><strong>Province/State:</strong> <span><?= htmlspecialchars($user[7] ?? '') ?></span></div>
        <div class="profile-row"><strong>Zip Code:</strong> <span><?= htmlspecialchars($user[8] ?? '') ?></span></div>
        <div class="profile-row"><strong>Country:</strong> <span><?= htmlspecialchars($user[9] ?? '') ?></span></div>
        <div class="profile-row"><strong>Username:</strong> <span><?= htmlspecialchars($user[10] ?? '') ?></span></div>
        <div style="text-align:center; margin-top: 24px;">
            <a href="logout.php">
                <button class="nav-button" style="background-color:#ff99cc;color:#fff;width:70%;">Logout</button>
            </a>
        </div>
    </div>

    <!--footer-->
    <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
    </footer>

    <?php if (isset($_GET['order']) && $_GET['order'] === 'success'): ?>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            confetti({
                particleCount: 120,
                spread: 70,
                origin: { y: 0.6 }
            });
            if (!document.getElementById('order-success')) {
                const msg = document.createElement('div');
                msg.id = 'order-success';
                msg.textContent = 'Order placed successfully!';
                msg.style.position = 'fixed';
                msg.style.top = '30%';
                msg.style.left = '50%';
                msg.style.transform = 'translate(-50%, -50%)';
                msg.style.background = '#fff';
                msg.style.color = '#941a34';
                msg.style.padding = '2rem 3rem';
                msg.style.borderRadius = '12px';
                msg.style.boxShadow = '0 4px 24px rgba(0,0,0,0.15)';
                msg.style.fontSize = '2rem';
                msg.style.zIndex = '9999';
                document.body.appendChild(msg);
                setTimeout(() => msg.remove(), 3000);
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>