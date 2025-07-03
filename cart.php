<?php
session_start();
$conn = new mysqli("localhost", "root", "", "estrada_db");
$user_id = $_SESSION['user_id'] ?? 1;

// Cart count for header
$cart_count = 0;
$count_result = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
if ($count_result && $row = $count_result->fetch_assoc()) {
    $cart_count = $row['total'] ?? 0;
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $cart_id = intval($_POST['cart_id']);
        $quantity = max(1, intval($_POST['quantity']));
        $conn->query("UPDATE cart SET quantity=$quantity WHERE id=$cart_id AND user_id=$user_id");
    }
    if (isset($_POST['remove_item'])) {
        $cart_id = intval($_POST['cart_id']);
        $conn->query("DELETE FROM cart WHERE id=$cart_id AND user_id=$user_id");
    }
    header("Location: cart.php");
    exit();
}

// Fetch cart items
$sql = "SELECT cart.id as cart_id, products.name, products.price, products.image_url, cart.quantity
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Kerin's Creations</title>
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
            display: flex;
            flex-direction: column;
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

        /* SHOPPING CART PAGE */
        .cart-container {
            padding: 3rem 2rem;
            background-color: #f9f9f9;
        }

        .cart-items {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
        }

        .cart-item-info {
            flex-grow: 1;
        }

        .cart-item-name {
            font-size: 1.25rem;
            color: #941a34;
        }

        .cart-item-price {
            font-size: 1.1rem;
            color: #7a1c33;
        }

        .cart-item-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quantity-control button {
            background-color: #f572d4;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .remove-btn {
            background-color: #941a34;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 1rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 2rem;
        }

        .cart-total p {
            font-size: 1.5rem;
            font-weight: bold;
            color: #941a34;
        }

        .checkout-btn {
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

        .checkout-btn:hover {
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
            margin-top: auto; /* Push footer to the bottom */
        }

        /* RESPONSIVE STYLES */
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .cart-item-controls {
                flex-direction: column;
            }

            .checkout-btn {
                width: 100%;
                margin-top: 1rem;
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

    <!-- Shopping Cart Section -->
    <section class="cart-container">
        <div class="cart-items">
            <?php if (empty($cart_items)): ?>
                <p>Your cart is empty.</p>
            <?php else: ?>
                <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="Product Image">
                    <div class="cart-item-info">
                        <p class="cart-item-name"><?= htmlspecialchars($item['name']) ?></p>
                        <p class="cart-item-price">₱<?= number_format($item['price'], 2) ?></p>
                    </div>
                    <div class="cart-item-controls">
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <div class="quantity-control">
                                <button type="submit" name="update_quantity" value="1" onclick="this.form.quantity.stepDown();">-</button>
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" style="width:50px;">
                                <button type="submit" name="update_quantity" value="1" onclick="this.form.quantity.stepUp();">+</button>
                            </div>
                        </form>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
                            <button class="remove-btn" type="submit" name="remove_item">Remove</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!-- Total Price Section -->
        <div class="cart-total">
            <p>Total: ₱<?= number_format($total, 2) ?></p>
            <a href="checkout.php">
                <button class="checkout-btn" <?= empty($cart_items) ? 'disabled' : '' ?>>Proceed to Checkout</button>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
    </footer>

</body>
</html>
