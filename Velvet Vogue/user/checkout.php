<?php
$pageTitle = "Checkout";
include '../common/db_connect.php';
include '../common/header.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$user = null;

if ($isLoggedIn) {
    $user_id = $_SESSION['user_id'];
    // Get user info
    $user_sql = "SELECT * FROM users WHERE id = $user_id";
    $user_result = $conn->query($user_sql);
    $user = $user_result->fetch_assoc();
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = 500;
$total = $subtotal + $shipping;

// Handle order placement
$message = '';
$error = '';
$order_id = null;
$customer_email = '';
$submitted_fullname = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $conn->real_escape_string(trim($_POST['fullname']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $address = $conn->real_escape_string(trim($_POST['address']));
    $city = $conn->real_escape_string(trim($_POST['city']));
    $create_account = isset($_POST['create_account']) ? true : false;
    
    // Store for later use
    $submitted_fullname = $fullname;
    
    // Validate required fields
    if (empty($fullname) || empty($email) || empty($address) || empty($city)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Initialize order_user_id - will be set based on user status
        $order_user_id = null;
        
        // If logged in, update user address and use their user_id
        if ($isLoggedIn) {
            $order_user_id = $user_id;
            $update_sql = "UPDATE users SET address = '$address', city = '$city' WHERE id = $user_id";
            $conn->query($update_sql);
        }
        
        // If guest wants to create account
        if (!$isLoggedIn && $create_account) {
            // Check if email already exists
            $check_email = $conn->query("SELECT id FROM users WHERE email = '$email'");
            if ($check_email->num_rows > 0) {
                $error = "An account with this email already exists. Please <a href='login.php'>log in</a>.";
            } else {
                // Create temporary password (user will need to reset it)
                $temp_password = bin2hex(random_bytes(8));
                $hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
                
                // Create account
                $create_user = "INSERT INTO users (fullname, email, password, address, city) 
                               VALUES ('$fullname', '$email', '$hashed_password', '$address', '$city')";
                
                if ($conn->query($create_user)) {
                    $order_user_id = $conn->insert_id;
                    $_SESSION['user_id'] = $order_user_id;
                    $_SESSION['user_name'] = $fullname;
                    $isLoggedIn = true;
                    
                    // TODO: Send email with temporary password or password reset link
                } else {
                    $error = "Error creating account: " . $conn->error;
                }
            }
        }
        
        if (!$error) {
            // Create order
            if ($order_user_id !== null) {
                // Logged in user or newly created account
                $order_sql = "INSERT INTO orders (user_id, total_amount, status) 
                              VALUES ($order_user_id, $total, 'completed')";
            } else {
                // Pure guest checkout - saves address and city
                $order_sql = "INSERT INTO orders (user_id, total_amount, status, guest_name, guest_email, guest_address, guest_city) 
                              VALUES (NULL, $total, 'completed', '$fullname', '$email', '$address', '$city')";
            }
            
            if ($conn->query($order_sql) === TRUE) {
                $order_id = $conn->insert_id;
                $customer_email = $email;
                
                // Insert order items WITH SIZE AND COLOR
                $order_items_success = true;
                foreach ($_SESSION['cart'] as $cartKey => $item) {
                    $productId = $item['id'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];
                    
                    // Get size and color from cart item (may be null/empty)
                    $size = !empty($item['size']) ? $conn->real_escape_string($item['size']) : NULL;
                    $color = !empty($item['color']) ? $conn->real_escape_string($item['color']) : NULL;
                    
                    // Build SQL with size and color
                    if ($size !== NULL && $color !== NULL) {
                        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price, size, color) 
                                     VALUES ($order_id, $productId, $quantity, $price, '$size', '$color')";
                    } elseif ($size !== NULL) {
                        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price, size) 
                                     VALUES ($order_id, $productId, $quantity, $price, '$size')";
                    } elseif ($color !== NULL) {
                        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price, color) 
                                     VALUES ($order_id, $productId, $quantity, $price, '$color')";
                    } else {
                        $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                                     VALUES ($order_id, $productId, $quantity, $price)";
                    }
                    
                    if (!$conn->query($item_sql)) {
                        $order_items_success = false;
                        $error = "Error inserting order items: " . $conn->error;
                        break;
                    }
                }
                
                if ($order_items_success) {
                    // Clear cart from database if user is logged in (or just created account)
                    if ($order_user_id !== null) {
                        $conn->query("DELETE FROM cart WHERE user_id = $order_user_id");
                    }
                    
                    // Clear session cart
                    $_SESSION['cart'] = [];
                    
                    $message = "✓ Order placed successfully! Order ID: #$order_id";
                }
            } else {
                $error = "Error placing order: " . $conn->error;
            }
        }
    }
}
?>

