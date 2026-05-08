<?php
$pageTitle = "Accessories Collection";
include '../common/db_connect.php';
include 'processes/get_products.php';
include '../common/header.php';

// Get products by specific category combinations using database categories
$menProducts = getProductsByMultipleCategories(['Men', 'Accessories']);
$womenProducts = getProductsByMultipleCategories(['Women', 'Accessories']);
$kidsProducts = getProductsByMultipleCategories(['Kids', 'Accessories']);

// Get all accessories (those that are ONLY in Accessories category, not combined with Men/Women/Kids)
$allAccessories = getProductsByCategory('Accessories');
$allProducts = [];
foreach ($allAccessories as $product) {
    $categories = strtolower($product['all_categories']);
    // Only include if it doesn't have Men, Women, or Kids category
    if (strpos($categories, 'men') === false && 
        strpos($categories, 'women') === false && 
        strpos($categories, 'kids') === false) {
        $allProducts[] = $product;
    }
}

$showCount = 8;
?>

<section class="page-header-section">
    <div class="container">
        <h1 class="page-header-title">ACCESSORIES</h1>
        <p class="page-header-subtitle">Complete your look with our premium accessories</p>
    </div>
</section>

<?php if (!empty($menProducts)): ?>
<section class="collection-products-section">
    <div class="container">
        <h2 class="section-title section-title-left">Men's Accessories</h2>
        <div class="products-fade" id="men-products-container">
            <div class="products-grid">
                <?php foreach ($menProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=accessories" 
                   class="product-card <?php echo $isHidden ? 'hidden-products' : ''; ?>">
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
        <h2 class="section-title section-title-left">Women's Accessories</h2>
        <div class="products-fade" id="women-products-container">
            <div class="products-grid">
                <?php foreach ($womenProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=accessories" 
                   class="product-card <?php echo $isHidden ? 'hidden-products' : ''; ?>">
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
        <h2 class="section-title section-title-left">Kids' Accessories</h2>
        <div class="products-fade" id="kids-products-container">
            <div class="products-grid">
                <?php foreach ($kidsProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=accessories" 
                   class="product-card <?php echo $isHidden ? 'hidden-products' : ''; ?>">
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

<?php if (!empty($allProducts)): ?>
<section class="collection-products-section collection-section-spacing">
    <div class="container">
        <h2 class="section-title section-title-left">All Accessories</h2>
        <div class="products-fade" id="all-products-container">
            <div class="products-grid">
                <?php foreach ($allProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=accessories" 
                   class="product-card <?php echo $isHidden ? 'hidden-products' : ''; ?>">
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
        <?php if (count($allProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="all-show-more" onclick="toggleProducts('all')">
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



