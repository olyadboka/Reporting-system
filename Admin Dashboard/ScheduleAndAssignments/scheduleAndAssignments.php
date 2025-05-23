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
    $approve_id = intval($_GET['approve_id']);
    $sql = "UPDATE reports SET status = 'approved' WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php");
    exit();
}

// Handle Reject Report
if (isset($_GET['reject_id'])) {
    $reject_id = intval($_GET['reject_id']);
    $sql = "UPDATE reports SET status = 'rejected' WHERE report_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reject_id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php");
    exit();
}

// Handle Add or Edit Schedule Fix
$edit_schedule = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_fix'])) {
    $report_id = intval($_POST['report_id']);
    $assigned_to = htmlspecialchars(trim($_POST['assigned_to']));
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $schedule_id = isset($_POST['schedule_id']) ? intval($_POST['schedule_id']) : null;

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

// Handle Edit Schedule
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
if (!isset($edit_schedule)) $edit_schedule = null;
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
  <link rel="stylesheet" href="scheduleAndAssignments.css">
</head>
<body>
<?php include "../commonAdmin.php"; ?>
<div class="container">
  <h1>Schedule and Assignments</h1>

  <!-- Reports Table -->
  <h2>Reports</h2>
  <table class="table table-bordered">
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
                        <button type='button' class='btn btn-primary schedule-fix-btn' data-report-id='{$row['report_id']}'>Schedule Fix</button>
                        <a href='?approve_id={$row['report_id']}' class='btn btn-success'>Approve</a>
                        <a href='?reject_id={$row['report_id']}' class='btn btn-danger'>Reject</a>
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
  <table class="table table-bordered">
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
                        <a href='?edit_schedule_id={$row['id']}' class='btn btn-success'>Edit</a>
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
  <div class="form-container mt-4">
    <h3 id="form-title"><?php echo $edit_schedule ? 'Edit Schedule Fix' : 'Add Schedule Fix'; ?></h3>
    <form method="POST" action="">
      <?php if ($edit_schedule): ?>
        <input type="hidden" name="schedule_id" value="<?php echo $edit_schedule['id']; ?>">
      <?php endif; ?>
      <input type="hidden" name="report_id" id="report_id" value="<?php echo $edit_schedule['report_id'] ?? ''; ?>">
      <div class="mb-2">
        <label for="assigned_to" class="form-label">Assigned To:</label>
        <input type="text" name="assigned_to" id="assigned_to" class="form-control" value="<?php echo $edit_schedule['assigned_to'] ?? ''; ?>" required>
      </div>
      <div class="mb-2">
        <label for="date" class="form-label">Date:</label>
        <input type="date" name="date" id="date" class="form-control" value="<?php echo $edit_schedule['date'] ?? ''; ?>" required>
      </div>
      <div class="mb-2">
        <label for="time" class="form-label">Time:</label>
        <input type="time" name="time" id="time" class="form-control" value="<?php echo $edit_schedule['time'] ?? ''; ?>" required>
      </div>
      <button type="submit" name="schedule_fix" class="btn btn-primary"><?php echo $edit_schedule ? 'Update' : 'Add'; ?></button>
    </form>
  </div>
</div>

<!-- JavaScript -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.schedule-fix-btn').forEach(button => {
      button.addEventListener('click', function () {
        const reportId = this.getAttribute('data-report-id');

        // Set the report ID
        const reportIdInput = document.getElementById('report_id');
        if (reportIdInput) reportIdInput.value = reportId;

        // Clear other input fields
        const assignedToInput = document.getElementById('assigned_to');
        const dateInput = document.getElementById('date');
        const timeInput = document.getElementById('time');

        if (assignedToInput) assignedToInput.value = '';
        if (dateInput) dateInput.value = '';
        if (timeInput) timeInput.value = '';

        // Remove schedule_id input if present (ensures Add mode)
        const scheduleIdInput = document.querySelector('input[name="schedule_id"]');
        if (scheduleIdInput) {
          scheduleIdInput.remove();
        }

        // Update form title and submit button text
        const formTitle = document.querySelector('.form-container h3');
        if (formTitle) formTitle.textContent = 'Add Schedule Fix';

        const submitButton = document.querySelector('.form-container button[type="submit"]');
        if (submitButton) submitButton.textContent = 'Add';

        // Make form visible in case it's hidden by CSS
        const formContainer = document.querySelector('.form-container');
        if (formContainer) {
          formContainer.style.display = 'block';
          formContainer.scrollIntoView({ behavior: 'smooth' });
        }

        // Debug log
        console.log("Schedule Fix button clicked for report ID:", reportId);
      });
    });
  });
</script>




</body>
</html>
