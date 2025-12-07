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
        $sql = "UPDATE schedules SET assigned_to = ?, date = ?, time = ? WHERE report_id = ?";
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
  <link rel="stylesheet" href="../dashboardHome.css">

  <style>
  body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }

  .sidebar {
    min-height: 100vh;
    background-color: #4a6fa5;
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

  /* Table Container */
  .table-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 20px;
    margin-bottom: 30px;
    overflow: hidden;
  }

  /* Base Table Styles */
  .table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.9rem;
    color: #212529;
  }

  /* Table Header */
  .table thead th {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 15px;
    border: none;
    vertical-align: middle;
    position: sticky;
    top: 0;
  }

  /* Table Body */
  .table tbody tr {
    transition: all 0.2s ease;
    background-color: white;
  }

  .table tbody tr:hover {
    background-color: rgba(78, 115, 223, 0.05);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
  }

  .table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
    border-top: 1px solid rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.03);
  }

  /* Zebra Striping */
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(0, 0, 0, 0.02);
  }

  /* Status Badges */
  .badge {
    font-weight: 500;
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 0.75rem;
    display: inline-block;
    min-width: 70px;
    text-align: center;
  }

  .status-pending {
    background-color: #f6c23e;
    color: #000;
  }

  .status-in-progress {
    background-color: #36b9cc;
    color: #fff;
  }

  .status-solved {
    background-color: #1cc88a;
    color: #fff;
  }

  .status-rejected {
    background-color: #e74a3b;
    color: #fff;
  }

  /* Priority Badges */
  .priority-low {
    background-color: #858796;
    color: #fff;
  }

  .priority-medium {
    background-color: #fd7e14;
    color: #000;
  }

  .priority-high {
    background-color: #e74a3b;
    color: #fff;
  }

  /* Action Buttons */
  .btn-table-action {
    padding: 5px 10px;
    font-size: 0.75rem;
    border-radius: 4px;
    margin: 2px;
    transition: all 0.2s;
    box-shadow: none;
  }

  .btn-table-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
  }

  /* Resolved Row Styling */
  .resolved-row {
    background-color: rgba(248, 249, 250, 0.7);
  }

  .resolved-row td {
    color: #6c757d;
  }

  .resolved-row:hover {
    background-color: rgba(248, 249, 250, 0.9);
  }

  /* Description Cell */
  .description-cell {
    max-width: 250px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  /* Responsive Table */
  @media (max-width: 768px) {
    .table-responsive {
      border: 1px solid #e3e6f0;
      border-radius: 5px;
    }

    .table thead {
      display: none;
    }

    .table tbody tr {
      display: block;
      margin-bottom: 15px;
      border: 1px solid #e3e6f0;
      border-radius: 5px;
    }

    .table tbody td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      border: none;
      border-bottom: 1px solid #e3e6f0;
    }

    .table tbody td::before {
      content: attr(data-label);
      font-weight: bold;
      margin-right: 10px;
      color: #4e73df;
    }

    .description-cell {
      max-width: 100%;
      white-space: normal;
    }
  }

  /* Hover Effects */
  .table-hover tbody tr:hover td {
    color: #212529;
  }

  /* Scrollable Table */
  .table-scrollable {
    max-height: 500px;
    overflow-y: auto;
  }

  /* Table Caption */
  .table caption {
    caption-side: top;
    padding: 10px;
    font-weight: bold;
    color: #4e73df;
  }

  /* Resolution Modal Styles */
  #resolveModal .modal-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
  }

  #resolveModal .modal-content {
    border: none;
    border-radius: 10px;
    overflow: hidden;
  }

  #resolutionNotes {
    min-height: 120px;
    resize: vertical;
  }
  </style>

</head>

<body>
  <div class="d-flex">
    <!-- Sidebar -->
    <aside class="sidebar">
      <h3 class="sidebar-title px-3"><i class="fas fa-cogs"></i> Admin Panel</h3>
      <nav class="menu-container mt-4">
        <ul class="nav flex-column">
          <li class="nav-item"><a class="nav-link text-white" href="/Admin Dashboard/dashboardHome.php"><i
                class="fas fa-home"></i>
              <span>Dashboard</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../all reports/allReports.php"><i
                class="fas fa-folder"></i> <span>All Reports</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../ResidentsManagement/residentsManagent.php"><i
                class="fas fa-users"></i> <span>Residents</span></a></li>

          <li class="nav-item"><a class="nav-link text-white active"
              href="../ScheduleAndAssignments/scheduleAndAssignments.php"><i class="fas fa-calendar-alt"></i>
              <span>Schedule</span></a></li>
          <li class="nav-item"><a class="nav-link text-white" href="../../Hermata home/index.php"><i
                class="fas fa-user-shield"></i> <span>Login as Resident</span></a></li>


          <li class="nav-item"><a class="nav-link text-white" href="../ActivityLogs/activityLogs.php"><i
                class="fas fa-history"></i> <span>Activity Logs</span></a></li>

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
                    echo "<a href='../ReportsAndAnalytics/reportRecord.php?id={$row['report_id']}' class='btn btn-info btn-sm'>View Record</a>";
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
        JOIN reports r ON s.report_id = r.report_id";
              $result = $conn->query($sql);

              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
    $row_class = strtolower($row['status']) === 'resolved' ? 'resolved-row' : '';
    echo "<tr class='{$row_class}'>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['report_id']}</td>";
    echo "<td>{$row['assigned_to']}</td>";
    echo "<td>{$row['date']}</td>";
    echo "<td>{$row['time']}</td>";
    echo "<td>";
    if (strtolower($row['status']) !== 'resolved') {
        echo "<a href='?edit_schedule_id={$row['id']}' class='btn btn-warning btn-sm'>Edit</a>";
        echo "<button class='btn btn-success btn-sm resolve-btn' data-id='{$row['report_id']}'>Resolve</button>";
    } else {
        echo "<span class='badge bg-secondary'>Resolved</span>";
    }
    echo "</td>";
    echo "</tr>";
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

  <!-- Resolution Dialog Modal -->
  <div class="modal fade" id="resolveModal" tabindex="-1" aria-labelledby="resolveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="resolveModalLabel">Resolve Report</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="resolveForm">
            <input type="hidden" id="reportToResolveId" name="report_id">
            <div class="mb-3">
              <label for="resolutionNotes" class="form-label">How was this resolved?</label>
              <textarea class="form-control" id="resolutionNotes" name="resolution_notes" rows="4" required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmResolve">Mark as Resolved</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
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


  });
  </script>
  <script>
  // Show resolution dialog
  $(document).on('click', '.resolve-btn', function() {
    const reportId = $(this).data('id');
    $('#reportToResolveId').val(reportId);
    $('#resolveModal').modal('show');
  });

  // Handle resolution submission
  $('#confirmResolve').click(function() {
    const $btn = $(this);
    const notes = $('#resolutionNotes').val().trim();

    if (!notes) {
      alert("Please enter resolution notes");
      return;
    }

    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

    $.post('resolve_report.php', {
      report_id: $('#reportToResolveId').val(),
      resolution_notes: notes
    }, function(response) {
      if (response === "OK") {
        location.reload(); // Refresh page on success
      } else {
        alert("Error: " + response);
        $btn.prop('disabled', false).html('Mark as Resolved');
      }
    }).fail(function() {
      alert("Request failed - try again");
      $btn.prop('disabled', false).html('Mark as Resolved');
    });
  });
  </script>
</body>

</html>