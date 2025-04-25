document.getElementById("reportType").addEventListener("change", function () {
  const reportType = this.value;
  let details = "";

  if (reportType === "housing") {
    details = `
        <label class="form-label required" id="descriptionLabel">Select Specific Issue</label>
        <select class="form-select" name="SpecificIssue">
          <option value="maintenance">House Maintenance Problems</option>
          <option value="rent">Rent Payment Issues</option>
          <option value="eviction">Unauthorized Evictions</option>
        </select>`;
  } else if (reportType === "community") {
    details = `
        <label class="form-label required" id="descriptionLabel">Select Specific Issue</label>
        <select class="form-select" name="SpecificIssue">
          <option value="water">Water & Sanitation Problems</option>
          <option value="road">Road & Pathway Damage</option>
          <option value="electricity">Electricity Problems</option>
          <option value="garbage">Garbage Collection Issues</option>
        </select>`;
  } else if (reportType === "security") {
    details = `
        <label class="form-label required" id="descriptionLabel">Select Specific Issue</label>
        <select class="form-select" name="SpecificIssue">
          <option value="crime">Crime & Safety Concerns</option>
          <option value="domestic">Domestic Violence & Abuse</option>
          <option value="illegal">Illegal Businesses</option>
        </select>`;
  }

  document.getElementById("reportDetails").innerHTML = details;
});

const translations = {
  en: {
    reportTitle: "Report Form",
    selectReportType: "Select Report Type",
    optionHousing: "Housing & Rental Issues",
    optionCommunity: "Community & Infrastructure Issues",
    optionSecurity: "Security & Law Enforcement Issues",
    descriptionLabel: "Description",
    uploadImagesLabel: "Upload Images",
    uploadVideosLabel: "Upload Videos",
    submitReportBtn: "Submit Report",
  },
  am: {
    reportTitle: "የሪፖርት ቅጽ",
    selectReportType: "የሪፖርት አይነት ይምረጡ",
    optionHousing: "የቤት እና ኪራይ ችግሮች",
    optionCommunity: "የማህበረሰብ እና መሠረተ ልማት ችግሮች",
    optionSecurity: "የደህንነት እና ህግ ማስፈጸሚያ ችግሮች",
    descriptionLabel: "መግለጫ",
    uploadImagesLabel: "ምስሎች ይጫኑ",
    uploadVideosLabel: "ቪዲዮዎች ይጫኑ",
    submitReportBtn: "ሪፖርት ያስገቡ",
  },
  or: {
    reportTitle: "Formii Gabaasa ",
    selectReportType: "Gosa Gabaasaa Filadhu",
    optionHousing: "Rakkoo Manaa fi Kiraa",
    optionCommunity: "Rakkoo Hawaasaa fi Ijaarsaa",
    optionSecurity: "Rakkoo Nageenyaa fi Seeraa Hojii Irratti",
    descriptionLabel: "Ibsa",
    uploadImagesLabel: "Suuraalee Fe’ii",
    uploadVideosLabel: "Viidiyoo Fe’ii",
    submitReportBtn: "Gabaasa Galchi",
  },
};

document
  .getElementById("languageSelector")
  .addEventListener("change", function () {
    const selectedLang = this.value;
    Object.keys(translations[selectedLang]).forEach((id) => {
      document.getElementById(id).textContent = translations[selectedLang][id];
    });
  });
