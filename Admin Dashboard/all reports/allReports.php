<?php
session_start();
require_once '../dbConnection.php';
$kebele_id = $_SESSION['kebele_id'] ?? '';

// Get filter parameters from URL if they exist
$categoryFilter = $_GET['category'] ?? '';
$priorityFilter = $_GET['priority'] ?? '';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../dashboardHome.css">
  <link rel="stylesheet" href="./allReports.css">
  <style>
  /* Responsive table styles */
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
    background: #0d6efd;
  }

  .timeline-date {
    font-size: 0.8rem;
    color: #6c757d;
  }

  .timeline-content {
    font-size: 0.9rem;
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
        <li><a href="./ManageCatagories/manageCategories.php"><i class="fas fa-tags"></i> <span>Categories</span></a>
        </li>
        <li><a href="../ScheduleAndAssignments/scheduleAndAssignments.php"><i class="fas fa-calendar-alt"></i>
            <span>Schedule</span></a></li>
        <li><a href="../Hermata home/index.php"><i class="fas fa-user-shield"></i> <span>Login as Resident</span></a>
        </li>
        <li><a href="../ReportsAndAnalytics/reportsAndAnalytics.php"><i class="fas fa-chart-bar"></i>
            <span>Analytics</span></a></li>
        <li><a href="../Notification/notification.php"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
        <li><a href="../ActivityLogs/activityLogs.php"><i class="fas fa-history"></i> <span>Activity Logs</span></a>
        </li>
        <li><a href="../SystemSettings/systemSetting.php"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
        <li><a href="../../login/logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Profile Bar -->
    <header class="profile-bar">
      <div class="profile-info">
        <?php
        $imageData = '';
        if (!empty($kebele_id)) {
            $stmt = mysqli_prepare($con, "SELECT photo FROM residents WHERE residence_id = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $kebele_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                if ($row = mysqli_fetch_assoc($result)) {
                    $imageData = base64_encode($row['photo']);
                }
                mysqli_stmt_close($stmt);
            }
        }

        if ($imageData) {
            echo '<a href="../editProfile/editProfile.php">';
            echo '<img src="data:image/jpeg;base64,' . $imageData . '" alt="Profile" style="width: 80px;height: 70px;border-radius:50%; object-fit:cover;">';
            echo '</a>';
        } else {
            echo '<img src="./images/default-profile.png" alt="Profile" style="width: 80px;height: 70px;border-radius:50%; object-fit:cover;">';
        }
        ?>

        <div>
          <h4 class="admin-name"><?php echo htmlspecialchars($_SESSION["username"] ?? 'Admin'); ?></h4>
          <p class="admin-role"><?php echo htmlspecialchars($_SESSION["role"] ?? 'Administrator'); ?></p>
        </div>
      </div>
      <p style="color:gray; font-size: 1rem;">HERMATA MENTINA RMS</p>
    </header>


    <!-- Main Content -->
    <div class="main-content">


      <!-- Main Section -->
      <!-- Reports Section -->
      <section class="dashboard-content">
        <div class="container-fluid py-3">
          <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-center">
              <h4 class="m-0"><i class="fas fa-clipboard-list me-2"></i>All Reports</h4>
              <div class="mt-2 mt-md-0">
                <div class="filter-container">
                  <button class="btn btn-sm btn-outline-secondary filter-btn"><i class="fas fa-filter me-1"></i>
                    Filter</button>
                  <div class="filter-options" id="filterOptions">
                    <div class="mb-2">
                      <label class="form-label">Category</label>
                      <select class="form-select form-select-sm filter-dropdown" id="categoryFilter">
                        <option value="">All Categories</option>
                        <?php
                    // Get distinct categories from database
                    $categorySql = "SELECT DISTINCT category FROM reports ORDER BY category";
                    $categoryResult = mysqli_query($con, $categorySql);
                    while($cat = mysqli_fetch_assoc($categoryResult)) {
                      $selected = $cat['category'] == $categoryFilter ? 'selected' : '';
                      echo '<option value="'.htmlspecialchars($cat['category']).'" '.$selected.'>'.htmlspecialchars($cat['category']).'</option>';
                    }
                    ?>
                      </select>
                    </div>
                    <div class="mb-2">
                      <label class="form-label">Priority</label>
                      <select class="form-select form-select-sm filter-dropdown" id="priorityFilter">
                        <option value="">All Priorities</option>
                        <option value="Low" <?= $priorityFilter == 'Low' ? 'selected' : '' ?>>Low</option>
                        <option value="Medium" <?= $priorityFilter == 'Medium' ? 'selected' : '' ?>>Medium</option>
                        <option value="High" <?= $priorityFilter == 'High' ? 'selected' : '' ?>>High</option>
                      </select>
                    </div>
                    <button class="btn btn-sm btn-primary filter-apply-btn w-100" id="applyFilters">Apply
                      Filters</button>
                    <?php if($categoryFilter || $priorityFilter): ?>
                    <button class="btn btn-sm btn-outline-danger mt-2 w-100" id="clearFilters">Clear Filters</button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="table-responsive">
              <?php
          // Build the SQL query with filters
          $sql = "SELECT * FROM reports WHERE 1=1";
          $params = [];
          $types = '';
          
          if(!empty($categoryFilter)) {
            $sql .= " AND category = ?";
            $params[] = $categoryFilter;
            $types .= 's';
          }
          
          if(!empty($priorityFilter)) {
            $sql .= " AND priority = ?";
            $params[] = $priorityFilter;
            $types .= 's';
          }
          
          $sql .= " ORDER BY created_at DESC";
          
          // Prepare and execute the query
          $stmt = mysqli_prepare($con, $sql);
          if(!empty($params)) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
          }
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          
          if(mysqli_num_rows($result) > 0) {
          ?>
              <table class="table table-hover mb-0">
                <thead class="table-light">
                  <tr>
                    <th>#ID</th>
                    <th>User</th>
                    <th>Category</th>
                    <th class="description-cell">Description</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Created</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
              while($row = mysqli_fetch_assoc($result)) {
                echo "<tr data-report-id='".htmlspecialchars($row['report_id'])."'>";
                echo "<td><span class='fw-bold'>#".htmlspecialchars($row['report_id'])."</span></td>";
                echo "<td><span class='badge bg-secondary'>".htmlspecialchars($row['user_id'])."</span></td>";
                echo "<td><span class='badge bg-info text-dark'>".htmlspecialchars($row['category'])."</span></td>";
                echo "<td class='description-cell'>".nl2br(htmlspecialchars(substr($row['description'], 0, 50)))."...</td>";
                echo "<td><i class='fas fa-map-marker-alt text-danger me-1'></i>".htmlspecialchars($row['location'])."</td>";
                echo "<td><span class='badge status-".strtolower(str_replace(' ', '-', $row['status']))."'>".htmlspecialchars($row['status'])."</span></td>";
                echo "<td><span class='badge priority-".strtolower($row['priority'])."'>".htmlspecialchars($row['priority'])."</span></td>";
                echo "<td><small class='text-muted'>".date('M d, Y', strtotime($row['created_at']))."</small></td>";
                echo "<td>
                        <button class='btn btn-sm btn-outline-primary view-btn' title='View' data-bs-toggle='modal' data-bs-target='#reportModal' data-id='".htmlspecialchars($row['report_id'])."'>
                          <i class='fas fa-eye'></i>
                        </button>
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
                    <h5 class="text-muted">No reports found</h5>';
            if($categoryFilter || $priorityFilter) {
              echo '<p class="text-muted">No reports match the selected filters</p>';
            } else {
              echo '<p class="text-muted">There are currently no reports in the system</p>';
            }
            echo '<a href="../../Report/reportForm.php" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Create New Report</a>
                  </div>';
          }
          ?>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Report Details Modal -->
    <div class="modal fade report-modal" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportModalLabel">Report Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" id="reportDetails">
            <!-- Content will be loaded via AJAX -->
            <div class="text-center py-5">
              <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Image Preview Modal -->
    <div class="modal fade image-modal" id="imageModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Image Preview</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img id="modalImage" src="" alt="Report Image" class="img-fluid">
          </div>
        </div>
      </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {

      $('.view-btn').click(function() {
        const reportId = $(this).data('id');
        $('#reportModalLabel').text('Report Details #' + reportId);

        $.ajax({
          url: 'get_report_details.php',
          type: 'GET',
          data: {
            id: reportId
          },
          beforeSend: function() {
            $('#reportDetails').html(`
              <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                  <span class="visually-hidden">Loading...</span>
                </div>
              </div>
            `);
          },
          success: function(response) {
            $('#reportDetails').html(response);

            $('.report-image').click(function() {
              $('#modalImage').attr('src', $(this).attr('src'));
              var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
              imageModal.show();
            });
          },
          error: function() {
            $('#reportDetails').html(`
              <div class="alert alert-danger">
                Failed to load report details. Please try again.
              </div>
            `);
          }
        });
      });

      // Make table rows clickable
      $('tbody tr').click(function(e) {
        // Don't trigger if clicking on action buttons
        if (!$(e.target).closest('.view-btn, .btn').length) {
          $(this).find('.view-btn').trigger('click');
        }
      });
    });
    $(document).ready(function() {
      // Toggle filter dropdown - only this handler should toggle
      $('.filter-btn').click(function(e) {
        e.stopPropagation();
        $('#filterOptions').toggleClass('show');
      });

      // Prevent dropdown from closing when clicking inside it
      $('#filterOptions').click(function(e) {
        e.stopPropagation();
      });

      // Close filter dropdown when clicking outside
      $(document).click(function() {
        $('#filterOptions').removeClass('show');
      });

      // Apply filters
      $('#applyFilters').click(function() {
        const category = $('#categoryFilter').val();
        const priority = $('#priorityFilter').val();

        // Build URL with filters
        let url = 'allReports.php?';
        if (category) url += `category=${encodeURIComponent(category)}&`;
        if (priority) url += `priority=${encodeURIComponent(priority)}`;

        // Remove trailing & if no priority filter
        if (url.endsWith('&')) url = url.slice(0, -1);

        window.location.href = url;
      });

      // Clear filters
      $('#clearFilters').click(function() {
        window.location.href = 'allReports.php';
      });

      // View report details
      $('.view-btn').click(function() {
        const reportId = $(this).data('id');
        $('#reportModalLabel').text('Report Details #' + reportId);

        $.ajax({
          url: 'get_report_details.php',
          type: 'GET',
          data: {
            id: reportId
          },
          beforeSend: function() {
            $('#reportDetails').html(`
          <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </div>
        `);
          },
          success: function(response) {
            $('#reportDetails').html(response);

            $('.report-image').click(function() {
              $('#modalImage').attr('src', $(this).attr('src'));
              var imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
              imageModal.show();
            });
          },
          error: function() {
            $('#reportDetails').html(`
          <div class="alert alert-danger">
            Failed to load report details. Please try again.
          </div>
        `);
          }
        });
      });

      // Make table rows clickable
      $('tbody tr').click(function(e) {
        if (!$(e.target).closest('.view-btn, .btn').length) {
          $(this).find('.view-btn').trigger('click');
        }
      });
    });
    </script>
</body>

</html>