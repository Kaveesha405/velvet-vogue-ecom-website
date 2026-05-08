<?php
$pageTitle = "Login";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    header("Location: HomePage.php");
    exit;
}

if (!isset($conn)) {
    include '../common/db_connect.php';
}

$error = '';

// Process login form BEFORE including header
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check if user exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            
            // Transfer session cart to database
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id => $item) {
                    // Extract quantity from the cart item array
                    $quantity = is_array($item) ? $item['quantity'] : $item;
                    
                    $check_sql = "SELECT * FROM cart WHERE user_id = {$user['id']} AND product_id = $product_id";
                    $check_result = $conn->query($check_sql);
                    
                    if ($check_result->num_rows > 0) {
                        $cart_item = $check_result->fetch_assoc();
                        $new_quantity = $cart_item['quantity'] + $quantity;
                        $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE user_id = {$user['id']} AND product_id = $product_id";
                        $conn->query($update_sql);
                    } else {
                        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ({$user['id']}, $product_id, $quantity)";
                        $conn->query($insert_sql);
                    }
                }
            }
            
            // Clear session cart and load from database
            $_SESSION['cart'] = [];
            $cart_sql = "SELECT c.product_id, c.quantity, p.name, p.price, p.image_url 
                        FROM cart c 
                        JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = {$user['id']}";
            $cart_result = $conn->query($cart_sql);
            
            while ($cart_item = $cart_result->fetch_assoc()) {
                $_SESSION['cart'][$cart_item['product_id']] = [
                    'id' => $cart_item['product_id'],
                    'name' => $cart_item['name'],
                    'price' => $cart_item['price'],
                    'image' => $cart_item['image_url'],
                    'quantity' => $cart_item['quantity']
                ];
            }
            
            header("Location: profile.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
}

include '../common/header.php';
?>

    <!-- Login Page -->
    <div class="login-page-wrapper">
        <div class="login-container">
            <!-- Left Side -->
            <div class="login-left">
                <h2>Welcome Back!</h2>
                <p>Sign in to your account to track orders and access exclusive deals</p>
                <ul class="login-benefits">
                    <li>Track your orders</li>
                    <li>Saved addresses</li>
                    <li>Exclusive offers</li>
                    <li>Fast checkout</li>
                </ul>
            </div>

            <!-- Right Side -->
            <div class="login-right">
                <h3>Sign In</h3>
                <p class="login-subtitle">Enter your credentials below</p>

                <?php if ($error): ?>
                    <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #dc2626;">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form class="login-form" method="POST">
                    <div class="form-group-login">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>

                    <div class="form-group-login">
                        <label for="password">Password</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" placeholder="Enter your password" required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">Sign In</button>

                    <p class="login-register-link">
                        Don't have an account? <a href="RegisterPage.php">Create one here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.toggle-password');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.classList.add('active');
            } else {
                passwordInput.type = 'password';
                toggleBtn.classList.remove('active');
            }
        }
    </script>

<?php
include '../common/footer.php';
?>



