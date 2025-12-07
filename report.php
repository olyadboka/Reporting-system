<?php
session_start();
include "./reportDB/dbconnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_report'])) {
        $reportId = intval($_POST['report_id']);
        $cancelReason = mysqli_real_escape_string($con, $_POST['cancel_reason']);
        
        // Update report status to cancelled
        $sql = "UPDATE reports SET is_cancelled = 1, cancel_reason = ? WHERE report_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "si", $cancelReason, $reportId);
        mysqli_stmt_execute($stmt);
        
        echo json_encode(['success' => true]);
        exit;
    } else {
        $reportId = intval($_POST['report_id']);
        $newCount = intval($_POST['new_count']);
        $isConsidered = intval($_POST['is_considered']);

        // Single update query
        $sql = "UPDATE reports SET count = ?, is_considered = ? WHERE report_id = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $newCount, $isConsidered, $reportId);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            echo json_encode(['success' => true, 'new_count' => $newCount]);
        } else {
            echo json_encode(['success' => false, 'error' => mysqli_error($con)]);
        }
        exit;
    }
}

// Fetch all reports
$sqlDis = "SELECT * FROM reports ORDER BY created_at DESC";
$result = mysqli_query($con, $sqlDis);

$reports = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reports[] = $row;
    }
}

$myreport = "SELECT * from reports where user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($con, $myreport);
mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$myreports = [];
if ($result) {
    while ($row = mysqli_fetch_array($result)) {
        $myreports[] = $row;
    }
}

// for checking the status of the report 
$reportStatus = "SELECT status from reports ";
$stmttt = mysqli_prepare($con,$reportStatus);
mysqli_stmt_execute($stmttt);
$statusResult = mysqli_stmt_get_result($stmttt); 
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Report Page</title>
  <link rel="stylesheet" href="./CSS/report.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
  #report-images {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
  }

  #report-images div {
    width: 48%;
  }

  #report-images img {
    width: 100%;
    height: auto;
    border: 1px solid #ccc;
    border-radius: 8px;
  }

  .cancel-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    justify-content: center;
    align-items: center;
  }

  .cancel-modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 400px;
  }

  .cancel-modal textarea {
    width: 100%;
    margin-bottom: 10px;
  }

  .cancel-modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
  }

  .my-report {
    display: none;
  }

  .status-button {
    min-width: 120px;
  }

  .btn-success {
    background-color: #28a745;
  }

  .btn-warning {
    background-color: #ffc107;
    color: #212529;
  }
  </style>
</head>

