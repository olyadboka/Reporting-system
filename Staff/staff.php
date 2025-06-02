<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hmreportsystem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff') {
    header("Location: ../login/login.php");
    exit();
}

// Retrieve staff name using staff_id from session
$staff_id = $_SESSION['user_id'];
$sql = "SELECT fname FROM residents WHERE residence_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $staff_name = $row['fname'];
} else {
    $staff_name = 'Unknown';
}
$stmt->close();

// Fetch all reports into an array for multiple passes
$sql = "SELECT report_id, user_id, category, description, location, image_url_1, image_url_2, image_url_3, image_url_4, status, priority, created_at, handled_by FROM reports";
$result = $conn->query($sql);
$reports = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Page</title>
  <link rel="stylesheet" href="staffCSS/staff.css">
</head>

<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2 class="sidebar-title">🛠 Staff Panel</h2>
    <nav class="menu-container">
      <ul>
        <li><a href="./Registration/register.php">📝 Registration</a></li>
        <li><a href="#" class="active">🗂️ Reports List</a></li>
        <li><a href="../Hermata home/index.php">👩‍💻 Login as Resident</a></li>
      </ul>
    </nav>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Profile Bar -->
    <header class="profile-bar">
      <div class="profile-info">
        <img src="../Admin Dashboard/images/olyad2.png" alt="Staff Profile" class="profile-pic">
        <div>
          <h4 class="admin-name"><?php echo htmlspecialchars($staff_name); ?></h4>
          <p class="admin-role">Staff</p>
        </div>
      </div>
      <form action="logout.php" method="post" style="margin:0;">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </header>

    <!-- Main Section -->
    <section class="dashboard-content">

      <main>
        <div class="tabs">
          <button class="tab-button" onclick="showTab('new-requests')">New Reports</button>
          <button class="tab-button" onclick="showTab('approved-reports')">Approved Reports</button>
          <button class="tab-button" onclick="showTab('rejected-reports')">Rejected Reports</button>
          <button class="tab-button" onclick="showTab('resolved-reports')">Resolved Reports</button>
        </div>

        <!-- New Requests Tab -->
        <div id="new-requests" class="tab-content">
          <?php
          $found = false;
          foreach ($reports as $row):
            if ($row['status'] === 'pending'):
              $found = true;
          ?>
          <div class="report-card" data-report-id="<?php echo $row['report_id']; ?>">
            <h3>Report #<?php echo $row['report_id']; ?></h3>
            <p><strong>Submitted by:</strong> User ID <?php echo $row['user_id']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
            <p><strong>Priority:</strong> <?php echo ucfirst($row['priority']); ?></p>
            <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>
            <?php if (!empty($row['image_url_1'])): ?>
            <img src="<?php echo $row['image_url_1']; ?>" alt="Report Image" style="max-width: 100%; height: auto;">
            <?php endif; ?>
            <div class="actions">
              <button class="accept-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'approve', '<?php echo $staff_name; ?>')">Approve</button>
              <button class="reject-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'reject', '<?php echo $staff_name; ?>')">Reject</button>
            </div>
          </div>
          <?php
            endif;
          endforeach;
          if (!$found) echo "<p>No reports found.</p>";
          ?>
        </div>

        <!-- Approved Reports Tab -->
        <div id="approved-reports" class="tab-content" style="display: none;">
          <h2>Approved Reports</h2>
          <?php
          $found = false;
          foreach ($reports as $row):
            if ($row['status'] === 'approved'):
              $found = true;
          ?>
          <div class="report-card" data-report-id="<?php echo $row['report_id']; ?>">
            <h3>Report #<?php echo $row['report_id']; ?></h3>
            <p><strong>Submitted by:</strong> User ID <?php echo $row['user_id']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
            <p><strong>Priority:</strong> <?php echo ucfirst($row['priority']); ?></p>
            <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>
            <p><strong>Approved By:</strong> <?php echo $row['handled_by']; ?></p>
            <?php if (!empty($row['image_url_1'])): ?>
            <img src="<?php echo $row['image_url_1']; ?>" alt="Report Image" style="max-width: 100%; height: auto;">
            <?php endif; ?>
            <div class="actions">
              <button class="resolve-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'resolve', '<?php echo $staff_name; ?>')">Mark
                as Resolved</button>
              <button class="reject-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'reject', '<?php echo $staff_name; ?>')">Reject</button>
            </div>
          </div>
          <?php
            endif;
          endforeach;
          if (!$found) echo "<p>No approved reports found.</p>";
          ?>
        </div>

        <!-- Rejected Reports Tab -->
        <div id="rejected-reports" class="tab-content" style="display: none;">
          <h2>Rejected Reports</h2>
          <?php
          $found = false;
          foreach ($reports as $row):
            if ($row['status'] === 'rejected'):
              $found = true;
          ?>
          <div class="report-card" data-report-id="<?php echo $row['report_id']; ?>">
            <h3>Report #<?php echo $row['report_id']; ?></h3>
            <p><strong>Submitted by:</strong> User ID <?php echo $row['user_id']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
            <p><strong>Priority:</strong> <?php echo ucfirst($row['priority']); ?></p>
            <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>
            <p><strong>Rejected By:</strong> <?php echo $row['handled_by']; ?></p>
            <?php if (!empty($row['image_url_1'])): ?>
            <img src="<?php echo $row['image_url_1']; ?>" alt="Report Image" style="max-width: 100%; height: auto;">
            <?php endif; ?>
            <div class="actions">
              <button class="accept-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'approve', '<?php echo $staff_name; ?>')">Approve</button>
            </div>
          </div>
          <?php
            endif;
          endforeach;
          if (!$found) echo "<p>No rejected reports found.</p>";
          ?>
        </div>

        <!-- Resolved Reports Tab -->
        <div id="resolved-reports" class="tab-content" style="display: none;">
          <h2>Resolved Reports</h2>
          <?php
          $found = false;
          foreach ($reports as $row):
            if ($row['status'] === 'resolved'):
              $found = true;
          ?>
          <div class="report-card" data-report-id="<?php echo $row['report_id']; ?>">
            <h3>Report #<?php echo $row['report_id']; ?></h3>
            <p><strong>Submitted by:</strong> User ID <?php echo $row['user_id']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
            <p><strong>Priority:</strong> <?php echo ucfirst($row['priority']); ?></p>
            <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>
            <p><strong>Resolved By:</strong> <?php echo $row['handled_by']; ?></p>
            <?php if (!empty($row['image_url_1'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_1']); ?>" alt="Report Image"
              style="max-width: 150px; height: auto;">

            <?php endif; ?>
            <?php if (!empty($row['image_url_2'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_2']); ?>" alt="Report Image"
              style="max-width: 150px; height: auto;" id="imageModal">

            <?php endif; ?>
            <?php if (!empty($row['image_url_3'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_3']); ?>" alt="Report Image"
              style="max-width: 150px; height: auto;">

            <?php endif; ?>
            <?php if (!empty($row['image_url_4'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_4']); ?>" alt="Report Image"
              style="max-width: 150px; height: auto;" id="imageModal">

            <?php endif; ?>
          </div>
          <?php
            endif;
          endforeach;
          if (!$found) echo "<p>No resolved reports found.</p>";
          ?>
        </div>
      </main>
    </section>
  </div>

  <script>
  const modal = document.getElementById("imageModal");
  const modalImg = document.getElementById("modalImage");
  const closeModal = document.querySelector(".close");

  document.querySelectorAll(".clickable-image").forEach(img => {
    img.addEventListener("click", () => {
      modal.style.display = "block";
      modalImg.src = img.src;
    });
  });

  closeModal.addEventListener("click", () => {
    modal.style.display = "none";
  });

  modal.addEventListener("click", () => {
    modal.style.display = "none";
  });

  function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
  }

  function handleAction(reportId, action, staffName) {
    // Send the action to the server to update the database
    fetch('process.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          report_id: reportId,
          action: action,
          staff_name: staffName
        })
      })
      .then(response => responses.json())
      .then(data => {
        if (data.success) {

          location.reload();
        } else {
          alert('Failed to update report: ' + (data.error || 'Unknown error'));
        }
      })
      .catch(error => alert('Error: ' + error));
  }
  </script>
  <script src="./js/modal.js"></script>
</body>

</html>