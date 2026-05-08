<?php
$pageTitle = "Contact Us";
include '../common/db_connect.php';
include '../common/header.php';

// Initialize variables
$successMessage = null;
$errorMessage = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If there are errors, show error message
    if (!empty($errors)) {
        $errorMessage = implode(", ", $errors);
    } else {
        // Save to database
        try {
            $stmt = $conn->prepare("INSERT INTO inquiries (name, email, message, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("sss", $name, $email, $message);
            
            if ($stmt->execute()) {
                $successMessage = "Thank you for contacting us! We'll get back to you within 24-48 hours.";
                // Clear form fields after successful submission
                $name = $email = $message = '';
            } else {
                throw new Exception("Failed to submit inquiry");
            }
        } catch (Exception $e) {
            $errorMessage = "Sorry, something went wrong. Please try again later.";
        }
    }
}
?>

    <!-- Contact Section -->
    <section class="page-section">
        <div class="container">
            <div class="page-wrapper">
                <h1 class="page-title">CONTACT US</h1>
                
                <div class="page-intro">
                    <p>Have a question or need assistance? We're here to help! Reach out to us through any of the following channels, and our team will get back to you as soon as possible.</p>
                </div>

                <div class="content-block">
                    <div class="content-marker"></div>
                    <h2 class="content-heading">EMAIL US</h2>
                </div>
                <p class="content-text">For general inquiries, product questions, or support, email us at <strong>support@velvetvogue.lk</strong>. We typically respond within 24-48 hours during business days.</p>

                <div class="content-block">
                    <div class="content-marker"></div>
                    <h2 class="content-heading">CALL US</h2>
                </div>
                <p class="content-text">Prefer to speak with someone directly? Call us at <strong>+94 11 234 5678</strong>. Our customer service team is available Monday to Saturday, 9:00 AM to 6:00 PM.</p>

                <div class="content-block">
                    <div class="content-marker"></div>
                    <h2 class="content-heading">VISIT OUR STORE</h2>
                </div>
                <p class="content-text">Come visit our flagship store at <strong>123 Fashion Avenue, Colombo 03, Sri Lanka</strong>. Experience our collections in person and get personalized styling advice from our fashion experts.</p>

                <div class="content-block">
                    <div class="content-marker"></div>
                    <h2 class="content-heading">SOCIAL MEDIA</h2>
                </div>
                <p class="content-text">Follow us on Instagram, Facebook, and Twitter @velvetvogue for the latest updates, style inspiration, and exclusive offers. Send us a direct message on any platform for quick responses.</p>

                <div class="content-block">
                    <div class="content-marker"></div>
                    <h2 class="content-heading">BUSINESS HOURS</h2>
                </div>
                <p class="content-text">Monday - Saturday: 9:00 AM - 6:00 PM. Sunday & Public Holidays: Closed. Online orders can be placed 24/7.</p>
            </div>
        </div>
    </section>

    <!-- Inquiry Form -->
    <section class="inquiry-section">
        <div class="container">
            <h2 class="section-title">Send Us a Message</h2>
            
            <!-- Success Message -->
            <?php if ($successMessage): ?>
            <div class="alert alert-success" id="successAlert">
                <?php echo htmlspecialchars($successMessage); ?>
            </div>
            <?php endif; ?>
            
            <!-- Error Message -->
            <?php if ($errorMessage): ?>
            <div class="alert alert-error" id="errorAlert">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
            <?php endif; ?>
            
            <form class="inquiry-form" method="POST" action="">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" name="name" id="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Your Message</label>
                    <textarea name="message" id="message" rows="5" required><?php echo isset($message) ? htmlspecialchars($message) : ''; ?></textarea>
                </div>
                <button type="submit" class="auth-btn">Submit Message</button>
            </form>
        </div>
    </section>

    <script>
        // Auto-hide success and error messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            function fadeOutAlert(alert) {
                if (alert) {
                    setTimeout(function() {
                        alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        alert.style.opacity = '0';
                        alert.style.transform = 'translateY(-20px)';
                        
                        setTimeout(function() {
                            alert.style.display = 'none';
                        }, 500);
                    }, 5000); // Wait 5 seconds before fading out
                }
            }
            
            fadeOutAlert(successAlert);
            fadeOutAlert(errorAlert);
        });
    </script>

<?php
include '../common/footer.php';
?>



