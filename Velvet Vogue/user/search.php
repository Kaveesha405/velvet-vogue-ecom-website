<?php
$pageTitle = "Search Results";
include '../common/db_connect.php';
include 'processes/get_products.php';
include '../common/header.php';

$products = [];
$searchQuery = '';
$resultCount = 0;

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $searchQuery = $conn->real_escape_string($_GET['query']);
    
    $sql = "SELECT * FROM products WHERE 
            name LIKE '%$searchQuery%' 
            OR description LIKE '%$searchQuery%' 
            OR category LIKE '%$searchQuery%'
            ORDER BY name ASC";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        $resultCount = count($products);
    }
}
?>

    <section class="page-header-section">
        <div class="container">
            <h1 class="page-header-title">SEARCH RESULTS</h1>
            <p class="page-header-subtitle">
                <?php if ($searchQuery): ?>
                    Results for "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"
                <?php else: ?>
                    Enter a search term
                <?php endif; ?>
            </p>
        </div>
    </section>

    <section class="collection-products-section">
        <div class="container">
            <?php if ($searchQuery): ?>
                <?php if ($resultCount > 0): ?>
                    <div style="margin-bottom: 1.5rem; color: #6b7280;">
                        Found <strong><?php echo $resultCount; ?></strong> 
                        <?php echo $resultCount === 1 ? 'product' : 'products'; ?>
                    </div>
                    <div class="products-grid">
                        <?php foreach ($products as $product): ?>
                        <a href="product-details.php?id=<?php echo $product['id']; ?>&from=search&query=<?php echo urlencode($searchQuery); ?>" class="product-card">
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
                <?php else: ?>
                    <div class="search-empty">
                        <h2>No products found</h2>
                        <p>We couldn't find any products matching "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"</p>
                        <p style="margin-top: 1rem; color: #6b7280;">Try:</p>
                        <ul style="list-style: none; padding: 0; margin-top: 0.5rem; color: #6b7280;">
                            <li>• Using different keywords</li>
                            <li>• Checking your spelling</li>
                            <li>• Browsing by category instead</li>
                        </ul>
                        <a href="HomePage.php" class="continue-shopping" style="margin-top: 1.5rem;">← Back to Home</a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="search-empty">
                    <h2>Enter a search term</h2>
                    <p>Use the search bar to find products by name, category, or description</p>
                    <a href="HomePage.php" class="continue-shopping" style="margin-top: 1.5rem;">← Back to Home</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

<?php
include '../common/footer.php';
?>



