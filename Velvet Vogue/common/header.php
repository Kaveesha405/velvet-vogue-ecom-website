<?php
include_once 'db_connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isCurrentPage($pageName) {
    $currentPage = basename($_SERVER['PHP_SELF']);
    return $currentPage === $pageName ? 'active' : '';
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Load cart from database if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = intval($_SESSION['user_id']);
    
    // Use prepared statement to prevent SQL injection - NOW WITH SIZE AND COLOR
    $cart_stmt = $conn->prepare("SELECT c.product_id, c.quantity, c.size, c.color, p.name, p.price, p.image_url 
                                  FROM cart c 
                                  JOIN products p ON c.product_id = p.id 
                                  WHERE c.user_id = ?");
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();
    
    $_SESSION['cart'] = [];
    
    while ($cart_item = $cart_result->fetch_assoc()) {
        // Create unique cart key based on product_id, size, and color
        $cartKey = $cart_item['product_id'];
        if ($cart_item['size'] || $cart_item['color']) {
            $cartKey = $cart_item['product_id'] . '_' . 
                      ($cart_item['size'] ? $cart_item['size'] : 'nosize') . '_' . 
                      ($cart_item['color'] ? $cart_item['color'] : 'nocolor');
        }
        
        $_SESSION['cart'][$cartKey] = [
            'id' => $cart_item['product_id'],
            'name' => $cart_item['name'],
            'price' => $cart_item['price'],
            'image' => $cart_item['image_url'],
            'quantity' => $cart_item['quantity'],
            'size' => $cart_item['size'],
            'color' => $cart_item['color']
        ];
    }
    
    $cart_stmt->close();
}

$cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Velvet Vogue' : 'Velvet Vogue'; ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <!-- Logo -->
                <div class="logo">
                    <img src="../assets/images/1.png" alt="Velvet Vogue Logo" class="logo-image">
                </div>

                <!-- Desktop Navigation -->
                <nav class="desktop-nav">
                    <a href="../user/HomePage.php" class="nav-link <?php echo isCurrentPage('HomePage.php'); ?>">HOME</a>
                    <a href="../user/newArrival.php" class="nav-link <?php echo isCurrentPage('newArrival.php'); ?>">NEW ARRIVALS</a>
                    <a href="../user/men.php" class="nav-link <?php echo isCurrentPage('men.php'); ?>">MEN</a>
                    <a href="../user/women.php" class="nav-link <?php echo isCurrentPage('women.php'); ?>">WOMEN</a>
                    <a href="../user/kids.php" class="nav-link <?php echo isCurrentPage('kids.php'); ?>">KIDS</a>
                    <a href="../user/accessories.php" class="nav-link <?php echo isCurrentPage('accessories.php'); ?>">ACCESSORIES</a>
                </nav>

                <!-- Icons -->
                <div class="header-icons">
                    <form class="search-form header-inline-search" method="GET" action="../user/search.php">
                        <input type="text" name="query" class="search-input" placeholder="Search for products, categories..." required>
                        <button type="submit" class="search-btn" aria-label="Search">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        </button>
                    </form>

                    <?php
                    $profile_link = isset($_SESSION['user_id']) ? '../user/profile.php' : '../user/login.php';
                    $profile_active = isset($_SESSION['user_id']) ? isCurrentPage('profile.php') : isCurrentPage('login.php');
                    ?>
                    <a href="<?php echo $profile_link; ?>" class="icon-btn <?php echo $profile_active ? 'active' : ''; ?>" aria-label="Account">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                    <a href="../user/cart.php" class="icon-btn cart-btn <?php echo isCurrentPage('cart.php') ? 'active' : ''; ?>" aria-label="Shopping Cart">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <?php if ($cartCount > 0): ?>
                        <span class="cart-badge"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                    <!-- Mobile search toggle (shows popover) -->
                    <button class="icon-btn mobile-search-toggle" id="mobileSearchToggle" aria-label="Open Search" aria-expanded="false">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                    <button class="icon-btn menu-toggle" id="menuToggle" aria-label="Menu" aria-controls="mobileNav" aria-expanded="false">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile search popover (visible on small screens when search icon is tapped) -->
        <div class="mobile-search-popover" id="mobileSearchPopover" aria-hidden="true">
            <form class="search-form" method="GET" action="../user/search.php" style="position: relative;">
                <input type="text" name="query" class="search-input" placeholder="Search products..." required>

                <!-- Mirror that shows typed text when native input rendering fails -->
                <div class="search-input-mirror" aria-hidden="true"></div>

                <button type="submit" class="search-btn" aria-label="Search">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
            </form>
        </div>

        <!-- Inline mobile dropdown (shows main navigation on small screens) -->
        <nav class="mobile-dropdown" id="mobileDropdown" aria-hidden="true">
            <a href="HomePage.php" class="mobile-dropdown-link <?php echo isCurrentPage('HomePage.php'); ?>">HOME</a>
            <a href="newArrival.php" class="mobile-dropdown-link <?php echo isCurrentPage('newArrival.php'); ?>">NEW ARRIVALS</a>
            <a href="men.php" class="mobile-dropdown-link <?php echo isCurrentPage('men.php'); ?>">MEN</a>
            <a href="women.php" class="mobile-dropdown-link <?php echo isCurrentPage('women.php'); ?>">WOMEN</a>
            <a href="kids.php" class="mobile-dropdown-link <?php echo isCurrentPage('kids.php'); ?>">KIDS</a>
            <a href="accessories.php" class="mobile-dropdown-link <?php echo isCurrentPage('accessories.php'); ?>">ACCESSORIES</a>
        </nav>
    </header>

    <!-- Mobile Navigation (slides in from right) -->
    <div class="mobile-nav" id="mobileNav" aria-hidden="true" tabindex="-1">
        <div class="mobile-nav-header">
            <h3>Menu</h3>
            <button class="close-nav" id="closeNav" aria-label="Close Menu">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <nav class="mobile-nav-links">
            <a href="HomePage.php" class="mobile-nav-link <?php echo isCurrentPage('HomePage.php'); ?>">HOME</a>
            <a href="newArrival.php" class="mobile-nav-link <?php echo isCurrentPage('newArrival.php'); ?>">NEW ARRIVALS</a>
            <a href="men.php" class="mobile-nav-link <?php echo isCurrentPage('men.php'); ?>">MEN</a>
            <a href="women.php" class="mobile-nav-link <?php echo isCurrentPage('women.php'); ?>">WOMEN</a>
            <a href="kids.php" class="mobile-nav-link <?php echo isCurrentPage('kids.php'); ?>">KIDS</a>
            <a href="accessories.php" class="mobile-nav-link <?php echo isCurrentPage('accessories.php'); ?>">ACCESSORIES</a>
            <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 1rem 0;">
            <a href="<?php echo $profile_link; ?>" class="mobile-nav-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.5rem;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?php echo isset($_SESSION['user_id']) ? 'My Profile' : 'Login'; ?>
            </a>
            <a href="cart.php" class="mobile-nav-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 0.5rem;">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Cart <?php if ($cartCount > 0): ?>(<?php echo $cartCount; ?>)<?php endif; ?>
            </a>
        </nav>
        <div style="padding: 1rem 1.5rem;">
        <form class="search-form" method="GET" action="search.php" style="display: flex; max-width: 100%;">
                <input type="text" name="query" class="search-input" placeholder="Search products..." required>
                <button type="submit" class="search-btn" aria-label="Search">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                </button>
        </form>
        </div>

    </div>

