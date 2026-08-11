<?php
session_start();
require_once 'connection.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real application, you would save this to a database or send an email
        $success = 'Thank you for your message! We will get back to you soon.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Environmental Pollution Monitoring System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-leaf"></i>
                <span>Pollution Monitor</span>
            </div>
            <div class="nav-menu">
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_type'] === 'user'): ?>
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                    <a href="outputs.php" class="nav-link">Reports</a>
                    <a href="about.php" class="nav-link">About</a>
                    <a href="contact.php" class="nav-link active">Contact</a>
                    <div class="user-menu">
                        <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="logout.php" class="nav-link logout-btn">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="about.php" class="nav-link">About</a>
                    <a href="contact.php" class="nav-link active">Contact</a>
                    <a href="login.php" class="nav-link">Login</a>
                    <a href="register.php" class="nav-link register-btn">Register</a>
                <?php endif; ?>
            </div>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Contact Header -->
    <section class="contact-header">
        <div class="container">
            <div class="contact-hero">
                <h1>Contact Us</h1>
                <p class="contact-subtitle">Get in touch with our team</p>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-content">
        <div class="container">
            <div class="contact-grid">
                <!-- Contact Form -->
                <div class="contact-form-section">
                    <div class="form-container-Contact">
                        <h2 class="form-title">Send us a Message</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i>
                                <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" id="name" name="name" class="form-input" 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" id="email" name="email" class="form-input" 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="subject" class="form-label">Subject *</label>
                                <select id="subject" name="subject" class="form-input" required>
                                    <option value="">Select a subject</option>
                                    <option value="General Inquiry" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                                    <option value="Research Collaboration" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Research Collaboration') ? 'selected' : ''; ?>>Research Collaboration</option>
                                    <option value="Data Access Request" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Data Access Request') ? 'selected' : ''; ?>>Data Access Request</option>
                                    <option value="Technical Support" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Technical Support') ? 'selected' : ''; ?>>Technical Support</option>
                                    <option value="Media Inquiry" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Media Inquiry') ? 'selected' : ''; ?>>Media Inquiry</option>
                                    <option value="Other" <?php echo (isset($_POST['subject']) && $_POST['subject'] === 'Other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="message" class="form-label">Message *</label>
                                <textarea id="message" name="message" class="form-input" rows="6" 
                                          placeholder="Please describe your inquiry in detail..." required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            
                            <button type="submit" class="form-button">
                                <i class="fas fa-paper-plane"></i> Send Message
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="contact-info-section">
                    <div class="contact-info-card">
                        <h3>Get in Touch</h3>
                        <p>We welcome inquiries about our platform, collaboration opportunities, and questions about environmental monitoring in the Aseer region.</p>
                        
                        <div class="contact-details">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-university"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Headquarters</h4>
                                    <p>Environmental Monitoring Center</p>
                                    <p>Abha, Aseer Region, Saudi Arabia</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Email</h4>
                                    <p>info@pollutionmonitor.com</p>
                                    <p>contact@pollutionmonitor.com</p>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div class="contact-text">
                                    <h4>Phone</h4>
                                    <p>+966 54 365 5456</p>
                                    <p>+966 54 465 8546</p>
                                </div>
                            </div>
                            
                           
                        </div>
                    </div>

                  
                    <!-- Social Media -->
                    <div class="social-card">
                        <h3>Follow Us</h3>
                        <div class="social-links">
                            <a href="https://x.com/" class="social-link twitter">
                                <i class="fab fa-twitter"></i>
                               
                            </a>
                            <a href="https://www.linkedin.com/" class="social-link linkedin">
                                <i class="fab fa-linkedin"></i>
                            
                            </a>
                            <a href="https://www.facebook.com/" class="social-link facebook">
                                <i class="fab fa-facebook"></i>
                           
                            </a>
                            <a href="https://www.instagram.com/" class="social-link instagram">
                                <i class="fab fa-instagram"></i>
                               
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

  

    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Environmental Pollution Monitoring</h3>
                    <p>Environmental Monitoring Platform 2026</p>
                    <div class="social-links">
                        <a href="https://x.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/"><i class="fab fa-linkedin"></i></a>
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="about.php">About</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="login.php">Login</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Research Areas</h4>
                    <ul>
                        <li>Air Quality Monitoring</li>
                        <li>Water Quality Analysis</li>
                        <li>Visual Pollution Assessment</li>
                        <li>Tourist Flow Correlation</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Environmental Pollution Monitoring System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>

