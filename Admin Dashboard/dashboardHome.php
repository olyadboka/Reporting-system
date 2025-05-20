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
        <img src="./images/olyad2.png" alt="Admin Profile" class="profile-pic">
        <div>
          <h4 class="admin-name">Olyad Boka</h4>
          <p class="admin-role">Admin</p>
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
          <div class="stat-value"><?php echo getResidentCount(); ?></div>
          <div class="stat-change">+5% from last month</div>
        </div>

        <div class="stat-card">
          <i class="fas fa-file-alt"></i>
          <h3>Total Reports</h3>
          <div class="stat-value"><?php echo getTotalReportCount(); ?></div>
          <div class="stat-change">+12% from last month</div>
        </div>

        <div class="stat-card">
          <i class="fas fa-check-circle"></i>
          <h3>Solved Reports</h3>
          <div class="stat-value"><?php echo getSolvedReportCount(); ?></div>
          <div class="stat-change">+8% from last month</div>
        </div>

        <div class="stat-card">
          <i class="fas fa-clock"></i>
          <h3>Pending Reports</h3>
          <div class="stat-value"><?php echo getPendingReportCount(); ?></div>
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
              <td><?php echo getTableRowCount('residents'); ?></td>
              <td>InnoDB</td>
              <td>288.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>reports</td>
              <td><?php echo getTableRowCount('reports'); ?></td>
              <td>InnoDB</td>
              <td>272.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>report_images</td>
              <td><?php echo getTableRowCount('report_images'); ?></td>
              <td>InnoDB</td>
              <td>400.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>report_status_history</td>
              <td><?php echo getTableRowCount('report_status_history'); ?></td>
              <td>InnoDB</td>
              <td>48.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>activity_logs</td>
              <td><?php echo getTableRowCount('activity_logs'); ?></td>
              <td>InnoDB</td>
              <td>16.0 KiB</td>
              <td><span class="status-badge status-active">Active</span></td>
            </tr>
            <tr>
              <td>notifications</td>
              <td><?php echo getTableRowCount('notifications'); ?></td>
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
  // These functions would need to be implemented to query your database
  function getResidentCount() {
    // Query database for resident count
    // Example: "SELECT COUNT(*) FROM residents"
    return "42"; // Replace with actual count
  }
  
  function getTotalReportCount() {
    // Query database for total report count
    // Example: "SELECT COUNT(*) FROM reports"
    return "127"; // Replace with actual count
  }
  
  function getSolvedReportCount() {
    // Query database for solved reports count
    // Example: "SELECT COUNT(*) FROM reports WHERE status = 'solved'"
    return "89"; // Replace with actual count
  }
  
  function getPendingReportCount() {
    // Query database for pending reports count
    // Example: "SELECT COUNT(*) FROM reports WHERE status = 'pending'"
    return "38"; // Replace with actual count
  }
  
  function getTableRowCount($tableName) {
    // Query database for row count in specified table
    // Example: "SELECT COUNT(*) FROM $tableName"
    switch($tableName) {
      case 'residents': return "42";
      case 'reports': return "127";
      case 'report_images': return "85";
      case 'report_status_history': return "210";
      case 'activity_logs': return "3";
      case 'notifications': return "15";
      default: return "0";
    }
  }
  ?>
</body>

</html>