// script.js - Velvet Vogue JavaScript

document.addEventListener('DOMContentLoaded', function () {

    // Mobile Menu Toggle - UPDATED VERSION
    const menuToggle = document.getElementById('menuToggle');
    const mobileNav = document.getElementById('mobileNav');
    const closeNav = document.getElementById('closeNav');

    let overlay = document.createElement('div');
    overlay.className = 'mobile-nav-overlay';
    document.body.appendChild(overlay);

    if (menuToggle && mobileNav) {
        menuToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            mobileNav.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    if (closeNav && mobileNav) {
        closeNav.addEventListener('click', function () {
            mobileNav.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    overlay.addEventListener('click', function () {
        mobileNav.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    });

    // Close mobile nav when clicking outside (backup)
    document.addEventListener('click', function (e) {
        if (mobileNav && mobileNav.classList.contains('active')) {
            if (!mobileNav.contains(e.target) && !menuToggle.contains(e.target)) {
                mobileNav.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        }
    });


    // Hero Slider Functionality
    const slides = document.querySelectorAll('.hero-slide');
    const indicators = document.querySelectorAll('.indicator');
    const totalSlides = slides.length;
    let currentSlide = 0;
    let slideInterval;

    console.log('Slides found:', slides.length);
    console.log('Indicators found:', indicators.length);

    if (slides.length > 0) {
        function showSlide(index) {
            // Remove active class from all slides and indicators
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Add active class to current slide and indicator
            if (slides[index]) {
                slides[index].classList.add('active');
            }
            if (indicators[index]) {
                indicators[index].classList.add('active');
            }
        }

        window.changeSlide = function (direction) {
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            showSlide(currentSlide);

            // Reset auto-slide interval
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        window.goToSlide = function (index) {
            currentSlide = index;
            showSlide(currentSlide);

            // Reset the interval
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        // Auto-advance slides every 5 seconds
        slideInterval = setInterval(nextSlide, 5000);

        console.log('Hero slider initialized');
    }

    initializeSizeTypeSelector();

    // Add to Cart Functionality - FOR PRODUCT CARDS
    console.log('=== ADD TO CART INITIALIZATION ===');
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

    console.log('Found buttons:', addToCartButtons.length);

    if (addToCartButtons.length === 0) {
        console.warn('WARNING: No add-to-cart buttons found on page!');
        console.log('Checking for product cards:', document.querySelectorAll('.product-card').length);
    }

    addToCartButtons.forEach((button, index) => {
        console.log(`Button ${index}:`, {
            element: button,
            productId: button.getAttribute('data-product-id'),
            text: button.textContent
        });

        button.addEventListener('click', function (e) {
            // IMPORTANT: Prevent the parent link from navigating
            e.preventDefault();
            e.stopPropagation();

            console.log('=== BUTTON CLICKED ===');

            const productId = this.getAttribute('data-product-id');
            console.log('Product ID:', productId);

            if (!productId) {
                console.error('ERROR: No product ID found on button!');
                showNotification('Error: Product ID missing', 'error');
                return;
            }

            const originalText = this.textContent;

            // Disable button during request
            this.disabled = true;
            this.textContent = 'Adding...';

            console.log('Sending request to processes/add_to_cart.php');

            // Send AJAX request to add item to cart
            fetch('processes/add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId + '&quantity=1'
            })
                .then(response => {
                    console.log('Response received:', response.status);

                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.text();
                })
                .then(text => {
                    console.log('Raw response text:', text);

                    try {
                        const data = JSON.parse(text);
                        console.log('Parsed JSON data:', data);

                        if (data.success) {
                            console.log('Success! Updating cart badge...');

                            // Update cart badge
                            const cartBadge = document.querySelector('.cart-badge');

                            if (cartBadge) {
                                const newCount = data.cartCount || data.totalItems;
                                cartBadge.textContent = newCount;
                            } else {
                                // Create badge if it doesn't exist
                                const cartBtn = document.querySelector('.cart-btn');

                                if (cartBtn) {
                                    const badge = document.createElement('span');
                                    badge.className = 'cart-badge';
                                    badge.textContent = data.cartCount || data.totalItems;
                                    cartBtn.appendChild(badge);
                                }
                            }

                            // Show success feedback
                            this.textContent = '✓ Added';
                            this.style.background = '#10b981';
                            showNotification('Item added to cart successfully!');

                            // Reset button after 2 seconds
                            setTimeout(() => {
                                this.textContent = originalText;
                                this.style.background = '';
                                this.disabled = false;
                            }, 2000);
                        } else {
                            throw new Error(data.message || 'Failed to add to cart');
                        }
                    } catch (parseError) {
                        console.error('JSON Parse Error:', parseError);
                        throw new Error('Invalid response from server: ' + parseError.message);
                    }
                })
                .catch(error => {
                    console.error('=== ERROR CAUGHT ===');
                    console.error('Error:', error);

                    showNotification(error.message || 'Failed to add product to cart', 'error');
                    this.textContent = originalText;
                    this.disabled = false;
                });
        });
    });

    console.log('=== INITIALIZATION COMPLETE ===');

    // ADMIN Product Search Functionality (only runs on admin pages)
    initializeAdminProductSearch();

    // Initialize Feedback Search and Filter (only runs on feedback page)
    initializeFeedbackFunctionality();

    // ========================================
    // LIVE IMAGE PREVIEW - FIXED VERSION
    // ========================================
    const imageUrlInput = document.getElementById('image_url');
    const imagePreview = document.getElementById('imagePreview');

    if (imageUrlInput && imagePreview) {
        console.log('Image preview elements found - initializing...');

        // Show existing image if editing
        if (imageUrlInput.value.trim()) {
            imagePreview.src = imageUrlInput.value.trim();
            imagePreview.style.display = 'block';
            console.log('Existing image loaded:', imageUrlInput.value.trim());
        }

        imageUrlInput.addEventListener('input', function () {
            const url = this.value.trim();
            console.log('Image URL input changed:', url);

            if (url) {
                imagePreview.src = url;
                imagePreview.style.display = 'block';

                // Hide image if it fails to load
                imagePreview.onerror = function () {
                    console.log('Image failed to load:', url);
                    this.style.display = 'none';
                };

                imagePreview.onload = function () {
                    console.log('Image loaded successfully:', url);
                };
            } else {
                imagePreview.style.display = 'none';
                console.log('Image URL cleared');
            }
        });

        console.log('Image preview initialized successfully');
    } else {
        console.log('Image preview elements not found on this page');
    }


    // Product Details Page Quantity Controls
    initializeQuantityControls();

    // Product Details Page - Size and Color Selection
    initializeSizeColorSelection();

    // Product Details Page - Add to Cart with Quantity, Size, and Color
    initializeProductDetailsAddToCart();
});

// SIZE AND COLOR SELECTION
function initializeSizeColorSelection() {
    const sizeButtons = document.querySelectorAll('.size-btn');
    const colorButtons = document.querySelectorAll('.color-btn');
    const selectedSizeText = document.getElementById('selectedSizeText');
    const selectedColorText = document.getElementById('selectedColorText');

    let selectedSize = null;
    let selectedColor = null;

    // Size button click handlers
    sizeButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove active class from all size buttons
            sizeButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Store selected size
            selectedSize = this.getAttribute('data-size');

            // Update text display
            if (selectedSizeText) {
                selectedSizeText.textContent = selectedSize;
                selectedSizeText.style.color = '#9333ea';
                selectedSizeText.style.fontWeight = '600';
            }

            console.log('Selected size:', selectedSize);
        });
    });

    // Color button click handlers
    colorButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Remove active class from all color buttons
            colorButtons.forEach(btn => btn.classList.remove('active'));

            // Add active class to clicked button
            this.classList.add('active');

            // Store selected color
            selectedColor = this.getAttribute('data-color');

            // Update text display
            if (selectedColorText) {
                selectedColorText.textContent = selectedColor;
                selectedColorText.style.color = '#9333ea';
                selectedColorText.style.fontWeight = '600';
            }

            console.log('Selected color:', selectedColor);
        });
    });

    // Store selections globally for add to cart function
    window.getSelectedSize = function () {
        return selectedSize;
    };

    window.getSelectedColor = function () {
        return selectedColor;
    };
}

