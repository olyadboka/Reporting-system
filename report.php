<?php
// session_start(); // Uncomment if needed
include "./reportDB/dbconnection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reportId = intval($_POST['report_id']);
    $newCount = intval($_POST['new_count']);
    $isConsidered = intval($_POST['is_considered']);

    // First update
    $sql = "UPDATE reports SET count = ? WHERE report_id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $newCount, $reportId);
    mysqli_stmt_execute($stmt);

    // Second update
    $sqlconsidered = "UPDATE reports SET count = ?, is_considered = ? WHERE report_id = ?";
    $stmt = mysqli_prepare($con, $sqlconsidered);
    mysqli_stmt_bind_param($stmt, "iii", $newCount, $isConsidered, $reportId);
    mysqli_stmt_execute($stmt);
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome to Report Page</title>
  <link rel="stylesheet" href="./CSS/report.css">
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
        <li><a href="#">Reports</a></li>
        <li><a href="#">Most Viewed</a></li>
        <li><a href="#">Answered</a></li>
        <li><a href="#">My Reports</a></li>
      </ul>

      <div class="main--post" id="main--post">
        <?php foreach ($reports as $report) : ?>
        <div class="post-box reveal" data-visible="true">
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
            <button class="btn1 btn <?php echo $report['is_considered'] ? 'btn-secondary considered' : 'btn-danger'; ?>"
              name="consider" data-report-id="<?php echo htmlspecialchars($report['report_id']); ?>">
              <?php echo $report['is_considered'] ? 'Considered' : 'Consider'; ?>
            </button>
          </div>
          <div class="post-box--top-right">
            <p class="btn btn-danger" name="count"><?php echo htmlspecialchars($report['count']); ?></p>
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
  <section class="footer">
    <?php include './common/footer.php'; ?>
  </section>

  <script src="../Hermata home/assets/js/script.js"></script>
  <script src="./report.js"></script>
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>