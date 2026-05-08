<?php
$pageTitle = "Profile";
include '../common/db_connect.php';
include '../common/header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user data from database
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle profile update
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $city = $conn->real_escape_string($_POST['city']);

    $update_sql = "UPDATE users SET fullname = '$fullname', email = '$email', address = '$address', city = '$city' WHERE id = $user_id";
    
    if ($conn->query($update_sql) === TRUE) {
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        $message = "Profile updated successfully!";
        // Refresh user data
        $result = $conn->query($sql);
        $user = $result->fetch_assoc();
    } else {
        $message = "Error updating profile: " . $conn->error;
    }
}

// Handle password change
$password_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
        $pass_sql = "UPDATE users SET password = '$hashed_password' WHERE id = $user_id";
        
        if ($conn->query($pass_sql) === TRUE) {
            $password_message = "Password changed successfully!";
        } else {
            $password_message = "Error changing password: " . $conn->error;
        }
    } else {
        $password_message = "Passwords do not match!";
    }
}
?>

    <!-- Profile Section -->
    <div class="profile-page-wrapper">
        <div class="profile-container">
            <!-- Sidebar -->
            <div class="profile-sidebar">
                <div class="profile-menu">
                    <button class="profile-menu-btn active" data-tab="user-info">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        User Info
                    </button>
                    <button class="profile-menu-btn" data-tab="orders">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        My Orders
                    </button>
                    <button class="profile-menu-btn" data-tab="change-password">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        Change Password
                    </button>
                    <a href="processes/logout.php" class="profile-menu-btn logout-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="profile-content">
                <!-- User Info Tab -->
                <div class="profile-tab active" id="user-info">
                    <h2 class="profile-title">Personal Details</h2>
                    
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?php echo $message; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fullname">Full Name</label>
                                <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                            </div>
                        </div>

                        <button type="submit" name="update_profile" class="profile-btn">Save Changes</button>
                    </form>
                </div>

                <!-- Orders Tab -->
                <div class="profile-tab" id="orders">
                    <h2 class="profile-title">My Orders</h2>
                    
                    <?php
                    // Fetch user's orders
                    $orders_sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
                    $orders_result = $conn->query($orders_sql);
                    $orders = [];
                    
                    while ($order = $orders_result->fetch_assoc()) {
                        $orders[] = $order;
                    }
                    ?>

                    <?php if (count($orders) > 0): ?>
                        <div class="orders-list">
                            <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-header">
                                    <div>
                                        <h3>Order #<?php echo $order['id']; ?></h3>
                                        <p class="order-date"><?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                                    </div>
                                    <div class="order-status">
                                        <span class="status-badge status-<?php echo $order['status']; ?>"><?php echo ucfirst($order['status']); ?></span>
                                    </div>
                                </div>

                                <div class="order-items">
                                    <?php
                                    // UPDATED: Now fetches size and color from order_items
                                    $items_sql = "SELECT oi.*, p.name, p.image_url 
                                                 FROM order_items oi 
                                                 JOIN products p ON oi.product_id = p.id 
                                                 WHERE oi.order_id = {$order['id']}";
                                    $items_result = $conn->query($items_sql);
                                    
                                    while ($item = $items_result->fetch_assoc()):
                                    ?>
                                    <div class="order-item-row">
                                        <img src="<?php echo htmlspecialchars($item['image_url']); ?>" 
                                             alt="<?php echo htmlspecialchars($item['name']); ?>" 
                                             class="order-item-image">
                                        <div class="order-item-info">
                                            <p><strong><?php echo htmlspecialchars($item['name']); ?></strong></p>
                                            
                                            <!-- Display Size and Color -->
                                            <?php if (!empty($item['size']) || !empty($item['color'])): ?>
                                            <div style="display: flex; gap: 0.5rem; margin: 0.5rem 0; flex-wrap: wrap;">
                                                <?php if (!empty($item['size'])): ?>
                                                    <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background-color: #f3f4f6; border-radius: 4px; font-size: 0.8rem; color: #374151;">
                                                        <strong style="color: #9333ea; margin-right: 0.25rem;">Size:</strong> 
                                                        <?php echo htmlspecialchars($item['size']); ?>
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($item['color'])): ?>
                                                    <span style="display: inline-flex; align-items: center; padding: 0.25rem 0.5rem; background-color: #f3f4f6; border-radius: 4px; font-size: 0.8rem; color: #374151;">
                                                        <strong style="color: #9333ea; margin-right: 0.25rem;">Color:</strong> 
                                                        <?php echo htmlspecialchars($item['color']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <p class="order-item-qty">Qty: <?php echo $item['quantity']; ?></p>
                                        </div>
                                        <div class="order-item-price">
                                            <p>LKR <?php echo number_format($item['price'] * $item['quantity']); ?></p>
                                        </div>
                                    </div>
                                    <?php endwhile; ?>
                                </div>

                                <div class="order-footer">
                                    <p class="order-total">Total: <strong>LKR <?php echo number_format($order['total_amount']); ?></strong></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-orders">
                            <p>You haven't placed any orders yet.</p>
                            <a href="HomePage.php" class="continue-shopping">Start Shopping</a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Change Password Tab -->
                <div class="profile-tab" id="change-password">
                    <h2 class="profile-title">Change Password</h2>
                    
                    <?php if ($password_message): ?>
                        <div class="alert alert-info"><?php echo $password_message; ?></div>
                    <?php endif; ?>

                    <form method="POST" class="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <div class="password-container">
                                    <input type="password" id="new_password" name="new_password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('new_password')">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <div class="password-container">
                                    <input type="password" id="confirm_password" name="confirm_password" required>
                                    <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="change_password" class="profile-btn">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.profile-menu-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (this.href) return; // Skip if it's a link (logout)
                e.preventDefault();
                
                const tabId = this.dataset.tab;
                
                // Remove active class from all buttons and tabs
                document.querySelectorAll('.profile-menu-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.profile-tab').forEach(tab => tab.classList.remove('active'));
                
                // Add active class to clicked button and corresponding tab
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');
            });
        });

        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleBtn = event.target.closest('.toggle-password');
            
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



