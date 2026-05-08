<?php
include '../common/db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: admin_users.php");
    exit;
}

$user_id = $conn->real_escape_string($_GET['id']);

// Get user details
$user_result = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
if (!$user_result || $user_result->num_rows === 0) {
    header("Location: admin_users.php");
    exit;
}
$user = $user_result->fetch_assoc();

// Get user's orders
$orders_result = $conn->query("
    SELECT * FROM orders 
    WHERE user_id = '$user_id' 
    ORDER BY order_date DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Orders - Admin Panel</title>
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
                <h1 class="admin-title">Orders by <?php echo htmlspecialchars($user['fullname']); ?></h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>

            <div class="admin-section">
                <h2 class="admin-section-title">User Information</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div>
                        <strong>Full Name:</strong><br>
                        <?php echo htmlspecialchars($user['fullname']); ?>
                    </div>
                    <div>
                        <strong>Email:</strong><br>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </div>
                    <div>
                        <strong>Address:</strong><br>
                        <?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?>
                    </div>
                    <div>
                        <strong>Registered:</strong><br>
                        <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                    </div>
                </div>
            </div>

            <div class="admin-section">
                <h2 class="admin-section-title">Order History</h2>
                
                <?php if ($orders_result && $orders_result->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
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
                            <td>LKR <?php echo number_format($order['total_amount'], 2); ?></td>
                            <td>
                                <span style="
                                    padding: 0.25rem 0.75rem;
                                    border-radius: 50px;
                                    font-size: 0.85rem;
                                    font-weight: 600;
                                    background-color: 
                                        <?php echo $order['status'] == 'pending' ? '#fef3c7' : 
                                              ($order['status'] == 'processing' ? '#dbeafe' : 
                                              ($order['status'] == 'shipped' ? '#f3e8ff' : 
                                              ($order['status'] == 'delivered' ? '#d1fae5' : '#fee2e2'))); ?>;
                                    color: 
                                        <?php echo $order['status'] == 'pending' ? '#92400e' : 
                                              ($order['status'] == 'processing' ? '#1e40af' : 
                                              ($order['status'] == 'shipped' ? '#7e22ce' : 
                                              ($order['status'] == 'delivered' ? '#065f46' : '#991b1b'))); ?>;
                                ">
                                    <?php echo ucfirst($order['status']); ?>
                                </span>
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
                    <p>This user hasn't placed any orders yet.</p>
                </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 2rem;">
                <a href="admin_users.php" class="admin-action-btn">← Back to Users</a>
            </div>
        </main>
    </div>
</body>
</html>




