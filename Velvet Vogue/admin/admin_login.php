<?php
include '../common/db_connect.php';
session_start();

// If admin already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check if admin exists in admin_users table
    $sql = "SELECT * FROM admin_users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            $_SESSION['admin_username'] = $admin['username'];
            
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Admin account not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Velvet Vogue</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="admin-login-wrapper">
        <div class="admin-login-container">
            <h1 class="admin-login-title">Admin Panel</h1>
            <p class="admin-login-subtitle">Velvet Vogue</p>

            <?php if ($error): ?>
                <div class="admin-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" class="admin-login-form">
                <div class="admin-form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="admin@velvetvogue.lk" required>
                </div>

                <div class="admin-form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="admin-login-btn">Login to Admin Panel</button>
            </form>

            <div class="admin-back-link">
                <a href="HomePage.php">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>




