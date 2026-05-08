<?php
include '../common/db_connect.php';
include '../user/processes/color_mapping.php';
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}

$colorMapping = [
    'Black' => '#000000',
    'White' => '#FFFFFF',
    'Red' => '#FF0000',
    'Blue' => '#0074D9',
    'Green' => '#2ECC40',
    'Yellow' => '#FFFF00',
    'Pink' => '#FF69B4',
    'Purple' => '#9333ea',
    'Gray' => '#808080',
    'Brown' => '#8B4513',
    'Navy' => '#001f3f',
    'Beige' => '#F5F5DC',
    'Orange' => '#FF8C00',
    'Maroon' => '#800000',
    'Cream' => '#FFFDD0',
    'Olive' => '#808000',
    'Khaki' => '#C3B091'
];

// DEFINE SIZE OPTIONS FIRST
$size_options = [
    'clothing' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', '2XL', '3XL'],
    'kids' => ['2T', '3T', '4T', '5T'],
    'footwear' => ['6', '7', '8', '9', '10', '11', '12'],
    'accessories' => ['One Size']
];

$available_colors = ['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Pink', 'Purple', 'Gray', 'Brown', 'Navy', 'Beige', 'Orange', 'Maroon', 'Cream', 'Olive', 'Khaki'];

// Get all available categories
$categories_query = $conn->query("SELECT * FROM categories ORDER BY name");
$all_categories = [];
while ($cat = $categories_query->fetch_assoc()) {
    $all_categories[] = $cat;
}

// Handle product deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $conn->real_escape_string($_GET['delete_id']);
    $conn->query("DELETE FROM products WHERE id = $delete_id");
    header("Location: admin_products.php?deleted=1");
    exit;
}

// Handle product addition/editing
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : 4.5; // Default rating
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $selected_categories = isset($_POST['categories']) ? $_POST['categories'] : [];
    
    // Validate rating (must be between 0 and 5)
    if ($rating < 0) $rating = 0;
    if ($rating > 5) $rating = 5;
    
    // Process colors
    $selected_colors = isset($_POST['colors']) ? $_POST['colors'] : [];
    $colors_with_hex = [];
    foreach ($selected_colors as $colorName) {
        $colorName = trim($colorName);
        if (isset($colorMapping[$colorName])) {
            $colors_with_hex[] = $colorName . '|' . $colorMapping[$colorName];
        }
    }
    $colors_string = !empty($colors_with_hex) ? implode(',', $colors_with_hex) : NULL;

    // Process sizes
    $selected_sizes = isset($_POST['sizes']) ? $_POST['sizes'] : [];
    $sizes_string = !empty($selected_sizes) ? implode(',', $selected_sizes) : NULL;
    
    if (isset($_POST['product_id']) && !empty($_POST['product_id'])) {
        // Update existing product
        $product_id = intval($_POST['product_id']);
        
        // Build SQL with rating and proper NULL handling
        if ($sizes_string !== NULL && $colors_string !== NULL) {
            $sql = "UPDATE products SET name='$name', description='$description', price='$price', rating='$rating', image_url='$image_url', sizes='$sizes_string', colors='$colors_string' WHERE id='$product_id'";
        } elseif ($sizes_string !== NULL) {
            $sql = "UPDATE products SET name='$name', description='$description', price='$price', rating='$rating', image_url='$image_url', sizes='$sizes_string', colors=NULL WHERE id='$product_id'";
        } elseif ($colors_string !== NULL) {
            $sql = "UPDATE products SET name='$name', description='$description', price='$price', rating='$rating', image_url='$image_url', sizes=NULL, colors='$colors_string' WHERE id='$product_id'";
        } else {
            $sql = "UPDATE products SET name='$name', description='$description', price='$price', rating='$rating', image_url='$image_url', sizes=NULL, colors=NULL WHERE id='$product_id'";
        }
        
        if ($conn->query($sql)) {
            // Update categories
            $conn->query("DELETE FROM product_categories WHERE product_id = $product_id");
            
            foreach ($selected_categories as $cat_id) {
                $cat_id = intval($cat_id);
                $conn->query("INSERT INTO product_categories (product_id, category_id) VALUES ($product_id, $cat_id)");
            }
            
            header("Location: admin_products.php?success=1");
            exit;
        }
    } else {
        // Add new product
        if ($sizes_string !== NULL && $colors_string !== NULL) {
            $sql = "INSERT INTO products (name, description, price, rating, image_url, sizes, colors) VALUES ('$name', '$description', '$price', '$rating', '$image_url', '$sizes_string', '$colors_string')";
        } elseif ($sizes_string !== NULL) {
            $sql = "INSERT INTO products (name, description, price, rating, image_url, sizes) VALUES ('$name', '$description', '$price', '$rating', '$image_url', '$sizes_string')";
        } elseif ($colors_string !== NULL) {
            $sql = "INSERT INTO products (name, description, price, rating, image_url, colors) VALUES ('$name', '$description', '$price', '$rating', '$image_url', '$colors_string')";
        } else {
            $sql = "INSERT INTO products (name, description, price, rating, image_url) VALUES ('$name', '$description', '$price', '$rating', '$image_url')";
        }
        
        if ($conn->query($sql)) {
            $product_id = $conn->insert_id;
            
            // Add categories
            foreach ($selected_categories as $cat_id) {
                $cat_id = intval($cat_id);
                $conn->query("INSERT INTO product_categories (product_id, category_id) VALUES ($product_id, $cat_id)");
            }
            
            header("Location: admin_products.php?success=1");
            exit;
        }
    }
}