<script>
    // Mobile Menu Toggle - UPDATED VERSION
    const menuToggle = document.getElementById('menuToggle');
    const mobileNav = document.getElementById('mobileNav');
    const closeNav = document.getElementById('closeNav');

    let overlay = document.createElement('div');
    overlay.className = 'mobile-nav-overlay';
    document.body.appendChild(overlay);

    const mobileDropdown = document.getElementById('mobileDropdown');
    const mobileSearchToggle = document.getElementById('mobileSearchToggle');
    const mobileSearchPopover = document.getElementById('mobileSearchPopover');
    const mobileSearchInput = mobileSearchPopover ? mobileSearchPopover.querySelector('.search-input') : null;
    const mobileSearchMirror = mobileSearchPopover ? mobileSearchPopover.querySelector('.search-input-mirror') : null;

    // Ensure mirror exists and has caret element
    if (mobileSearchMirror && !mobileSearchMirror.querySelector('.mirror-caret')) {
        mobileSearchMirror.innerHTML = '<span class="mirror-text"></span><span class="mirror-caret"></span>';
    }

    function updateMirror() {
        if (!mobileSearchMirror || !mobileSearchInput) return;
        const txt = mobileSearchInput.value || '';
        const textEl = mobileSearchMirror.querySelector('.mirror-text');
        if (textEl) {
            if (txt) {
                textEl.textContent = txt;
                mobileSearchMirror.classList.remove('empty');
            } else {
                // show placeholder when empty
                textEl.textContent = mobileSearchInput.placeholder || '';
                mobileSearchMirror.classList.add('empty');
            }
        }
    }

    if (mobileSearchInput) {
        mobileSearchInput.addEventListener('input', updateMirror);
        mobileSearchInput.addEventListener('focus', function() { if (mobileSearchMirror) mobileSearchMirror.classList.remove('hidden'); updateMirror(); });
        mobileSearchInput.addEventListener('blur', function() { if (mobileSearchMirror) mobileSearchMirror.classList.add('hidden'); });
    }

    // If user taps the popover area (not the submit button), force focus into the input
    if (mobileSearchPopover && mobileSearchInput) {
        mobileSearchPopover.addEventListener('pointerdown', function(e) {
            // allow the submit button to function normally
            if (e.target && e.target.closest && e.target.closest('button[type="submit"]')) return;
            // If the click lands on the popover container or its form wrapper, focus input
            if (e.target === mobileSearchPopover || e.target === mobileSearchPopover.querySelector('form') || !mobileSearchInput.contains(e.target)) {
                e.preventDefault();
                mobileSearchInput.focus();
            }
        }, true);
    }

    // Diagnostic listeners (added/removed while popover is open)
    let _globalKeyLogger = null;
    let _pointerLogger = null;
    let _keyboardForwarder = null; // forwards keys when input is not receiving them
    let _focusOnFirstKey = null; // focuses input when first printable key pressed

    function attachDiagnosticListeners() {
        if (!_globalKeyLogger) {
            _globalKeyLogger = function(e) { console.log('GLOBAL KEYDOWN', e.key, e.type, 'target:', e.target && e.target.tagName); };
            document.addEventListener('keydown', _globalKeyLogger, true);
        }
        if (!_pointerLogger) {
            _pointerLogger = function(e) { console.log('GLOBAL POINTER', e.type, 'at', e.clientX, e.clientY, 'topEl:', document.elementFromPoint(e.clientX, e.clientY)); };
            document.addEventListener('pointerdown', _pointerLogger, true);
        }

        // Keyboard forwarder: when popover is open and the input isn't focused, forward printable keys/backspace/enter into it
        if (!_keyboardForwarder) {
            _keyboardForwarder = function(e) {
                try {
                    if (!mobileSearchPopover || !mobileSearchInput) return;
                    if (!mobileSearchPopover.classList.contains('open')) return;

                    // If the input is already focused, let it handle keys normally
                    if (document.activeElement === mobileSearchInput) return;

                    const key = e.key;
                    // ignore pure modifier keys
                    if (key === 'Shift' || key === 'Control' || key === 'Alt' || key === 'Meta' || key === 'Tab') return;

                    // Printable character
                    if (key.length === 1 && !e.ctrlKey && !e.metaKey) {
                        e.preventDefault();
                        const start = mobileSearchInput.selectionStart != null ? mobileSearchInput.selectionStart : mobileSearchInput.value.length;
                        const end = mobileSearchInput.selectionEnd != null ? mobileSearchInput.selectionEnd : start;
                        const newVal = mobileSearchInput.value.slice(0, start) + key + mobileSearchInput.value.slice(end);
                        mobileSearchInput.value = newVal;
                        const pos = start + 1;
                        mobileSearchInput.setSelectionRange(pos, pos);
                        console.log('FORWARDED key into input:', key);
                        // fire input event so any listeners react
                        mobileSearchInput.dispatchEvent(new Event('input', { bubbles: true }));
                        return;
                    }

                    // Backspace
                    if (key === 'Backspace') {
                        e.preventDefault();
                        const start = mobileSearchInput.selectionStart != null ? mobileSearchInput.selectionStart : mobileSearchInput.value.length;
                        const end = mobileSearchInput.selectionEnd != null ? mobileSearchInput.selectionEnd : start;
                        if (start === end && start > 0) {
                            const newVal = mobileSearchInput.value.slice(0, start - 1) + mobileSearchInput.value.slice(end);
                            mobileSearchInput.value = newVal;
                            const pos = start - 1;
                            mobileSearchInput.setSelectionRange(pos, pos);
                        } else if (start !== end) {
                            const newVal = mobileSearchInput.value.slice(0, start) + mobileSearchInput.value.slice(end);
                            mobileSearchInput.value = newVal;
                            mobileSearchInput.setSelectionRange(start, start);
                        }
                        mobileSearchInput.dispatchEvent(new Event('input', { bubbles: true }));
                        console.log('FORWARDED Backspace');
                        return;
                    }

                    // Enter -> submit
                    if (key === 'Enter') {
                        e.preventDefault();
                        const form = mobileSearchInput.closest('form');
                        if (form) form.submit();
                        return;
                    }
                } catch (err) {
                    console.warn('Keyboard forwarder error', err);
                }
            };
            document.addEventListener('keydown', _keyboardForwarder, true);
        }

        // Focus-on-first-key: ensure the input receives actual focus when user starts typing
        if (!_focusOnFirstKey) {
            _focusOnFirstKey = function(e) {
                try {
                    if (!mobileSearchPopover || !mobileSearchInput) return;
                    if (!mobileSearchPopover.classList.contains('open')) return;
                    if (document.activeElement === mobileSearchInput) return;
                    const k = e.key;
                    if (!k || k.length !== 1 || e.ctrlKey || e.metaKey) return;
                    // focus input and set caret at end so subsequent keys go directly to it
                    mobileSearchInput.focus();
                    try { const len = mobileSearchInput.value ? mobileSearchInput.value.length : 0; mobileSearchInput.setSelectionRange(len, len); } catch(e){}
                    console.log('FOCUS-ON-FIRST-KEY triggered; focused input');
                } catch (err) {
                }
            };
            document.addEventListener('keydown', _focusOnFirstKey, true);
        }
    }

    function detachDiagnosticListeners() {
        if (_globalKeyLogger) {
            document.removeEventListener('keydown', _globalKeyLogger, true);
            _globalKeyLogger = null;
        }
        if (_pointerLogger) {
            document.removeEventListener('pointerdown', _pointerLogger, true);
            _pointerLogger = null;
        }
        if (_keyboardForwarder) {
            document.removeEventListener('keydown', _keyboardForwarder, true);
            _keyboardForwarder = null;
        }
        if (_focusOnFirstKey) {
            document.removeEventListener('keydown', _focusOnFirstKey, true);
            _focusOnFirstKey = null;
        }
    }

    // Debug: allow forced-open with ?debug_search=1 so we can see the popover while testing
    try {
        const params = new URLSearchParams(window.location.search);
        if (params.get('debug_search') === '1' && mobileSearchPopover) {
            mobileSearchPopover.classList.add('open');
            mobileSearchPopover.setAttribute('aria-hidden', 'false');
            console.log('DEBUG: forced mobile search popover open via URL param');
            if (mobileSearchInput) mobileSearchInput.focus();
            attachDiagnosticListeners();
        }
    } catch (err) {
        console.warn('DEBUG: URLSearchParams not available', err);
    }

    // Mobile Search Toggle behavior
    if (mobileSearchToggle && mobileSearchPopover) {
        mobileSearchToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('Mobile search toggle clicked. Popover exists:', !!mobileSearchPopover);
            const open = mobileSearchPopover.classList.toggle('open');
            console.log('Popover open state now:', open, 'classList:', mobileSearchPopover.className);
            mobileSearchToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            mobileSearchPopover.setAttribute('aria-hidden', open ? 'false' : 'true');

            // Close other menus when opening search
            if (open) {
                // start capturing global key/pointer diagnostics while popover is open
                attachDiagnosticListeners();
                if (mobileDropdown) {
                    mobileDropdown.classList.remove('open');
                    mobileDropdown.setAttribute('aria-hidden', 'true');
                }
                if (mobileNav) {
                    mobileNav.classList.remove('active');
                }
                overlay.classList.remove('active');

                // Focus input after animation starts
                setTimeout(function() {
                    if (mobileSearchInput) {
                        // Make sure input is enabled and can receive events
                        try {
                            // Ensure the search toggle doesn't keep focus (so keys go to input)
                            if (mobileSearchToggle && typeof mobileSearchToggle.blur === 'function') {
                                try { mobileSearchToggle.blur(); } catch(e){}
                            }

                            mobileSearchInput.disabled = false;
                            mobileSearchInput.readOnly = false;
                            mobileSearchInput.tabIndex = 0;
                            mobileSearchInput.style.zIndex = '10060';
                            mobileSearchInput.style.pointerEvents = 'auto';
                            // Force visible text and caret colors in case global styles hide them
                            mobileSearchInput.style.color = '#111827';
                            mobileSearchInput.style.caretColor = '#111827';
                            mobileSearchInput.focus();
                            // Place caret at end
                            try { const len = mobileSearchInput.value ? mobileSearchInput.value.length : 0; mobileSearchInput.setSelectionRange(len, len); } catch (e) {}
                            console.log('Focused mobile search input');

                            // Ensure mirror shows up and is visible above other elements
                            if (mobileSearchMirror) {
                                try {
                                    mobileSearchMirror.classList.remove('hidden');
                                    mobileSearchMirror.style.display = 'block';
                                    mobileSearchMirror.style.visibility = 'visible';
                                    mobileSearchMirror.style.opacity = '1';
                                    mobileSearchMirror.style.zIndex = '20001';
                                    updateMirror();
                                    console.log('DEBUG: mirror text', mobileSearchMirror.textContent);
                                    console.log('DEBUG: mirror rect', mobileSearchMirror.getBoundingClientRect());
                                    const mrect = mobileSearchMirror.getBoundingClientRect();
                                    const cx = Math.round(mrect.left + Math.min(8, Math.floor(mrect.width/2)));
                                    const cy = Math.round(mrect.top + mrect.height/2);
                                    console.log('DEBUG: elementFromPoint at mirror center:', document.elementFromPoint(cx, cy));
                                } catch (err) { console.warn('DEBUG: mirror show error', err); }
                            }

                        } catch (err) {
                            console.warn('Error enabling/focusing mobileSearchInput', err);
                        }
                    } else {
                        console.warn('mobileSearchInput not found');
                    }

                    // Debug: highlight popover and log position so we can see where it is rendered
                    if (mobileSearchPopover) {
                        try {
                            const rect = mobileSearchPopover.getBoundingClientRect();
                            console.log('DEBUG: popover rect', rect);
                            mobileSearchPopover.style.outline = '3px solid rgba(220,38,38,0.85)';
                            setTimeout(() => { mobileSearchPopover.style.outline = ''; }, 1400);

                            // Check what element is on top at the center of the popover (helps detect overlays)
                            const cx = Math.round(rect.left + rect.width/2);
                            const cy = Math.round(rect.top + rect.height/2);
                            const topEl = document.elementFromPoint(cx, cy);
                            console.log('DEBUG: elementFromPoint at popover center:', topEl, topEl && topEl.className, topEl && topEl.nodeName);

                            // Ensure popover itself accepts pointer events
                            mobileSearchPopover.style.pointerEvents = 'auto';
                        } catch (err) {
                            console.warn('DEBUG: could not get popover rect', err);
                        }
                    }

                    // Add focus/blur/key/touch listeners to help diagnose why typing may not be working
                    if (mobileSearchInput && !mobileSearchInput._diagnosticAttached) {
                        mobileSearchInput.addEventListener('focus', function() { console.log('DIAG: mobileSearchInput focus event'); });
                        mobileSearchInput.addEventListener('blur', function() { console.log('DIAG: mobileSearchInput blur event'); });
                        mobileSearchInput.addEventListener('keydown', function(e) { console.log('DIAG: keydown', e.key); });
                        mobileSearchInput.addEventListener('touchstart', function() { console.log('DIAG: touchstart on input'); this.focus(); });
                        mobileSearchInput._diagnosticAttached = true;
                    }

                    // If a mysterious overlay or element is covering the popover center, briefly highlight it for inspection
                    if (typeof topEl !== 'undefined' && topEl) {
                        try {
                            const oldOutline = topEl.style && topEl.style.outline;
                            topEl.style.outline = '2px dashed rgba(59,130,246,0.85)';
                            setTimeout(() => { if (topEl.style) topEl.style.outline = oldOutline || ''; }, 1400);
                        } catch (err) {  }
                    }
                }, 80);
            } else {
                // stop diagnostic listeners when popover closed
                detachDiagnosticListeners();
                mobileSearchToggle.focus();
            }
        });
    }

    // Close mobile search popover when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileSearchPopover && mobileSearchPopover.classList.contains('open')) {
            if (!mobileSearchPopover.contains(e.target) && !mobileSearchToggle.contains(e.target)) {
                mobileSearchPopover.classList.remove('open');
                mobileSearchPopover.setAttribute('aria-hidden', 'true');
                if (mobileSearchToggle) mobileSearchToggle.setAttribute('aria-expanded', 'false');
                // stop diagnostic listeners when popover closed
                detachDiagnosticListeners();
            }
        }
    });

    if (menuToggle && mobileNav) {
        menuToggle.addEventListener('click', function(e) {
            e.stopPropagation();

            // On small screens show the inline dropdown instead of the slide-in panel
            if (window.innerWidth <= 768 && mobileDropdown) {
                const open = mobileDropdown.classList.toggle('open');
                menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                mobileDropdown.setAttribute('aria-hidden', open ? 'false' : 'true');

                // When opening, close slide-in if it was open
                mobileNav.classList.remove('active');
                overlay.classList.remove('active');

                // Lock body scroll only for slide-in; allow normal flow for inline dropdown
                document.body.style.overflow = '';
            } else {
                // default: open slide-in mobile nav
                mobileNav.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent body scroll
                // Accessibility
                menuToggle.setAttribute('aria-expanded', 'true');
                mobileNav.setAttribute('aria-hidden', 'false');
                mobileNav.focus();
            }
        });
    }

    if (closeNav && mobileNav) {
        closeNav.addEventListener('click', function() {
            mobileNav.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore body scroll
            // Accessibility
            menuToggle.setAttribute('aria-expanded', 'false');
            mobileNav.setAttribute('aria-hidden', 'true');
            menuToggle.focus();
        });
    }

    // Close when clicking overlay
    overlay.addEventListener('click', function() {
        mobileNav.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileNav.setAttribute('aria-hidden', 'true');
        menuToggle.focus();
    });

    // Click outside to close inline mobile dropdown
    document.addEventListener('click', function(e) {
        if (mobileDropdown && mobileDropdown.classList.contains('open')) {
            // ignore clicks inside the dropdown or on the menuToggle
            if (!mobileDropdown.contains(e.target) && !menuToggle.contains(e.target)) {
                mobileDropdown.classList.remove('open');
                mobileDropdown.setAttribute('aria-hidden', 'true');
                menuToggle.setAttribute('aria-expanded', 'false');
            }
        }
    });

    // Close mobile nav when clicking outside (backup)
    document.addEventListener('click', function(e) {
        if (mobileNav && mobileNav.classList.contains('active')) {
            if (!mobileNav.contains(e.target) && !menuToggle.contains(e.target)) {
                mobileNav.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
                menuToggle.setAttribute('aria-expanded', 'false');
                mobileNav.setAttribute('aria-hidden', 'true');
                menuToggle.focus();
            }
        }
    });

    // Close with Escape key (close slide-in or search popover)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (mobileNav && mobileNav.classList.contains('active')) {
                // Trigger close
                if (closeNav) closeNav.click();
                return;
            }

            if (mobileSearchPopover && mobileSearchPopover.classList.contains('open')) {
                mobileSearchPopover.classList.remove('open');
                mobileSearchPopover.setAttribute('aria-hidden', 'true');
                if (mobileSearchToggle) mobileSearchToggle.setAttribute('aria-expanded', 'false');
                if (mobileSearchToggle) mobileSearchToggle.focus();
                // stop diagnostic listeners when popover closed via Escape
                detachDiagnosticListeners();
            }
        }
    });
</script>
