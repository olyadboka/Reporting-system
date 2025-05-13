<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Reports</title>
  <link rel="stylesheet" href="../dashboardHome.css" />
  <link rel="stylesheet" href="allReports.css">
</head>

<body>
  <?php include "../commonAdmin.php"; ?>
  <?php include "../../reportDB/dbconnection.php"; ?>

  <div class="container">
    <h1>All Reports</h1>

    <?php
    $sql = "SELECT * FROM reports";
    $result = mysqli_query($con, $sql);
    
    if(mysqli_num_rows($result) > 0) {
    ?>
    <table>
      <thead>
        <tr>
          <th>Report ID</th>
          <th>User ID</th>
          <th>Category</th>
          <th>Description</th>
          <th>Location</th>
          <th>Status</th>
          <th>Priority</th>
          <th>Created At</th>
          <th>Count</th>
          <th>Considered</th>
        </tr>
      </thead>
      <tbody>
        <?php
          while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".htmlspecialchars($row['report_id'])."</td>";
            echo "<td>".htmlspecialchars($row['user_id'])."</td>";
            echo "<td>".htmlspecialchars($row['category'])."</td>";
            echo "<td>".htmlspecialchars($row['description'])."</td>";
            echo "<td>".htmlspecialchars($row['location'])."</td>";
            echo "<td>".htmlspecialchars($row['status'])."</td>";
            echo "<td>".htmlspecialchars($row['priority'])."</td>";
            echo "<td>".htmlspecialchars($row['created_at'])."</td>";
            echo "<td>".htmlspecialchars($row['count'])."</td>";
            echo "<td>".($row['is_considered'] ? 'Yes' : 'No')."</td>";
            echo "</tr>";
          }
          ?>
      </tbody>
    </table>
    <?php
    } else {
      echo "<p>No reports found.</p>";
    }
    ?>
  </div>

</body>

</html>