<?php
session_start();
include '../common/db_connect.php';
include 'processes/get_products.php';
include 'processes/color_mapping.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$product = getProductById($productId);

// If product not found, redirect to home
if (!$product) {
    header('Location: HomePage.php');
    exit();
}

// Get related products (same category, excluding current product)
$relatedProducts = [];
if (!empty($product['all_categories'])) {
    $categories = explode(', ', $product['all_categories']);
    $firstCategory = $categories[0];
    $allCategoryProducts = getProductsByCategory($firstCategory);
    
    // Filter out current product and limit to 4
    $relatedProducts = array_filter($allCategoryProducts, function($p) use ($productId) {
        return $p['id'] != $productId;
    });
    $relatedProducts = array_slice($relatedProducts, 0, 4);
}

$pageTitle = htmlspecialchars($product['name']) . " - Velvet Vogue";
include '../common/header.php';
?>

<!-- Product Details Section -->
<section class="product-details-section">
    <div class="product-details-container">
        <!-- Back to Products Link -->
        <?php
        // Determine where to go back based on 'from' parameter
        $backUrl = 'HomePage.php';
        $backText = 'Back to Products';

        if (isset($_GET['from'])) {
            $from = $_GET['from'];
            
            switch($from) {
                case 'men':
                    $backUrl = 'men.php';
                    $backText = 'Back to Men\'s Collection';
                    break;
                case 'women':
                    $backUrl = 'women.php';
                    $backText = 'Back to Women\'s Collection';
                    break;
                case 'kids':
                    $backUrl = 'kids.php';
                    $backText = 'Back to Kids\' Collection';
                    break;
                case 'accessories':
                    $backUrl = 'accessories.php';
                    $backText = 'Back to Accessories';
                    break;
                case 'casual':
                    $backUrl = 'casual.php';
                    $backText = 'Back to Casual Wear';
                    break;
                case 'formal':
                    $backUrl = 'formal.php';
                    $backText = 'Back to Formal Wear';
                    break;
                case 'footwear':
                    $backUrl = 'footwear.php';
                    $backText = 'Back to Footwear';
                    break;
                case 'bestsellers':
                    $backUrl = 'bestSellers.php';
                    $backText = 'Back to Best Sellers';
                    break;
                case 'newarrivals':
                    $backUrl = 'newArrival.php';
                    $backText = 'Back to New Arrivals';
                    break;
                case 'search':
                    if (isset($_GET['query'])) {
                        $backUrl = 'search.php?query=' . urlencode($_GET['query']);
                        $backText = 'Back to Search Results';
                    }
                    break;
            }
        }
        ?>
        <a href="<?php echo $backUrl; ?>" class="back-to-products">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
            <?php echo $backText; ?>
        </a>

        <!-- Product Details Grid -->
        <div class="product-details-grid">
            <!-- Product Image Section -->
            <div class="product-image-section">
                <?php if (!empty($product['all_categories']) && strpos($product['all_categories'], 'New') !== false): ?>
                <span class="product-badge-detail new-badge">NEW</span>
                <?php endif; ?>
                
                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                     class="product-main-image">
            </div>

            <!-- Product Info Section -->
            <div class="product-info-section">
                <h1 class="product-detail-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <!-- Rating -->
                <div class="product-detail-rating">
                    <div class="product-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <svg class="star <?php echo $i <= floor($product['rating']) ? 'filled' : ''; ?>" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                        </svg>
                        <?php endfor; ?>
                    </div>
                    <span class="product-rating-text"><?php echo $product['rating']; ?> / 5.0</span>
                </div>

                <!-- Price -->
                <p class="product-detail-price">LKR <?php echo number_format($product['price']); ?></p>

                <!-- Categories -->
                <?php if (!empty($product['all_categories'])): ?>
                <div class="product-categories">
                    <?php
                    $categories = explode(', ', $product['all_categories']);
                    foreach ($categories as $category):
                    ?>
                    <span class="category-tag"><?php echo htmlspecialchars($category); ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Description -->
                <div class="product-description">
                    <?php if (!empty($product['description'])): ?>
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    <?php else: ?>
                        <p>This is a premium quality product from Velvet Vogue. Experience luxury and style with this carefully crafted item designed to elevate your wardrobe.</p>
                    <?php endif; ?>
                </div>

                <!-- SIZE SELECTOR -->
                <?php if (!empty($product['sizes'])): ?>
                <div class="product-options">
                    <label class="option-label">Size: <span class="selected-option" id="selectedSizeText">Select a size</span></label>
                    <div class="size-selector" id="sizeSelector">
                        <?php
                        $sizes = explode(',', $product['sizes']);
                        foreach ($sizes as $size):
                            $size = trim($size);
                        ?>
                        <button type="button" class="size-btn" data-size="<?php echo htmlspecialchars($size); ?>">
                            <?php echo htmlspecialchars($size); ?>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- COLOR SELECTOR -->
                <?php if (!empty($product['colors'])): ?>
                <div class="product-options">
                    <label class="option-label">Color: <span class="selected-option" id="selectedColorText">Select a color</span></label>
                    <div class="color-selector" id="colorSelector">
                        <?php
                        $colors = explode(',', $product['colors']);
                        foreach ($colors as $color):
                            $colorParts = explode('|', trim($color));
                            $colorName = $colorParts[0]; // Get the color name
                            $colorHex = isset($colorParts[1]) ? $colorParts[1] : getColorHex($colorName); // Use stored hex or fallback to mapping
                        ?>
                        <button type="button" class="color-btn" data-color="<?php echo htmlspecialchars($colorName); ?>" title="<?php echo htmlspecialchars($colorName); ?>">
                            <span class="color-circle" style="background-color: <?php echo htmlspecialchars($colorHex); ?>;"></span>
                            <span class="color-name"><?php echo htmlspecialchars($colorName); ?></span>
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Product Actions -->
                <div class="product-actions">
                    <!-- Quantity Selector -->
                    <div class="quantity-selector">
                        <label for="quantity">Quantity:</label>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn" id="decreaseQty">-</button>
                            <input type="number" id="quantity" class="qty-input" value="1" min="1">
                            <button type="button" class="qty-btn" id="increaseQty">+</button>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <button class="add-to-cart-detail-btn" id="addToCartDetailBtn" 
                            data-product-id="<?php echo $product['id']; ?>"
                            data-has-sizes="<?php echo !empty($product['sizes']) ? 'true' : 'false'; ?>"
                            data-has-colors="<?php echo !empty($product['colors']) ? 'true' : 'false'; ?>">
                        Add to Cart
                    </button>
                </div>
            </div>
        </div>

        <!-- Related Products Section -->
        <?php if (count($relatedProducts) > 0): ?>
        <div class="related-products-section">
            <h2 class="related-products-title">You May Also Like</h2>
            <div class="products-grid">
                <?php foreach ($relatedProducts as $relatedProduct): ?>
                <div class="product-card">
                    <!-- Clickable Product Link - Preserve the 'from' parameter -->
                    <a href="product-details.php?id=<?php echo $relatedProduct['id']; ?>&from=<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : 'home'; ?><?php echo (isset($_GET['query']) ? '&query=' . urlencode($_GET['query']) : ''); ?>" class="product-link">
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($relatedProduct['image_url']); ?>" alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>">
                        </div>
                        <h3 class="product-name"><?php echo htmlspecialchars($relatedProduct['name']); ?></h3>
                        <div class="product-rating">
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                <svg class="star <?php echo $i <= floor($relatedProduct['rating']) ? 'filled' : ''; ?>" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                                <?php endfor; ?>
                            </div>
                            <span class="rating-value">(<?php echo $relatedProduct['rating']; ?>)</span>
                        </div>
                        <p class="product-price">LKR <?php echo number_format($relatedProduct['price']); ?></p>
                    </a>
                    
                    
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php
include '../common/footer.php';
?>



