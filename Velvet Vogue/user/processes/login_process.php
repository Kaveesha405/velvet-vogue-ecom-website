<?php
include '../../common/db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            
            // Transfer session cart to database
            if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    // Check if product already in user's cart
                    $check_sql = "SELECT * FROM cart WHERE user_id = {$user['id']} AND product_id = $product_id";
                    $check_result = $conn->query($check_sql);
                    
                    if ($check_result->num_rows > 0) {
                        $cart_item = $check_result->fetch_assoc();
                        $new_quantity = $cart_item['quantity'] + $quantity;
                        $update_sql = "UPDATE cart SET quantity = $new_quantity WHERE user_id = {$user['id']} AND product_id = $product_id";
                        $conn->query($update_sql);
                    } else {
                        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES ({$user['id']}, $product_id, $quantity)";
                        $conn->query($insert_sql);
                    }
                }
            }
            
            // Clear session cart and load from database
            $_SESSION['cart'] = [];
            $cart_sql = "SELECT product_id, quantity FROM cart WHERE user_id = {$user['id']}";
            $cart_result = $conn->query($cart_sql);
            
            while ($cart_item = $cart_result->fetch_assoc()) {
                $_SESSION['cart'][$cart_item['product_id']] = $cart_item['quantity'];
            }
            
            
            header("Location: HomePage.php");
            exit;
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "User not found!";
    }
}
?>



