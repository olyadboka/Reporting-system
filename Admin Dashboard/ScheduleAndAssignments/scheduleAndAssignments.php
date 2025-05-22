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
  <link rel="stylesheet" href="scheduleAndAssignments.css">
</head>

<body>
<?php include "../commonAdmin.php"; ?>
  <?php include "../../reportDB/dbconnection.php"; ?>
  <div class="container">
    <h1>Schedule and Assignments</h1>

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