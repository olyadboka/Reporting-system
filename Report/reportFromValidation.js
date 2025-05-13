document
  .getElementById("reportForm")
  .addEventListener("submit", function (event) {
    let reportType = document.getElementById("reportType").value;

    let description = document.getElementById("description").value;

    let reportDate = document.getElementById("report_date").value;

    let images = document.getElementById("images").files;

    let errors = [];

    if (reportType === " ") {
      errors.push("Please select a report type.");

      document.getElementById("selectReportType-error").textContent =
        "Please select a report type.";

      event.preventDefault();
    } else {
      document.getElementById("selectReportType-error").textContent = "";
    }

    if (description.trim() === " ") {
      errors.push("Please enter a description.");

      document.querySelector(".description-error").textContent =
        "Please enter a description.";

      event.preventDefault();
    } else {
      document.querySelector(".description-error").textContent = "";
    }

    if (reportDate === " ") {
      errors.push("Please select a report start date.");

      document.querySelector(".error-StartDate").textContent =
        "Please select a report start date.";

      event.preventDefault();
    } else {
      let selectedDate = new Date(reportDate);

      let currentDate = new Date();

      currentDate.setHours(0, 0, 0, 0);

      if (selectedDate > currentDate) {
        errors.push("Report date cannot be in the future.");

        document.querySelector(".error-StartDate").textContent =
          "Report date cannot be in the future.";

        event.preventDefault();
      } else {
        document.querySelector(".error-StartDate").textContent = "";
      }
    }

    if (images.length === 0) {
      errors.push("Please upload at least one image.");

      document.querySelector(".error-images").textContent =
        "Please upload at least one image.";

      event.preventDefault();
    } else {
      for (let i = 0; i < images.length; i++) {
        let image = images[i];

        let allowedTypes = ["image/jpeg", "image/png"];

        if (allowedTypes.indexOf(image.type) === -1) {
          errors.push("Invalid image file type.");

          document.querySelector(".error-images").textContent =
            "Invalid image file type.";

          event.preventDefault();

          break;
        }

        let maxSize = 2 * 1024 * 1024;

        if (image.size > maxSize) {
          errors.push("Image file size is too large.");

          document.querySelector(".error-images").textContent =
            "Image file size is too large.";

          event.preventDefault();

          break;
        }
      }

      if (errors.length === 0) {
        document.querySelector(".error-images").textContent = "";
      }
    }

    if (errors.length > 0) {
      event.preventDefault();

      console.log("Validation Errors:", errors);
    }
  });
