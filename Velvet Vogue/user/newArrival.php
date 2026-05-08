<?php
$pageTitle = "New Arrivals";
include '../common/db_connect.php';
include 'processes/get_products.php';
include '../common/header.php';

// Use the getNewProducts function which already gets newest products
$newArrivals = getNewProducts(100); // Get more than needed so we can filter

$menProducts = [];
$womenProducts = [];
$kidsProducts = [];

foreach ($newArrivals as $product) {
    $categories = strtolower($product['all_categories']);
    
    if (strpos($categories, 'men') !== false && strpos($categories, 'women') === false) {
        $menProducts[] = $product;
    } elseif (strpos($categories, 'women') !== false) {
        $womenProducts[] = $product;
    } elseif (strpos($categories, 'kids') !== false) {
        $kidsProducts[] = $product;
    }
}

$showCount = 8; // Number of products to show initially
?>

<section class="page-header-section">
    <div class="container">
        <h1 class="page-header-title">NEW ARRIVALS</h1>
        <p class="page-header-subtitle">Discover our latest collection of fresh styles</p>
    </div>
</section>

<?php if (!empty($menProducts)): ?>
<section class="collection-products-section">
    <div class="container">
        <h2 class="section-title section-title-left">Men's New Arrivals</h2>
        <div class="products-fade" id="men-products-container">
            <div class="products-grid">
                <?php foreach ($menProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=newarrivals" 
                   class="product-card <?php echo $isHidden ? 'hidden-products' : ''; ?>">
                    <span class="product-badge new-badge">NEW</span>
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-rating">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="star <?php echo $i <= floor($product['rating']) ? 'filled' : ''; ?>" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-value">(<?php echo $product['rating']; ?>)</span>
                    </div>
                    <p class="product-price">LKR <?php echo number_format($product['price']); ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (count($menProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="men-show-more" onclick="toggleProducts('men')">
                <span class="btn-text">Show More</span>
                <svg class="arrow-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($womenProducts)): ?>
<section class="collection-products-section collection-section-spacing">
    <div class="container">
        <h2 class="section-title section-title-left">Women's New Arrivals</h2>
        <div class="products-fade" id="women-products-container">
            <div class="products-grid">
                <?php foreach ($womenProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=newarrivals" 
                   class="product-card <?php echo $isHidden ? 'hidden-products' : ''; ?>">
                    <span class="product-badge new-badge">NEW</span>
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-rating">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="star <?php echo $i <= floor($product['rating']) ? 'filled' : ''; ?>" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-value">(<?php echo $product['rating']; ?>)</span>
                    </div>
                    <p class="product-price">LKR <?php echo number_format($product['price']); ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (count($womenProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="women-show-more" onclick="toggleProducts('women')">
                <span class="btn-text">Show More</span>
                <svg class="arrow-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($kidsProducts)): ?>
<section class="collection-products-section collection-section-spacing">
    <div class="container">
        <h2 class="section-title section-title-left">Kids' New Arrivals</h2>
        <div class="products-fade" id="kids-products-container">
            <div class="products-grid">
                <?php foreach ($kidsProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=newarrivals" 
                   class="product-card <?php echo $isHidden ? 'hidden-products' : ''; ?>">
                    <span class="product-badge new-badge">NEW</span>
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                    <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <div class="product-rating">
                        <div class="stars">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <svg class="star <?php echo $i <= floor($product['rating']) ? 'filled' : ''; ?>" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                            </svg>
                            <?php endfor; ?>
                        </div>
                        <span class="rating-value">(<?php echo $product['rating']; ?>)</span>
                    </div>
                    <p class="product-price">LKR <?php echo number_format($product['price']); ?></p>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php if (count($kidsProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="kids-show-more" onclick="toggleProducts('kids')">
                <span class="btn-text">Show More</span>
                <svg class="arrow-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"></polyline>
                </svg>
            </button>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>


<?php
include '../common/footer.php';
?>



