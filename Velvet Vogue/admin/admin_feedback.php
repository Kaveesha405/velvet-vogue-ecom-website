<?php
include '../common/db_connect.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $inquiry_id = intval($_POST['inquiry_id']);
    $new_status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE inquiries SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $inquiry_id);
    
    if ($stmt->execute()) {
        $success_message = "Status updated successfully!";
    } else {
        $error_message = "Failed to update status.";
    }
    $stmt->close();
}

// Handle delete
if (isset($_GET['delete'])) {
    $inquiry_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM inquiries WHERE id = ?");
    $stmt->bind_param("i", $inquiry_id);
    
    if ($stmt->execute()) {
        $success_message = "Inquiry deleted successfully!";
    } else {
        $error_message = "Failed to delete inquiry.";
    }
    $stmt->close();
}

// Get all inquiries
$inquiries_query = "SELECT * FROM inquiries ORDER BY created_at DESC";
$inquiries_result = $conn->query($inquiries_query);

// Get statistics
$total_inquiries = $conn->query("SELECT COUNT(*) as count FROM inquiries")->fetch_assoc()['count'];
$new_inquiries = $conn->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'new'")->fetch_assoc()['count'];
$read_inquiries = $conn->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'read'")->fetch_assoc()['count'];
$replied_inquiries = $conn->query("SELECT COUNT(*) as count FROM inquiries WHERE status = 'replied'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Management - Velvet Vogue</title>
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
                <a href="admin_users.php" class="admin-menu-link">👥 Users</a>
                <a href="admin_feedback.php" class="admin-menu-link active">⭐ Feedback</a>
                <a href="admin_logout.php" class="admin-menu-link admin-logout-link">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">Customer Feedback & Inquiries</h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>

            <!-- Alert Messages -->
            <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
            <div class="alert alert-error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="feedback-stats">
                <div class="stat-card total">
                    <div class="stat-number"><?php echo $total_inquiries; ?></div>
                    <div class="stat-label">Total Inquiries</div>
                </div>
                <div class="stat-card new">
                    <div class="stat-number"><?php echo $new_inquiries; ?></div>
                    <div class="stat-label">New</div>
                </div>
                <div class="stat-card in-progress">
                    <div class="stat-number"><?php echo $read_inquiries; ?></div>
                    <div class="stat-label">Read</div>
                </div>
                <div class="stat-card resolved">
                    <div class="stat-number"><?php echo $replied_inquiries; ?></div>
                    <div class="stat-label">Replied</div>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="search-container">
                <div class="search-box">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" class="search-input" placeholder="Search by name, email, or message...">
                </div>
                <div class="filter-buttons">
                    <button class="filter-btn active" data-status="all">All</button>
                    <button class="filter-btn" data-status="new">New</button>
                    <button class="filter-btn" data-status="read">Read</button>
                    <button class="filter-btn" data-status="replied">Replied</button>
                </div>
            </div>

            <!-- Inquiries List -->
            <div class="admin-section">
                <h2 class="admin-section-title">All Inquiries</h2>
                
                <?php if ($inquiries_result && $inquiries_result->num_rows > 0): ?>
                <div class="inquiries-container" id="inquiriesContainer">
                    <?php while ($inquiry = $inquiries_result->fetch_assoc()): ?>
                    <div class="inquiry-card" data-status="<?php echo $inquiry['status']; ?>" data-inquiry-id="<?php echo $inquiry['id']; ?>">
                        <div class="inquiry-header">
                            <div class="inquiry-info">
                                <h3 class="inquiry-name"><?php echo htmlspecialchars($inquiry['name']); ?></h3>
                                <p class="inquiry-email"><?php echo htmlspecialchars($inquiry['email']); ?></p>
                                <p class="inquiry-date">📅 <?php echo date('M d, Y - h:i A', strtotime($inquiry['created_at'])); ?></p>
                            </div>
                            <div class="inquiry-status-badge">
                                <span class="status-badge status-<?php echo $inquiry['status']; ?>">
                                    <?php echo ucfirst(str_replace('_', ' ', $inquiry['status'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="inquiry-message">
                            <p><?php echo nl2br(htmlspecialchars($inquiry['message'])); ?></p>
                        </div>

                        <div class="inquiry-actions">
                            <form method="POST" class="status-form">
                                <input type="hidden" name="inquiry_id" value="<?php echo $inquiry['id']; ?>">
                                <select name="status" class="status-select" onchange="this.form.submit()">
                                    <option value="new" <?php echo $inquiry['status'] === 'new' ? 'selected' : ''; ?>>New</option>
                                    <option value="read" <?php echo $inquiry['status'] === 'read' ? 'selected' : ''; ?>>Read</option>
                                    <option value="replied" <?php echo $inquiry['status'] === 'replied' ? 'selected' : ''; ?>>Replied</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                            
                            <div class="action-buttons">
                                <a href="mailto:<?php echo htmlspecialchars($inquiry['email']); ?>" class="admin-action-btn email-btn">📧 Email</a>
                                <a href="?delete=<?php echo $inquiry['id']; ?>" class="admin-delete-btn" onclick="return confirm('Are you sure you want to delete this inquiry?');">🗑️ Delete</a>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div id="noResults" class="no-results">
                    No inquiries found matching your search.
                </div>

                <?php else: ?>
                <p class="admin-empty">No inquiries received yet.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="assets/js/script.js"></script>
</body>
</html>




