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

require_once '../connection.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get statistics for admin dashboard
try {
    // User statistics
    $stmt = $conn->prepare("SELECT COUNT(*) as total_users FROM users WHERE is_active = 1");
    $stmt->execute();
    $total_users = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];
    
    // Recent registrations
    $stmt = $conn->prepare("SELECT COUNT(*) as recent_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $stmt->execute();
    $recent_users = $stmt->fetch(PDO::FETCH_ASSOC)['recent_users'];
    
    // Pollution data statistics
    $stmt = $conn->prepare("SELECT COUNT(*) as total_records FROM pollution_data");
    $stmt->execute();
    $total_records = $stmt->fetch(PDO::FETCH_ASSOC)['total_records'];
    
    // Recent pollution data
    $stmt = $conn->prepare("SELECT * FROM pollution_data ORDER BY created_at DESC LIMIT 5");
    $stmt->execute();
    $recent_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // User activity
    $stmt = $conn->prepare("SELECT * FROM users ORDER BY last_login DESC LIMIT 10");
    $stmt->execute();
    $recent_activity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $total_users = 0;
    $recent_users = 0;
    $total_records = 0;
    $recent_data = [];
    $recent_activity = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Environmental Pollution Monitoring System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <i class="fas fa-leaf"></i>
                <span>Pollution Monitor - Admin</span>
            </div>
            <div class="nav-menu">
                <a href="dashboard.php" class="nav-link active">Dashboard</a>
                <a href="users.php" class="nav-link">User Management</a>
                <div class="user-menu">
                    <span class="user-name">Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="../logout.php" class="nav-link logout-btn">Logout</a>
                </div>
            </div>
            <div class="hamburger">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </div>
        </div>
    </nav>

    <!-- Admin Dashboard Header -->
    <div class="dashboard-header-admin">
        <div class="container">
            <br><br><br><br>
            <h1 class="dashboard-title">Administrator Dashboard</h1>
           
        </div>
    </div>

    <!-- Admin Dashboard Content -->
    <div class="dashboard">
        <div class="container">
            <!-- Statistics Cards -->
            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-title">
                        <i class="fas fa-users"></i>
                        Total Users
                    </div>
                    <div class="card-content">
                        <div class="metric-value"><?php echo $total_users; ?></div>
                        <div class="metric-label">Registered Users</div>
                        <div class="metric-trend">
                            <i class="fas fa-arrow-up text-success"></i>
                            <span>Active</span>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-title">
                        <i class="fas fa-user-plus"></i>
                        New Registrations
                    </div>
                    <div class="card-content">
                        <div class="metric-value"><?php echo $recent_users; ?></div>
                        <div class="metric-label">Last 7 Days</div>
                        <div class="metric-trend">
                            <i class="fas fa-calendar"></i>
                            <span>This Week</span>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-title">
                        <i class="fas fa-database"></i>
                        Data Records
                    </div>
                    <div class="card-content">
                        <div class="metric-value"><?php echo $total_records; ?></div>
                        <div class="metric-label">Pollution Data Points</div>
                        <div class="metric-trend">
                            <i class="fas fa-chart-line"></i>
                            <span>Monitoring</span>
                        </div>
                    </div>
                </div>
                
                <div class="dashboard-card">
                    <div class="card-title">
                        <i class="fas fa-server"></i>
                        System Status
                    </div>
                    <div class="card-content">
                        <div class="metric-value">Online</div>
                        <div class="metric-label">System Status</div>
                        <div class="metric-trend">
                            <i class="fas fa-check-circle text-success"></i>
                            <span>Operational</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="admin-sections">
                <div class="section-row">
                    <!-- Recent Pollution Data -->
                    <div class="section-card">
                        <div class="card-title">
                            <i class="fas fa-chart-line"></i>
                            Recent Pollution Data
                        </div>
                        <div class="card-content">
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Location</th>
                                            <th>Air Quality</th>
                                            <th>Water Quality</th>
                                            <th>Date</th>
                                            <th>Season</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_data as $data): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($data['location']); ?></td>
                                            <td>
                                                <span class="quality-badge <?php echo $data['air_quality_index'] >= 80 ? 'good' : ($data['air_quality_index'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                    <?php echo $data['air_quality_index']; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="quality-badge <?php echo $data['water_quality_index'] >= 80 ? 'good' : ($data['water_quality_index'] >= 60 ? 'moderate' : 'poor'); ?>">
                                                    <?php echo $data['water_quality_index']; ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($data['date_recorded'])); ?></td>
                                            <td>
                                                <span class="season-badge <?php echo strtolower($data['season']); ?>">
                                                    <?php echo $data['season']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Recent User Activity -->
                    <div class="section-card">
                        <div class="card-title">
                            <i class="fas fa-user-clock"></i>
                            Recent User Activity
                        </div>
                        <div class="card-content">
                            <div class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Full Name</th>
                                            <th>Last Login</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_activity as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                                            <td>
                                                <?php 
                                                if ($user['last_login']) {
                                                    echo date('M d, Y H:i', strtotime($user['last_login']));
                                                } else {
                                                    echo 'Never';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <span class="status-badge <?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                                    <?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <div class="dashboard-card">
                    <div class="card-title">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </div>
                    <div class="card-content">
                        <div class="actions-grid">
                            <a href="users.php" class="action-btn">
                                <i class="fas fa-users"></i>
                                <span>Manage Users</span>
                            </a>
                            <a href="#" class="action-btn" onclick="exportSystemData()">
                                <i class="fas fa-download"></i>
                                <span>Export Data</span>
                            </a>
                            <a href="#" class="action-btn" onclick="systemBackup()">
                                <i class="fas fa-save"></i>
                                <span>System Backup</span>
                            </a>
                            <a href="#" class="action-btn" onclick="viewLogs()">
                                <i class="fas fa-file-alt"></i>
                                <span>View Logs</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
      
            <div class="footer-bottom">
                <p>&copy; 2026 Environmental Pollution Monitoring System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="../assets/js/script.js"></script>
    <script>
        function exportSystemData() {
            alert('System data export feature');
        }
        
        function systemBackup() {
            alert('System backup feature');
        }
        
        function viewLogs() {
            alert('System logs feature');
        }
    </script>
</body>
</html>

