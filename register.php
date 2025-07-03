<?php
session_start();
$conn = new mysqli("localhost", "root", "", "estrada_db");

$full_name = $gender = $birth_date = $number = $email = "";
$street = $city = $province = $zip = $country = "";
$username = $registered_password = $confirm_password = "";
$error = "";

function calculateAge($dob) {
    $birth_date = new DateTime($dob);
    $today = new DateTime();
    return $today->diff($birth_date)->y;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST["fullname"] ?? '';
    $gender = $_POST["gender"] ?? '';
    $birth_date = $_POST["dob"] ?? '';
    $phone_number = $_POST["number"] ?? '';
    $email = $_POST["email"] ?? '';

    $street = $_POST["street"] ?? '';
    $city = $_POST["city"] ?? '';
    $province = $_POST["province"] ?? '';
    $zip_code = $_POST["zip"] ?? '';
    $country = $_POST["country"] ?? '';

    $username = $_POST["username"] ?? '';
    $registered_password = $_POST["password"] ?? '';
    $confirm_password = $_POST["confirm_password"] ?? '';

    // Validation
    if (!preg_match("/^[a-zA-Z ]{2,50}$/", $full_name)) {
        $error = "Full name must be 2-50 letters and spaces only.";
    } elseif (empty($gender)) {
        $error = "Gender is required.";
    } elseif (!DateTime::createFromFormat('Y-m-d', $birth_date) || calculateAge($birth_date) < 18) {
        $error = "You must be at least 18 years old.";
    } elseif (!preg_match("/^09\d{9}$/", $phone_number)) {
        $error = "Phone number must start with 09 and be 11 digits.";
    } elseif (!preg_match("/^[a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$/", $email)) {
        $error = "Invalid email format.";
    } elseif (!preg_match("/^[a-zA-Z0-9\s#.,-]{5,100}$/", $street)) {
        $error = "Street must be 5–100 characters and can include # , . -";
    } elseif (!preg_match("/^[a-zA-Z ]{2,50}$/", $city)) {
        $error = "City must be 2–50 letters and spaces only.";
    } elseif (!preg_match("/^[a-zA-Z ]{2,50}$/", $province)) {
        $error = "Province/State must be 2–50 letters and spaces only.";
    } elseif (!preg_match("/^\d{4}$/", $zip_code)) {
        $error = "ZIP Code must be 4 digits.";
    } elseif (!preg_match("/^[a-zA-Z ]+$/", $country)) {
        $error = "Country must contain letters and spaces only.";
    } elseif (!preg_match("/^\w{5,20}$/", $username)) {
        $error = "Username must be 5–20 characters, letters, digits or underscore.";
    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/", $registered_password)) {
        $error = "Password must be at least 8 characters, include uppercase, lowercase, digit, and special character.";
    } elseif ($registered_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check username duplication in DB
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $error = "Username already exists.";
        }
        $stmt->close();

        // Save if no error
        if (!$error) {
            $hashed_password = password_hash($registered_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, gender, birth_date, phone_number, email, street, city, province, zip_code, country, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssss", $full_name, $gender, $birth_date, $phone_number, $email, $street, $city, $province, $zip_code, $country, $username, $hashed_password);
            $stmt->execute();
            $stmt->close();

            $_SESSION['username'] = $username;
            echo "<script>alert('Registration successful'); window.location.href='login.php';</script>";
            exit;
        }
    }

    if ($error) {
        echo "<script>alert('$error');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register</title>
        <link rel="stylesheet" href="register.css">
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

            .form-container {
                width: 80%;
                margin: 30px auto;
                background-color: #fff0f5;
                padding: 20px;
                border: 2px solid #ff99cc;
                border-radius: 10px;
                box-shadow: 2px 2px 10px #ffb3d9;
            }

            h2 {
                text-align: center;
                color: #cc397b;
            }

            label {
                display: block;
                margin-top: 10px;
                color: #cc0066;
            }

            input, select {
                width: 100%;
                padding: 8px;
                margin-top: 5px;
                border: 1px solid #ffb6c1;
                border-radius: 5px;
            }

            input[type="submit"], input[type="reset"] {
                cursor: pointer;
                margin-top: 15px;
                width: 100%;
                padding: 10px;
                border: none;
                border-radius: 5px;
                font-weight: bold;
                font-size: 16px;
            }

            /* Submit button (Register) */
            .register-btn {
                background-color:rgb(228, 125, 192);
                color: white;
            }

            .register-btn:hover {
                background-color: #e754a0;
            }

            /* Reset button (lighter pink) */
            .reset-btn {
                background-color: #ffc0d9;
                color: #b3005a;
            }

            .reset-btn:hover {
                background-color: #ffb3cc;
            }

            /* FOOTER */
            footer {
                background-color: rgb(228, 125, 192);
                color: white;
                text-align: center;
                padding: 40px;
                font-size: 20px;
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
                    <a href="logout.php"><button class="nav-button" style="background-color:#ff99cc;color:#fff;margin-left:10px;">Logout</button></a>
                <?php else: ?>
                    <a href="login.php"><button class="nav-button">Cart</button></a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

        <div class="form-container">
            <h2>Registration Form</h2>
            <form action="register.php" method="POST">
                <h3>Personal Information</h3>
                <label>Full Name:</label>
                <input type="text" name="fullname" value="<?= htmlspecialchars($full_name) ?>"required>
                    <?php if (isset($error) && strpos($error, 'Full name') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>Gender:</label>
                <select name="gender" value="<?= htmlspecialchars($gender) ?>" required>
                    <option value="">-- Select Gender --</option>
                    <option>Male</option>
                    <option>Female</option>
                    <option>Other</option>
                </select>

                <label>Date of Birth:</label>
                <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>"required>
                    <?php if (isset($error) && strpos($error, 'You must be at least 18 years old') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>Phone Number:</label>
                <input type="tel" name="number" value="<?= htmlspecialchars($number) ?>" required>
                    <?php if (isset($error) && strpos($error, 'Phone number must start with 09') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    <?php if (isset($error) && strpos($error, 'Invalid email format') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <h3>Address Details</h3>
                <label>Street:</label>
                <input type="text" name="street" value="<?= htmlspecialchars($street) ?>"required>
                    <?php if (isset($error) && strpos($error, 'Street must be 5–100 characters') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>City:</label>
                <input type="text" name="city" value="<?= htmlspecialchars($city) ?>"required>
                    <?php if (isset($error) && strpos($error, 'City must be 2–50 letters') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>Province/State:</label>
                <input type="text" name="province" value="<?= htmlspecialchars($province) ?>" required>
                    <?php if (isset($error) && strpos($error, 'Province/State must be 2–50 letters') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>ZIP Code:</label>
                <input type="text" name="zip" value="<?= htmlspecialchars($zip) ?>"required>
                    <?php if (isset($error) && strpos($error, 'ZIP Code must be 4 digits') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>Country:</label>
                <input type="text" name="country" value="<?= htmlspecialchars($country) ?>" required>
                    <?php if (isset($error) && strpos($error, 'Country must contain letters') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <h3>Account Details</h3>
                <label>Username:</label>
                <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required>
                    <?php if (isset($error) && strpos($error, 'Username already exists') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>Password:</label>
                <input type="password" name="password" value="<?= htmlspecialchars($registered_password) ?>" required>
                    <?php if (isset($error) && strpos($error, 'Password must be at least 8 characters') !== false) echo "<span style='color: red;'>$error</span>"; ?>

                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" value="<?= htmlspecialchars($confirm_password) ?>"required>
                    <?php if (isset($error) && strpos($error, 'Passwords do not match') !== false) echo "<span style='color: red;'>$error</span>"; ?>
                <!-- Submit and Reset buttons -->
                <input type="submit" name="register" value="Register" class="register-btn">
                <input type="reset" value="Reset" class="reset-btn">
            </form>
        </div>

        <!--footer-->
        <footer>
        <p>&copy; 2025 Copyright by Kerin's Creations. All rights reserved.</p>
        </footer>
    </body>
</html>