<!-- Checkout Page -->
<section class="checkout-page">
    <div class="checkout-container">
        <h1 class="checkout-title">Checkout</h1>
        
        <?php if ($message): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
            <div class="checkout-success">
                <h2>🎉 Thank you for your purchase!</h2>
                <p class="success-subtitle">Order confirmation has been sent to:</p>
                <p class="success-email"><?php echo htmlspecialchars($customer_email); ?></p>
                
                <?php if (!$isLoggedIn): ?>
                    <div class="account-prompt">
                        <h3>📦 Want to track your order?</h3>
                        <p>Create an account to:</p>
                        <ul>
                            <li>View your order history</li>
                            <li>Track delivery status</li>
                            <li>Save addresses for faster checkout</li>
                            <li>Get exclusive member offers</li>
                        </ul>
                        <a href="RegisterPage.php?email=<?php echo urlencode($customer_email); ?>&name=<?php echo urlencode($submitted_fullname); ?>" 
                           class="create-account-btn">
                            Create Account (30 seconds)
                        </a>
                    </div>
                <?php endif; ?>
                
                <a href="HomePage.php" class="continue-shopping-btn">
                    ← Continue Shopping
                </a>
            </div>
        </section>
        <?php include '../common/footer.php'; exit; endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!$isLoggedIn): ?>
            <div class="login-prompt">
                <div class="login-prompt-content">
                    <h3>Already have an account?</h3>
                    <p>Log in for faster checkout and order tracking</p>
                </div>
                <a href="login.php?redirect=checkout.php" class="login-prompt-btn">
                    Log In
                </a>
            </div>
        <?php endif; ?>
        
        <div class="checkout-content">
            <div class="checkout-section">
                <h2><?php echo $isLoggedIn ? 'Billing Information' : 'Guest Checkout'; ?></h2>
                <form method="POST" class="checkout-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullname">Full Name *</label>
                            <input type="text" 
                                   id="fullname" 
                                   name="fullname" 
                                   value="<?php echo $user ? htmlspecialchars($user['fullname']) : ''; ?>" 
                                   <?php echo $user ? 'readonly' : 'required'; ?>>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="<?php echo $user ? htmlspecialchars($user['email']) : ''; ?>" 
                                   <?php echo $user ? 'readonly' : 'required'; ?>>
                            <small class="form-note">Order confirmation will be sent to this email</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="address">Street Address *</label>
                            <input type="text" 
                                   id="address" 
                                   name="address" 
                                   value="<?php echo $user ? htmlspecialchars($user['address'] ?? '') : ''; ?>" 
                                   required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City *</label>
                            <input type="text" 
                                   id="city" 
                                   name="city" 
                                   value="<?php echo $user ? htmlspecialchars($user['city'] ?? '') : ''; ?>" 
                                   required>
                        </div>
                    </div>

                    <?php if (!$isLoggedIn): ?>
                        <div class="create-account-option">
                            <label class="account-checkbox">
                                <input type="checkbox" name="create_account" value="1">
                                <span class="checkbox-content">
                                    <strong>Create an account</strong>
                                    <small>Get faster future checkouts and order tracking. We'll send you a password reset link to set up your account</small>
                                </span>
                            </label>
                        </div>
                    <?php endif; ?>

                    <!-- Order Summary with Size and Color Display -->
                    <div class="order-summary">
                        <h2>Order Summary</h2>
                        <div class="order-items">
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                            <div class="order-item">
                                <span>
                                    <?php echo htmlspecialchars($item['name']); ?> x <?php echo $item['quantity']; ?>
                                    <?php if (!empty($item['size']) || !empty($item['color'])): ?>
                                        <br>
                                        <small style="color: #6b7280;">
                                            <?php if (!empty($item['size'])): ?>
                                                Size: <?php echo htmlspecialchars($item['size']); ?>
                                            <?php endif; ?>
                                            <?php if (!empty($item['size']) && !empty($item['color'])): ?>
                                                 | 
                                            <?php endif; ?>
                                            <?php if (!empty($item['color'])): ?>
                                                Color: <?php echo htmlspecialchars($item['color']); ?>
                                            <?php endif; ?>
                                        </small>
                                    <?php endif; ?>
                                </span>
                                <span>LKR <?php echo number_format($item['price'] * $item['quantity']); ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-totals">
                            <div class="total-row">
                                <span>Subtotal:</span>
                                <span>LKR <?php echo number_format($subtotal); ?></span>
                            </div>
                            <div class="total-row">
                                <span>Shipping:</span>
                                <span>LKR <?php echo number_format($shipping); ?></span>
                            </div>
                            <div class="total-row grand-total">
                                <span>Total:</span>
                                <span>LKR <?php echo number_format($total); ?></span>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="place-order-btn">Place Order</button>
                    <a href="cart.php" class="back-to-cart">← Back to Cart</a>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include '../common/footer.php'; ?>