// Product Details Page - Add to Cart with Quantity, Size, and Color
function initializeProductDetailsAddToCart() {
    const addToCartDetailBtn = document.getElementById('addToCartDetailBtn');
    const quantityInput = document.getElementById('quantity');

    if (!addToCartDetailBtn) {
        console.log('Product details add-to-cart button not found - skipping initialization');
        return;
    }

    console.log('Initializing product details add-to-cart...');

    addToCartDetailBtn.addEventListener('click', function () {
        const productId = this.getAttribute('data-product-id');
        const quantity = parseInt(quantityInput.value) || 1;
        const hasSizes = this.getAttribute('data-has-sizes') === 'true';
        const hasColors = this.getAttribute('data-has-colors') === 'true';

        // Get selected size and color
        const selectedSize = window.getSelectedSize ? window.getSelectedSize() : null;
        const selectedColor = window.getSelectedColor ? window.getSelectedColor() : null;

        if (!productId) {
            showNotification('Error: Product ID missing', 'error');
            return;
        }

        // Validate size selection if product has sizes
        if (hasSizes && !selectedSize) {
            showNotification('Please select a size', 'error');

            // Shake the size selector to draw attention
            const sizeSelector = document.getElementById('sizeSelector');
            if (sizeSelector) {
                sizeSelector.style.animation = 'shake 0.5s';
                setTimeout(() => {
                    sizeSelector.style.animation = '';
                }, 500);
            }
            return;
        }

        // Validate color selection if product has colors
        if (hasColors && !selectedColor) {
            showNotification('Please select a color', 'error');

            // Shake the color selector to draw attention
            const colorSelector = document.getElementById('colorSelector');
            if (colorSelector) {
                colorSelector.style.animation = 'shake 0.5s';
                setTimeout(() => {
                    colorSelector.style.animation = '';
                }, 500);
            }
            return;
        }

        const originalText = this.textContent;
        this.disabled = true;
        this.textContent = 'Adding...';

        // Build request body
        let requestBody = 'product_id=' + productId + '&quantity=' + quantity;
        if (selectedSize) {
            requestBody += '&size=' + encodeURIComponent(selectedSize);
        }
        if (selectedColor) {
            requestBody += '&color=' + encodeURIComponent(selectedColor);
        }

        // Send AJAX request
        fetch('processes/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: requestBody
        })
            .then(response => response.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);

                    if (data.success) {
                        // Update cart badge
                        const cartBadge = document.querySelector('.cart-badge');

                        if (cartBadge) {
                            cartBadge.textContent = data.cartCount || data.totalItems;
                        } else {
                            const cartBtn = document.querySelector('.cart-btn');
                            if (cartBtn) {
                                const badge = document.createElement('span');
                                badge.className = 'cart-badge';
                                badge.textContent = data.cartCount || data.totalItems;
                                cartBtn.appendChild(badge);
                            }
                        }

                        // Show success
                        this.textContent = '✓ Added to Cart';
                        this.style.background = 'linear-gradient(135deg, #10b981, #059669)';

                        // Build success message
                        let successMsg = 'Added ' + quantity + ' item(s) to cart';
                        if (selectedSize || selectedColor) {
                            successMsg += ' (';
                            if (selectedSize) successMsg += 'Size: ' + selectedSize;
                            if (selectedSize && selectedColor) successMsg += ', ';
                            if (selectedColor) successMsg += 'Color: ' + selectedColor;
                            successMsg += ')';
                        }
                        showNotification(successMsg);

                        // Reset after 2 seconds
                        setTimeout(() => {
                            this.textContent = originalText;
                            this.style.background = '';
                            this.disabled = false;
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Failed to add to cart');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification(error.message || 'Failed to add product to cart', 'error');
                    this.textContent = originalText;
                    this.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Failed to add product to cart', 'error');
                this.textContent = originalText;
                this.disabled = false;
            });
    });

    console.log('Product details add-to-cart initialized successfully');
}

