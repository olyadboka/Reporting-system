<?php
// Connect to DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$report_id = intval($_GET['id']);

$sql = "SELECT * FROM reports WHERE report_id = ? AND status = 'Fixed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();
$report = $result->fetch_assoc();
$stmt->close();

if (!$report) {
    echo "<h3>This record is not available or not marked as Fixed.</h3>";
    exit();
}

// Fetch schedule info
$sql_schedule = "SELECT * FROM schedules WHERE report_id = ?";
$stmt = $conn->prepare($sql_schedule);
$stmt->bind_param("i", $report_id);
$stmt->execute();
$schedule_result = $stmt->get_result();
$schedule = $schedule_result->fetch_assoc();
$stmt->close();

// For this example, let's assume the person who approved is stored in the reports table (extend logic if needed)
$approved_by = $report['approved_by'] ?? 'Admin'; // You can adjust based on your schema

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report Record</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      padding: 40px;
      font-family: 'Times New Roman', serif;
    }
    .record-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .paragraph {
      margin-top: 30px;
      font-size: 18px;
      line-height: 1.6;
    }
    @media print {
      .btn-print {
        display: none;
      }
    }
  </style>
</head>
<body>
  <div class="record-header">
    <h2>Maintenance Report Record</h2>
    <p><strong>Date Generated:</strong> <?php echo date('Y-m-d'); ?></p>
  </div>

  <table class="table table-bordered">
    <tr><th>Report ID</th><td><?php echo $report['report_id']; ?></td></tr>
    <tr><th>Category</th><td><?php echo $report['category']; ?></td></tr>
    <tr><th>Description</th><td><?php echo $report['description']; ?></td></tr>
    <tr><th>Reported Status</th><td><?php echo $report['status']; ?></td></tr>
    <tr><th>Approved By</th><td><?php echo $approved_by; ?></td></tr>
    <tr><th>Assigned To</th><td><?php echo $schedule['assigned_to'] ?? 'N/A'; ?></td></tr>
    <tr><th>Scheduled Date</th><td><?php echo $schedule['date'] ?? 'N/A'; ?></td></tr>
    <tr><th>Scheduled Time</th><td><?php echo $schedule['time'] ?? 'N/A'; ?></td></tr>
  </table>

  <div class="paragraph">
    <?php if ($schedule): ?>
      <p>
        On <strong><?php echo $schedule['date']; ?></strong> at <strong><?php echo $schedule['time']; ?></strong>, the issue reported under ID 
        <strong><?php echo $report['report_id']; ?></strong> in the <strong><?php echo $report['category']; ?></strong> category was scheduled for resolution.
        The task was assigned to <strong><?php echo $schedule['assigned_to']; ?></strong>. The report, described as 
        "<em><?php echo $report['description']; ?></em>", was initially reviewed and accepted by <strong><?php echo $approved_by; ?></strong>.
        This document serves as a formal record of the actions taken regarding the reported issue.
      </p>
    <?php else: ?>
      <p>
        The issue reported under ID <strong><?php echo $report['report_id']; ?></strong> in the <strong><?php echo $report['category']; ?></strong> category has not been scheduled for a fix yet.
        The report, described as "<em><?php echo $report['description']; ?></em>", was reviewed and accepted by <strong><?php echo $approved_by; ?></strong>.
      </p>
    <?php endif; ?>
  </div>

  <button class="btn btn-primary btn-print mt-4" onclick="window.print()">Print Record</button>
</body>
</html>
