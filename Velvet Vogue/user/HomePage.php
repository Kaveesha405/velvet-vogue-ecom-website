<?php
$pageTitle = "Velvet Vogue - Express Your Identity Through Style";
include '../common/db_connect.php';
include 'processes/get_products.php';
include '../common/header.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Sample data for hero slides
$heroSlides = [
    ['image' => 'assets/images/2.png'],
    ['image' => 'assets/images/3.png'],
    ['image' => 'assets/images/4.png']
];

// categories with links
$categories = [
    ['name' => 'Casual Wear', 'image' => 'https://cdn.luxe.digital/media/2019/09/12084906/casual-dress-code-men-street-style-luxe-digital-1.jpg', 'link' => 'casual.php'],
    ['name' => 'Formal Wear', 'image' => 'https://i.pinimg.com/originals/66/9c/9f/669c9f39f83daebe73c4db652a09a1bb.jpg', 'link' => 'formal.php'],
    ['name' => 'Accessories', 'image' => 'https://images.unsplash.com/photo-1492707892479-7bc8d5a4ee93?w=400&h=500&fit=crop', 'link' => 'accessories.php'],
    ['name' => 'Footwear', 'image' => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=400&h=500&fit=crop', 'link' => 'footwear.php']
];

// Fetch products from database
$newArrivals = getNewProducts(8);
$bestSellers = getBestSellerProducts(8);
?>

    <!-- Hero Slider -->
    <section class="hero-slider">
        <?php foreach ($heroSlides as $index => $slide): ?>
        <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo $slide['image']; ?>')">
        </div>
        <?php endforeach; ?>

        <!-- Navigation Arrows -->
        <button class="hero-arrow left-arrow" onclick="changeSlide(-1)">&#10094;</button>
        <button class="hero-arrow right-arrow" onclick="changeSlide(1)">&#10095;</button>

        <!-- Slider Dots -->
        <div class="slider-indicators">
            <?php foreach ($heroSlides as $index => $slide): ?>
            <button class="indicator <?php echo $index === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $index; ?>)"></button>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Categories -->
    <section class="categories-section">
        <div class="container">
            <h2 class="section-title">Shop by Category</h2>
            <div class="categories-grid">
                <?php foreach ($categories as $category): ?>
                <a href="<?php echo $category['link']; ?>" class="category-card">
                    <div class="category-image">
                        <img src="<?php echo $category['image']; ?>" alt="<?php echo $category['name']; ?>">
                    </div>
                    <div class="category-overlay"></div>
                    <div class="category-content">
                        <h3><?php echo $category['name']; ?></h3>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="products-section new-arrivals-section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">New Arrivals</h2>
                </div>
                <a href="newArrival.php" class="view-all">
                    <span>View All</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </div>
            <div class="products-grid">
                <?php foreach ($newArrivals as $product): ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>" class="product-card">
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
    </section>

    <!-- Best Sellers Section -->
    <section class="products-section best-sellers-section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Best Sellers</h2>
                </div>
                <a href="bestSellers.php" class="view-all">
                    <span>View All</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            </div>
            <div class="products-grid">
                <?php foreach ($bestSellers as $product): ?>
                <a href="product-details.php?id=<?php echo $product['id']; ?>" class="product-card">
                    <span class="product-badge bestseller-badge">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27 6.91 8.26 12 2z"/>
                        </svg>
                        BEST SELLER
                    </span>
                    
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
    </section>

<?php
include '../common/footer.php';
?>