// Notification System
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.textContent = message;

    // Add styles
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.padding = '1rem 1.5rem';
    notification.style.borderRadius = '8px';
    notification.style.color = 'white';
    notification.style.fontWeight = '600';
    notification.style.zIndex = '9999';
    notification.style.animation = 'slideIn 0.3s ease-out';
    notification.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';

    if (type === 'success') {
        notification.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    } else {
        notification.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    }

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add animation keyframes
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
`;
document.head.appendChild(style);

// Smooth Scroll for Navigation Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Search Functionality
const searchBtn = document.querySelector('.icon-btn[aria-label="Search"]');
if (searchBtn) {
    searchBtn.addEventListener('click', function () {
        window.location.href = 'search.php';
    });
}

// ADMIN Product Search Functionality - RENAMED AND ISOLATED
function initializeAdminProductSearch() {
    console.log('=== Attempting to initialize admin product search ===');

    const searchInput = document.getElementById('productSearch');
    const productsTableBody = document.getElementById('productsTableBody');
    const productCount = document.getElementById('productCount');
    const noResults = document.getElementById('noResults');
    const productsTable = document.getElementById('productsTable');

    console.log('Elements found:', {
        searchInput: !!searchInput,
        productsTableBody: !!productsTableBody,
        productCount: !!productCount,
        noResults: !!noResults,
        productsTable: !!productsTable
    });

    // Only run if we're on the admin products page
    if (!searchInput || !productsTableBody) {
        console.log('Admin product search elements not found - skipping initialization');
        return;
    }

    console.log('Initializing ADMIN product search...');

    // Store original products for filtering
    const originalRows = Array.from(productsTableBody.querySelectorAll('tr'));
    console.log('Found', originalRows.length, 'product rows');

    searchInput.addEventListener('input', function () {
        console.log('Search input event triggered! Value:', this.value);

        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        originalRows.forEach(row => {
            const cells = row.querySelectorAll('td');

            const id = cells[1] ? cells[1].textContent.toLowerCase() : '';
            const name = cells[2] ? cells[2].textContent.toLowerCase() : '';
            const category = cells[3] ? cells[3].textContent.toLowerCase() : '';
            const price = cells[4] ? cells[4].textContent.toLowerCase() : '';

            const matches = name.includes(searchTerm) ||
                category.includes(searchTerm) ||
                price.includes(searchTerm) ||
                id.includes(searchTerm);

            if (matches) {
                row.style.display = '';
                visibleCount++;

                if (searchTerm) {
                    highlightText(row, searchTerm);
                } else {
                    removeHighlights(row);
                }
            } else {
                row.style.display = 'none';
                removeHighlights(row);
            }
        });

        if (productCount) {
            productCount.textContent = visibleCount;
        }

        if (noResults) {
            if (visibleCount === 0 && searchTerm) {
                noResults.style.display = 'block';
                if (productsTable) productsTable.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                if (productsTable) productsTable.style.display = 'table';
            }
        }
    });

    searchInput.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
    });

    searchInput.addEventListener('search', function () {
        if (!this.value) {
            originalRows.forEach(row => removeHighlights(row));
        }
    });

    console.log('Admin product search initialized successfully');
}

// Feedback Page Search and Filter Functionality
function initializeFeedbackFunctionality() {
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.filter-btn');
    const inquiriesContainer = document.getElementById('inquiriesContainer');
    const noResults = document.getElementById('noResults');

    if (!searchInput || !inquiriesContainer) {
        console.log('Feedback search elements not found - skipping initialization');
        return;
    }

    console.log('Initializing feedback search...');

    const inquiryCards = Array.from(inquiriesContainer.querySelectorAll('.inquiry-card'));
    let currentFilter = 'all';

    // Filter functionality
    filterButtons.forEach(button => {
        button.addEventListener('click', function () {
            // Update active button
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            // Update current filter
            currentFilter = this.getAttribute('data-status');

            // Apply filter
            applyFilters();
        });
    });

    // Search functionality
    searchInput.addEventListener('input', function () {
        applyFilters();
    });

    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let visibleCount = 0;

        inquiryCards.forEach(card => {
            const status = card.getAttribute('data-status');
            const name = card.querySelector('.inquiry-name')?.textContent.toLowerCase() || '';
            const email = card.querySelector('.inquiry-email')?.textContent.toLowerCase() || '';
            const message = card.querySelector('.inquiry-message p')?.textContent.toLowerCase() || '';

            // Check status filter
            const statusMatch = currentFilter === 'all' || status === currentFilter;

            // Check search term
            const searchMatch = !searchTerm ||
                name.includes(searchTerm) ||
                email.includes(searchTerm) ||
                message.includes(searchTerm);

            if (statusMatch && searchMatch) {
                card.style.display = '';
                visibleCount++;

                // Highlight search terms
                if (searchTerm) {
                    highlightInquiry(card, searchTerm);
                } else {
                    removeInquiryHighlights(card);
                }
            } else {
                card.style.display = 'none';
                removeInquiryHighlights(card);
            }
        });

        // Show/hide no results message
        if (noResults) {
            if (visibleCount === 0) {
                noResults.style.display = 'block';
                inquiriesContainer.style.display = 'none';
            } else {
                noResults.style.display = 'none';
                inquiriesContainer.style.display = 'flex';
            }
        }
    }

    function highlightInquiry(card, searchTerm) {
        const nameElement = card.querySelector('.inquiry-name');
        const emailElement = card.querySelector('.inquiry-email');
        const messageElement = card.querySelector('.inquiry-message p');

        if (nameElement) {
            const originalText = nameElement.textContent;
            const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
            nameElement.innerHTML = originalText.replace(regex, '<span class="highlight">$1</span>');
        }

        if (emailElement) {
            const originalText = emailElement.textContent;
            const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
            emailElement.innerHTML = originalText.replace(regex, '<span class="highlight">$1</span>');
        }

        if (messageElement) {
            const originalText = messageElement.textContent;
            const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
            messageElement.innerHTML = originalText.replace(regex, '<span class="highlight">$1</span>');
        }
    }

    function removeInquiryHighlights(card) {
        const highlightedElements = card.querySelectorAll('.highlight');
        highlightedElements.forEach(span => {
            const text = document.createTextNode(span.textContent);
            span.parentNode.replaceChild(text, span);
        });
    }

    console.log('Feedback search initialized successfully');
}

function highlightText(row, searchTerm) {
    const cells = row.querySelectorAll('td:not(:first-child):not(:last-child)');

    cells.forEach(cell => {
        const originalText = cell.textContent;
        const regex = new RegExp(`(${escapeRegex(searchTerm)})`, 'gi');
        const highlightedText = originalText.replace(regex, '<span class="highlight">$1</span>');

        if (highlightedText !== originalText) {
            cell.innerHTML = highlightedText;
        }
    });
}

function removeHighlights(row) {
    const cells = row.querySelectorAll('td');

    cells.forEach(cell => {
        const highlightedSpans = cell.querySelectorAll('.highlight');
        highlightedSpans.forEach(span => {
            const text = document.createTextNode(span.textContent);
            span.parentNode.replaceChild(text, span);
        });
    });
}

function escapeRegex(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

// Quantity Controls for Product Details Page
function initializeQuantityControls() {
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const qtyInput = document.getElementById('quantity');

    if (!decreaseBtn || !increaseBtn || !qtyInput) {
        console.log('Quantity control elements not found - skipping initialization');
        return;
    }

    console.log('Initializing quantity controls...');

    decreaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(qtyInput.value);
        if (currentValue > 1) {
            qtyInput.value = currentValue - 1;
        }
    });

    increaseBtn.addEventListener('click', function () {
        let currentValue = parseInt(qtyInput.value);
        qtyInput.value = currentValue + 1;
    });

    // Ensure only numbers can be entered
    qtyInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value === '' || parseInt(this.value) < 1) {
            this.value = 1;
        }
    });

    console.log('Quantity controls initialized successfully');
}

// ============================================
// CART PAGE QUANTITY CONTROLS
// ============================================
// Global functions for cart quantity controls
window.increaseQuantity = function (btn) {
    const form = btn.closest('form');
    const input = form.querySelector('.quantity-input');
    input.value = parseInt(input.value) + 1;
    form.submit();
}

window.decreaseQuantity = function (btn) {
    const form = btn.closest('form');
    const input = form.querySelector('.quantity-input');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        form.submit();
    }
}

// ========================================
// SIZE TYPE SELECTOR FUNCTIONALITY
// ========================================
function initializeSizeTypeSelector() {
    console.log('=== SIZE SWITCHER STARTING ===');

    const sizeTypeSelector = document.getElementById('sizeType');

    if (!sizeTypeSelector) {
        console.log('Size type selector not found - skipping initialization');
        return;
    }

    console.log('✓ Found size type selector');

    // Get all size groups
    const allSizeGroups = document.querySelectorAll('.size-group');
    console.log('Size groups found:', allSizeGroups.length);

    // Store initial checked states to preserve them
    const initialCheckedStates = new Map();
    allSizeGroups.forEach(group => {
        const groupType = group.getAttribute('data-type');
        const checkedBoxes = [];
        group.querySelectorAll('input[type="checkbox"][name="sizes[]"]:checked').forEach(cb => {
            checkedBoxes.push(cb.value);
        });
        if (checkedBoxes.length > 0) {
            initialCheckedStates.set(groupType, checkedBoxes);
        }
    });

    // Function to switch size groups
    function switchSizeGroup(selectedType) {
        console.log('--- SWITCHING TO:', selectedType, '---');

        allSizeGroups.forEach((group) => {
            const groupType = group.getAttribute('data-type');

            if (groupType === selectedType) {
                group.style.display = 'flex';
                group.classList.add('active');
                console.log('✓ SHOWING:', groupType);
            } else {
                group.style.display = 'none';
                group.classList.remove('active');

                // FIXED: Only uncheck if this is a user-initiated change, not initial load
                // AND only if there were no initial selections for this group
                if (!initialCheckedStates.has(groupType)) {
                    const checkboxes = group.querySelectorAll('input[type="checkbox"][name="sizes[]"]');
                    checkboxes.forEach(cb => cb.checked = false);
                }
                console.log('✗ HIDING:', groupType);
            }
        });
    }

    // Listen for dropdown changes
    sizeTypeSelector.addEventListener('change', function () {
        console.log('🔄 DROPDOWN CHANGED to:', this.value);
        switchSizeGroup(this.value);
    });

    // Initialize with the dropdown's current selected value
    const initialType = sizeTypeSelector.value;
    console.log('🚀 Initializing with type:', initialType);
    switchSizeGroup(initialType);

    console.log('=== SIZE SWITCHER READY ===');
}

document.addEventListener('DOMContentLoaded', function () {
    const ratingInput = document.getElementById('rating');
    const ratingPreview = document.getElementById('ratingPreview');

    // Only initialize rating preview if both elements exist on the current page
    if (ratingInput && ratingPreview) {
        function updateRatingPreview() {
            const rating = parseFloat(ratingInput.value) || 0;
            let starsHTML = '';

            for (let i = 1; i <= 5; i++) {
                if (i <= Math.floor(rating)) {
                    starsHTML += '<span class="star-preview filled">★</span>';
                } else if (i - 0.5 <= rating) {
                    starsHTML += '<span class="star-preview half">★</span>';
                } else {
                    starsHTML += '<span class="star-preview">★</span>';
                }
            }

            ratingPreview.innerHTML = starsHTML + '<span class="rating-value-preview">' + rating.toFixed(1) + ' / 5.0</span>';
        }

        ratingInput.addEventListener('input', updateRatingPreview);
        updateRatingPreview(); // Initial load
    }
});

function toggleProducts(category) {
    const container = document.getElementById(category + '-products-container');
    const button = document.getElementById(category + '-show-more');
    const hiddenProducts = container.querySelectorAll('.hidden-products');
    const buttonText = button.querySelector('.btn-text');

    if (container.classList.contains('expanded')) {
        // Collapse
        hiddenProducts.forEach(product => {
            product.style.display = 'none';
        });
        container.classList.remove('expanded');
        button.classList.remove('expanded');
        buttonText.textContent = 'Show More';

        // Scroll to section title
        container.scrollIntoView({ behavior: 'smooth', block: 'start' });
    } else {
        // Expand
        hiddenProducts.forEach(product => {
            product.style.display = 'block';
        });
        container.classList.add('expanded');
        button.classList.add('expanded');
        buttonText.textContent = 'Show Less';
    }
}