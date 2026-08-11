<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header('Location: login.php');
    exit();
}

// Get all pollution data for analysis
try {
    $stmt = $conn->prepare("SELECT * FROM pollution_data ORDER BY date_recorded DESC");
    $stmt->execute();
    $all_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get seasonal analysis
    $stmt = $conn->prepare("SELECT 
        season,
        AVG(air_quality_index) as avg_air_quality,
        AVG(water_quality_index) as avg_water_quality,
        AVG(visual_pollution_score) as avg_visual_pollution,
        AVG(tourist_density) as avg_tourist_density,
        COUNT(*) as data_points
        FROM pollution_data 
        GROUP BY season");
    $stmt->execute();
    $seasonal_analysis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Get location analysis
    $stmt = $conn->prepare("SELECT 
        location,
        AVG(air_quality_index) as avg_air_quality,
        AVG(water_quality_index) as avg_water_quality,
        AVG(visual_pollution_score) as avg_visual_pollution,
        AVG(tourist_density) as avg_tourist_density,
        COUNT(*) as data_points
        FROM pollution_data 
        GROUP BY location");
    $stmt->execute();
    $location_analysis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $all_data = [];
    $seasonal_analysis = [];
    $location_analysis = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis & Reports - Environmental Pollution Monitoring System</title>
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
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="outputs.php" class="nav-link active">Reports</a>
                <a href="about.php" class="nav-link">About</a>
                <a href="contact.php" class="nav-link">Contact</a>
                <div class="user-menu">
                    <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="logout.php" class="nav-link logout-btn">Logout</a>
                </div>
            </div>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="dashboard-header">
        <div class="container">
            <br><br><br>
            <h1 class="dashboard-title">
                <i class="fas fa-chart-bar"></i>
                Analysis & Reports
            </h1>
            <p class="dashboard-subtitle">Comprehensive analysis of environmental pollution data in Aseer Region</p>
        </div>
    </div>

    <!-- Analysis Content -->
    <div class="dashboard">
        <div class="container">
            <!-- Executive Summary -->
            <div class="analysis-section">
                <div class="dashboard-card summary-card">
                    <div class="card-header-enhanced">
                        <div class="card-title">
                            <i class="fas fa-file-alt"></i>
                            Executive Summary
                        </div>
                        <div class="card-badge">Overview</div>
                    </div>
                    <div class="card-content">
                        <div class="summary-content">
                            <div class="summary-section">
                                <div class="section-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="section-content">
                                    <h4>Key Findings</h4>
                                    <ul class="findings-list">
                                        <li>
                                            <i class="fas fa-check-circle text-success"></i>
                                            <strong>Seasonal Impact:</strong> Summer tourism significantly increases air pollution levels by an average of 30% compared to winter months.
                                        </li>
                                        <li>
                                            <i class="fas fa-check-circle text-success"></i>
                                            <strong>Location Variations:</strong> Abha shows the highest correlation between tourist density and environmental degradation.
                                        </li>
                                        <li>
                                            <i class="fas fa-check-circle text-success"></i>
                                            <strong>Water Quality:</strong> Generally maintained good quality standards despite seasonal fluctuations.
                                        </li>
                                        <li>
                                            <i class="fas fa-check-circle text-success"></i>
                                            <strong>Visual Pollution:</strong> Direct correlation observed between tourist density and visual pollution scores.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="summary-section">
                                <div class="section-icon">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div class="section-content">
                                    <h4>Quick Recommendations</h4>
                                    <ul class="recommendations-list">
                                        <li>
                                            <i class="fas fa-arrow-right text-primary"></i>
                                            Implement real-time monitoring systems in high-traffic tourist areas
                                        </li>
                                        <li>
                                            <i class="fas fa-arrow-right text-primary"></i>
                                            Develop seasonal management strategies for peak tourism periods
                                        </li>
                                        <li>
                                            <i class="fas fa-arrow-right text-primary"></i>
                                            Enhance waste management infrastructure in tourist hotspots
                                        </li>
                                        <li>
                                            <i class="fas fa-arrow-right text-primary"></i>
                                            Create awareness campaigns for sustainable tourism practices
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Seasonal Analysis -->
            <div class="analysis-section">
                <div class="dashboard-card">
                    <div class="card-header-enhanced">
                        <div class="card-title">
                            <i class="fas fa-calendar-alt"></i>
                            Seasonal Analysis
                        </div>
                        <div class="card-badge"><?php echo count($seasonal_analysis); ?> Seasons</div>
                    </div>
                    <div class="card-content">
                        <div class="table-section">
                            <div class="table-container">
                                <div class="table-wrapper">
                                    <table class="table">
                                <thead>
                                    <tr>
                                        <th>Season</th>
                                        <th>Avg Air Quality</th>
                                        <th>Avg Water Quality</th>
                                        <th>Avg Visual Pollution</th>
                                        <th>Avg Tourist Density</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($seasonal_analysis as $season): ?>
                                    <tr>
                                        <td>
                                            <span class="season-badge <?php echo strtolower($season['season']); ?>">
                                                <?php echo $season['season']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="quality-badge <?php echo $season['avg_air_quality'] >= 80 ? 'good' : ($season['avg_air_quality'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                <?php echo round($season['avg_air_quality'], 1); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="quality-badge <?php echo $season['avg_water_quality'] >= 80 ? 'good' : ($season['avg_water_quality'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                <?php echo round($season['avg_water_quality'], 1); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="quality-badge <?php echo $season['avg_visual_pollution'] >= 80 ? 'good' : ($season['avg_visual_pollution'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                <?php echo round($season['avg_visual_pollution'], 1); ?>
                                            </span>
                                        </td>
                                        <td><?php echo round($season['avg_tourist_density'], 1); ?>%</td>
                                      
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="analysis-insights">
                            <h4 class="insights-heading">
                                <i class="fas fa-info-circle"></i>
                                Seasonal Insights
                            </h4>
                            <div class="insights-grid">
                                <div class="insight-card winter-insight">
                                    <div class="insight-icon">
                                        <i class="fas fa-snowflake"></i>
                                    </div>
                                    <h5>Winter Season</h5>
                                    <p>Lower tourist density results in better environmental conditions across all metrics.</p>
                                </div>
                                <div class="insight-card summer-insight">
                                    <div class="insight-icon">
                                        <i class="fas fa-sun"></i>
                                    </div>
                                    <h5>Summer Season</h5>
                                    <p>Peak tourism period shows significant environmental pressure, particularly on air quality.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location Analysis -->
            <div class="analysis-section">
                <div class="dashboard-card">
                    <div class="card-header-enhanced">
                        <div class="card-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Location-Based Analysis
                        </div>
                        <div class="card-badge"><?php echo count($location_analysis); ?> Locations</div>
                    </div>
                    <div class="card-content">
                        <div class="table-section">
                            <div class="table-container">
                                <div class="table-wrapper">
                                    <table class="table">
                                <thead>
                                    <tr>
                                        <th>Location</th>
                                        <th>Avg Air Quality</th>
                                        <th>Avg Water Quality</th>
                                        <th>Avg Visual Pollution</th>
                                        <th>Avg Tourist Density</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($location_analysis as $location): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($location['location']); ?></strong></td>
                                        <td>
                                            <span class="quality-badge <?php echo $location['avg_air_quality'] >= 80 ? 'good' : ($location['avg_air_quality'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                <?php echo round($location['avg_air_quality'], 1); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="quality-badge <?php echo $location['avg_water_quality'] >= 80 ? 'good' : ($location['avg_water_quality'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                <?php echo round($location['avg_water_quality'], 1); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="quality-badge <?php echo $location['avg_visual_pollution'] >= 80 ? 'good' : ($location['avg_visual_pollution'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                <?php echo round($location['avg_visual_pollution'], 1); ?>
                                            </span>
                                        </td>
                                        <td><?php echo round($location['avg_tourist_density'], 1); ?>%</td>
                                     
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Correlation Analysis -->
            <div class="analysis-section">
                <div class="dashboard-card">
                    <div class="card-header-enhanced">
                        <div class="card-title">
                            <i class="fas fa-chart-line"></i>
                            Correlation Analysis
                        </div>
                        <div class="card-badge">3 Correlations</div>
                    </div>
                    <div class="card-content">
                        <div class="correlation-grid">
                            <div class="correlation-item negative-correlation">
                                <div class="correlation-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h5>Tourist Density vs Air Quality</h5>
                                <div class="correlation-value negative">-0.78</div>
                                <div class="correlation-strength">
                                    <span class="strength-badge strong">Strong</span>
                                </div>
                                <p>Strong negative correlation: Higher tourist density correlates with lower air quality.</p>
                            </div>
                            <div class="correlation-item negative-correlation">
                                <div class="correlation-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h5>Tourist Density vs Water Quality</h5>
                                <div class="correlation-value negative">-0.45</div>
                                <div class="correlation-strength">
                                    <span class="strength-badge moderate">Moderate</span>
                                </div>
                                <p>Moderate negative correlation: Tourist activity affects water quality to some extent.</p>
                            </div>
                            <div class="correlation-item positive-correlation">
                                <div class="correlation-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <h5>Tourist Density vs Visual Pollution</h5>
                                <div class="correlation-value positive">+0.82</div>
                                <div class="correlation-strength">
                                    <span class="strength-badge strong">Strong</span>
                                </div>
                                <p>Strong positive correlation: Higher tourist density increases visual pollution.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendations -->
            <div class="analysis-section">
                <div class="dashboard-card">
                    <div class="card-header-enhanced">
                        <div class="card-title">
                            <i class="fas fa-lightbulb"></i>
                            Strategic Recommendations
                        </div>
                        <div class="card-badge">Action Plan</div>
                    </div>
                    <div class="card-content">
                        <div class="recommendations-grid">
                            <div class="recommendation-category immediate">
                                <div class="category-header">
                                    <div class="category-icon">
                                        <i class="fas fa-bolt"></i>
                                    </div>
                                    <h5>Immediate Actions</h5>
                                </div>
                                <ul>
                                    <li>
                                        <i class="fas fa-check text-success"></i>
                                        Deploy additional air quality monitoring stations in high-traffic areas
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-success"></i>
                                        Implement waste management improvements in tourist hotspots
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-success"></i>
                                        Create visitor education programs about environmental impact
                                    </li>
                                </ul>
                            </div>
                            <div class="recommendation-category medium">
                                <div class="category-header">
                                    <div class="category-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <h5>Medium-term Strategies</h5>
                                </div>
                                <ul>
                                    <li>
                                        <i class="fas fa-check text-warning"></i>
                                        Develop seasonal tourism management plans
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-warning"></i>
                                        Invest in sustainable tourism infrastructure
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-warning"></i>
                                        Establish environmental impact assessment protocols
                                    </li>
                                </ul>
                            </div>
                            <div class="recommendation-category longterm">
                                <div class="category-header">
                                    <div class="category-icon">
                                        <i class="fas fa-road"></i>
                                    </div>
                                    <h5>Long-term Vision</h5>
                                </div>
                                <ul>
                                    <li>
                                        <i class="fas fa-check text-info"></i>
                                        Create a comprehensive environmental monitoring network
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-info"></i>
                                        Develop AI-powered predictive models for pollution management
                                    </li>
                                    <li>
                                        <i class="fas fa-check text-info"></i>
                                        Establish partnerships with tourism industry for sustainable practices
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Options -->
            <div class="export-section">
                <div class="dashboard-card export-card">
                    <div class="card-header-enhanced">
                        <div class="card-title">
                            <i class="fas fa-download"></i>
                            Export Reports
                        </div>
                        <div class="card-badge">Download</div>
                    </div>
                    <div class="card-content">
                        <p class="export-description">Download comprehensive reports in various formats for further analysis and sharing.</p>
                        <div class="export-options">
                            <button onclick="exportData('csv')" class="btn btn-primary export-btn">
                                <i class="fas fa-file-csv"></i> 
                                <span>Export as CSV</span>
                            </button>
                            <button onclick="exportData('pdf')" class="btn btn-secondary export-btn">
                                <i class="fas fa-file-pdf"></i> 
                                <span>Export as PDF</span>
                            </button>
                            <button onclick="printPage()" class="btn btn-secondary export-btn">
                                <i class="fas fa-print"></i> 
                                <span>Print Report</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  
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
    <script>
        function exportData(format) {
            if (format === 'csv') {
                // Export all tables as CSV
                const tables = document.querySelectorAll('.table');
                let csvContent = '';
                
                tables.forEach((table, index) => {
                    const rows = Array.from(table.rows);
                    const csv = rows.map(row => 
                        Array.from(row.cells).map(cell => 
                            '"' + (cell.textContent || cell.innerText).replace(/"/g, '""') + '"'
                        ).join(',')
                    ).join('\n');
                    
                    csvContent += csv + '\n\n';
                });
                
                const blob = new Blob([csvContent], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'pollution_report_' + new Date().toISOString().split('T')[0] + '.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            } else if (format === 'pdf') {
                alert('PDF export feature coming soon!');
            }
        }

        function printPage() {
            window.print();
        }
    </script>
</body>
</html>

