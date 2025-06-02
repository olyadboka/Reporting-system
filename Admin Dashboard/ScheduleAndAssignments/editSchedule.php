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

// Retrieve the schedule ID from the query string
if (isset($_GET['edit_schedule_id'])) {
    $edit_schedule_id = intval($_GET['edit_schedule_id']); 
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
  <title>Edit Schedule</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="container">
    <h1>Edit Schedule</h1>
    <?php if (isset($_SESSION['edit_schedule'])): ?>
    <?php $edit_schedule = $_SESSION['edit_schedule']; ?>
    <form method="POST" action="">
      <input type="hidden" name="schedule_id" value="<?php echo $edit_schedule['id']; ?>">
      <label for="assigned_to">Assigned To:</label>
      <input type="text" name="assigned_to" id="assigned_to" value="<?php echo $edit_schedule['assigned_to']; ?>"
        required>
      <label for="date">Date:</label>
      <input type="date" name="date" id="date" value="<?php echo $edit_schedule['date']; ?>" required>
      <label for="time">Time:</label>
      <input type="time" name="time" id="time" value="<?php echo $edit_schedule['time']; ?>" required>
      <button type="submit" name="update_schedule" class="btn btn-primary">Update Schedule</button>
    </form>
    <?php else: ?>
    <p>No schedule found to edit.</p>
    <?php endif; ?>
  </div>
</body>

</html>