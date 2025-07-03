
##  Setup Instructions

1. **Install XAMPP** (or any Apache + MySQL stack) on your computer.
2. **Clone or copy** this project folder into your XAMPP `htdocs` directory:
    ```
    C:\xampp\htdocs\KERIN
    ```
3. **Start XAMPP** and ensure Apache and MySQL are running.
4. **Create the database:**
    - Go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
    - Create a database named `estrada_db`
    - Import the provided SQL file.
5. **Configure database connection** in all PHP files if your MySQL password is not empty:
    ```php
    $conn = new mysqli("localhost", "root", "", "estrada_db");
    ```
6. **Open your browser** and go to:
    ```
    http://localhost/KERIN/index.php
    ```

---

## 🔑 Login Info

- **To register:**  
  Go to the [Register page](http://localhost/KERIN/register.php) and create a new account.
- **To log in:**  
  Use your registered username and password on the [Login page](http://localhost/KERIN/login.php).

---

## 🖼️ Screenshots
- **Home Page:**  
  ![Home Page](screenshots/Homepage.jpeg)
- **About Us:**  
  ![About Us](screenshots/About us.jpeg)
- **What We Offer:**  
  ![Products](screenshots/Products.jpeg)
- **Login:**  
  ![Login](screenshots/Login.jpeg)
- **Register:**  
  ![Register](screenshots/Register.jpeg)
- **Profile Dashboard:**  
  ![Profile](screenshots/Profile.jpeg)
- **Checkout:**  
  ![Checkout](screenshots/Checkout.jpeg)

## 📄 File Descriptions

| File Name            | Description                                                                 |
|----------------------|-----------------------------------------------------------------------------|
| `index.php`          | Home page with featured products and navigation.                            |
| `about_us.php`       | About page with company info, developer intro, and contact form.            |
| `what_we_offer.php`  | Product listing page with sorting and add-to-cart functionality.            |
| `login.php`          | Login form for users. Redirects to profile/dashboard on success.            |
| `register.php`       | Registration form for new users.                                            |
| `welcome.php`        | User profile dashboard, shows user info and logout button.                  |
| `checkout.php`       | Checkout page for reviewing cart and entering shipping/payment details.     |
| `style.css`          | Main stylesheet for the website.                                            |
| `login.css`          | Styles for the login and register pages.                                    |
| `cart.php`           | (If present) Shopping cart page for logged-in users.                        |
| `logout.php`         | Logs out the current user and redirects to login or home.                   |
| `images/`            | Folder containing all images and logos used on the site.                    |
| `screenshots/`       | (Optional) Folder for screenshots for this README.                          |