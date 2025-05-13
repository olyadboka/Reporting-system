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

// Handle Schedule Fix
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_fix'])) {
    $report_id = intval($_POST['report_id']); // Sanitize input
    $assigned_to = htmlspecialchars(trim($_POST['assigned_to']));
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));

    $sql = "INSERT INTO schedules (report_id, assigned_to, date, time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $report_id, $assigned_to, $date, $time);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php"); // Refresh the page
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Schedule and Assignments</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f8f9fc;
    }

    .container {
      max-width: 1200px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #4e73df;
      text-align: center;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table th,
    table td {
      border: 1px solid #858796;
      padding: 10px;
      text-align: left;
    }

    table th {
      background-color: #4e73df;
      color: white;
    }

    table tr:nth-child(even) {
      background-color: #f8f9fc;
    }

    table tr:hover {
      background-color: #36b9cc;
      color: white;
    }

    .btn-success {
      background-color: #1cc88a;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-success:hover {
      background-color: #5a5c69;
    }

    .btn-danger {
      background-color: #e74a3b;
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-danger:hover {
      background-color: #5a5c69;
    }

    .form-container {
      display: none;
      margin-top: 20px;
      padding: 20px;
      background-color: #f8f9fc;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container.active {
      display: block;
    }

    .form-container label {
      display: block;
      margin-bottom: 5px;
      color: #4e73df;
    }

    .form-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid #858796;
      border-radius: 5px;
    }

    .form-container button {
      background-color: #1cc88a;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: #5a5c69;
    }
  </style>
</head>

<body>
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
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No scheduled fixes found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Schedule Fix Form -->
    <div id="scheduleFixForm" class="form-container">
      <h3>Schedule Fix</h3>
      <form method="POST" action="">
        <input type="hidden" name="report_id" id="report_id">
        <label for="assigned_to">Assigned To:</label>
        <input type="text" name="assigned_to" id="assigned_to" required>
        <label for="date">Date:</label>
        <input type="date" name="date" id="date" required>
        <label for="time">Time:</label>
        <input type="time" name="time" id="time" required>
        <button type="submit" name="schedule_fix">Schedule</button>
      </form>
    </div>
  </div>

  <script>
    function scheduleFix(reportId) {
      document.getElementById('scheduleFixForm').classList.add('active');
      document.getElementById('report_id').value = reportId;
    }
  </script>
</body>

</html>