<?php
session_start();

if (isset($_SESSION['success_login_message'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style=" margin-top: 120px;  text-align: center;">
            ' . $_SESSION['success_login_message'] . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
    // حذف الرسالة بعد عرضها مرة واحدة
    unset($_SESSION['success_login_message']);
}



require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'user') {
    header('Location: login.php');
    exit();
}

// دالة لقراءة بيانات CSV
function readCSVData($file_path) {
    $data = [];
    if (file_exists($file_path) && is_readable($file_path)) {
        $handle = fopen($file_path, 'r');
        if ($handle !== false) {
            // قراءة السطر الأول (العناوين)
            $headers = fgetcsv($handle);
            if ($headers !== false) {
                // قراءة باقي البيانات
                while (($row = fgetcsv($handle)) !== false) {
                    if (count($row) === count($headers)) {
                        $data[] = array_combine($headers, $row);
                    }
                }
            }
            fclose($handle);
        }
    }
    return $data;
}

// قراءة بيانات التلوث من ملف CSV
$pollution_data = [];
$csv_file_path = null;
$possible_csv_paths = [
    __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'aseer_pollution_raw.csv',
    __DIR__ . '/data/aseer_pollution_raw.csv',
    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'aseer_pollution_raw.csv',
];

foreach ($possible_csv_paths as $p) {
    if (file_exists($p) && is_readable($p)) {
        $csv_file_path = $p;
        break;
    }
}

if ($csv_file_path) {
    $pollution_data = readCSVData($csv_file_path);
    
    // التأكد من أن البيانات تم قراءتها بشكل صحيح
    if (!empty($pollution_data)) {
        // تحويل القيم الرقمية من نص إلى أرقام
        foreach ($pollution_data as &$row) {
            // تنظيف القيم وإزالة أي مسافات أو أحرف غير مرغوبة
            $row['air_quality_index'] = isset($row['air_quality_index']) ? (float)trim($row['air_quality_index']) : 0;
            $row['water_quality_index'] = isset($row['water_quality_index']) ? (float)trim($row['water_quality_index']) : 0;
            $row['visual_pollution_score'] = isset($row['visual_pollution_score']) ? (float)trim($row['visual_pollution_score']) : 0;
            $row['tourist_density'] = isset($row['tourist_density']) ? (float)trim($row['tourist_density']) : 0;
            $row['location'] = isset($row['location']) ? trim($row['location']) : '';
            $row['date_recorded'] = isset($row['date_recorded']) ? trim($row['date_recorded']) : '';
            $row['season'] = isset($row['season']) ? trim($row['season']) : '';
        }
        unset($row);
        
        // عرض البيانات بنفس الترتيب الموجود في ملف CSV (بدون ترتيب)
        // أخذ آخر 10 سجلات من الملف كما هي
        $pollution_data = array_slice($pollution_data, -10);
    }
    
    // حساب الإحصائيات من بيانات CSV
    if (!empty($pollution_data)) {
        $total_air = 0;
        $total_water = 0;
        $total_visual = 0;
        $total_tourist = 0;
        $count = count($pollution_data);
        
        foreach ($pollution_data as $row) {
            $total_air += $row['air_quality_index'];
            $total_water += $row['water_quality_index'];
            $total_visual += $row['visual_pollution_score'];
            $total_tourist += $row['tourist_density'];
        }
        
        $db_stats = [
            'avg_air_quality' => $count > 0 ? $total_air / $count : 0,
            'avg_water_quality' => $count > 0 ? $total_water / $count : 0,
            'avg_visual_pollution' => $count > 0 ? $total_visual / $count : 0,
            'avg_tourist_density' => $count > 0 ? $total_tourist / $count : 0,
        ];
    } else {
        $db_stats = [
            'avg_air_quality' => 0,
            'avg_water_quality' => 0,
            'avg_visual_pollution' => 0,
            'avg_tourist_density' => 0,
        ];
    }
} else {
    // إذا لم يتم العثور على ملف CSV، محاولة جلب البيانات من قاعدة البيانات كبديل
    try {
        $stmt = $conn->prepare("SELECT * FROM pollution_data ORDER BY date_recorded DESC LIMIT 10");
        $stmt->execute();
        $pollution_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $conn->prepare("SELECT 
            AVG(air_quality_index) as avg_air_quality,
            AVG(water_quality_index) as avg_water_quality,
            AVG(visual_pollution_score) as avg_visual_pollution,
            AVG(tourist_density) as avg_tourist_density
            FROM pollution_data");
        $stmt->execute();
        $db_stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$db_stats || !is_array($db_stats)) {
            $db_stats = [
                'avg_air_quality' => 0,
                'avg_water_quality' => 0,
                'avg_visual_pollution' => 0,
                'avg_tourist_density' => 0,
            ];
        }
    } catch (PDOException $e) {
        $pollution_data = [];
        $db_stats = [
            'avg_air_quality' => 0,
            'avg_water_quality' => 0,
            'avg_visual_pollution' => 0,
            'avg_tourist_density' => 0,
        ];
        error_log("Database error in dashboard.php: " . $e->getMessage());
    }
}

// استخدام إحصائيات قاعدة البيانات كقيم افتراضية
$stats = $db_stats;

// إذا وُجد تصدير من دفتر التحليل Pollution_data.ipynb نستخدمه للإحصائيات والرسوم
$chart_data = null;
$dashboard_export_file = null;
$export = null;

// البحث عن ملف التصدير في مسارات محتملة
$possible_paths = [
    __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'dashboard_export.json',
    __DIR__ . '/data/dashboard_export.json',
    dirname(__DIR__) . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'dashboard_export.json',
];

foreach ($possible_paths as $p) {
    if (file_exists($p) && is_readable($p)) {
        $dashboard_export_file = $p;
        break;
    }
}

// قراءة وتحميل البيانات من ملف JSON
if ($dashboard_export_file) {
    $raw = @file_get_contents($dashboard_export_file);
    if ($raw !== false) {
        $export = json_decode($raw, true);
        $json_error = json_last_error();
        
        // إذا تم تحميل البيانات بنجاح
        if (is_array($export) && $json_error === JSON_ERROR_NONE) {
            // تحديث الإحصائيات من ملف JSON إذا كانت موجودة (الأولوية للبيانات من JSON)
            if (isset($export['stats']) && is_array($export['stats'])) {
                // استخدام القيم من JSON مباشرة (بدون دمج مع قاعدة البيانات)
                $stats = [
                    'avg_air_quality' => isset($export['stats']['avg_air_quality']) ? (float)$export['stats']['avg_air_quality'] : 0,
                    'avg_water_quality' => isset($export['stats']['avg_water_quality']) ? (float)$export['stats']['avg_water_quality'] : 0,
                    'avg_visual_pollution' => isset($export['stats']['avg_visual_pollution']) ? (float)$export['stats']['avg_visual_pollution'] : 0,
                    'avg_tourist_density' => isset($export['stats']['avg_tourist_density']) ? (float)$export['stats']['avg_tourist_density'] : 0,
                ];
            }
            
            // تحميل بيانات الرسوم البيانية
            if (isset($export['chart_air_monthly']) || isset($export['chart_water_labels']) || isset($export['chart_correlation'])) {
                $chart_data = $export;
            }
        }
    }
}

// التأكد من أن $stats موجودة حتى لو لم يتم تحميلها من JSON
if (!isset($stats) || !is_array($stats)) {
    $stats = [
        'avg_air_quality' => 0,
        'avg_water_quality' => 0,
        'avg_visual_pollution' => 0,
        'avg_tourist_density' => 0,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Environmental Pollution Monitoring System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <a href="dashboard.php" class="nav-link active">Dashboard</a>
                <a href="outputs.php" class="nav-link">Reports</a>
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

    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="container">
            <br><br><br>
            <h1 class="dashboard-title">Environmental Pollution Monitoring Dashboard</h1>
           
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard">
        <div class="container">
            <!-- Current Status Cards -->
            <div class="dashboard-grid">
                <div class="dashboard-card metric-card air-quality">
                    <div class="card-icon-wrapper">
                        <div class="card-icon air-icon">
                            <i class="fas fa-wind"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-title">Air Quality</div>
                        <div class="metric-value" id="currentAirQuality" data-original-value="<?php echo round($stats['avg_air_quality'] ?? 0, 1); ?>"><?php echo round($stats['avg_air_quality'] ?? 0, 1); ?></div>
                        <div class="metric-label">Air Quality Index</div>
                        <div class="metric-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo min(100, max(0, ($stats['avg_air_quality'] ?? 0))); ?>%"></div>
                            </div>
                        </div>
                        <div class="metric-trend">
                            <?php 
                            $air_quality = $stats['avg_air_quality'] ?? 0;
                            if ($air_quality >= 80) {
                                echo '<i class="fas fa-arrow-down text-success"></i><span class="status-text">Good</span>';
                            } elseif ($air_quality >= 60) {
                                echo '<i class="fas fa-minus text-warning"></i><span class="status-text">Moderate</span>';
                            } else {
                                echo '<i class="fas fa-arrow-up text-danger"></i><span class="status-text">Poor</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card metric-card water-quality">
                    <div class="card-icon-wrapper">
                        <div class="card-icon water-icon">
                            <i class="fas fa-tint"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-title">Water Quality</div>
                        <div class="metric-value" id="currentWaterQuality" data-original-value="<?php echo round($stats['avg_water_quality'] ?? 0, 1); ?>"><?php echo round($stats['avg_water_quality'] ?? 0, 1); ?></div>
                        <div class="metric-label">Water Quality Index</div>
                        <div class="metric-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo min(100, max(0, ($stats['avg_water_quality'] ?? 0))); ?>%"></div>
                            </div>
                        </div>
                        <div class="metric-trend">
                            <?php 
                            $water_quality = $stats['avg_water_quality'] ?? 0;
                            if ($water_quality >= 80) {
                                echo '<i class="fas fa-arrow-down text-success"></i><span class="status-text">Good</span>';
                            } elseif ($water_quality >= 60) {
                                echo '<i class="fas fa-minus text-warning"></i><span class="status-text">Moderate</span>';
                            } else {
                                echo '<i class="fas fa-arrow-up text-danger"></i><span class="status-text">Poor</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card metric-card visual-pollution">
                    <div class="card-icon-wrapper">
                        <div class="card-icon visual-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-title">Visual Pollution</div>
                        <div class="metric-value" data-original-value="<?php echo round($stats['avg_visual_pollution'] ?? 0, 1); ?>"><?php echo round($stats['avg_visual_pollution'] ?? 0, 1); ?></div>
                        <div class="metric-label">Visual Pollution Score</div>
                        <div class="metric-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo min(100, max(0, ($stats['avg_visual_pollution'] ?? 0))); ?>%"></div>
                            </div>
                        </div>
                        <div class="metric-trend">
                            <?php 
                            $visual_pollution = $stats['avg_visual_pollution'] ?? 0;
                            if ($visual_pollution >= 80) {
                                echo '<i class="fas fa-arrow-down text-success"></i><span class="status-text">Good</span>';
                            } elseif ($visual_pollution >= 60) {
                                echo '<i class="fas fa-minus text-warning"></i><span class="status-text">Moderate</span>';
                            } else {
                                echo '<i class="fas fa-arrow-up text-danger"></i><span class="status-text">Poor</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card metric-card tourist-density">
                    <div class="card-icon-wrapper">
                        <div class="card-icon tourist-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-title">Tourist Density</div>
                        <div class="metric-value" id="currentTouristDensity" data-original-value="<?php echo round($stats['avg_tourist_density'] ?? 0, 1); ?>"><?php echo round($stats['avg_tourist_density'] ?? 0, 1); ?>%</div>
                        <div class="metric-label">Current Tourist Density</div>
                        <div class="metric-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?php echo min(100, max(0, ($stats['avg_tourist_density'] ?? 0))); ?>%"></div>
                            </div>
                        </div>
                        <div class="metric-trend">
                            <?php 
                            $tourist_density = $stats['avg_tourist_density'] ?? 0;
                            if ($tourist_density >= 70) {
                                echo '<i class="fas fa-arrow-up text-info"></i><span class="status-text">High Season</span>';
                            } elseif ($tourist_density >= 40) {
                                echo '<i class="fas fa-minus text-warning"></i><span class="status-text">Moderate</span>';
                            } else {
                                echo '<i class="fas fa-arrow-down text-success"></i><span class="status-text">Low Season</span>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <h2 class="section-heading">
                    <i class="fas fa-chart-area"></i>
                    Data Analytics & Trends
                </h2>
                <div class="charts-grid">
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="card-title">
                                <i class="fas fa-chart-line"></i>
                                Air Quality Trends
                            </div>
                            <div class="chart-controls">
                                <select class="chart-period" id="airQualityPeriod">
                                    <option value="7">Last 7 Days</option>
                                    <option value="30" selected>Last 30 Days</option>
                                    <option value="90">Last 90 Days</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="airQualityChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-card">
                        <div class="chart-header">
                            <div class="card-title">
                                <i class="fas fa-chart-bar"></i>
                                Water Quality by Location
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="waterQualityChart"></canvas>
                        </div>
                    </div>
                    
                   
                </div>
            </div>

            <!-- Recent Data Table -->
            <div class="table-section">
                <div class="table-container">
                    <div class="table-header">
                        <div class="table-title-wrapper">
                            <h3>
                                <i class="fas fa-table"></i>
                                Recent Pollution Data
                            </h3>
                            <span class="table-count"><?php echo count($pollution_data); ?> records</span>
                        </div>
                        <div class="table-actions">
                            <button onclick="exportData('csv')" class="btn btn-secondary btn-sm">
                                <i class="fas fa-download"></i> Export CSV
                            </button>
                            <button onclick="printPage()" class="btn btn-secondary btn-sm">
                                <i class="fas fa-print"></i> Print
                            </button>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table class="table" id="pollutionTable">
                    <thead>
                        <tr>
                            <th>Location</th>
                            <th>Air Quality</th>
                            <th>Water Quality</th>
                            <th>Visual Pollution</th>
                            <th>Tourist Density</th>
                            <th>Date</th>
                            <th>Season</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pollution_data)): ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 20px;">
                                <p>No pollution data available. Please check if the CSV file exists.</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php 
                        // التأكد من أن البيانات موجودة وصحيحة
                        foreach ($pollution_data as $index => $data): 
                            // استخراج القيم مع التحقق من وجودها
                            $location = isset($data['location']) ? trim($data['location']) : 'N/A';
                            $air_quality = isset($data['air_quality_index']) ? (float)$data['air_quality_index'] : 0;
                            $water_quality = isset($data['water_quality_index']) ? (float)$data['water_quality_index'] : 0;
                            $visual_pollution = isset($data['visual_pollution_score']) ? (float)$data['visual_pollution_score'] : 0;
                            $tourist_density = isset($data['tourist_density']) ? (float)$data['tourist_density'] : 0;
                            $date_recorded = isset($data['date_recorded']) ? trim($data['date_recorded']) : '';
                            $season = isset($data['season']) ? trim($data['season']) : 'N/A';
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($location); ?></td>
                            <td>
                                <span class="quality-badge <?php 
                                    echo $air_quality >= 80 ? 'good' : ($air_quality >= 60 ? 'moderate' : 'poor'); 
                                ?>">
                                    <?php echo number_format($air_quality, 1); ?>
                                </span>
                            </td>
                            <td>
                                <span class="quality-badge <?php 
                                    echo $water_quality >= 80 ? 'good' : ($water_quality >= 60 ? 'moderate' : 'poor'); 
                                ?>">
                                    <?php echo number_format($water_quality, 1); ?>
                                </span>
                            </td>
                            <td>
                                <span class="quality-badge <?php 
                                    echo $visual_pollution >= 80 ? 'good' : ($visual_pollution >= 60 ? 'moderate' : 'poor'); 
                                ?>">
                                    <?php echo number_format($visual_pollution, 1); ?>
                                </span>
                            </td>
                            <td><?php echo number_format($tourist_density, 1); ?>%</td>
                            <td><?php 
                                if ($date_recorded) {
                                    $timestamp = strtotime($date_recorded);
                                    if ($timestamp !== false) {
                                        echo date('M d, Y', $timestamp);
                                    } else {
                                        echo htmlspecialchars($date_recorded);
                                    }
                                } else {
                                    echo 'N/A';
                                }
                            ?></td>
                            <td>
                                <span class="season-badge <?php echo strtolower($season); ?>">
                                    <?php echo htmlspecialchars($season); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
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
        // علامة لتحديد أننا في صفحة dashboard (لمنع تهيئة الرسوم البيانية من script.js)
        window.isDashboardPage = true;
        
        // بيانات الرسوم من Pollution_data.ipynb إن وُجدت
        var dashboardChartData = <?php 
            if ($chart_data !== null && is_array($chart_data)) {
                echo json_encode($chart_data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);
            } else {
                echo 'null';
            }
        ?>;
        
        document.addEventListener('DOMContentLoaded', function() {
            // منع تحديث القيم تلقائياً - استخدام القيم الثابتة من JSON
            var airQualityElement = document.getElementById('currentAirQuality');
            var waterQualityElement = document.getElementById('currentWaterQuality');
            var touristDensityElement = document.getElementById('currentTouristDensity');
            
            // حفظ القيم الأصلية من data-original-value
            if (airQualityElement) {
                var originalValue = airQualityElement.getAttribute('data-original-value') || airQualityElement.textContent.trim();
                window.originalAirQuality = parseFloat(originalValue) || 0;
            }
            
            if (waterQualityElement) {
                var originalValue = waterQualityElement.getAttribute('data-original-value') || waterQualityElement.textContent.trim();
                window.originalWaterQuality = parseFloat(originalValue) || 0;
            }
            
            if (touristDensityElement) {
                var originalValue = touristDensityElement.getAttribute('data-original-value') || touristDensityElement.textContent.trim().replace('%', '');
                window.originalTouristDensity = parseFloat(originalValue) || 0;
            }
            
            // تعطيل دالة التحديث التلقائي إذا كانت موجودة
            if (typeof updateDashboardData === 'function') {
                window.updateDashboardData = function() { return false; };
            }
            
            // منع أي setInterval للتحديثات التلقائية
            var originalSetInterval = window.setInterval;
            window.setInterval = function(func, delay) {
                if (func && typeof func === 'function' && func.toString().includes('updateDashboardData')) {
                    return null;
                }
                return originalSetInterval.apply(this, arguments);
            };
            
            if (typeof Chart !== 'undefined') {
                initializeDashboardCharts();
            }
        });

        function initializeDashboardCharts() {
            // تدمير أي رسوم بيانية موجودة مسبقاً لتجنب خطأ "Canvas is already in use"
            var chartInstances = Chart.getChart('airQualityChart');
            if (chartInstances) {
                chartInstances.destroy();
            }
            chartInstances = Chart.getChart('waterQualityChart');
            if (chartInstances) {
                chartInstances.destroy();
            }
            
            var monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // استخدام البيانات من JSON إذا كانت متوفرة، وإلا استخدام البيانات الافتراضية
            var airData = null;
            var waterLabels = null;
            var waterData = null;
            
            if (dashboardChartData) {
                // بيانات جودة الهواء الشهرية
                if (Array.isArray(dashboardChartData.chart_air_monthly) && dashboardChartData.chart_air_monthly.length > 0) {
                    airData = dashboardChartData.chart_air_monthly;
                }
                
                // بيانات جودة الماء
                if (Array.isArray(dashboardChartData.chart_water_labels) && Array.isArray(dashboardChartData.chart_water_values)) {
                    waterLabels = dashboardChartData.chart_water_labels;
                    waterData = dashboardChartData.chart_water_values;
                }
                
            }
            
            // استخدام البيانات الافتراضية إذا لم تكن البيانات من JSON متوفرة
            if (!airData || !Array.isArray(airData) || airData.length === 0) {
                airData = [65, 68, 72, 75, 78, 82, 85, 80, 75, 70, 68, 65];
            }
            
            if (!waterLabels || !Array.isArray(waterLabels) || waterLabels.length === 0) {
                waterLabels = ['Abha', 'Khamis Mushait', 'Al-Namas', 'Bisha', 'Muhayil'];
            }
            
            if (!waterData || !Array.isArray(waterData) || waterData.length === 0) {
                waterData = [85, 82, 88, 80, 83];
            }
            
            // التأكد من أن عدد التسميات والبيانات متطابق
            if (waterLabels.length !== waterData.length) {
                var minLength = Math.min(waterLabels.length, waterData.length);
                waterLabels = waterLabels.slice(0, minLength);
                waterData = waterData.slice(0, minLength);
            }

            // Air Quality Trend Chart
            const airCtx = document.getElementById('airQualityChart');
            if (airCtx) {
                new Chart(airCtx, {
                    type: 'line',
                    data: {
                        labels: monthLabels,
                        datasets: [{
                            label: 'Air Quality Index',
                            data: airData,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                min: 50,
                                max: 100
                            }
                        }
                    }
                });
            }

            // Water Quality by Location Chart
            const waterCtx = document.getElementById('waterQualityChart');
            if (waterCtx) {
                new Chart(waterCtx, {
                    type: 'bar',
                    data: {
                        labels: waterLabels,
                        datasets: [{
                            label: 'Water Quality Index',
                            data: waterData,
                            backgroundColor: [
                                'rgba(40, 167, 69, 0.8)',
                                'rgba(40, 167, 69, 0.7)',
                                'rgba(40, 167, 69, 0.9)',
                                'rgba(40, 167, 69, 0.6)',
                                'rgba(40, 167, 69, 0.75)'
                            ],
                            borderColor: '#28a745',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: false,
                                min: 70,
                                max: 100
                            }
                        }
                    }
                });
            }
        }

        function exportData(format) {
            if (format === 'csv') {
                const table = document.getElementById('pollutionTable');
                const rows = Array.from(table.rows);
                const csvContent = rows.map(row => 
                    Array.from(row.cells).map(cell => 
                        '"' + (cell.textContent || cell.innerText).replace(/"/g, '""') + '"'
                    ).join(',')
                ).join('\n');
                
                const blob = new Blob([csvContent], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const link = document.createElement('a');
                link.href = url;
                link.download = 'pollution_data_' + new Date().toISOString().split('T')[0] + '.csv';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                window.URL.revokeObjectURL(url);
            }
        }

        function printPage() {
            window.print();
        }
    </script>
</body>
</html>

