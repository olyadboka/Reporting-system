<?php
include "../commonAdmin.php";
include "../../reportDB/dbconnection.php"; // Ensure this file initializes $conn

// Handle Delete User
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']); // Sanitize input
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php"); // Refresh the page
    exit();
}

// Handle Edit User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $edit_id = intval($_POST['id']); // Sanitize input
    $fname = htmlspecialchars(trim($_POST['fname']));
    $mname = htmlspecialchars(trim($_POST['mname']));
    $fathersName = htmlspecialchars(trim($_POST['fathersName']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = htmlspecialchars(trim($_POST['email']));
    $role = htmlspecialchars(trim($_POST['role']));

    $sql = "UPDATE users SET fname = ?, mname = ?, fathersName = ?, phone = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $fname, $mname, $fathersName, $phone, $email, $role, $edit_id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php"); // Refresh the page
    exit();
}

// Handle Approve Report
if (isset($_GET['approve_id'])) {
    $approve_id = intval($_GET['approve_id']); // Sanitize input
    $sql = "UPDATE reports SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $approve_id);
    $stmt->execute();
    $stmt->close();
    header("Location: scheduleAndAssignments.php"); // Refresh the page
    exit();
}

// Handle Schedule Fix
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_fix'])) {
    $report_id = intval($_POST['report_id']); // Sanitize input
    $task_name = htmlspecialchars(trim($_POST['task_name']));
    $assigned_to = htmlspecialchars(trim($_POST['assigned_to']));
    $deadline = htmlspecialchars(trim($_POST['deadline']));

    $sql = "INSERT INTO schedules (task_name, assigned_to, deadline, report_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $task_name, $assigned_to, $deadline, $report_id);
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
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../dashboardHome.css">
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #858796;
      --success-color: #1cc88a;
      --danger-color: #e74a3b;
      --light-color: #f8f9fc;
      --dark-color: #5a5c69;
      --info-color: #36b9cc;
    }

    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: var(--light-color);
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
      color: var(--primary-color);
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
      border: 1px solid var(--secondary-color);
      padding: 10px;
      text-align: left;
    }

    table th {
      background-color: var(--primary-color);
      color: white;
    }

    table tr:nth-child(even) {
      background-color: var(--light-color);
    }

    table tr:hover {
      background-color: var(--info-color);
      color: white;
    }

    .btn-success {
      background-color: var(--success-color);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-success:hover {
      background-color: var(--dark-color);
    }

    .btn-info {
      background-color: var(--info-color);
      color: white;
      border: none;
      padding: 5px 10px;
      border-radius: 5px;
      cursor: pointer;
    }

    .btn-info:hover {
      background-color: var(--dark-color);
    }

    .form-container {
      display: none;
      margin-top: 20px;
      padding: 20px;
      background-color: var(--light-color);
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .form-container.active {
      display: block;
    }

    .form-container h3 {
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .form-container label {
      display: block;
      margin-bottom: 5px;
      color: var(--dark-color);
    }

    .form-container input {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
      border: 1px solid var(--secondary-color);
      border-radius: 5px;
    }

    .form-container button {
      background-color: var(--success-color);
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
    }

    .form-container button:hover {
      background-color: var(--dark-color);
    }
  </style>
</head>

<body>
  <div class="container">
    <h1>Schedule and Assignments</h1>

    <!-- Rejected Reports Table -->
    <h2>Rejected Reports</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT id, title, description, status FROM reports WHERE status = 'rejected'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['status']}</td>
                        <td>
                          <a href='?approve_id={$row['id']}' class='btn btn-success'>Approve</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No rejected reports found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Approved Reports Table -->
    <h2>Approved Reports</h2>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT id, title, description, status FROM reports WHERE status = 'approved'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['title']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['status']}</td>
                        <td>
                          <button class='btn btn-info' onclick=\"scheduleFix({$row['id']}, '{$row['title']}')\">Schedule Fix</button>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No approved reports found.</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <!-- Schedule Fix Form -->
    <div id="scheduleFixForm" class="form-container">
      <h3>Schedule Fix</h3>
      <form method="POST" action="">
        <input type="hidden" name="report_id" id="report_id">
        <label for="task_name">Task Name:</label>
        <input type="text" name="task_name" id="task_name" required>
        <label for="assigned_to">Assigned To:</label>
        <input type="text" name="assigned_to" required>
        <label for="deadline">Deadline:</label>
        <input type="date" name="deadline" required>
        <button type="submit" name="schedule_fix" class="btn btn-success">Schedule</button>
      </form>
    </div>
  </div>

  <script>
    function scheduleFix(reportId, title) {
      document.getElementById('scheduleFixForm').classList.add('active');
      document.getElementById('report_id').value = reportId;
      document.getElementById('task_name').value = title + " Fix";
    }
  </script>
</body>

</html>