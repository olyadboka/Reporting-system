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

    <!-- Report form  -->

    <form action="../reportDB/reportFromDB.php" method="post" enctype="multipart/form-data">
      <!-- Pesonal of the reporter -->
      <table>

        <tr>

          <td>
            <div class="name"></div> <label class="form-label required" id="reporter">Name</label>
          </td>
          <td> <input type="text" name="reporter_name" id="reporter_name" class="reporter-name">

  </div>
  </td>
  </tr>
  <tr>

    <td>
      <div class="name"></div> <label class="form-label required" id="reporter-id">ID</label>
    </td>
    <td> <input type="text" name="reporter-id" id="reporter-id" class="reporter-id">

      </div>
      <p class="error-id"></p>
    </td>
  </tr>



  <tr>

    <td>
      <div class="name"></div> <label class="form-label required" id="address">Address</label>
    </td>
    <td>
      <input type="text" name="reporter_address" id="reporter_address">

      </div>
      <p class="error-address"></p>
    </td>
  </tr>




  </table>




  <div class="mb-3">
    <label class="form-label required" id="selectReportType">Select Report Type</label>
    <select class="form-select" id="reportType" name="reportType">
      <option value="housing" id="optionHousing">Housing & Rental Issues</option>
      <option value="community" id="optionCommunity">Community & Infrastructure Issues</option>
      <option value="security" id="optionSecurity">Security & Law Enforcement Issues</option>
    </select>
    <p class="error-reportType"></p>
  </div>

  <!-- ===================== -->
  <div id="reportDetails">
    <p class="error-specificIssue"></p>
  </div>
  <!-- ===================== -->
  <div class="mb-3">
    <label class="form-label required" id="descriptionLabel">Description</label>
    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
  </div>
  <p class="error-description"></p>
  <!-- When is the start date of the report -->

  <div class="name">
    <label class="form-label required" id="address">When the problem has Started?</label>
    <input type="date" name="report_date" id="report_date">
    <p class="error-StartDate"></p>
  </div>


  <div class="mb-3">
    <label class="form-label required" id="uploadImagesLabel">Upload Images</label>
    <input class="form-control" type="file" id="images" name="images" multiple>
    <p class="error-images"></p>
  </div>
  <div class="mb-3">
    <label class="form-label" id="uploadVideosLabel">Upload Videos</label>
    <input class="form-control" type="file" id="videos" name="videos" accept="video/*" multiple>
    <p class="error-video"></p>
  </div>

  <div class="mb-3">
    <label class="form-label" id="uploadVideosLabel">Upload File/s</label>
    <input class="form-control" type="file" id="file" name="file" accept="file/.pdf,.dox" multiple>
    <p class="error-files"></p>
  </div>


  <button type="submit" class="btn btn-primary" id="submitReportBtn">Submit Report</button>
  </form>
  </div>

  <script src="reportForm.js"></script>
</body>

</html>