// Validating the report Form
document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector("form");

  // Getting form elements
  const name = document.getElementById("reporter_name");
  const address = document.getElementById("reporter_address");
  const reportType = document.getElementById("reportType");
  let specificIssue = document.getElementsByName("SpecificIssue")[0];
  const description = document.getElementById("description");
  const startDate = document.getElementById("report_date");
  const idField = document.getElementById("reporter-id");
  const images = document.getElementById("images");

  // Error message containers
  const errorFields = {
    name: document.querySelector(".error-name"),
    address: document.querySelector(".error-address"),
    id: document.querySelector(".error-id"),
    reportType: document.querySelector(".error-reportType"),
    specificIssue: document.querySelector(".error-specificIssue"),
    description: document.querySelector(".error-description"),
    startDate: document.querySelector(".error-startDate"),
    images: document.querySelector(".error-images"),
  };

  // Utility function to check if a value is empty
  function isEmpty(value) {
    return !value || value.trim() === "";
  }

  // Reset error message and invalid styles for a specific field
  function clearError(field, errorContainer) {
    errorContainer.innerHTML = "";
    errorContainer.style.color = "";
    field.classList.remove("invalid");
  }

  // Field-specific validators
  function NameValidator() {
    const nameRegex = /^[a-zA-Z\s]{2,50}$/;
    if (isEmpty(name.value) || !nameRegex.test(name.value)) {
      errorFields.name.innerHTML =
        "Invalid Name! It must be 2-50 letters with no numbers or special characters.";
      errorFields.name.style.color = "red";
      name.classList.add("invalid");
      return false;
    }
    clearError(name, errorFields.name);
    return true;
  }

  function AddressValidator() {
    const addressRegex = /^[a-zA-Z0-9\s,\/\-]{5,}$/;
    if (isEmpty(address.value) || !addressRegex.test(address.value)) {
      errorFields.address.innerHTML =
        "Invalid Address! It must be at least 5 characters long and contain only letters, numbers, commas, slashes, or hyphens.";
      errorFields.address.style.color = "red";
      address.classList.add("invalid");
      return false;
    }
    clearError(address, errorFields.address);
    return true;
  }

  function idValidator() {
    const idRegex = /^[a-zA-Z0-9]{8,20}$/;
    if (isEmpty(idField.value) || !idRegex.test(idField.value)) {
      errorFields.id.innerHTML =
        "Invalid ID! It must be 8-20 alphanumeric characters.";
      errorFields.id.style.color = "red";
      idField.classList.add("invalid");
      return false;
    }
    clearError(idField, errorFields.id);
    return true;
  }

  function reportTypeValidator() {
    if (reportType.value === "default") {
      errorFields.reportType.innerHTML = "Please select a valid report type.";
      errorFields.reportType.style.color = "red";
      reportType.classList.add("invalid");
      return false;
    }
    clearError(reportType, errorFields.reportType);
    return true;
  }

  function SpecificIssueValidator() {
    if (specificIssue && isEmpty(specificIssue.value)) {
      errorFields.specificIssue.innerHTML = "Please select a specific issue.";
      errorFields.specificIssue.style.color = "red";
      specificIssue.classList.add("invalid");
      return false;
    }
    clearError(specificIssue, errorFields.specificIssue);
    return true;
  }

  function descriptionValidator() {
    const descriptionRegex = /^[a-zA-Z0-9\s.,!?]{20,}$/;
    if (
      isEmpty(description.value) ||
      !descriptionRegex.test(description.value)
    ) {
      errorFields.description.innerHTML =
        "Description must be at least 20 characters long and meaningful.";
      errorFields.description.style.color = "red";
      description.classList.add("invalid");
      return false;
    }
    clearError(description, errorFields.description);
    return true;
  }

  function startDateValidator() {
    const today = new Date().toISOString().split("T")[0];
    const tenYearsAgo = new Date();
    tenYearsAgo.setFullYear(tenYearsAgo.getFullYear() - 10);
    const tenYearsAgoDate = tenYearsAgo.toISOString().split("T")[0];

    if (
      isEmpty(startDate.value) ||
      startDate.value > today ||
      startDate.value < tenYearsAgoDate
    ) {
      errorFields.startDate.innerHTML =
        "Invalid date! The date must be within the last 10 years and not in the future.";
      errorFields.startDate.style.color = "red";
      startDate.classList.add("invalid");
      return false;
    }
    clearError(startDate, errorFields.startDate);
    return true;
  }

  function imagesValidator() {
    if (images.files.length === 0) {
      errorFields.images.innerHTML = "Please upload at least one image.";
      errorFields.images.style.color = "red";
      images.classList.add("invalid");
      return false;
    }
    for (let file of images.files) {
      if (!["image/png", "image/jpeg", "image/jpg"].includes(file.type)) {
        errorFields.images.innerHTML = `Invalid file type: ${file.name}`;
        errorFields.images.style.color = "red";
        images.classList.add("invalid");
        return false;
      }
      if (file.size > 5 * 1024 * 1024) {
        errorFields.images.innerHTML = `File too large: ${file.name} (Max 5MB)`;
        errorFields.images.style.color = "red";
        images.classList.add("invalid");
        return false;
      }
    }
    clearError(images, errorFields.images);
    return true;
  }

  // Attach validation to individual fields
  name.addEventListener("input", NameValidator);
  address.addEventListener("input", AddressValidator);
  idField.addEventListener("input", idValidator);
  reportType.addEventListener("change", function () {
    reportTypeValidator();
    specificIssue = document.getElementsByName("SpecificIssue")[0];
    if (specificIssue) {
      specificIssue.addEventListener("change", SpecificIssueValidator);
    }
  });
  description.addEventListener("input", descriptionValidator);
  startDate.addEventListener("input", startDateValidator);
  images.addEventListener("change", imagesValidator);

  // Attach validation to form submission
  form.addEventListener("submit", function (event) {
    const isValid =
      NameValidator() &&
      AddressValidator() &&
      idValidator() &&
      reportTypeValidator() &&
      SpecificIssueValidator() &&
      descriptionValidator() &&
      startDateValidator() &&
      imagesValidator();

    if (!isValid) {
      event.preventDefault();
    }
  });
});
