<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle status updates
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $action = $_GET['action'];
    
    switch($action) {
        case 'approve':
            $sql = "UPDATE reports SET status = 'approved' WHERE report_id = ?";
            break;
        case 'reject':
            $sql = "UPDATE reports SET status = 'rejected' WHERE report_id = ?";
            break;
        case 'resolve':
            $sql = "UPDATE reports SET status = 'resolved' WHERE report_id = ?";
            // Also remove from schedules if exists
            $conn->query("DELETE FROM schedules WHERE report_id = $id");
            break;
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php");
    exit();
}

// Add or Edit Schedule
$edit_schedule = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_fix'])) {
    $report_id = intval($_POST['report_id']);
    $assigned_to = htmlspecialchars(trim($_POST['assigned_to']));
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null;

    // First update report status to approved if not already
    $conn->query("UPDATE reports SET status = 'approved' WHERE report_id = $report_id");
    
    if ($schedule_id) {
        $sql = "UPDATE schedules SET assigned_to = ?, date = ?, time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $assigned_to, $date, $time, $schedule_id);
    } else {
        $sql = "INSERT INTO schedules (report_id, assigned_to, date, time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $report_id, $assigned_to, $date, $time);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php");
    exit();
}

// Load schedule for editing
if (isset($_GET['edit_schedule_id'])) {
    $edit_schedule_id = intval($_GET['edit_schedule_id']);
    $sql = "SELECT * FROM schedules WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_schedule = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Schedule and Assignments</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
  body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .sidebar {
    min-height: 100vh;
    background: #343a40;
    color: white;
    width: 250px;
    position: fixed;
    padding-top: 20px;
  }

  .main-content {
    margin-left: 250px;
    padding: 20px;
    width: calc(100% - 250px);
  }

  .table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    padding: 20px;
    margin-bottom: 30px;
  }

  .form-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
    padding: 25px;
    margin-top: 20px;
    display: none;
    /* Initially hidden */
  }

  .table {
    table-layout: fixed;
  }

  .table th {
    background-color: #f8f9fa;
    font-weight: 600;
  }

  .table td {
    vertical-align: middle;
    word-wrap: break-word;
  }

  /* Column widths */
  .reports-table td:nth-child(1),
  .reports-table th:nth-child(1) {
    width: 8%;
  }

  .reports-table td:nth-child(2),
  .reports-table th:nth-child(2) {
    width: 12%;
  }

  .reports-table td:nth-child(3),
  .reports-table th:nth-child(3) {
    width: 35%;
  }

  .reports-table td:nth-child(4),
  .reports-table th:nth-child(4) {
    width: 10%;
  }

  .reports-table td:nth-child(5),
  .reports-table th:nth-child(5) {
    width: 10%;
  }

  .reports-table td:nth-child(6),
  .reports-table th:nth-child(6) {
    width: 25%;
  }

  .schedules-table td:nth-child(1),
  .schedules-table th:nth-child(1) {
    width: 10%;
  }

  .schedules-table td:nth-child(2),
  .schedules-table th:nth-child(2) {
    width: 10%;
  }

  .schedules-table td:nth-child(3),
  .schedules-table th:nth-child(3) {
    width: 20%;
  }

  .schedules-table td:nth-child(4),
  .schedules-table th:nth-child(4) {
    width: 15%;
  }

  .schedules-table td:nth-child(5),
  .schedules-table th:nth-child(5) {
    width: 15%;
  }

  .schedules-table td:nth-child(6),
  .schedules-table th:nth-child(6) {
    width: 20%;
  }

  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    margin: 2px;
  }

  @media (max-width: 992px) {
    .sidebar {
      width: 100%;
      position: relative;
      min-height: auto;
    }

    .main-content {
      margin-left: 0;
      width: 100%;
    }
  }
  </style>