// Get all products WITH their categories
$products_result = $conn->query("
    SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as all_categories
    FROM products p
    LEFT JOIN product_categories pc ON p.id = pc.product_id
    LEFT JOIN categories c ON pc.category_id = c.id
    GROUP BY p.id
    ORDER BY p.id DESC
");

// If editing, get the product and its categories
$edit_product = null;
$edit_product_categories = [];
$edit_product_sizes = [];
$edit_product_colors = [];
$detected_size_type = 'clothing'; // Default

if (isset($_GET['edit'])) {
    $edit_id = intval($_GET['edit']);
    $edit_product = $conn->query("SELECT * FROM products WHERE id = $edit_id")->fetch_assoc();
    
    // Get selected categories for this product
    $cat_result = $conn->query("SELECT category_id FROM product_categories WHERE product_id = $edit_id");
    while ($cat = $cat_result->fetch_assoc()) {
        $edit_product_categories[] = $cat['category_id'];
    }
    
    // Parse sizes and colors
    if (!empty($edit_product['sizes'])) {
        $edit_product_sizes = explode(',', $edit_product['sizes']);
        
        // DETECT WHICH SIZE TYPE THIS PRODUCT USES
        foreach ($edit_product_sizes as $size) {
            $size = trim($size);
            
            if (in_array($size, $size_options['footwear'])) {
                $detected_size_type = 'footwear';
                break;
            } elseif (in_array($size, $size_options['kids'])) {
                $detected_size_type = 'kids';
                break;
            } elseif (in_array($size, $size_options['accessories'])) {
                $detected_size_type = 'accessories';
                break;
            }
        }
    }
    
    if (!empty($edit_product['colors'])) {
        $color_entries = explode(',', $edit_product['colors']);
        foreach ($color_entries as $color_entry) {
            $parts = explode('|', trim($color_entry));
            $edit_product_colors[] = $parts[0];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Admin Panel</title>
    <link rel="stylesheet" href="../assets/css/admin_style.css">
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-logo">
                <img src="../assets/images/1.png" alt="Velvet Vogue Logo" class="admin-logo-image">
            </div>
            <nav class="admin-menu">
                <a href="admin_dashboard.php" class="admin-menu-link">📊 Dashboard</a>
                <a href="admin_products.php" class="admin-menu-link active">📦 Products</a>
                <a href="admin_orders.php" class="admin-menu-link">🛒 Orders</a>
                <a href="admin_users.php" class="admin-menu-link">👥 Users</a>
                <a href="admin_feedback.php" class="admin-menu-link">⭐ Feedback</a>
                <a href="admin_logout.php" class="admin-menu-link admin-logout-link">🚪 Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <h1 class="admin-title">Manage Products</h1>
                <div class="admin-user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                </div>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    Product operation completed successfully!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-error">
                    Product deleted successfully!
                </div>
            <?php endif; ?>

            <!-- Add Product Form -->
            <div class="admin-section">
                <h2 class="admin-section-title"><?php echo isset($_GET['edit']) ? 'Edit Product' : 'Add New Product'; ?></h2>
                <form class="admin-form" method="POST">
                    <?php if (isset($_GET['edit'])): ?>
                        <input type="hidden" name="product_id" value="<?php echo $edit_product['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="admin-form-row two-col">
                        <div class="admin-form-group">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" required 
                                   value="<?php echo isset($edit_product) ? htmlspecialchars($edit_product['name']) : ''; ?>">
                        </div>
                        <div class="admin-form-group">
                            <label for="price">Price (LKR)</label>
                            <input type="number" id="price" name="price" step="0.01" required 
                                   value="<?php echo isset($edit_product) ? $edit_product['price'] : ''; ?>">
                        </div>
                    </div>

                    <!-- RATING INPUT -->
                    <div class="admin-form-group">
                        <label for="rating">
                            Product Rating (0.0 - 5.0)
                        </label>
                        <div class="rating-input-container">
                            <input type="number" 
                                   id="rating" 
                                   name="rating" 
                                   step="0.1" 
                                   min="0" 
                                   max="5" 
                                   value="<?php echo isset($edit_product) ? $edit_product['rating'] : '4.5'; ?>"
                                   required>
                            <div class="rating-stars-preview" id="ratingPreview">
                                <!-- Stars will be generated by JavaScript -->
                            </div>
                        </div>
                        <small style="color: #6b7280; margin-top: 0.5rem; display: block;">
                            Enter a value between 0.0 and 5.0 (e.g., 4.5, 4.7, 5.0)
                        </small>
                    </div>
                    
                    <!-- Categories -->
                    <div class="admin-form-group">
                        <label>Categories (Select all that apply)</label>
                        <div class="checkbox-group">
                            <?php foreach ($all_categories as $category): ?>
                                <label class="checkbox-label">
                                    <input type="checkbox" 
                                           name="categories[]" 
                                           value="<?php echo $category['id']; ?>"
                                           <?php echo in_array($category['id'], $edit_product_categories) ? 'checked' : ''; ?>>
                                    <span><?php echo htmlspecialchars($category['name']); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- SIZE TYPE SELECTOR -->
                    <div class="admin-form-group">
                        <label for="sizeType">Size Type</label>
                        <select id="sizeType" class="admin-select">
                            <option value="clothing" <?php echo $detected_size_type === 'clothing' ? 'selected' : ''; ?>>Clothing Sizes (XS-3XL)</option>
                            <option value="kids" <?php echo $detected_size_type === 'kids' ? 'selected' : ''; ?>>Kids Sizes (2T-5T)</option>
                            <option value="footwear" <?php echo $detected_size_type === 'footwear' ? 'selected' : ''; ?>>Shoe Sizes (6-12)</option>
                            <option value="accessories" <?php echo $detected_size_type === 'accessories' ? 'selected' : ''; ?>>Accessories (One Size)</option>
                        </select>
                    </div>

                    <!-- SIZES SECTION -->
                    <div class="admin-form-group">
                        <label>Available Sizes (Select all that apply)</label>
                        <div class="checkbox-group" id="sizesContainer">
                            <!-- Clothing Sizes -->
                            <div class="size-group" data-type="clothing">
                                <?php foreach ($size_options['clothing'] as $size): ?>
                                    <label class="checkbox-label size-option">
                                        <input type="checkbox" name="sizes[]" value="<?php echo $size; ?>"
                                               <?php echo in_array($size, $edit_product_sizes) ? 'checked' : ''; ?>>
                                        <span><?php echo $size; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                            <!-- Kids Sizes -->
                            <div class="size-group" data-type="kids">
                                <?php foreach ($size_options['kids'] as $size): ?>
                                    <label class="checkbox-label size-option">
                                        <input type="checkbox" name="sizes[]" value="<?php echo $size; ?>"
                                               <?php echo in_array($size, $edit_product_sizes) ? 'checked' : ''; ?>>
                                        <span><?php echo $size; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                            <!-- Footwear Sizes -->
                            <div class="size-group" data-type="footwear">
                                <?php foreach ($size_options['footwear'] as $size): ?>
                                    <label class="checkbox-label size-option">
                                        <input type="checkbox" name="sizes[]" value="<?php echo $size; ?>"
                                               <?php echo in_array($size, $edit_product_sizes) ? 'checked' : ''; ?>>
                                        <span><?php echo $size; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                            <!-- Accessories Sizes -->
                            <div class="size-group" data-type="accessories">
                                <?php foreach ($size_options['accessories'] as $size): ?>
                                    <label class="checkbox-label size-option">
                                        <input type="checkbox" name="sizes[]" value="<?php echo $size; ?>"
                                               <?php echo in_array($size, $edit_product_sizes) ? 'checked' : ''; ?>>
                                        <span><?php echo $size; ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- COLORS SECTION -->
                    <div class="admin-form-group">
                        <label>Available Colors (Select all that apply)</label>
                        <div class="checkbox-group colors-grid">
                            <?php foreach ($available_colors as $color): 
                                $hexCode = isset($colorMapping[$color]) ? $colorMapping[$color] : '#CCCCCC';
                            ?>
                            <label class="checkbox-label color-option">
                                <input type="checkbox" 
                                       name="colors[]" 
                                       value="<?php echo $color; ?>"
                                       <?php echo in_array($color, $edit_product_colors) ? 'checked' : ''; ?>>
                                <span class="color-swatch-admin" style="background-color: <?php echo $hexCode; ?>;"></span>
                                <span><?php echo $color; ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="admin-form-group">
                        <label for="image_url">Product Image URL</label>
                        <input type="url" id="image_url" name="image_url" 
                               placeholder="https://example.com/image.jpg"
                               value="<?php echo isset($edit_product) && !empty($edit_product['image_url']) ? htmlspecialchars($edit_product['image_url']) : ''; ?>">
                        <img id="imagePreview" 
                             src="<?php echo isset($edit_product) && !empty($edit_product['image_url']) ? htmlspecialchars($edit_product['image_url']) : ''; ?>" 
                             alt="Product Preview" 
                             class="image-preview">
                    </div>
                    
                    <div class="admin-form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required><?php echo isset($edit_product) ? htmlspecialchars($edit_product['description']) : ''; ?></textarea>
                    </div>
                    
                    <div class="admin-form-buttons">
                        <button type="submit" class="admin-login-btn">
                            <?php echo isset($_GET['edit']) ? 'Update Product' : 'Add Product'; ?>
                        </button>
                        
                        <?php if (isset($_GET['edit'])): ?>
                            <a href="admin_products.php" class="cancel-btn">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Products List -->
            <div class="admin-section">
                <h2 class="admin-section-title">All Products</h2>
                
                <!-- Search Bar -->
                <div class="search-container">
                    <div class="search-box">
                        <div class="search-icon">🔍</div>
                        <input type="text" id="productSearch" class="search-input" placeholder="Search products by name, category, or price...">
                    </div>
                    <div class="search-stats">
                        <span id="productCount"><?php echo $products_result->num_rows; ?></span> products found
                    </div>
                </div>
                
                
                <?php if ($products_result && $products_result->num_rows > 0): ?>
                <table class="admin-table" id="productsTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Categories</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody">
                        <?php while ($product = $products_result->fetch_assoc()): ?>
                        <tr>
                            <td class="product-image-cell">
                                <?php if (!empty($product['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         class="product-image-small"
                                         onerror="this.style.display='none'">
                                <?php else: ?>
                                    <div class="no-image-placeholder">
                                        No Image
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $product['id']; ?></td>
                            <td class="product-name"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="product-category"><?php echo htmlspecialchars($product['all_categories'] ?: 'Uncategorized'); ?></td>
                            
                            <td class="product-price">LKR <?php echo number_format($product['price'], 2); ?></td>
                            <td>
                                <a href="admin_products.php?edit=<?php echo $product['id']; ?>" class="admin-action-btn">Edit</a>
                                <a href="admin_products.php?delete_id=<?php echo $product['id']; ?>" class="admin-action-btn admin-delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <div id="noResults" class="no-results">
                    No products found matching your search criteria.
                </div>
                <?php else: ?>
                <div class="admin-empty">
                    <p>No products found.</p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="../assets/js/script.js"></script>
</body>
</html>





