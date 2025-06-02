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
$sql = "SELECT full_name FROM staff WHERE staff_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $staff_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $staff_name = $row['full_name'];
} else {
    $staff_name = 'Unknown';
}

$stmt->close();

// Fetch reports from the database
$sql = "SELECT report_id, user_id, category, description, location, image_url, status, priority, created_at, handled_by FROM reports";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Staff Page</title>
  <link rel="stylesheet" href="staffCSS/staff.css">
  <style>

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
      <form action="logout.php" method="post" style="margin:0;">
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </header>

    <!-- Main Section -->
    <section class="dashboard-content">

      <main>
        <div class="tabs">
          <button class="tab-button" onclick="showTab('new-requests')">New Reports</button>
          <button class="tab-button" onclick="showTab('accepted-reports')">Accepted Reports</button>
          <button class="tab-button" onclick="showTab('rejected-reports')">Rejected Reports</button>
        </div>

        <!-- New Requests Tab -->
        <div id="new-requests" class="tab-content">
          <?php if ($result->num_rows > 0): ?>
          <?php while ($row = $result->fetch_assoc()): ?>
          <?php if ($row['status'] === 'new'): ?>
          <div class="report-card" data-report-id="<?php echo $row['report_id']; ?>">
            <h3>Report #<?php echo $row['report_id']; ?></h3>
            <p><strong>Submitted by:</strong> User ID <?php echo $row['user_id']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
            <p><strong>Priority:</strong> <?php echo ucfirst($row['priority']); ?></p>
            <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>
            <?php if (!empty($row['image_url'])): ?>
            <img src="<?php echo $row['image_url']; ?>" alt="Report Image" style="max-width: 100%; height: auto;">
            <?php endif; ?>
            <div class="actions">
              <button class="accept-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'accept', '<?php echo $staff_name; ?>')">Accept</button>
              <button class="reject-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'reject', '<?php echo $staff_name; ?>')">Reject</button>
            </div>
          </div>
          <?php endif; ?>
          <?php endwhile; ?>
          <?php else: ?>
          <p>No reports found.</p>
          <?php endif; ?>
        </div>

        <!-- Accepted Reports Tab -->
        <div id="accepted-reports" class="tab-content" style="display: none;">
          <h2>Accepted Reports</h2>
          <?php
                $result->data_seek(0); // Reset the result pointer
                while ($row = $result->fetch_assoc()):
                    if ($row['status'] === 'Accepted'): ?>
          <div class="report-card" data-report-id="<?php echo $row['report_id']; ?>">
            <h3>Report #<?php echo $row['report_id']; ?></h3>
            <p><strong>Submitted by:</strong> User ID <?php echo $row['user_id']; ?></p>
            <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
            <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
            <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
            <p><strong>Priority:</strong> <?php echo ucfirst($row['priority']); ?></p>
            <p><strong>Created At:</strong> <?php echo $row['created_at']; ?></p>
            <p><strong>Accepted By:</strong> <?php echo $row['handled_by']; ?></p>
            <?php if (!empty($row['image_url'])): ?>
            <img src="<?php echo $row['image_url']; ?>" alt="Report Image" style="max-width: 100%; height: auto;">
            <?php endif; ?>
            <div class="actions">
              <button class="reject-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'reject', '<?php echo $staff_name; ?>')">Reject</button>
            </div>
          </div>
          <?php endif;
                endwhile; ?>
        </div>

        <!-- Rejected Reports Tab -->
        <div id="rejected-reports" class="tab-content" style="display: none;">
          <h2>Rejected Reports</h2>
          <?php
                $result->data_seek(0); // Reset the result pointer
                while ($row = $result->fetch_assoc()):
                    if ($row['status'] === 'Rejected'): ?>
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
            <?php if (!empty($row['image_url'])): ?>
            <img src="<?php echo $row['image_url']; ?>" alt="Report Image" style="max-width: 100%; height: auto;">
            <?php endif; ?>
            <div class="actions">
              <button class="accept-btn"
                onclick="handleAction(<?php echo $row['report_id']; ?>, 'accept', '<?php echo $staff_name; ?>')">Accept</button>
            </div>
          </div>
          <?php endif;
                endwhile; ?>
        </div>
      </main>
    </section>
  </div>

  <script>
  function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
    document.getElementById(tabId).style.display = 'block';
  }

  function handleAction(reportId, action, staffName) {
    const reportCard = document.querySelector(`.report-card[data-report-id="${reportId}"]`);
    const reportClone = reportCard.cloneNode(true);
    reportCard.remove();

    if (action === 'accept') {
      reportClone.querySelector('.actions').remove();
      reportClone.innerHTML += `<p><strong>Accepted By:</strong> ${staffName}</p>`;
      document.getElementById('accepted-reports').appendChild(reportClone);
    } else if (action === 'reject') {
      reportClone.querySelector('.actions').remove();
      reportClone.innerHTML += `<p><strong>Rejected By:</strong> ${staffName}</p>`;
      document.getElementById('rejected-reports').appendChild(reportClone);
    }

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
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          console.log('Report updated successfully');
        } else {
          console.error('Failed to update report:', data.error);
        }
      })
      .catch(error => console.error('Error:', error));
  }
  </script>
</body>

</html>