</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h2 class="sidebar-title px-3"><i class="fas fa-cogs"></i> Admin Panel</h2>
      <nav class="menu-container mt-4">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link text-white" href="#"><i class="fas fa-home"></i>
              <span>Dashboard</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="./all reports/allReports.php"><i
                class="fas fa-folder"></i> <span>All Reports</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="./ResidentsManagement/residentsManagent.php"><i
                class="fas fa-users"></i> <span>Residents</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="./ManageCatagories/manageCategories.php"><i
                class="fas fa-tags"></i> <span>Categories</span></a></li>
          <li class="nav-item"><a class="nav-link text-white active"
              href="./ScheduleAndAssignments/scheduleAndAssignments.php"><i class="fas fa-calendar-alt"></i>
              <span>Schedule</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../Hermata home/index.php"><i
                class="fas fa-user-shield"></i> <span>Login as Resident</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="./ReportsAndAnalytics/reportsAndAnalytics.php"><i
                class="fas fa-chart-bar"></i> <span>Analytics</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="./Notification/notification.php"><i
                class="fas fa-bell"></i> <span>Notifications</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="./ActivityLogs/activityLogs.php"><i
                class="fas fa-history"></i> <span>Activity Logs</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="./SystemSettings/systemSetting.php"><i
                class="fas fa-cog"></i> <span>Settings</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../login/logout.php"><i
                class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
      <h1 class="mb-4">Schedule and Assignments</h1>

      <!-- Reports Table -->
      <div class="table-container">
        <h2 class="mb-3">Reports</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-hover reports-table">
            <thead>
              <tr>
                <th>Report ID</th>
                <th>Category</th>
                <th>Description</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT report_id, category, description, status, priority FROM reports";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $status_class = '';
                  switch(strtolower($row['status'])) {
                    case 'approved': $status_class = 'text-primary'; break;
                    case 'rejected': $status_class = 'text-danger'; break;
                    case 'resolved': $status_class = 'text-success'; break;
                    default: $status_class = 'text-secondary';
                  }
                  
                  echo "<tr>
                    <td>{$row['report_id']}</td>
                    <td>{$row['category']}</td>
                    <td style='word-wrap: break-word;'>{$row['description']}</td>
                    <td class='{$status_class} fw-bold'>{$row['status']}</td>
                    <td>{$row['priority']}</td>
                    <td>";
                  
                  if (strtolower($row['status']) === 'resolved') {
                    echo "<a href='generate_report.php?report_id={$row['report_id']}' class='btn btn-info btn-sm'>View Record</a>";
                  } else {
                    echo "<button class='btn btn-primary btn-sm schedule-fix-btn' data-report-id='{$row['report_id']}'>Schedule Fix</button>";
                    if (strtolower($row['status']) !== 'approved') {
                      echo "<a href='?action=approve&id={$row['report_id']}' class='btn btn-success btn-sm'>Approve</a>";
                      echo "<a href='?action=reject&id={$row['report_id']}' class='btn btn-danger btn-sm'>Reject</a>";
                    }
                  }
                  
                  echo "</td></tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='text-center'>No reports found.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Scheduled Fixes Table -->
      <div class="table-container">
        <h2 class="mb-3">Scheduled Fixes</h2>
        <div class="table-responsive">
          <table class="table table-bordered table-hover schedules-table">
            <thead>
              <tr>
                <th>Schedule ID</th>
                <th>Report ID</th>
                <th>Assigned To</th>
                <th>Date</th>
                <th>Time</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $sql = "SELECT s.id, s.report_id, s.assigned_to, s.date, s.time, r.status 
                      FROM schedules s
                      JOIN reports r ON s.report_id = r.report_id
                      WHERE r.status != 'resolved'";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['report_id']}</td>
                    <td style='word-wrap: break-word;'>{$row['assigned_to']}</td>
                    <td>{$row['date']}</td>
                    <td>{$row['time']}</td>
                    <td>
                      <a href='?edit_schedule_id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>
                      <a href='?action=resolve&id={$row['report_id']}' class='btn btn-success btn-sm'>Resolve</a>
                    </td>
                  </tr>";
                }
              } else {
                echo "<tr><td colspan='6' class='text-center'>No scheduled fixes found.</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Schedule Fix Form -->
      <div class="form-container" id="scheduleForm">
        <h3 class="mb-4"><?php echo isset($edit_schedule) ? 'Edit Schedule Fix' : 'Add Schedule Fix'; ?></h3>
        <form method="POST" action="">
          <?php if (isset($edit_schedule)): ?>
          <input type="hidden" name="schedule_id" value="<?php echo $edit_schedule['id']; ?>">
          <?php endif; ?>
          <div class="mb-3">
            <label for="report_id" class="form-label">Report ID</label>
            <input type="number" class="form-control" name="report_id" id="report_id"
              value="<?php echo $edit_schedule['report_id'] ?? ''; ?>" required readonly>
          </div>
          <div class="mb-3">
            <label for="assigned_to" class="form-label">Assigned To</label>
            <input type="text" class="form-control" name="assigned_to" id="assigned_to"
              value="<?php echo $edit_schedule['assigned_to'] ?? ''; ?>" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="date" class="form-label">Date</label>
              <input type="date" class="form-control" name="date" id="date"
                value="<?php echo $edit_schedule['date'] ?? ''; ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label for="time" class="form-label">Time</label>
              <input type="time" class="form-control" name="time" id="time"
                value="<?php echo $edit_schedule['time'] ?? ''; ?>" required>
            </div>
          </div>
          <button type="submit" name="schedule_fix" class="btn btn-primary">
            <?php echo isset($edit_schedule) ? 'Update Schedule' : 'Add Schedule'; ?>
          </button>
          <button type="button" class="btn btn-secondary" id="cancelForm">Cancel</button>
        </form>
      </div>
    </div>
  </div>

  <script>
  // Show/hide form functionality
  document.querySelectorAll('.schedule-fix-btn').forEach(button => {
    button.addEventListener('click', function() {
      const reportId = this.getAttribute('data-report-id');
      document.getElementById('report_id').value = reportId;

      // Clear other fields if not in edit mode
      if (!<?php echo isset($edit_schedule) ? 'true' : 'false'; ?>) {
        document.getElementById('assigned_to').value = '';
        document.getElementById('date').value = '';
        document.getElementById('time').value = '';
      }

      // Show form
      document.getElementById('scheduleForm').style.display = 'block';

      // Scroll to form
      document.getElementById('scheduleForm').scrollIntoView({
        behavior: 'smooth'
      });
    });
  });

  // Cancel button functionality
  document.getElementById('cancelForm').addEventListener('click', function() {
    document.getElementById('scheduleForm').style.display = 'none';
  });

  // Show form if in edit mode
  <?php if (isset($edit_schedule)): ?>
  document.getElementById('scheduleForm').style.display = 'block';
  <?php endif; ?>
  </script>
</body>

</html>