<?php
session_start();
require_once './dbConnection.php'; // This should contain your database connection
// $con = connectDB(); // Make sure this function returns a valid mysqli connection
$kebele_id = $_SESSION['kebele_id'] ?? ''; // Assuming kebele_id is stored in session
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="dashboardHome.css">
</head>

<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="sidebar-title"><i class="fas fa-cogs"></i> Admin Panel</h2>
    <nav class="menu-container">
      <ul>
        <li><a href="#"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        <li><a href="./all reports/allReports.php"><i class="fas fa-folder"></i> <span>All Reports</span></a></li>
        <li><a href="./ResidentsManagement/residentsManagent.php"><i class="fas fa-users"></i>
            <span>Residents</span></a></li>
        <li><a href="./ManageCatagories/manageCategories.php"><i class="fas fa-tags"></i> <span>Categories</span></a>
        </li>
        <li><a href="./ScheduleAndAssignments/scheduleAndAssignments.php"><i class="fas fa-calendar-alt"></i>
            <span>Schedule</span></a></li>
        <li><a href="../Hermata home/index.php"><i class="fas fa-user-shield"></i> <span>Login as Resident</span></a>
        </li>
        <li><a href="./ReportsAndAnalytics/reportsAndAnalytics.php"><i class="fas fa-chart-bar"></i>
            <span>Analytics</span></a></li>
        <li><a href="./Notification/notification.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
        <li><a href="./ActivityLogs/activityLogs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>
        <li><a href="./SystemSettings/systemSetting.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
        <li><a href="../login/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Profile Bar -->
    <header class="profile-bar">
      <div class="profile-info">
        <?php
        $imageData = '';
        if (!empty($kebele_id)) {
            $stmt = mysqli_prepare($con, "SELECT photo FROM residents WHERE residence_id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $kebele_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($result)) {
                    $imageData = base64_encode($row['photo']);
                }
                mysqli_stmt_close($stmt);
            }
        }

        if ($imageData) {
            echo '<a href="../editProfile/editProfile.php">';
            echo '<img src="data:image/jpeg;base64,' . $imageData . '" alt="Profile" style="width: 80px;height: 70px;border-radius:50%; object-fit:cover;">';
            echo '</a>';
        } else {
            echo '<img src="./images/default-profile.png" alt="Profile" style="width: 80px;height: 70px;border-radius:50%; object-fit:cover;">';
        }
        ?>

        <div>
          <h4 class="admin-name"><?php echo htmlspecialchars($_SESSION["username"] ?? 'Admin'); ?></h4>
          <p class="admin-role"><?php echo htmlspecialchars($_SESSION["role"] ?? 'Administrator'); ?></p>
        </div>
      </div>
      <p style="color:gray; font-size: 1rem;">HERMATA MENTINA RMS</p>
    </header>

    <!-- Main Section -->
    <section class="dashboard-content">
      <h1>Welcome Back, Admin <i class="fas fa-hand-sparkles"></i></h1>
      <p>Here's what's happening with your system today.</p>

      <!-- Stats Cards -->
      <div class="stats-container">
        <div class="stat-card">
          <i class="fas fa-users"></i>
          <h3>Total Residents</h3>
          <div class="stat-value"><?php echo getResidentCount($con); ?></div>
          <div class="stat-change">+5% from last month</div>
        </div>

        <div class="stat-card">
          <i class="fas fa-file-alt"></i>
          <h3>Total Reports</h3>
          <div class="stat-value"><?php echo getTotalReportCount($con); ?></div>
          <div class="stat-change">+12% from last month</div>
        </div>

        <div class="stat-card">
          <i class="fas fa-check-circle"></i>
          <h3>Solved Reports</h3>
          <div class="stat-value"><?php echo getSolvedReportCount($con); ?></div>
          <div class="stat-change">+8% from last month</div>
        </div>

        <div class="stat-card">
          <i class="fas fa-clock"></i>
          <h3>Pending Reports</h3>
          <div class="stat-value"><?php echo getPendingReportCount($con); ?></div>
          <div class="stat-change negative">+3 from yesterday</div>
        </div>
      </div>

      <!-- Database Tables Overview -->
      <div class="table-stats">
        <h2><i class="fas fa-database"></i> Database Overview</h2>
        <table>
          <thead>
            <tr>
              <th>Table Name</th>
              <th>Rows</th>
              <th>Type</th>
              <th>Size</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>residents</td>
              <td><?php echo getTableRowCount($con, 'residents'); ?></td>
              <td>InnoDB</td>
              <td>288.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>reports</td>
              <td><?php echo getTableRowCount($con, 'reports'); ?></td>
              <td>InnoDB</td>
              <td>272.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>report_images</td>
              <td><?php echo getTableRowCount($con, 'report_images'); ?></td>
              <td>InnoDB</td>
              <td>400.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>report_status_history</td>
              <td><?php echo getTableRowCount($con, 'report_status_history'); ?></td>
              <td>InnoDB</td>
              <td>48.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>activity_logs</td>
              <td><?php echo getTableRowCount($con, 'activity_logs'); ?></td>
              <td>InnoDB</td>
              <td>16.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>notifications</td>
              <td><?php echo getTableRowCount($con, 'notifications'); ?></td>
              <td>InnoDB</td>
              <td>48.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </div>

  <?php
/**
 * Counts all residents in the database
 * @param mysqli $conn Database connection
 * @return int Number of residents
 */
function getResidentCount($conn) {
    $sql = "SELECT COUNT(*) as total FROM residents";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['total'];
    }
    return 0;
}

/**
 * Counts all reports in the database
 * @param mysqli $conn Database connection
 * @return int Total number of reports
 */
function getTotalReportCount($conn) {
    $sql = "SELECT COUNT(*) as total FROM reports";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['total'];
    }
    return 0;
}

/**
 * Counts all solved reports
 * @param mysqli $conn Database connection
 * @return int Number of solved reports
 */
function getSolvedReportCount($conn) {
    $sql = "SELECT COUNT(*) as total FROM reports WHERE status = 'resolved'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['total'];
    }
    return 0;
}

/**
 * Counts all pending reports
 * @param mysqli $conn Database connection
 * @return int Number of pending reports
 */
function getPendingReportCount($conn) {
    $sql = "SELECT COUNT(*) as total FROM reports WHERE status = 'pending'";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['total'];
    }
    return 0;
}

/**
 * Counts rows in specified table (with security checks)
 * @param mysqli $conn Database connection
 * @param string $tableName Name of table to count
 * @return int Number of rows in table
 */
function getTableRowCount($conn, $tableName) {
    // Whitelist allowed tables for security
    $allowedTables = [
        'residents', 
        'reports', 
        'report_images', 
        'report_status_history', 
        'activity_logs', 
        'notifications'
    ];
    
    if (!in_array($tableName, $allowedTables)) {
        return 0;
    }
    
    $sql = "SELECT COUNT(*) as total FROM `" . mysqli_real_escape_string($conn, $tableName) . "`";
    $result = mysqli_query($conn, $sql);
    if ($result && $row = mysqli_fetch_assoc($result)) {
        return $row['total'];
    }
    return 0;
}
?>
</body>

</html>