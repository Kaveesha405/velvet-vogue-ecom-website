<?php
ob_start();
$pageTitle = "Shopping Cart";
include '../common/db_connect.php';
include '../common/header.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update' && isset($_POST['product_id']) && isset($_POST['quantity'])) {
        $productId = intval($_POST['product_id']);
        $cartKey = $_POST['cart_key'] ?? $productId; // Use cart_key if available
        $quantity = intval($_POST['quantity']);
        
        if ($quantity > 0 && isset($_SESSION['cart'][$cartKey])) {
            $_SESSION['cart'][$cartKey]['quantity'] = $quantity;
            
            // Update database if user is logged in
            if (isset($_SESSION['user_id'])) {
                $size = $_SESSION['cart'][$cartKey]['size'] ?? null;
                $color = $_SESSION['cart'][$cartKey]['color'] ?? null;
                
                $conn->query("UPDATE cart SET quantity = $quantity 
                             WHERE user_id = {$_SESSION['user_id']} 
                             AND product_id = $productId 
                             AND size " . ($size ? "= '$size'" : "IS NULL") . " 
                             AND color " . ($color ? "= '$color'" : "IS NULL"));
            }
        }
    } elseif ($_POST['action'] === 'remove' && isset($_POST['product_id'])) {
        $productId = intval($_POST['product_id']);
        $cartKey = $_POST['cart_key'] ?? $productId; // Use cart_key if available
        
        // Remove from database if user is logged in
        if (isset($_SESSION['user_id']) && isset($_SESSION['cart'][$cartKey])) {
            $size = $_SESSION['cart'][$cartKey]['size'] ?? null;
            $color = $_SESSION['cart'][$cartKey]['color'] ?? null;
            
            $conn->query("DELETE FROM cart 
                         WHERE user_id = {$_SESSION['user_id']} 
                         AND product_id = $productId 
                         AND size " . ($size ? "= '$size'" : "IS NULL") . " 
                         AND color " . ($color ? "= '$color'" : "IS NULL"));
        }
        
        unset($_SESSION['cart'][$cartKey]);
    }
    
    header('Location: cart.php');
    exit();
}

// Calculate totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}

$shipping = 500; // Fixed shipping cost
$total = $subtotal + $shipping;
ob_end_flush();
?>

    <!-- Cart Page -->
    <section class="cart-page">
        <div class="cart-container">
            <h1 class="cart-title">Shopping Cart</h1>
            
            <?php if (empty($_SESSION['cart'])): ?>
                <div class="cart-empty">
                    <h2>Your cart is empty</h2>
                    <a href="HomePage.php" class="continue-shopping">Continue Shopping</a>
                </div>
            <?php else: ?>
                <div class="cart-content">
                    <div class="cart-items">
                        <?php foreach ($_SESSION['cart'] as $cartKey => $item): ?>
                        <div class="cart-item">
                            <div class="cart-item-image">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                            </div>
                            <div class="cart-item-details">
                                <h3 class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></h3>
                                <p class="cart-item-price">LKR <?php echo number_format($item['price']); ?></p>
                                
                                <!-- Display Size and Color if available -->
                                <?php if (!empty($item['size']) || !empty($item['color'])): ?>
                                <div class="cart-item-options">
                                    <?php if (!empty($item['size'])): ?>
                                        <span class="cart-option">
                                            <strong>Size:</strong> <?php echo htmlspecialchars($item['size']); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($item['color'])): ?>
                                        <span class="cart-option">
                                            <strong>Color:</strong> <?php echo htmlspecialchars($item['color']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="cart-item-actions">
                                <form method="POST" class="quantity-control">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($cartKey); ?>">
                                    <button type="button" class="quantity-btn" onclick="decreaseQuantity(this)">−</button>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input" readonly>
                                    <button type="button" class="quantity-btn" onclick="increaseQuantity(this)">+</button>
                                </form>
                                <form method="POST" style="margin-top: 1rem;">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <input type="hidden" name="cart_key" value="<?php echo htmlspecialchars($cartKey); ?>">
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="cart-summary">
                        <h2 class="summary-title">Order Summary</h2>
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>LKR <?php echo number_format($subtotal); ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping:</span>
                            <span>LKR <?php echo number_format($shipping); ?></span>
                        </div>
                        <div class="summary-total">
                            <span>Total:</span>
                            <span>LKR <?php echo number_format($total); ?></span>
                        </div>
                        <button class="checkout-btn" onclick="window.location.href='checkout.php'">
                            Proceed to Checkout
                        </button>
                        <a href="HomePage.php" class="continue-shopping">← Continue Shopping</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php
include '../common/footer.php';
?>



