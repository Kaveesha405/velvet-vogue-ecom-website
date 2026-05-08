<?php
$pageTitle = "Kids' Collection";
include '../common/db_connect.php';
include 'processes/get_products.php';
include '../common/header.php';

$products = getProductsByCategory('Kids');
?>

    <section class="page-header-section">
        <div class="container">
            <h1 class="page-header-title">KIDS' COLLECTION</h1>
            <p class="page-header-subtitle">Fun and comfortable clothing for kids</p>
        </div>
    </section>

    <section class="collection-products-section">
        <div class="container">
            <div class="products-grid">
                <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <!-- Clickable Product Link -->
                    <a href="product-details.php?id=<?php echo $product['id']; ?>&from=kids" class="product-link">
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
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php
include '../common/footer.php';
?>



