<?php
session_start();
require_once 'connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Environmental Pollution Monitoring System</title>
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
                    <a href="about.php" class="nav-link active">About</a>
                    <a href="contact.php" class="nav-link">Contact</a>
                    <div class="user-menu">
                        <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                        <a href="logout.php" class="nav-link logout-btn">Logout</a>
                    </div>
                <?php else: ?>
                    <a href="index.php" class="nav-link">Home</a>
                    <a href="about.php" class="nav-link active">About</a>
                    <a href="contact.php" class="nav-link">Contact</a>
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

    <!-- About Header -->
    <section class="about-header">
        <div class="container">
            <div class="about-hero">
                <h1>About Our Platform</h1>
                <p class="about-subtitle">Using Artificial Intelligence to Monitor Environmental Pollution in Aseer Region</p>
            </div>
        </div>
    </section>

    <!-- Project Overview -->
    <section class="project-overview">
        <div class="container">
            <div class="overview-content">
                <div class="overview-text">
                    <h2>Project Overview</h2>
                    <p>
                        Our platform utilizes advanced artificial intelligence tools and techniques to monitor environmental pollution resulting from seasonal tourist flows in the Aseer region of Saudi Arabia.
                    </p>
                    <p>
                        The Aseer region experiences high levels of tourist flows, receiving nearly eight million visitors in 2025. This surge in tourism increases environmental pressures, particularly in natural areas, creating a need for intelligent monitoring systems to support sustainable tourism development.
                    </p>
                </div>
                <div class="overview-image">
                    <img src="assets/images/aseer-region.jpg" alt="Aseer Region" class="region-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Research Objectives -->
    <section class="research-objectives">
        <div class="container">
            <h2 class="section-title">Our Objectives</h2>
            <div class="objectives-grid">
                <div class="objective-card">
                    <div class="objective-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Monitor Environmental Impact</h3>
                    <p>Develop comprehensive monitoring systems for air, water, and visual pollution in the Aseer region.</p>
                </div>
                <div class="objective-card">
                    <div class="objective-icon">
                        <i class="fas fa-robot"></i>
                    </div>
                    <h3>AI-Powered Analysis</h3>
                    <p>Implement artificial intelligence algorithms for predictive insights and trend analysis.</p>
                </div>
              
                <div class="objective-card">
                    <div class="objective-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3>Decision Support</h3>
                    <p>Provide real-time data and recommendations to support decision-making by local authorities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Methodology -->
    <section class="methodology">
        <div class="container">
            <h2 class="section-title">Our Methodology</h2>
            <div class="methodology-content">
                <div class="methodology-steps">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4>Data Collection</h4>
                            <p>Gather environmental data from various sources including air quality stations, water quality measurements, and visual pollution assessments.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4>AI Processing</h4>
                            <p>Apply machine learning algorithms to process and analyze the collected data for patterns and trends.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4>Correlation Analysis</h4>
                            <p>Establish correlations between tourist density and environmental pollution levels across different seasons.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4>Visualization & Reporting</h4>
                            <p>Create interactive dashboards, maps, and comprehensive reports for stakeholders and decision-makers.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Research Areas -->
    <section class="research-areas">
        <div class="container">
            <h2 class="section-title">Monitoring Areas</h2>
            <div class="areas-grid">
                <div class="area-card">
                    <div class="area-icon">
                        <i class="fas fa-wind"></i>
                    </div>
                    <h3>Air Quality Monitoring</h3>
                    <p>Real-time monitoring of air pollutants including PM2.5, PM10, NO2, SO2, and O3 levels across the Aseer region.</p>
                    <ul class="area-features">
                        <li>Continuous monitoring stations</li>
                        <li>AI-powered prediction models</li>
                        <li>Seasonal trend analysis</li>
                    </ul>
                </div>
                <div class="area-card">
                    <div class="area-icon">
                        <i class="fas fa-tint"></i>
                    </div>
                    <h3>Water Quality Assessment</h3>
                    <p>Comprehensive analysis of water quality parameters in rivers, lakes, and groundwater sources.</p>
                    <ul class="area-features">
                        <li>Chemical composition analysis</li>
                        <li>Biological contamination monitoring</li>
                        <li>Tourist impact assessment</li>
                    </ul>
                </div>
                <div class="area-card">
                    <div class="area-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h3>Visual Pollution Analysis</h3>
                    <p>Assessment of visual pollution through image analysis and satellite data processing.</p>
                    <ul class="area-features">
                        <li>Satellite image processing</li>
                        <li>Computer vision algorithms</li>
                        <li>Landscape degradation tracking</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

   

    <!-- Impact & Significance -->
    <section class="impact-section">
        <div class="container">
            <h2 class="section-title">Project Impact & Significance</h2>
            <div class="impact-content">
                <div class="impact-grid">
                    <div class="impact-item">
                        <div class="impact-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h4>Environmental Sustainability</h4>
                        <p>Contributes to Saudi Vision 2030's environmental sustainability goals by providing data-driven insights for pollution management.</p>
                    </div>
                    <div class="impact-item">
                        <div class="impact-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Tourism Management</h4>
                        <p>Supports sustainable tourism development by identifying optimal visitor management strategies.</p>
                    </div>
                    <div class="impact-item">
                        <div class="impact-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4>Technology Innovation</h4>
                        <p>Demonstrates the application of AI and machine learning in environmental monitoring and management.</p>
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

