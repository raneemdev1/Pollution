<?php
session_start();
require_once 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Environmental Pollution Monitoring System</title>
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
                <a href="index.php" class="nav-link active">Home</a>
                <a href="about.php" class="nav-link">About</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <a href="login.php" class="nav-link">Login</a>
                <a href="register.php" class="nav-link register-btn">Register</a>
            </div>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <h1 class="hero-title">Environmental Pollution Monitoring System</h1>
                <p class="hero-subtitle">Using Artificial Intelligence to Monitor Environmental Pollution in Aseer Region</p>
                <p class="hero-description">
                    Monitor and analyze environmental pollution resulting from seasonal tourist flows using advanced AI tools and techniques.
                </p>
                <div class="hero-buttons">
                    <a href="register.php" class="btn btn-primary">Get Started</a>
                    <a href="about.php" class="btn btn-secondary">Learn More</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/images/environmental-monitoring.jpg" alt="Environmental Monitoring" class="hero-img">
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Key Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Real-time Monitoring</h3>
                    <p>Monitor air, water, and visual pollution levels in real-time across the Aseer region.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>AI-Powered Analysis</h3>
                    <p>Advanced artificial intelligence algorithms for predictive insights and trend analysis.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3>Interactive Maps</h3>
                    <p>Visual representation of pollution data through interactive maps and charts.</p>
                </div>
               
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="statistics">
        <div class="container">
            <h2 class="section-title">Project Statistics</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">8M+</div>
                    <div class="stat-label">Tourist Visitors (2025)</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">3</div>
                    <div class="stat-label">Pollution Types Monitored</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Real-time Monitoring</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">AI</div>
                    <div class="stat-label">Powered Analysis</div>
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

