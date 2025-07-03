<?php
session_start();
$conn = new mysqli("localhost", "root", "", "estrada_db");

// Initialize cart count
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Kerin's Creations</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css"> <!-- Link to external stylesheet -->
    <style>
        /* BASE LAYOUT */
        html, body {
            height: 100%;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        /* HEADER */
        header {
            background-color: rgb(228, 125, 192);
            color: white;
            padding: 1rem 2rem;
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-text p {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .logo-icon {
            height: 2em;
            width: auto;
            vertical-align: middle;
            margin-right: 0.5em;
        }

        .header-text {
            display: flex;
            align-items: center;
        }

        .nav-tabs {
            list-style: none;
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .nav-tabs li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-tabs li a:hover,
        .nav-tabs li a.active {
            text-decoration: underline;
        }

        .nav-button {
            background-color: #f572d4;
            color: #fff;
            border: none;
            border-radius: 25px;
            padding: 10px 28px;
            font-size: 1rem;
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            margin-left: 15px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(229, 57, 53, 0.15);
            transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
        }

        .nav-button:hover {
            background: linear-gradient(90deg, #972e92 0%, #be1da9 100%);
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 16px rgba(229, 57, 53, 0.25);
        }

        /* ABOUT US PAGE */
        .about-container {
            padding: 3rem 2rem;
            background-color: #f9f9f9;
        }

        .company-info, .dev-container {
            background-color: white;
            padding: 1.2rem 1.2rem; /* reduced left/right padding */
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.2rem;   /* reduced space between containers */
        }

        .company-info h2, .contact-form h2 {
            font-size: 1.5rem;
            color: #941a34;
            margin-bottom: 1rem;
        }

        .company-info p, .contact-form p {
            font-size: 1rem;
            color: #7a1c33;
            margin-bottom: 1.5rem;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .contact-form button {
            background-color: #f572d4;
            color: #fff;
            padding: 12px 25px;
            font-size: 1.1rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(229, 57, 53, 0.15);
        }

        .contact-form button:hover {
            background-color: #972e92;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 16px rgba(229, 57, 53, 0.25);
        }

        .contact-info {
            font-size: 1.1rem;
            color: #7a1c33;
        }

        /* FOOTER */
        footer {
            background-color: rgb(228, 125, 192);
            color: white;
            text-align: center;
            padding: 40px;
            font-size: 20px;
        }

        /* RESPONSIVE STYLES */
        @media (max-width: 768px) {
            .about-container {
                padding: 2rem 1rem;
            }

            .company-info, .contact-form {
                padding: 1rem;
            }

            .company-info p, .contact-form p {
                font-size: 0.9rem;
            }
        }
    </style>
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

    <!-- About Us Section -->
    <section class="about-container" style="max-width: 700px; margin: 3rem auto; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08);">
        <!-- Company Information -->
        <div class="company-info">
            <h2>About Kerin's Creations</h2>
            <p>
                Welcome to Kerin's Creations — Where Beauty Meets Self-Care.

At Kerin's Creations, we believe that true beauty starts with confidence and self-love. Our mission is to empower individuals through high-quality makeup and skincare products that not only enhance your natural glow but also care for your skin from within.

Founded with passion and purpose, Kerin's Creations was born out of a dream to make beauty routines simple, enjoyable, and effective. Whether you're creating bold looks or embracing your bare-faced beauty, our carefully curated products are designed to suit all skin types and tones.
            </p>
            <p>
                We combine clean ingredients, innovative formulas, and artful packaging to bring you a collection that’s as expressive as it is nurturing. Every product is cruelty-free, thoughtfully crafted, and tested to meet the highest standards — because you deserve nothing less.

                Join our growing community of beauty lovers who value authenticity, quality, and the power of self-expression.
            </p>
        </div>
    </section>

    <!-- Web Developer Introduction Section -->
    <section class="dev-container" style="max-width: 700px; margin: 2rem auto 3rem auto; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); background: #fff;">
        <div style="display: flex; flex-direction: row; gap: 0; align-items: center;">
            <!-- Left: Developer Photo or Illustration -->
            <div style="flex: 1; text-align: center; padding: 2rem;">
                <img src="_11_EJON_KIM34286.jpg" alt="Web Developer" style="width: 120px; border-radius: 50%; box-shadow: 0 2px 12px rgba(228,125,192,0.15);">
                <h3 style="margin-top: 1rem; color: #941a34;">KIMBERLY EJON</h3>
                <span style="color: #941a34; font-weight: bold;">CEO and Founder</span>
            </div>
            <!-- Right: Developer Info -->
            <div style="flex: 2; padding: 2rem;">
                <h4 style="color: #7a1c33; margin-bottom: 0.5rem;">Our Expert</h4>
                <p style="color: #7a1c33;">
                    “At Kerin’s Creations, we don’t just create beauty products — we create tools of self-expression and self-care. Our goal is to empower every individual to feel confident in their skin, knowing that what they’re using is gentle, effective, and made with intention.”
                
                </p>
        
            </div>
        </div>
        <div style="display: flex; flex-direction: row; gap: 0; align-items: center;">
            <!-- Left: Developer Photo or Illustration -->
            <div style="flex: 1; text-align: center; padding: 2rem;">
                <img src="2x2 ESTRADA.JPG" alt="Web Developer" style="width: 120px; border-radius: 50%; box-shadow: 0 2px 12px rgba(228,125,192,0.15);">
                <h3 style="margin-top: 1rem; color: #941a34;">GABRIEL ESTRADA</h3>
                 <span style="color: #941a34; font-weight: bold;">Co-Founder</span>
            </div>
            <!-- Right: Developer Info -->
            <div style="flex: 2; padding: 2rem;">
                <h4 style="color: #7a1c33; margin-bottom: 0.5rem;">Our Expert</h4>
                <div style="color: #7a1c33;">
                    <p style="color: #7a1c33;">
                        “Every product we launch is a reflection of the trust our customers place in us. At Kerin’s Creations, we prioritize quality, transparency, and care—because our community deserves nothing less. Beauty is personal, and we’re honored to be part of that journey.”
                    </p>

                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form (fixed container size to match others) -->
    <section class="contact-form" style="max-width: 700px; margin: 2rem auto 3rem auto; border-radius: 12px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); background: #fff; padding: 1.2rem 1.2rem;">
        <h2>Contact Us</h2>
        <p>If you have any questions or need assistance, feel free to reach out!</p>
        <form action="#" method="post">
            <input type="text" name="name" placeholder="Your Name" required>
            <input type="email" name="email" placeholder="Your Email" required>
            <textarea name="message" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
    </footer>

</body>
</html>
