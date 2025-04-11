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

// Validating the report Formdocument.addEventListener("DOMContentLoaded", function () {
const form = document.querySelector("form");

// Getting form elements
const name = document.getElementById("reporter_name");
const address = document.getElementById("reporter_address");
const reportType = document.getElementById("reportType");
const specificIssue = document.getElementsByName("SpecificIssue")[0];
const description = document.getElementById("description");
const startDate = document.getElementById("report_date");
const idField = document.getElementById("reporter-id");
const images = document.getElementById("images");
const videos = document.getElementById("videos");
const files = document.getElementById("file");

// Error message containers
const errorFields = {
  name: document.querySelector(".error-name"),
  address: document.querySelector(".error-address"),
  id: document.querySelector(".error-id"),
  reportType: document.querySelector(".error-reportType"),
  specificIssue: document.querySelector(".error-specificIssue"),
  description: document.querySelector(".error-description"),
  startDate: document.querySelector(".error-startDate"),
  files: document.querySelector(".error-files"),
};

// Helper function to check if a field is empty
function isEmpty(value) {
  return !value || value.trim() === "";
}

// Reset all errors
function clearErrors() {
  document.querySelectorAll(".error").forEach((el) => {
    el.innerHTML = "";
    el.style.color = "";
  });

  document.querySelectorAll(".invalid").forEach((el) => {
    el.classList.remove("invalid");
  });
}

// **Main Validation Function**
function Validator() {
  clearErrors(); // Clear errors before running validation
  let isValid = true;
  let emptyFields = 0;

  // Validate each field
  if (!NameValidator()) {
    isValid = false;
    emptyFields++;
  }
  if (!AddressValidator()) {
    isValid = false;
    emptyFields++;
  }
  if (!idValidator()) {
    isValid = false;
    emptyFields++;
  }
  if (!reportTypeValidator()) {
    isValid = false;
    emptyFields++;
  }
  if (!SpecificIssueValidator()) {
    isValid = false;
    emptyFields++;
  }
  if (!descriptionValidator()) {
    isValid = false;
    emptyFields++;
  }
  if (!startDateValidator()) {
    isValid = false;
    emptyFields++;
  }

  // File Validation
  if (
    !fileValidator(
      images,
      ["image/png", "image/jpeg", "image/jpg"],
      5,
      errorFields.files
    )
  )
    isValid = false;
  if (
    !fileValidator(
      videos,
      ["video/mp4", "video/avi", "video/mkv"],
      50,
      errorFields.files
    )
  )
    isValid = false;
  if (
    !fileValidator(
      files,
      ["application/pdf", "application/msword"],
      10,
      errorFields.files
    )
  )
    isValid = false;

  // Check if user tries to submit without entering any fields
  if (emptyFields === 7) {
    alert("Please fill in all required fields before submitting the form.");
    return false;
  }

  return isValid;
}

// **Field Validators**
function NameValidator() {
  const nameRegex = /^[a-zA-Z\s]{3,}$/;
  if (isEmpty(name.value) || !nameRegex.test(name.value)) {
    errorFields.name.innerHTML =
      "Invalid Name! It must be at least 3 letters with no numbers.";
    errorFields.name.style.color = "red";
    name.classList.add("invalid");
    return false;
  }
  return true;
}

function AddressValidator() {
  if (isEmpty(address.value) || address.value.length < 5) {
    errorFields.address.innerHTML =
      "Address must be at least 5 characters long.";
    errorFields.address.style.color = "red";
    address.classList.add("invalid");
    return false;
  }
  return true;
}

function idValidator() {
  const idRegex = /^[0-9]{5,}$/;
  if (isEmpty(idField.value) || !idRegex.test(idField.value)) {
    errorFields.id.innerHTML = "Invalid ID! It must be at least 5 digits.";
    errorFields.id.style.color = "red";
    idField.classList.add("invalid");
    return false;
  }
  return true;
}

function reportTypeValidator() {
  if (reportType.value === "default") {
    errorFields.reportType.innerHTML = "Please select a valid report type.";
    errorFields.reportType.style.color = "red";
    reportType.classList.add("invalid");
    return false;
  }
  return true;
}

function SpecificIssueValidator() {
  if (isEmpty(specificIssue.value)) {
    errorFields.specificIssue.innerHTML =
      "Please provide details about the specific issue.";
    errorFields.specificIssue.style.color = "red";
    specificIssue.classList.add("invalid");
    return false;
  }
  return true;
}

function descriptionValidator() {
  if (isEmpty(description.value) || description.value.length < 20) {
    errorFields.description.innerHTML =
      "Description must be at least 20 characters long.";
    errorFields.description.style.color = "red";
    description.classList.add("invalid");
    return false;
  }
  return true;
}

function startDateValidator() {
  const today = new Date().toISOString().split("T")[0];
  if (isEmpty(startDate.value) || startDate.value > today) {
    errorFields.startDate.innerHTML =
      "Invalid date! The start date cannot be in the future.";
    errorFields.startDate.style.color = "red";
    startDate.classList.add("invalid");
    return false;
  }
  return true;
}


function fileValidator(input, allowedTypes, maxSizeMB, errorContainer) {
  if (input.files.length > 0) {
    for (let file of input.files) {
      if (!allowedTypes.includes(file.type)) {
        errorContainer.innerHTML = `Invalid file type: ${file.name}`;
        errorContainer.style.color = "red";
        input.classList.add("invalid");
        return false;
      }
      if (file.size > maxSizeMB * 1024 * 1024) {
        errorContainer.innerHTML = `File too large: ${file.name} (Max ${maxSizeMB}MB)`;
        errorContainer.style.color = "red";
        input.classList.add("invalid");
        return false;
      }
    }
  }
  return true;
}

// **Attach validation to form submission**
form.addEventListener("submit", function (event) {
  if (!Validator()) {
    event.preventDefault();
  }
});

// **Real-time Validation Feedback**
document.querySelectorAll("input, textarea, select").forEach((input) => {
  input.addEventListener("input", () => {
    Validator();
  });
});
