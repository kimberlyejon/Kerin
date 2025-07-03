<?php
session_start();
$conn = new mysqli("localhost", "root", "", "estrada_db"); // Change to your DB credentials

// Redirect to login if not logged in and trying to add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
    $product_id = intval($_POST['product_id']);
    $quantity = max(1, intval($_POST['quantity']));
    $user_id = $_SESSION['user_id'] ?? 1; // Replace with actual user session logic

    // Insert into cart table (adjust table/column names as needed)
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
    $stmt->close();

    header("Location: what_we_offer.php");
    exit();
}

// Place this at the top of your file, after DB connection and session_start
$user_id = $_SESSION['user_id'] ?? 1;
$cart_count = 0;
$count_result = $conn->query("SELECT SUM(quantity) as total FROM cart WHERE user_id = $user_id");
if ($count_result && $row = $count_result->fetch_assoc()) {
    $cart_count = $row['total'] ?? 0;
}

// Handle sorting
$sort = $_GET['sort'] ?? 'newest';
$order_by = "date_added DESC"; // default

if ($sort === 'cheapest') {
    $order_by = "price ASC";
} elseif ($sort === 'recommended') {
    $order_by = "rating DESC";
}

// Fetch all products with sorting
$products = [];
$sql = "SELECT * FROM products ORDER BY $order_by";
$result = $conn->query($sql);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
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
    <!-- Featured Products Section -->
    <section class="featured-products">
        <!-- Sort Dropdown -->
        <form method="get" style="text-align:right; margin-bottom: 20px;" id="sortForm">
            <label for="sort">Sort options:</label>
            <select id="sort" name="sort" onchange="document.getElementById('sortForm').submit()">
                <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest</option>
                <option value="cheapest" <?= $sort === 'cheapest' ? 'selected' : '' ?>>Cheapest</option>
                <option value="recommended" <?= $sort === 'recommended' ? 'selected' : '' ?>>Most Recommended</option>
            </select>
        </form>
        <div class="product-container">
            <?php foreach ($products as $product): ?>
            <div class="product-item">
                <p>⭐ <?= $product['rating'] ?> / 5.0</p>
                <a href="product_detail.php?id=<?= $product['id'] ?>">
                    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                </a>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <p>₱<?= number_format($product['price'], 0) ?></p>
                <p>Date Added: <?= $product['date_added'] ?></p>
                <form method="post">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <input type="number" name="quantity" value="1" min="1" style="width:50px;">
                    <button type="submit" name="add_to_cart">Add to Cart</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
    </footer>

</body>
</html>
