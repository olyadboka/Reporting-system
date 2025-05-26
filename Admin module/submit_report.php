<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>submit</title><link rel="stylesheet" href="submit.css">
</head>
<body>
<?php
    include 'admin_dashboard.php'
    
    ?>
<section id="submit-report">
        <h2>Submit Report</h2>
        <form id="report-form" action="submit_report.php" method="POST">
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" required />

          <label for="description">Description:</label>
          <textarea id="description" name="description" required></textarea>

          <label for="date">Date:</label>
          <input type="date" id="date" name="date" required />

          <button type="submit">Submit Report</button>
        </form>
        <div id="form-message"></div>
      </section>
      
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];

    
    $conn = new mysqli("localhost", "root", "", "report_management");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO reports (title, description, date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $title, $description, $date);

    if ($stmt->execute()) {
        echo "Report submitted successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>



    <script src="report.js"></script>
</body>
</html>





