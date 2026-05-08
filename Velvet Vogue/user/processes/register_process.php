<?php
include '../../common/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: RegisterPage.php");
        exit;
    }

    // Check if email already exists
    $check_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if ($check_email->num_rows > 0) {
        $_SESSION['error'] = "Email already exists!";
        header("Location: RegisterPage.php");
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (fullname, email, password) VALUES ('$fullname', '$email', '$hashed_password')";
    
    if ($conn->query($sql) === TRUE) {
        // Auto login after registration
        $user_id = $conn->insert_id;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['fullname'] = $fullname;
        $_SESSION['email'] = $email;
        
        header("Location: profile.php");
        exit;
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: RegisterPage.php");
        exit;
    }
}
?>



