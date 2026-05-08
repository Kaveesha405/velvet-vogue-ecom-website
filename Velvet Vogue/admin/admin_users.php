<?php
include '../common/db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Handle user deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id = $delete_id");
    header("Location: admin_users.php?deleted=1");
    exit;
}

// Get all users
$users_result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Admin Panel</title>
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
                <a href="admin_orders.php" class="admin-menu-link">🛒 Orders</a>
                <a href="admin_users.php" class="admin-menu-link active">👥 Users</a>
                <a href="admin_feedback.php" class="admin-menu-link">⭐ Feedback</a>
                <a href="admin_logout.php" class="admin-menu-link admin-logout-link">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">Manage Users</h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>

            <?php if (isset($_GET['deleted'])): ?>
                <div style="background-color: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #dc2626;">
                    User deleted successfully!
                </div>
            <?php endif; ?>

            <div class="admin-section">
                <h2 class="admin-section-title">All Users</h2>
                
                <?php if ($users_result && $users_result->num_rows > 0): ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></td>
                            <td><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                            <td>
                                <a href="admin_user_orders.php?id=<?php echo $user['id']; ?>" class="admin-action-btn">View Orders</a>
                                <a href="admin_users.php?delete_id=<?php echo $user['id']; ?>" class="admin-delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <div class="admin-empty">
                    <p>No users found.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>




