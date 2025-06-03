<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Activity Log</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../dashboardHome.css">
  <link rel="stylesheet" href="activityLogs.css">
</head>

<body>
<<<<<<< HEAD
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="sidebar-title"><i class="fas fa-cogs"></i> Admin Panel</h2>
    <nav class="menu-container">
      <ul>
        <li><a href="../dashboardHome.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        <li><a href="../all reports/allReports.php"><i class="fas fa-folder"></i> <span>All Reports</span></a></li>
        <li><a href="./ResidentsManagement/residentsManagent.php"><i class="fas fa-users"></i>
            <span>Residents</span></a></li>

        <li><a href="../ScheduleAndAssignments/scheduleAndAssignments.php"><i class="fas fa-calendar-alt"></i>
            <span>Schedule</span></a></li>
        <li><a href="../../Hermata home/index.php"><i class="fas fa-user-shield"></i> <span>Login as Resident</span></a>
        </li>
        <li><a href="../ReportsAndAnalytics/reportsAndAnalytics.php"><i class="fas fa-chart-bar"></i>
            <span>Analytics</span></a></li>

        <li><a href="#"><i class="fas fa-history"></i> <span>Activity Logs</span></a></li>

        <li><a href="../../login/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
      </ul>
    </nav>
  </aside>
=======
  <?php include "../commonAdmin.php"; ?>
  <?php include "../../reportDB/dbconnection.php"; ?>
  <div class="container">
    <h1>Activity Logs</h1>
>>>>>>> caa0e440608bf04a06fe2b50e8bb4420363ba971

    <?php
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hmreportsystem";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch activity logs
    $sql = "SELECT id, activity, user, timestamp FROM activity_logs ORDER BY timestamp DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Activity</th>";
        echo "<th>User</th>";
        echo "<th>Timestamp</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['activity'] . "</td>";
            echo "<td>" . $row['user'] . "</td>";
            echo "<td>" . $row['timestamp'] . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
    } else {
        echo "<p>No activity logs found.</p>";
    }

    $conn->close();
    ?>
  </div>
</body>

</html>