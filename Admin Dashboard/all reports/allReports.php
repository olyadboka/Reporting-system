<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Reports</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../dashboardHome.css">
<<<<<<< HEAD
  <link rel="stylesheet" href="./allReports.css">
  <style>
  /* Responsive table styles */
  :root {
    --primary-color: #4a6fa5;
    --secondary-color: #166088;
    --accent-color: #4fc3f7;
    --light-color: #f8f9fa;
    --dark-color: #343a40;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --danger-color: #dc3545;
  }


  .sidebar {
    width: 250px;
    background-color: var(--primary-color) !important;
    color: white;
    padding: 20px 0;
    height: 100vh;
    position: fixed;
    transition: all 0.3s;
  }

  .table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }

  .filter-dropdown {
    min-width: 200px;
  }

  .filter-container {
    position: relative;
    display: inline-block;
  }

  .filter-options {
    display: none;
    position: absolute;
    background-color: white;
    min-width: 220px;
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
    padding: 1rem;
    border-radius: 0.5rem;
    right: 0;
  }

  .filter-options.show {
    display: block;
  }

  .filter-apply-btn {
    margin-top: 0.5rem;
  }

  @media (max-width: 992px) {

    .description-cell {
      max-width: 200px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
  }

  /* Modal styles */
  .report-modal .modal-dialog {
    max-width: 800px;
  }

  .report-details {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
  }

  .detail-group {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
  }

  .detail-group h6 {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
    color: #495057;
  }

  .report-image {
    max-height: 200px;
    object-fit: contain;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .report-image:hover {
    transform: scale(1.05);
  }

  .image-modal .modal-dialog {
    max-width: 90%;
    max-height: 90vh;
  }

  .image-modal img {
    max-height: 80vh;
    width: auto;
    margin: 0 auto;
    display: block;
  }

  /* Status badge colors */
  .status-pending {
    background-color: #ffc107;
    color: #000;
  }

  .status-in-progress {
    background-color: #17a2b8;
    color: #fff;
  }

  .status-solved {
    background-color: #28a745;
    color: #fff;
  }

  .status-rejected {
    background-color: #dc3545;
    color: #fff;
  }

  /* Priority badge colors */
  .priority-low {
    background-color: #6c757d;
    color: #fff;
  }

  .priority-medium {
    background-color: #fd7e14;
    color: #000;
  }

  .priority-high {
    background-color: #dc3545;
    color: #fff;
  }

  /* Timeline for status history */
  .timeline {
    position: relative;
    padding-left: 1.5rem;
  }

  .timeline:before {
    content: '';
    position: absolute;
    left: 7px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
  }

  .timeline-item {
    position: relative;
    padding-bottom: 1rem;
  }

  .timeline-item:last-child {
    padding-bottom: 0;
  }

  .timeline-dot {
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    /* background: #0d6efd; */
  }

  .timeline-date {
    font-size: 0.8rem;
    color: #6c757d;
  }

  .timeline-content {
    font-size: 0.9rem;
  }


  .main-content {
    margin-left: 5rem;
  }

  .profile-bar .profile-info {
    margin-left: 10rem;
  }
  </style>
</head>

<body>
  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="sidebar-title"><i class="fas fa-cogs"></i> Admin Panel</h2>
    <nav class="menu-container">
      <ul>
        <li><a href="../dashboardHome.php"><i class="fas fa-home"></i> <span>Dashboard</span></a></li>
        <li><a href="#"><i class="fas fa-folder"></i> <span>All Reports</span></a></li>
        <li><a href="../ResidentsManagement/residentsManagent.php"><i class="fas fa-users"></i>
            <span>Residents</span></a></li>

        <li><a href="../ScheduleAndAssignments/scheduleAndAssignments.php"><i class="fas fa-calendar-alt"></i>
            <span>Schedule</span></a></li>
        <li><a href="../../Hermata home/index.php"><i class="fas fa-user-shield"></i> <span>Login as Resident</span></a>
        </li>


        <li><a href="../ActivityLogs/activityLogs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a>
        </li>

        <li><a href="../../login/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
      </ul>
    </nav>
  </aside>
=======
  <link rel="stylesheet" href="allReports.css">
</head>

<body>
  <?php include "../commonAdmin.php"; ?>
  <?php include "../../reportDB/dbconnection.php"; ?>
>>>>>>> caa0e440608bf04a06fe2b50e8bb4420363ba971

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