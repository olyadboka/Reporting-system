document.addEventListener("DOMContentLoaded", function () {
  const reportType = document.getElementById("reportType");
  const description = document.getElementById("description");
  const descriptionError = document.querySelector(".description-error");

  const reportTypeError = document.getElementById("selectReportType-error");

  const startDate = document.getElementById("report_date");
  const startDateError = document.querySelector(".error-StartDate");
  const todayDate = new Date().toISOString().split("T")[0];

  const formReport = document.getElementById("reportForm");

  formReport.addEventListener("submit", (e) => {
    e.preventDefault();

    let isValid = true;

    // Validation of the select report type....

    if (reportType.value == "") {
      reportTypeError.textContent = "Report Type is not selected.";
      reportTypeError.style.color = "red";
      reportTypeError.style.fontSize = "12px";
      isValid = false;
    } else {
      reportTypeError.textContent = "";
    }

    // Validation of the description..........

    if (description.value.trim() === "") {
      descriptionError.textContent = "Description is required.";
      descriptionError.style.color = "red";
      descriptionError.style.fontSize = "12px";
      isValid = false;
    } else {
      descriptionError.textContent = "";
    }

    startDate.setAttribute("max", todayDate);
    if (startDate.value === "") {
      startDateError.textContent = "Start Date is not selected.";
      startDateError.style.color = "red";
      startDateError.style.fontSize = "12px";
      isValid = false;
    } else {
      startDateError.textContent = "";
    }
    const maxImages = 4;
    const images = document.getElementById("images").files;
    const imageError = document.querySelector(".error-images");

    if (images.length > maxImages) {
      imageError.textContent = `You can only upload a maximum of ${maxImages} images.`;
      imageError.style.color = "red";
      imageError.style.fontSize = "12px";
      isValid = false;
    } else {
      imageError.textContent = "";
    }

    if (isValid) {
      formReport.submit(); // Manually submit if valid
    }
  });
});
