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
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_id = $_SESSION['user_id'];

// Handle order placement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    // Update user info if form fields are set
    $fields = ['full_name', 'email', 'phone_number', 'street', 'city', 'province', 'zip_code', 'country'];
    $updates = [];
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $value = $conn->real_escape_string($_POST[$field]);
            $updates[] = "$field = '$value'";
        }
    }
    if (!empty($updates)) {
        $update_sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = $user_id";
        $conn->query($update_sql);
    }

    // Fetch cart items for this user
    $cart_sql = "SELECT cart.product_id, cart.quantity, products.price
                 FROM cart
                 JOIN products ON cart.product_id = products.id
                 WHERE cart.user_id = $user_id";
    $cart_result = $conn->query($cart_sql);

    $cart_items = [];
    $total = 0;
    if ($cart_result) {
        while ($row = $cart_result->fetch_assoc()) {
            $cart_items[] = $row;
            $total += $row['price'] * $row['quantity'];
        }
    }

    // Prepare address string
    $address = '';
    $user_sql = "SELECT street, city, province, zip_code, country FROM users WHERE id = $user_id";
    $user_result = $conn->query($user_sql);
    if ($user_result && $user_result->num_rows > 0) {
        $user_info = $user_result->fetch_assoc();
        $address = $user_info['street'] . ', ' . $user_info['city'] . ', ' . $user_info['province'] . ', ' . $user_info['zip_code'] . ', ' . $user_info['country'];
    }

    $payment_method = $conn->real_escape_string($_POST['payment_method']);

    // Insert into transactions table
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, total, address, payment_method) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $user_id, $total, $address, $payment_method);
    $stmt->execute();
    $transaction_id = $stmt->insert_id;
    $stmt->close();

    // Insert each cart item into transaction_items
    $stmt = $conn->prepare("INSERT INTO transaction_items (transaction_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach ($cart_items as $item) {
        $stmt->bind_param("iii", $transaction_id, $item['product_id'], $item['quantity']);
        $stmt->execute();
    }
    $stmt->close();

    // Clear the cart for this user
    $conn->query("DELETE FROM cart WHERE user_id = $user_id");

    // Redirect to welcome.php with a success flag
    header("Location: welcome.php?order=success");
    exit;
}

// Fetch cart items
$sql = "SELECT products.name, products.price, products.image_url, cart.quantity
        FROM cart
        JOIN products ON cart.product_id = products.id
        WHERE cart.user_id = $user_id";
$result = $conn->query($sql);

$cart_items = [];
$total = 0;
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total += $row['price'] * $row['quantity'];
    }
}

