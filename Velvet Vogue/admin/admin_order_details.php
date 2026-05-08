<?php
include '../common/db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Get order ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: admin_orders.php");
    exit;
}

$order_id = $conn->real_escape_string($_GET['id']);

// Get order details with user information (UPDATED: include guest information)
$order_query = $conn->query("
    SELECT o.*, u.fullname as user_fullname, u.email as user_email, u.address as user_address, u.city as user_city
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    WHERE o.id = '$order_id'
");

if (!$order_query) {
    die("Database error: " . $conn->error);
}

if ($order_query->num_rows === 0) {
    header("Location: admin_orders.php");
    exit;
}

$order = $order_query->fetch_assoc();

// Get order items with product details
$order_items_query = $conn->query("
    SELECT oi.*, p.name as product_name, p.price as unit_price 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = '$order_id'
");

if (!$order_items_query) {
    die("Database error: " . $conn->error);
}

// UPDATED: Determine if this is a guest or registered user order
$is_guest = ($order['user_id'] === null || $order['user_id'] == 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <img src="../assets/images/1.png" alt="Velvet Vogue Logo" class="admin-logo-image">
            </div>
            <nav class="admin-menu">
                <a href="admin_dashboard.php" class="admin-menu-link">📊 Dashboard</a>
                <a href="admin_products.php" class="admin-menu-link">📦 Products</a>
                <a href="admin_orders.php" class="admin-menu-link active">🛒 Orders</a>
                <a href="admin_users.php" class="admin-menu-link">👥 Users</a>
                <a href="admin_feedback.php" class="admin-menu-link">⭐ Feedback</a>
                <a href="admin_logout.php" class="admin-menu-link admin-logout-link">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">
                    Order Details
                    <?php if ($is_guest): ?>
                        <span class="guest-badge">👤 Guest Order</span>
                    <?php endif; ?>
                </h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>

            <a href="admin_orders.php" class="back-btn">
                ← Back to Orders
            </a>

            <div class="order-details-container">
                <!-- Order Information -->
                <div class="order-info-card">
                    <h3>Order Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Order ID:</strong> #<?php echo $order['id']; ?>
                        </div>
                        <div class="info-item">
                            <strong>Order Date:</strong> <?php echo date('M d, Y g:i A', strtotime($order['order_date'])); ?>
                        </div>
                        <div class="info-item">
                            <strong>Status:</strong> 
                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <strong>Total Amount:</strong> LKR <?php echo number_format($order['total_amount'], 2); ?>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="customer-info-card">
                    <h3>
                        Customer Information 
                        <?php if ($is_guest): ?>
                            <span style="font-size: 0.85rem; color: #92400e;">(Guest Checkout)</span>
                        <?php endif; ?>
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <strong>Name:</strong> 
                            <?php 
                            if ($is_guest) {
                                echo htmlspecialchars($order['guest_name'] ?? 'N/A');
                            } else {
                                echo htmlspecialchars($order['user_fullname'] ?? 'N/A');
                            }
                            ?>
                        </div>
                        <div class="info-item">
                            <strong>Email:</strong> 
                            <?php 
                            if ($is_guest) {
                                echo htmlspecialchars($order['guest_email'] ?? 'N/A');
                            } else {
                                echo htmlspecialchars($order['user_email'] ?? 'N/A');
                            }
                            ?>
                        </div>
                        <div class="info-item">
                            <strong>Address:</strong> 
                            <?php 
                            if ($is_guest) {
                                $guest_address = htmlspecialchars($order['guest_address'] ?? 'Not provided');
                                $guest_city = htmlspecialchars($order['guest_city'] ?? '');
                                if ($guest_address !== 'Not provided' && $guest_city) {
                                    echo $guest_address . ', ' . $guest_city;
                                } elseif ($guest_address !== 'Not provided') {
                                    echo $guest_address;
                                } else {
                                    echo 'Not provided';
                                }
                            } else {
                                $user_address = htmlspecialchars($order['user_address'] ?? 'Not provided');
                                $user_city = htmlspecialchars($order['user_city'] ?? '');
                                if ($user_address !== 'Not provided' && $user_city) {
                                    echo $user_address . ', ' . $user_city;
                                } elseif ($user_address !== 'Not provided') {
                                    echo $user_address;
                                } else {
                                    echo 'Not provided';
                                }
                            }
                            ?>
                        </div>
                        <?php if (!$is_guest): ?>
                        <div class="info-item">
                            <strong>Customer Type:</strong> 
                            <span style="color: #059669; font-weight: 600;">Registered User</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="admin-section">
                <h2 class="admin-section-title">Order Items</h2>
                
                <?php if ($order_items_query && $order_items_query->num_rows > 0): ?>
                <table class="order-items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Size</th>
                            <th>Colour</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($item = $order_items_query->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['product_name'] ?? 'Unknown Product'); ?></td>
                            <td>LKR <?php echo number_format($item['unit_price'], 2); ?></td>
                            <td><?php echo $item['quantity']; ?></td>
                            <td><?php echo htmlspecialchars($item['size'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($item['color'] ?? 'N/A'); ?></td>
                            <td>LKR <?php echo number_format($item['quantity'] * $item['unit_price'], 2); ?></td>
                        </tr>
                        <?php endwhile; ?>
                        <tr class="total-row">
                            <td colspan="5" style="text-align: right; font-weight: bold;">Grand Total:</td>
                            <td style="font-weight: bold;">LKR <?php echo number_format($order['total_amount'], 2); ?></td>
                        </tr>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="admin-empty">
                    <p>No items found for this order.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>