<body>
  <div>
    <?php include './common/header.php'; ?>
  </div>

  <div class="header-top">
    <div class="header--text">
      <h1>Welcome to Report Page</h1>
      <a href="./Report/reportForm.php" class="report-now">Report Now</a>
    </div>
  </div>

  <div class="container">
    <div class="main">
      <ul class="report-links">
        <li><a href="#" class="all-reports">Reports</a></li>
        <li><a href="#" class="most-viewed">Most Viewed</a></li>
        <li><a href="#" class="answered">Answered</a></li>
        <li><a href="#" class="my-reports">My Reports</a></li>
      </ul>

      <div class="main--post" id="main--post">
        <?php foreach ($reports as $report) : ?>
        <div class="post-box reveal" data-visible="true"
          data-user-id="<?php echo htmlspecialchars($report['user_id']); ?>">
          <div class="post-box--report-type">
            <h1>Type: <?php echo htmlspecialchars($report['category']); ?></h1>
            <p>Reported date: <?php echo htmlspecialchars($report['created_at']); ?></p>
          </div>
          <div class="post-box--report-description">
            <p>Description: <?php echo htmlspecialchars($report['description']); ?></p>
          </div>
          <div class="hidden-details" id="report-images" style="display: none;">
            <?php if (!empty($report['image_url_1'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($report['image_url_1']) ?>" alt="Report Image"
              class="zoomable-image">
            <?php endif; ?>
            <?php if (!empty($report['image_url_2'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($report['image_url_2']) ?>" alt="Report Image"
              class="zoomable-image">
            <?php endif; ?>
            <?php if (!empty($report['image_url_3'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($report['image_url_3']) ?>" alt="Report Image"
              class="zoomable-image">
            <?php endif; ?>
            <?php if (!empty($report['image_url_4'])): ?>
            <img src="data:image/jpeg;base64,<?= base64_encode($report['image_url_4']) ?>" alt="Report Image"
              class="zoomable-image">
            <?php endif; ?>
          </div>

          <div class="post-box--buttons">
            <button class="btn1 btn btn-primary" name="more" id="more">More</button>
            <?php if($report['user_id'] == $_SESSION['user_id']): ?>
            <?php if($report['status'] == 'pending'): ?>
            <button class="btn1 btn btn-danger cancel-report-btn" name="cancel_report"
              data-report-id="<?php echo htmlspecialchars($report['report_id']); ?>">
              Cancel Report
            </button>
            <?php else: ?>
            <button
              class="btn1 btn <?php echo $report['status'] == 'approved' ? 'btn-success' : 'btn-warning'; ?> status-button"
              disabled>
              <i class="fas <?php echo $report['status'] == 'approved' ? 'fa-check-circle' : 'fa-check-double'; ?>"></i>
              <?php echo ucfirst($report['status']); ?>
            </button>
            <?php endif; ?>
            <?php else: ?>
            <button class="btn1 btn 
                <?php 
                switch($report['status']) {
                  case 'approved':
                    echo 'btn-success';
                    break;
                  case 'solved':
                    echo 'btn-warning';
                    break;
                  case 'pending':
                    echo $report['is_considered'] ? 'btn-secondary' : 'btn-danger';
                    break;
                  default:
                    echo 'btn-primary';
                }
                ?> 
                status-button" name="consider" data-report-id="<?php echo htmlspecialchars($report['report_id']); ?>"
              data-status="<?php echo htmlspecialchars($report['status']); ?>"
              <?php if($report['status'] == 'approved' || $report['status'] == 'resolved'): ?> disabled <?php endif; ?>>
              <?php
                switch($report['status']) {
                  case 'approved':
                    echo '<i class="fas fa-check-circle"></i> Approved';
                    break;
                  case 'resolved':
                    echo '<i class="fas fa-check-double"></i> Solved';
                    break;
                  case 'pending':
                    echo $report['is_considered'] ? '<i class="fas fa-hourglass-half"></i> Considered' : '<i class="fas fa-exclamation-circle"></i> Consider';
                    break;
                  default:
                    echo 'Consider';
                }
                ?>
            </button>
            <?php endif; ?>
          </div>
          <div class="post-box--top-right">
            <p class="btn btn-danger" name="count">
              <?php
              
              echo htmlspecialchars($report['count']); 
              
              
              ?></p>
            <p class="btn btn-warning" name="priority">Priority: <?php echo htmlspecialchars($report['priority']); ?>
            </p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="pagination" id="pagination">
        <button class="page-btn btn-primary btn" id="prevBtn">Previous</button>
        <span id="pageInfo" class="mx-2">Page 1 of <?php echo ceil(count($reports)/4); ?></span>
        <button class="page-btn btn-primary btn ms-2" id="nextBtn">Next</button>
      </div>
    </div>

    <div class="right-topic">
      <div class="report-now--right">
        <div class="card" style="width: 18rem;">
          <img src="..." class="card-img-top" alt="...">
          <div class="card-body">
            <h5 class="card-title">Kebele Reporting System</h5>
            <p class="card-text">Easily report community issues and concerns to local authorities for quick resolution.
            </p>
            <a href="./Report/reportForm.php" class="btn btn-primary">Submit Report</a>
          </div>
        </div>
      </div>
      <h1>Related Topic</h1>
      <div class="topics--">
        <div class="accordion accordion-flush" id="accordionFlushExample">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                Housing & Rental Issues
              </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                <!-- ---------- -->
                <div class="accordion" id="accordionExample">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        House Maintenance Problems
                      </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Leaking roofs, broken doors/windows, plumbing issues, electrical problems, etc.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Rent Payment Issues
                      </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Overcharging, unclear payment methods, or missing receipts.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Unauthorized Evictions
                      </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Complaints about forced eviction without proper notice or process.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- ------------------------ -->
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                Community & Infrastructure Issues
              </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                <!-- ---------- -->
                <div class="accordion" id="accordionExample">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Water & Sanitation Problems

                      </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Water shortages, broken pipes, sewer overflows, or lack of toilets.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Road & Pathway Damage
                      </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Potholes, damaged sidewalks, or blocked roads.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Electricity Problems
                      </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Power outages, exposed electrical wires, or faulty street lights.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Garbage Collection Issues
                      </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Delayed waste pickup, illegal dumping, or lack of bins.
                      </div>
                    </div>
                  </div>
                </div>
                <!-- ------------------------------- -->
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                Security & Law Enforcement Issues
              </button>
            </h2>
            <div id="flush-collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
              <div class="accordion-body">
                <div class="accordion" id="accordionExample">
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Crime & Safety Concerns
                      </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Reporting theft, vandalism, or suspicious activities.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Domestic Violence & Abuse
                      </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        A way to report cases anonymously to authorities.
                      </div>
                    </div>
                  </div>
                  <div class="accordion-item">
                    <h2 class="accordion-header">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Illegal Businesses
                      </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                      <div class="accordion-body">
                        Unlicensed businesses operating in kebele houses.
                      </div>
                    </div>
                  </div>

                </div>
                <!-- ------------------------------- -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="cancel-modal" id="cancelModal">
    <div class="cancel-modal-content">
      <h3>Cancel Report</h3>
      <p>Please provide a reason for cancelling this report:</p>
      <textarea id="cancelReason" rows="4" required></textarea>
      <div class="cancel-modal-buttons">
        <button id="cancelCancelBtn" class="btn btn-secondary">Back</button>
        <button id="confirmCancelBtn" class="btn btn-danger">Confirm Cancel</button>
      </div>
    </div>
  </div>
  <section class="footer">
    <?php include './common/footer.php'; ?>
  </section>
  <script>
  document.addEventListener('DOMContentLoaded', function() {

    const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;

    // Navigation links functionality...........................................................
    const links = document.querySelectorAll(".report-links a");
    const allReportsLink = document.querySelector(".all-reports");
    const mostViewedLink = document.querySelector(".most-viewed");
    const answeredLink = document.querySelector(".answered");
    const myReportsLink = document.querySelector(".my-reports");
    const reportBoxes = document.querySelectorAll(".post-box");


    links.forEach((link) => {
      link.addEventListener("click", function(e) {
        e.preventDefault();
        links.forEach((l) => l.classList.remove("active"));
        this.classList.add("active");
      });
    });

    // Filter reports.................................................
    allReportsLink.addEventListener("click", function(e) {
      e.preventDefault();
      reportBoxes.forEach((box) => {
        box.dataset.visible = "true";
        box.style.display = "block";
      });
      updatePagination();
    });

    mostViewedLink.addEventListener("click", function(e) {
      e.preventDefault();
      const counts = Array.from(reportBoxes).map(box =>
        parseInt(box.querySelector("p[name='count']").textContent)
      );
      const maxCount = Math.max(...counts);
      const threshold = maxCount * 0.7;

      reportBoxes.forEach((box) => {
        const count = parseInt(box.querySelector("p[name='count']").textContent);
        if (count >= threshold) {
          box.dataset.visible = "true";
          box.style.display = "block";
        } else {
          box.dataset.visible = "false";
          box.style.display = "none";
        }
      });
      updatePagination();
    });

    answeredLink.addEventListener("click", function(e) {
      e.preventDefault();
      reportBoxes.forEach((box) => {
        const status = box.querySelector('.status-button')?.getAttribute('data-status') ||
          box.querySelector('.btn-success, .btn-warning')?.textContent?.toLowerCase().trim();

        if (status === 'approved' || status === 'resolved') {
          box.dataset.visible = "true";
          box.style.display = "block";
        } else {
          box.dataset.visible = "false";
          box.style.display = "none";
        }
      });
      updatePagination();
    });

    myReportsLink.addEventListener("click", function(e) {
      e.preventDefault();
      reportBoxes.forEach((box) => {
        const boxUserId = parseInt(box.dataset.userId);
        if (boxUserId === userId) {
          box.dataset.visible = "true";
          box.style.display = "block";
        } else {
          box.dataset.visible = "false";
          box.style.display = "none";
        }
      });
      updatePagination();
    });


    document.querySelectorAll('[name="consider"]').forEach((considerBtn) => {
      const reportBox = considerBtn.closest('.post-box');
      const countElement = reportBox.querySelector('[name="count"]');
      let currentCount = parseInt(countElement.textContent) || 0;
      const reportId = considerBtn.dataset.reportId;


      if (considerBtn.textContent.includes('Considered')) {
        considerBtn.classList.remove('btn-danger');
        considerBtn.classList.add('btn-secondary');
      }

      considerBtn.addEventListener('click', function() {
        const isCurrentlyConsidered = considerBtn.textContent.includes('Considered');
        const newConsideredState = !isCurrentlyConsidered;

        if (newConsideredState) {
          considerBtn.innerHTML = '<i class="fas fa-hourglass-half"></i> Considered';
          considerBtn.classList.remove('btn-danger');
          considerBtn.classList.add('btn-secondary');
          currentCount++;
        } else {
          considerBtn.innerHTML = '<i class="fas fa-exclamation-circle"></i> Consider';
          considerBtn.classList.remove('btn-secondary');
          considerBtn.classList.add('btn-danger');
          currentCount--;
        }

        countElement.textContent = currentCount;


        updateConsiderState(reportId, currentCount, newConsideredState);
      });
    });

    function updateConsiderState(reportId, newCount, isConsidered) {
      fetch("./report.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `report_id=${reportId}&new_count=${newCount}&is_considered=${isConsidered ? 1 : 0}`,
        })
        .then(response => response.json())
        .then(data => {
          if (!data.success) {

            location.reload();
          }
        })
        .catch(error => {
          console.error("Error:", error);
          location.reload();
        });
    }

    // Cancel Report functionality.........................................................
    const cancelModal = document.getElementById("cancelModal");
    const cancelCancelBtn = document.getElementById("cancelCancelBtn");
    const confirmCancelBtn = document.getElementById("confirmCancelBtn");
    const cancelReason = document.getElementById("cancelReason");
    let currentReportId = null;

    document.querySelectorAll(".cancel-report-btn").forEach((btn) => {
      btn.addEventListener("click", function() {
        currentReportId = this.dataset.reportId;
        cancelModal.style.display = "flex";
      });
    });

    cancelCancelBtn.addEventListener("click", function() {
      cancelModal.style.display = "none";
      cancelReason.value = "";
    });

    confirmCancelBtn.addEventListener("click", function() {
      if (!cancelReason.value.trim()) {
        alert("Please provide a reason for cancelling the report.");
        return;
      }

      fetch("./report.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded",
          },
          body: `report_id=${currentReportId}&cancel_reason=${encodeURIComponent(
        cancelReason.value
      )}&cancel_report=true`,
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert("Report cancelled successfully.");
            location.reload();
          } else {
            alert("Failed to cancel report.");
          }
        })
        .catch(error => {
          console.error("Error:", error);
          alert("An error occurred while cancelling the report.");
        });

      cancelModal.style.display = "none";
      cancelReason.value = "";
    });

    // More/Less button functionality................................
    const moreButtons = document.getElementsByName("more");
    const reportImages = document.querySelectorAll(".hidden-details");

    moreButtons.forEach((more, index) => {
      more.addEventListener("click", function() {
        const reportImage = reportImages[index];

        if (!more.classList.contains("active")) {
          more.classList.add("active");
          more.innerHTML = "Less";
          reportImage.style.display = "flex";
        } else {
          more.classList.remove("active");
          more.innerHTML = "More";
          reportImage.style.display = "none";
        }
      });
    });

    // Image zoom functionality
    document.querySelectorAll(".zoomable-image").forEach((img) => {
      img.addEventListener("click", function() {
        const overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = "0";
        overlay.style.left = "0";
        overlay.style.width = "100vw";
        overlay.style.height = "100vh";
        overlay.style.backgroundColor = "rgba(0, 0, 0, 0.85)";
        overlay.style.display = "flex";
        overlay.style.alignItems = "center";
        overlay.style.justifyContent = "center";
        overlay.style.zIndex = "9999";

        const largeImg = document.createElement("img");
        largeImg.src = img.src;
        largeImg.style.maxWidth = "90%";
        largeImg.style.maxHeight = "90%";
        largeImg.style.borderRadius = "12px";
        largeImg.style.boxShadow = "0 0 20px rgba(255,255,255,0.3)";

        overlay.appendChild(largeImg);
        document.body.appendChild(overlay);

        overlay.addEventListener("click", () => {
          document.body.removeChild(overlay);
        });
      });
    });

    // Pagination functionality
    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");
    const pageInfo = document.getElementById("pageInfo");
    const reportsPerPage = 4;
    let currentPage = 1;
    let totalPages = Math.ceil(reportBoxes.length / reportsPerPage);

    function updatePaginationButtons() {
      prevBtn.disabled = currentPage === 1;
      nextBtn.disabled = currentPage === totalPages;

      if (prevBtn.disabled) {
        prevBtn.classList.remove("btn-primary");
        prevBtn.classList.add("btn-secondary");
      } else {
        prevBtn.classList.remove("btn-secondary");
        prevBtn.classList.add("btn-primary");
      }

      if (nextBtn.disabled) {
        nextBtn.classList.remove("btn-primary");
        nextBtn.classList.add("btn-secondary");
      } else {
        nextBtn.classList.remove("btn-secondary");
        nextBtn.classList.add("btn-primary");
      }
    }

    function showCurrentPage() {
      const visibleBoxes = Array.from(reportBoxes).filter(
        (box) => box.dataset.visible === "true"
      );
      totalPages = Math.max(1, Math.ceil(visibleBoxes.length / reportsPerPage));

      if (currentPage > totalPages) {
        currentPage = totalPages;
      }

      reportBoxes.forEach((box) => {
        box.style.display = "none";
      });

      const startIndex = (currentPage - 1) * reportsPerPage;
      const endIndex = startIndex + reportsPerPage;

      let visibleCount = 0;
      reportBoxes.forEach((box) => {
        if (box.dataset.visible === "true") {
          if (visibleCount >= startIndex && visibleCount < endIndex) {
            box.style.display = "block";
          }
          visibleCount++;
        }
      });

      pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
      updatePaginationButtons();
    }

    function updatePagination() {
      currentPage = 1;
      showCurrentPage();
    }

    prevBtn.addEventListener("click", function() {
      if (currentPage > 1) {
        currentPage--;
        showCurrentPage();
      }
    });

    nextBtn.addEventListener("click", function() {
      if (currentPage < totalPages) {
        currentPage++;
        showCurrentPage();
      }
    });

    // Initialize
    showCurrentPage();
  });
  </script>
  <script src="../Hermata home/assets/js/script.js"></script>
  <script src="./report.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>