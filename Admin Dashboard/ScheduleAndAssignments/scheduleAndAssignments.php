<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Approve Report
if (isset($_GET['approve_id'])) {
    $approve_id = intval($_GET['approve_id']); // Sanitize input
    $sql = "UPDATE reports SET status = 'approved' WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php"); // Refresh the page
    exit();
}

// Handle Reject Report
if (isset($_GET['reject_id'])) {
    $reject_id = intval($_GET['reject_id']); // Sanitize input
    $sql = "UPDATE reports SET status = 'rejected' WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reject_id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php"); // Refresh the page
    exit();
}

// Handle Add or Edit Schedule Fix
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_fix'])) {
    $report_id = intval($_POST['report_id']); // Sanitize input
    $assigned_to = htmlspecialchars(trim($_POST['assigned_to']));
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null;

    if ($schedule_id) {
        // Update existing schedule
        $sql = "UPDATE schedules SET assigned_to = ?, date = ?, time = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $assigned_to, $date, $time, $schedule_id);
    } else {
        // Add new schedule
        $sql = "INSERT INTO schedules (report_id, assigned_to, date, time) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $report_id, $assigned_to, $date, $time);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php"); // Refresh the page
    exit();
}

// Retrieve the schedule ID from the query string
if (isset($_GET['edit_schedule_id'])) {
    $edit_schedule_id = intval($_GET['edit_schedule_id']); // Sanitize input
    $sql = "SELECT * FROM schedules WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $edit_schedule_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_schedule = $result->fetch_assoc();
    $stmt->close();

    // Store the schedule data in the session
    $_SESSION['edit_schedule'] = $edit_schedule;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_schedule'])) {
    // Handle the form submission to update the schedule
    $schedule_id = intval($_POST['schedule_id']);
    $assigned_to = htmlspecialchars(trim($_POST['assigned_to']));
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));

    $sql = "UPDATE schedules SET assigned_to = ?, date = ?, time = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $assigned_to, $date, $time, $schedule_id);
    $stmt->execute();
    $stmt->close();

    // Redirect back to the main page
    header("Location: scheduleAndAssignments.php");
    exit();
} else {
    // If no schedule ID is provided, redirect back to the main page
    header("Location: scheduleAndAssignments.php");
    exit();
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
<<<<<<< HEAD

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
          <li class="nav-item"><a class="nav-link text-white" href="../Hermata home/index.php"><i
                class="fas fa-user-shield"></i> <span>Login as Resident</span></a></li>


          <li class="nav-item"><a class="nav-link text-white" href="../ActivityLogs/activityLogs.php"><i
                class="fas fa-history"></i> <span>Activity Logs</span></a></li>

          <li class="nav-item"><a class="nav-link text-white" href="../login/logout.php"><i
                class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
        </ul>
      </nav>
    </aside>
=======
  <link rel="stylesheet" href="scheduleAndAssignments.css">
</head>

<body>
<?php include "../commonAdmin.php"; ?>
  <?php include "../../reportDB/dbconnection.php"; ?>
  <div class="container">
    <h1>Schedule and Assignments</h1>
>>>>>>> caa0e440608bf04a06fe2b50e8bb4420363ba971

    <!-- Reports Table -->
    <h2>Reports</h2>
    <table>
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
                echo "<tr>
                        <td>{$row['report_id']}</td>
                        <td>{$row['category']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['priority']}</td>
                        <td>
                          <button class='btn-success' onclick=\"scheduleFix({$row['report_id']})\">Schedule Fix</button>
                          <a href='?approve_id={$row['report_id']}' class='btn-success'>Approve</a>
                          <a href='?reject_id={$row['report_id']}' class='btn-danger'>Reject</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No reports found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Scheduled Fixes Table -->
    <h2>Scheduled Fixes</h2>
    <table>
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
        $sql = "SELECT id, report_id, assigned_to, date, time FROM schedules";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['report_id']}</td>
                        <td>{$row['assigned_to']}</td>
                        <td>{$row['date']}</td>
                        <td>{$row['time']}</td>
                        <td>
                          <a href=\"editSchedule.php?edit_schedule_id={$row['id']}\" class=\"btn-success\">Edit</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No scheduled fixes found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Schedule Fix Form -->
    <div class="form-container">
      <h3><?php echo $edit_schedule ? 'Edit Schedule Fix' : 'Add Schedule Fix'; ?></h3>
      <form method="POST" action="">
        <?php if ($edit_schedule): ?>
          <input type="hidden" name="schedule_id" value="<?php echo $edit_schedule['id']; ?>">
        <?php endif; ?>
        <input type="hidden" name="report_id" id="report_id" value="<?php echo $edit_schedule['report_id'] ?? ''; ?>">
        <label for="assigned_to">Assigned To:</label>
        <input type="text" name="assigned_to" id="assigned_to" value="<?php echo $edit_schedule['assigned_to'] ?? ''; ?>" required>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" value="<?php echo $edit_schedule['date'] ?? ''; ?>" required>
        <label for="time">Time:</label>
        <input type="time" name="time" id="time" value="<?php echo $edit_schedule['time'] ?? ''; ?>" required>
        <button type="submit" name="schedule_fix"><?php echo $edit_schedule ? 'Update' : 'Add'; ?></button>
      </form>
    </div>
  </div>

  <script>
    function scheduleFix(reportId) {
        console.log("Schedule Fix clicked for Report ID:", reportId); // Debugging output
        const reportInput = document.getElementById('report_id');
        if (reportInput) {
            reportInput.value = reportId; // Set the report ID in the hidden input field
            console.log("Report ID set in form:", reportInput.value); // Debugging output
        } else {
            console.error("Report ID input field not found!"); // Debugging output
        }
        const formContainer = document.querySelector('.form-container');
        if (formContainer) {
            formContainer.scrollIntoView({ behavior: 'smooth' }); // Scroll to the form
            console.log("Form scrolled into view."); // Debugging output
        } else {
            console.error("Form container not found!"); // Debugging output
        }
    }

    // Handle "Schedule Fix" button click
    document.querySelectorAll('.btn-success').forEach(button => {
      if (button.textContent.trim() === 'Schedule Fix') {
        button.addEventListener('click', function () {
          const reportId = this.getAttribute('onclick').match(/\d+/)[0]; // Extract report ID
          document.getElementById('report_id').value = reportId; // Set the report ID in the hidden input field
          document.querySelector('.form-container').scrollIntoView({ behavior: 'smooth' }); // Scroll to the form
        });
      }
    });

    // Handle "Edit" button click
    document.querySelectorAll('.btn-success').forEach(button => {
      if (button.textContent.trim() === 'Edit') {
        button.addEventListener('click', function (event) {
          event.preventDefault(); // Prevent default link behavior
          const editScheduleId = this.getAttribute('href').match(/\d+/)[0]; // Extract schedule ID
          console.log("Edit button clicked for Schedule ID:", editScheduleId); // Debugging output
          if (editScheduleId) {
              window.location.href = `?edit_schedule_id=${editScheduleId}`; // Redirect with the edit_schedule_id
          } else {
              console.error("Edit Schedule ID not found!"); // Debugging output
          }
        });
      }
    });
  </script>
</body>

</html>