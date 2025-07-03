<!--header & footer template-->
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "estrada_db");
$error = '';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"] ?? '';
    $password = $_POST["password"] ?? '';

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $db_username, $db_password);
        $stmt->fetch();
        if (password_verify($password, $db_password)) {
            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $db_username;

            // Fetch full user data for welcome.php
            $user_stmt = $conn->prepare("SELECT full_name, gender, birth_date, phone_number, email, street, city, province, zip_code, country, username FROM users WHERE id = ?");
            $user_stmt->bind_param("i", $id);
            $user_stmt->execute();
            $user_stmt->bind_result($full_name, $gender, $birth_date, $phone_number, $email, $street, $city, $province, $zip_code, $country, $username);
            $user_stmt->fetch();
            $_SESSION['user_data'] = [
                $full_name, $gender, $birth_date, $phone_number, $email,
                $street, $city, $province, $zip_code, $country, $username
            ];
            $user_stmt->close();

            header("Location: welcome.php");
            exit;
        }
    }
    $error = "Invalid username or password";
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Log In</title>
        <link rel="stylesheet" href="login.css">
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
                <?php else: ?>
                    <a href="login.php"><button class="nav-button">Cart</button></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>


        <!--main-->
        <main>
            <form action="login.php" method="post">
                <label>Username</label>
                <input type="text" name="username"> <br>
                <label>Password</label>
                <input type="password" name="password"> <br> <!-- fixed here -->
                <input type="submit" name="login" value="LOG IN"> <br>
                
                <a href="register.php" class="btn-link">REGISTER</a>
            </form>

            <?php if ($error): ?>
                <div style="color:red;"><?= $error ?></div>
            <?php endif; ?>
        </main>

        <!--footer-->
        <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
        </footer>
    </body>
</html>