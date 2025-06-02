<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Reports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../dashboardHome.css">
  <link rel="stylesheet" href="allReports.css">
</head>

<body>
  <?php include "../commonAdmin.php"; ?>
  <?php include "../../reportDB/dbconnection.php"; ?>

  <div class="container py-5">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="m-0"><i class="fas fa-clipboard-list me-2"></i>All Reports</h4>
        <div>
          <button class="btn btn-sm btn-light"><i class="fas fa-download me-1"></i> Export</button>
          <button class="btn btn-sm btn-light"><i class="fas fa-filter me-1"></i> Filter</button>
        </div>
      </div>

      <div class="table-responsive">
        <?php
        $sql = "SELECT * FROM reports";
        $result = mysqli_query($con, $sql);
        
        if(mysqli_num_rows($result) > 0) {
        ?>
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th>#ID</th>
              <th>User</th>
              <th>Category</th>
              <th class="description-cell">Description</th>
              <th>Location</th>
              <th>Status</th>
              <th>Priority</th>
              <th>Created</th>
              <th>Count</th>
              <th>Considered</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            while($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td><span class='fw-bold'>#".htmlspecialchars($row['report_id'])."</span></td>";
              echo "<td><span class='badge bg-secondary'>".htmlspecialchars($row['user_id'])."</span></td>";
              echo "<td><span class='badge bg-info text-dark'>".htmlspecialchars($row['category'])."</span></td>";
              echo "<td class='description-cell'>".nl2br(htmlspecialchars($row['description']))."</td>";
              echo "<td><i class='fas fa-map-marker-alt text-danger me-1'></i>".htmlspecialchars($row['location'])."</td>";
              echo "<td><span class='badge badge-pill status- allStatus".strtolower(str_replace(' ', '-', $row['status']))."'>".htmlspecialchars($row['status'])."</span></td>";
              echo "<td><span class='badge badge-pill priority-".strtolower($row['priority'])."'>".htmlspecialchars($row['priority'])."</span></td>";
              echo "<td><small class='text-muted'>".htmlspecialchars($row['created_at'])."</small></td>";
              echo "<td><span class='badge bg-dark rounded-circle'>".htmlspecialchars($row['count'])."</span></td>";
              echo "<td>".($row['is_considered'] ? '<span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>' : '<span class="badge bg-secondary"><i class="fas fa-times"></i> No</span>')."</td>";
              echo "<td>
                      <button class='btn btn-sm btn-outline-primary action-btn' title='View'><i class='fas fa-eye'></i></button>
                      <button class='btn btn-sm btn-outline-warning action-btn' title='Edit'><i class='fas fa-edit'></i></button>
                    </td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
        <?php
        } else {
          echo '<div class="p-4 text-center">
                  <i class="fas fa-exclamation-circle fa-3x text-muted mb-3"></i>
                  <h5 class="text-muted">No reports found</h5>
                  <p class="text-muted">There are currently no reports in the system</p>
                  <button class="btn btn-primary"><i class="fas fa-plus me-1"></i> Create New Report</button>
                </div>';
        }
        ?>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Font Awesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>