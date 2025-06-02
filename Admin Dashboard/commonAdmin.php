<?php
// session_start();
include './dbConnection.php'; // This should contain your database connection
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
  <link rel="stylesheet" href="./allReports.css">
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


    <!-- Main Content -->
    <div class="main-content">


      <!-- Main Section -->
      <section class="dashboard-content">