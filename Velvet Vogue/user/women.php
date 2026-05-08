<?php
$pageTitle = "Women's Collection";
include '../common/db_connect.php';
include 'processes/get_products.php';
include '../common/header.php';

// Get products by specific category combinations using database categories
$formalProducts = getProductsByMultipleCategories(['Women', 'Formal Wear']);
$casualProducts = getProductsByMultipleCategories(['Women', 'Casual Wear']);
$accessoriesProducts = getProductsByMultipleCategories(['Women', 'Accessories']);
$footwearProducts = getProductsByMultipleCategories(['Women', 'Footwear']);

$showCount = 8; // Number of products to show initially
?>

<section class="page-header-section">
    <div class="container">
        <h1 class="page-header-title">WOMEN'S COLLECTION</h1>
        <p class="page-header-subtitle">Discover elegant styles and timeless fashion for women</p>
    </div>
</section>

<!-- Formal Wear Section -->
<?php if (!empty($formalProducts)): ?>
<section class="collection-products-section">
    <div class="container">
        <h2 class="section-title section-title-left">Formal Wear</h2>
        <div class="products-fade" id="formal-products-container">
            <div class="products-grid">
                <?php foreach ($formalProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=women" 
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
        <?php if (count($formalProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="formal-show-more" onclick="toggleProducts('formal')">
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

<!-- Casual Wear Section -->
<?php if (!empty($casualProducts)): ?>
<section class="collection-products-section collection-section-spacing">
    <div class="container">
        <h2 class="section-title section-title-left">Casual Wear</h2>
        <div class="products-fade" id="casual-products-container">
            <div class="products-grid">
                <?php foreach ($casualProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=women" 
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
        <?php if (count($casualProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="casual-show-more" onclick="toggleProducts('casual')">
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

<!-- Accessories Section -->
<?php if (!empty($accessoriesProducts)): ?>
<section class="collection-products-section collection-section-spacing">
    <div class="container">
        <h2 class="section-title section-title-left">Accessories</h2>
        <div class="products-fade" id="accessories-products-container">
            <div class="products-grid">
                <?php foreach ($accessoriesProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=women" 
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
        <?php if (count($accessoriesProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="accessories-show-more" onclick="toggleProducts('accessories')">
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

<!-- Footwear Section -->
<?php if (!empty($footwearProducts)): ?>
<section class="collection-products-section collection-section-spacing">
    <div class="container">
        <h2 class="section-title section-title-left">Footwear</h2>
        <div class="products-fade" id="footwear-products-container">
            <div class="products-grid">
                <?php foreach ($footwearProducts as $index => $product): 
                    $isHidden = $index >= $showCount;
                ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>&from=women" 
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
        <?php if (count($footwearProducts) > $showCount): ?>
        <div class="show-more-container">
            <button class="show-more-btn" id="footwear-show-more" onclick="toggleProducts('footwear')">
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



