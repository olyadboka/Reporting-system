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
  <style>
  .action-buttons {
    display: flex;
    gap: 10px;
    margin-top: 15px;
  }

  .approve-btn {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .reject-btn {
    background-color: #f44336;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  .resolve-btn {
    background-color: #2196F3;
    color: white;
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }

  /* Modal styles */
  .modal {
    display: none;
    position: fixed;
    z-index: 1000;
    padding-top: 100px;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.9);
  }

  .modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
  }

  .modal-content {
    animation-name: zoom;
    animation-duration: 0.6s;
  }

  @keyframes zoom {
    from {
      transform: scale(0)
    }

    to {
      transform: scale(1)
    }
  }

  .close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
  }

  .close:hover,
  .close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
  }

  .clickable-image {
    cursor: pointer;
    transition: 0.3s;
    max-width: 150px;
    height: auto;
    margin: 5px;
  }

  .clickable-image:hover {
    opacity: 0.7;
  }
  </style>
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
      <form action="../login/logout.php" method="post" style="margin:0;">
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
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_1']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_2'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_2']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_3'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_3']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_4'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_4']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <div class="action-buttons">
              <form method="post" action="updateStatus.php" style="display: inline;">
                <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="approve-btn">Approve</button>

              </form>
              <form method="post" action="updateStatus.php" style="display: inline;">
                <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="reject-btn">Reject</button>
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
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_1']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_2'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_2']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_3'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_3']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_4'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_4']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <div class="action-buttons">
              <form method="post" action="updateStatus.php" style="display: inline;">
                <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="reject-btn">Reject</button>
              </form>
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
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_1']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_2'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_2']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_3'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_3']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_4'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_4']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <div class="action-buttons">
              <form method="post" action="updateStatus.php" style="display: inline;">
                <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                <input type="hidden" name="status" value="approved">
                <button type="submit" class="approve-btn">Approve</button>
              </form>
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
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_2'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_2']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_3'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_3']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
            <?php endif; ?>
            <?php if (!empty($row['image_url_4'])): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image_url_4']); ?>" alt="Report Image"
              class="clickable-image" onclick="openModal(this)">
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

  <!-- The Modal -->
  <div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
  </div>

  <script>
  // Function to open the modal with the clicked image
  function openModal(img) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    modal.style.display = "block";
    modalImg.src = img.src;
  }

  // Function to close the modal
  function closeModal() {
    document.getElementById("imageModal").style.display = "none";
  }

  // Close the modal when clicking outside of the image
  window.onclick = function(event) {
    const modal = document.getElementById("imageModal");
    if (event.target == modal) {
      modal.style.display = "none";
    }
  }

  function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
  }

  function updateStatus(reportId, status) {
    // You'll need to implement this function to handle the status update
    // This is just a placeholder for the functionality
    console.log(`Updating report ${reportId} to status ${status}`);
    // Typically you would make an AJAX call here to update the status
  }
  </script>
</body>

</html>