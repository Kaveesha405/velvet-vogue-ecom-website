<?php
ob_start(); 

session_start();

ob_clean();

header('Content-Type: application/json');

include '../../common/db_connect.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id is provided
if (isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    $size = isset($_POST['size']) ? trim($_POST['size']) : null;
    $color = isset($_POST['color']) ? trim($_POST['color']) : null;

    // Get product from database using prepared statement
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    
    if (!$stmt) {
        ob_end_clean();
        echo json_encode([
            'success' => false,
            'message' => 'Database error'
        ]);
        exit;
    }
    
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        
        // Create a unique cart key that includes size and color
        // This allows the same product with different size/color to be separate cart items
        $cartKey = $productId;
        if ($size || $color) {
            $cartKey = $productId . '_' . ($size ? $size : 'nosize') . '_' . ($color ? $color : 'nocolor');
        }
        
        // Check if this exact product+size+color combination already exists in cart
        if (isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$cartKey] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image_url'],
                'quantity' => $quantity,
                'size' => $size,
                'color' => $color
            ];
        }
        
        // If user is logged in, save to database
        if (isset($_SESSION['user_id'])) {
            $user_id = intval($_SESSION['user_id']);
            $cart_quantity = $_SESSION['cart'][$cartKey]['quantity'];
            
            // Check if this exact product+size+color combination exists in user's cart
            $check_stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?");
            
            // Handle NULL values for size and color
            $db_size = $size ?: null;
            $db_color = $color ?: null;
            
            $check_stmt->bind_param("iiss", $user_id, $productId, $db_size, $db_color);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                // Update quantity
                $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ? AND size = ? AND color = ?");
                $update_stmt->bind_param("iiiss", $cart_quantity, $user_id, $productId, $db_size, $db_color);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                // Insert new item
                $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity, size, color) VALUES (?, ?, ?, ?, ?)");
                $insert_stmt->bind_param("iiiss", $user_id, $productId, $cart_quantity, $db_size, $db_color);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
            $check_stmt->close();
        }
        
        $stmt->close();
        
        // Calculate total items in cart
        $totalItems = 0;
        foreach ($_SESSION['cart'] as $item) {
            $totalItems += $item['quantity'];
        }
        
        // Clear output buffer and send clean JSON
        ob_end_clean();
        
        echo json_encode([
            'success' => true,
            'message' => 'Product added to cart successfully',
            'cartCount' => count($_SESSION['cart']),
            'totalItems' => $totalItems,
            'product_name' => $product['name'],
            'size' => $size,
            'color' => $color
        ]);
        
    } else {
        $stmt->close();
        ob_end_clean();
        
        echo json_encode([
            'success' => false,
            'message' => 'Product not found'
        ]);
    }
} else {
    ob_end_clean();
    
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request - product_id missing'
    ]);
}

$conn->close();
exit;
?>



