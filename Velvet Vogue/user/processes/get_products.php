<?php
include '../common/db_connect.php';

// Get products by a single category
function getProductsByCategory($category) {
    global $conn;
    $category = $conn->real_escape_string($category);
    
    // NEW: Query using the product_categories junction table
    $sql = "SELECT DISTINCT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as all_categories
            FROM products p
            INNER JOIN product_categories pc ON p.id = pc.product_id
            INNER JOIN categories c1 ON pc.category_id = c1.id
            LEFT JOIN product_categories pc2 ON p.id = pc2.product_id
            LEFT JOIN categories c ON pc2.category_id = c.id
            WHERE c1.name = '$category'
            GROUP BY p.id
            ORDER BY p.created_at DESC";
    
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Get products by multiple categories (e.g., "Men" AND "Casual Wear")
function getProductsByMultipleCategories($categories) {
    global $conn;
    
    if (empty($categories)) {
        return [];
    }
    
    // Escape all category names
    $escapedCategories = array_map(function($cat) use ($conn) {
        return "'" . $conn->real_escape_string($cat) . "'";
    }, $categories);
    
    $categoryList = implode(',', $escapedCategories);
    $categoryCount = count($categories);
    
    // Find products that have ALL specified categories
    $sql = "SELECT p.*, GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as all_categories
            FROM products p
            INNER JOIN product_categories pc ON p.id = pc.product_id
            INNER JOIN categories c ON pc.category_id = c.id
            WHERE c.name IN ($categoryList)
            GROUP BY p.id
            HAVING COUNT(DISTINCT c.id) = $categoryCount
            ORDER BY p.created_at DESC";
    
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Get all products with their categories
function getAllProducts() {
    global $conn;
    
    // NEW: Include all categories for each product
    $sql = "SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as all_categories
            FROM products p
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            GROUP BY p.id
            ORDER BY p.created_at DESC";
    
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Get a single product by ID with all its categories
function getProductById($id) {
    global $conn;
    $id = intval($id);
    
    // NEW: Include all categories
    $sql = "SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as all_categories
            FROM products p
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            WHERE p.id = $id
            GROUP BY p.id";
    
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

// Get categories for a specific product
function getProductCategories($productId) {
    global $conn;
    $productId = intval($productId);
    
    $sql = "SELECT c.* 
            FROM categories c
            INNER JOIN product_categories pc ON c.id = pc.category_id
            WHERE pc.product_id = $productId
            ORDER BY c.name";
    
    $result = $conn->query($sql);
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

// Get all available categories
function getAllCategories() {
    global $conn;
    
    $sql = "SELECT * FROM categories ORDER BY name";
    $result = $conn->query($sql);
    
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

// Get new products with categories
function getNewProducts($limit = 6) {
    global $conn;
    $limit = intval($limit);
    
    $sql = "SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') as all_categories
            FROM products p
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            GROUP BY p.id
            ORDER BY p.created_at DESC 
            LIMIT $limit";
    
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}

// Get best seller products with categories
// Now sorts by sales count first, then by rating as a tiebreaker
function getBestSellerProducts($limit = 6) {
    global $conn;
    $limit = intval($limit);
    
    $sql = "SELECT p.*, COUNT(oi.id) as sold_count, 
                   GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') as all_categories
            FROM products p 
            LEFT JOIN order_items oi ON p.id = oi.product_id 
            LEFT JOIN product_categories pc ON p.id = pc.product_id
            LEFT JOIN categories c ON pc.category_id = c.id
            GROUP BY p.id 
            ORDER BY sold_count DESC, p.rating DESC
            LIMIT $limit";
    
    $result = $conn->query($sql);
    
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    return $products;
}


// Add a product to a category
function addProductToCategory($productId, $categoryId) {
    global $conn;
    $productId = intval($productId);
    $categoryId = intval($categoryId);
    
    $sql = "INSERT IGNORE INTO product_categories (product_id, category_id) 
            VALUES ($productId, $categoryId)";
    
    return $conn->query($sql);
}

// Remove a product from a category
function removeProductFromCategory($productId, $categoryId) {
    global $conn;
    $productId = intval($productId);
    $categoryId = intval($categoryId);
    
    $sql = "DELETE FROM product_categories 
            WHERE product_id = $productId AND category_id = $categoryId";
    
    return $conn->query($sql);
}

// Set all categories for a product (replaces existing)
function setProductCategories($productId, $categoryIds) {
    global $conn;
    $productId = intval($productId);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Remove all existing categories
        $conn->query("DELETE FROM product_categories WHERE product_id = $productId");
        
        // Add new categories
        foreach ($categoryIds as $categoryId) {
            $categoryId = intval($categoryId);
            $conn->query("INSERT INTO product_categories (product_id, category_id) 
                         VALUES ($productId, $categoryId)");
        }
        
        // Commit transaction
        $conn->commit();
        return true;
        
    } catch (Exception $e) {
        // Rollback on error
        $conn->rollback();
        return false;
    }
}
// Get products by parent category and subcategory
function getProductsByParentAndSubcategory($parentCategory, $subcategory) {
    return getProductsByMultipleCategories([$parentCategory, $subcategory]);
}
?>