// Fetch user info from database
$user_info = [
    'full_name' => '',
    'email' => '',
    'phone_number' => '',
    'street' => '',
    'city' => '',
    'province' => '',
    'zip_code' => '',
    'country' => ''
];
$user_sql = "SELECT full_name, email, phone_number, street, city, province, zip_code, country FROM users WHERE id = $user_id";
$user_result = $conn->query($user_sql);
if ($user_result && $user_result->num_rows > 0) {
    $user_info = $user_result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Kerin's Creations</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <style>
        /* RESET STYLES */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

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

        /* CHECKOUT PAGE */
        .checkout-container {
            padding: 3rem 2rem;
            background-color: #f9f9f9;
            max-width: 750px;        /* Set max width */
            width: 100%;             /* Responsive */
            margin: 3rem auto;       /* Center horizontally */
            border-radius: 12px;     /* Optional: rounded corners */
            box-shadow: 0 4px 24px rgba(0,0,0,0.08); /* Optional: subtle shadow */
        }

        .checkout-form {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .form-section {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-section h2 {
            font-size: 1.5rem;
            color: #941a34;
            margin-bottom: 1rem;
        }

        .form-section input,
        .form-section select {
            width: 100%;
            padding: 10px;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-section textarea {
            width: 100%;
            padding: 10px;
            height: 100px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .payment-method {
            display: flex;
            gap: 1.5rem;
        }

        .payment-method input {
            width: auto;
        }

        .order-summary {
            background-color: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .order-summary h3 {
            font-size: 1.5rem;
            color: #941a34;
            margin-bottom: 1rem;
        }

        .order-summary ul {
            list-style: none;
            margin-bottom: 1rem;
        }

        .order-summary ul li {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            font-size: 1.1rem;
            color: #7a1c33;
        }

        .total-price {
            font-weight: bold;
            color: #941a34;
            font-size: 1.3rem;
            margin-top: 1rem;
        }

        .place-order-btn {
            background-color: #f572d4;
            color: #fff;
            padding: 12px 25px;
            font-size: 1.2rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(229, 57, 53, 0.15);
            margin-top: 1rem;
            width: 100%;
        }

        .place-order-btn:hover {
            background-color: #972e92;
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 16px rgba(229, 57, 53, 0.25);
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
            .checkout-form {
                gap: 1rem;
            }

            .payment-method {
                flex-direction: column;
            }

            .place-order-btn {
                width: 100%;
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

    <!-- Checkout Section -->
    <section class="checkout-container">
        <form class="checkout-form" method="POST" action="">
            <div class="checkout-form">
                <!-- Combined Customer & Shipping Info Section -->
                <div class="form-section">
                    <h2>Customer & Shipping Information</h2>
                    <label><strong>Full Name:</strong>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($user_info['full_name']) ?>" required>
                    </label>
                    <label><strong>Email Address:</strong>
                        <input type="email" name="email" value="<?= htmlspecialchars($user_info['email']) ?>" required>
                    </label>
                    <label><strong>Phone Number:</strong>
                        <input type="text" name="phone_number" value="<?= htmlspecialchars($user_info['phone_number']) ?>" required>
                    </label>
                    <label><strong>Street Address:</strong>
                        <input type="text" name="street" value="<?= htmlspecialchars($user_info['street']) ?>" required>
                    </label>
                    <label><strong>City:</strong>
                        <input type="text" name="city" value="<?= htmlspecialchars($user_info['city']) ?>" required>
                    </label>
                    <label><strong>State/Province/Region:</strong>
                        <input type="text" name="province" value="<?= htmlspecialchars($user_info['province']) ?>" required>
                    </label>
                    <label><strong>Postal / ZIP Code:</strong>
                        <input type="text" name="zip_code" value="<?= htmlspecialchars($user_info['zip_code']) ?>" required>
                    </label>
                    <label><strong>Country:</strong>
                        <input type="text" name="country" value="<?= htmlspecialchars($user_info['country']) ?>" required>
                    </label>
                </div>

                <!-- Order Summary Section with Images -->
                <div class="order-summary">
                    <h3>Order Summary</h3>
                    <ul style="padding:0;">
                        <?php if (empty($cart_items)): ?>
                            <li>Your cart is empty.</li>
                        <?php else: ?>
                            <?php foreach ($cart_items as $item): ?>
                                <li style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;">
                                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                                    <div style="flex:1;">
                                        <span style="font-weight:600;"><?= htmlspecialchars($item['name']) ?></span>
                                        <span style="margin-left:10px;color:#7a1c33;">x <?= $item['quantity'] ?></span>
                                    </div>
                                    <span style="font-weight:600;">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></span>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Payment Method Section -->
                <div class="form-section" style="margin-top:2rem;">
                    <h2>Payment Method</h2>
                    <div class="payment-method">
                        <label>
                            <input type="radio" name="payment_method" value="credit_card" checked>
                            Credit Card
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="gcash">
                            Gcash
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="cod">
                            Cash on Delivery (COD)
                        </label>
                    </div>
                    <!-- Move total price and button outside .payment-method for full width -->
                    <div class="total-price" style="margin-top:2rem;">
                        <span>Total: ₱<?= number_format($total, 2) ?></span>
                    </div>
                    <?php if (!empty($cart_items)): ?>
                        <button type="submit" class="place-order-btn" style="width:100%;margin-top:1.5rem;">Place Order</button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
    </footer>


</body>
</html>
