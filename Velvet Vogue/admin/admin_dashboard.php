<?php
include '../common/db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Get statistics (UPDATED: exclude admin_users from user count)
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total_amount) as sum FROM orders")->fetch_assoc()['sum'] ?? 0;

// Get recent orders (UPDATED: include guest order information)
$recent_orders_result = $conn->query("
    SELECT o.*, u.fullname as user_fullname
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC 
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Velvet Vogue</title>
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
                <a href="admin_dashboard.php" class="admin-menu-link active">📊 Dashboard</a>
                <a href="admin_products.php" class="admin-menu-link">📦 Products</a>
                <a href="admin_orders.php" class="admin-menu-link">🛒 Orders</a>
                <a href="admin_users.php" class="admin-menu-link">👥 Users</a>
                <a href="admin_feedback.php" class="admin-menu-link">⭐ Feedback</a>
                <a href="admin_logout.php" class="admin-menu-link admin-logout-link">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">Dashboard</h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="admin-cards">
                <div class="admin-card">
                    <div class="admin-card-label">Total Customers</div>
                    <div class="admin-card-value"><?php echo $total_users; ?></div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-label">Total Products</div>
                    <div class="admin-card-value"><?php echo $total_products; ?></div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-label">Total Orders</div>
                    <div class="admin-card-value"><?php echo $total_orders; ?></div>
                </div>
                <div class="admin-card">
                    <div class="admin-card-label">Total Revenue</div>
                    <div class="admin-card-value">LKR <?php echo number_format($total_revenue); ?></div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="admin-section">
                <h2 class="admin-section-title">Recent Orders</h2>
                <?php if ($recent_orders_result && $recent_orders_result->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $recent_orders_result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td>
                                <?php
                                // UPDATED: Handle guest orders
                                if ($order['user_id'] === null || $order['user_id'] == 0) {
                                    // Guest order
                                    echo '<span style="color: #6b7280;">👤 Guest: ' . htmlspecialchars($order['guest_name'] ?? 'Unknown') . '</span>';
                                } else {
                                    // Registered user order
                                    echo htmlspecialchars($order['user_fullname'] ?? 'Unknown User');
                                }
                                ?>
                            </td>
                            <td>LKR <?php echo number_format($order['total_amount']); ?></td>
                            <td><strong><?php echo ucfirst($order['status']); ?></strong></td>
                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            <td>
                                <a href="admin_orders.php" class="admin-action-btn">View</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <p style="text-align: center; padding: 2rem; color: #6b7280;">No orders yet.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>




