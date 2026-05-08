<?php
include '../common/db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Handle order status update
if (isset($_POST['update_status'])) {
    $order_id = $conn->real_escape_string($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $sql = "UPDATE orders SET status = '$status' WHERE id = '$order_id'";
    if ($conn->query($sql)) {
        header("Location: admin_orders.php?updated=1");
        exit;
    }
}

// Get all orders with user information (UPDATED: include guest information)
$orders_result = $conn->query("
    SELECT o.*, u.fullname as user_fullname, u.email as user_email
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.order_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin Panel</title>
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
                <h1 class="admin-title">Manage Orders</h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>

            <?php if (isset($_GET['updated'])): ?>
                <div style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #10b981;">
                    Order status updated successfully!
                </div>
            <?php endif; ?>

            <div class="admin-section">
                <h2 class="admin-section-title">All Orders</h2>
                
                <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($order = $orders_result->fetch_assoc()): ?>
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
                            <td>
                                <?php
                                // UPDATED: Show guest email or user email
                                if ($order['user_id'] === null || $order['user_id'] == 0) {
                                    echo htmlspecialchars($order['guest_email'] ?? 'N/A');
                                } else {
                                    echo htmlspecialchars($order['user_email'] ?? 'N/A');
                                }
                                ?>
                            </td>
                            <td>LKR <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <select name="status" onchange="this.form.submit()" style="padding: 0.25rem; border: 1px solid #e5e7eb; border-radius: 4px;">
                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] == 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] == 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                    <input type="hidden" name="update_status" value="1">
                                </form>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($order['order_date'])); ?></td>
                            <td>
                                <a href="admin_order_details.php?id=<?php echo $order['id']; ?>" class="admin-action-btn">View Details</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="admin-empty">
                    <p>No orders found.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>




