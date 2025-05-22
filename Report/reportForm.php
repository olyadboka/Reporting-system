<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="reportFrom.css">
</head>

<body>
  <div class="container mt-5">
    <div class="d-flex justify-content-end">
      <select id="languageSelector" class="form-select w-auto">
        <option value="en">English</option>
        <option value="am">Amharic</option>
        <option value="or">Oromo</option>
      </select>
    </div>
    <h1 class="text-center" id="reportTitle">Report Form</h1>

    <form action="../reportDB/reportFromDB.php" method="post" enctype="multipart/form-data" id="reportForm">
      <table>
        <tr>
          <td>
            <label class="form-label required" id="reporterLabel">Name</label>
          </td>
          <td>
            <input type="text" name="reporter_name" id="reporter_name" class="reporter-name"
              value="<?php echo $_SESSION['user_name'];?>" placeholder="<?php echo $_SESSION['user_name'];?>" disabled>
          </td>
        </tr>
        <tr>
          <td>
            <label class="form-label required" id="reporterIdLabel">ID</label>
          </td>
          <td>
            <input type="text" name="reporter_id" id="reporter_id" class="reporter-id"
              value="<?php echo strtoupper($_SESSION['kebele_id']);?>" disabled>
          </td>
        </tr>
        <tr>
          <td>
            <label class="form-label required" id="addressLabel">Address</label>
          </td>
          <td>
            <input type="text" name="reporter_address" id="reporter_address" disabled value="HERMATA MENINA">
          </td>
        </tr>
      </table>

      <div class="mb-3">
        <label class="form-label required" id="selectReportTypeLabel">Select Report Type</label>
        <select class="form-select" id="reportType" name="reportType">
          <option value="" disabled selected>Select Report Type</option>
          <option value="housing" id="optionHousing">Housing & Rental Issues</option>
          <option value="community" id="optionCommunity">Community & Infrastructure Issues</option>
          <option value="security" id="optionSecurity">Security & Law Enforcement Issues</option>
        </select>
        <p class="error-reportType" id="selectReportTypeError" style="color: red;"></p>
      </div>

      <div id="reportDetails"></div>

      <div class="mb-3">
        <label class="form-label required" id="descriptionLabel">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        <p class="description-error"></p>
      </div>

      <div class="mb-3">
        <label class="form-label required" id="startDateLabel">When the problem has Started?</label>
        <input type="date" name="report_date" id="report_date">
        <p class="error-StartDate"></p>
      </div>

      <div class="mb-3">
        <label class="form-label" id="uploadImagesLabel">Upload Images</label>
        <input type="file" name="image1" id="image1" accept="image/*">
        <p class="error-images1"></p>
      </div>

      <div class="mb-3">
        <label class="form-label" id="uploadImagesLabel2">Upload Images</label>
        <input type="file" name="image2" id="image2" accept="image/*">
        <p class="error-images2"></p>
      </div>

      <div class="mb-3">
        <label class="form-label" id="uploadImagesLabel3">Upload Images</label>
        <input type="file" name="image3" id="image3" accept="image/*">
        <p class="error-images3"></p>
      </div>

      <div class="mb-3">
        <label class="form-label" id="uploadImagesLabel4">Upload Images</label>
        <input type="file" name="image4" id="image4" accept="image/*">
        <p class="error-images4"></p>
      </div>

      <div class="mb-3">
        <label class="form-label" id="uploadVideosLabel">Upload Videos</label>
        <input class="form-control" type="file" id="videos" name="videos" accept="video/*" multiple>
        <p class="error-video"></p>
      </div>

      <div class="mb-3">
        <label class="form-label" id="uploadFilesLabel">Upload File/s</label>
        <input class="form-control" type="file" id="file" name="file" accept=".pdf,.doc,.docx" multiple>
        <p class="error-files"></p>
      </div>

      <button type="submit" class="btn btn-primary" id="submitReportBtn">Submit Report</button>
    </form>
  </div>

  <script src="reportForm.js"></script>
</body>

</html>