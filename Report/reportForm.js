document.getElementById("reportType").addEventListener("change", function () {
  const reportType = this.value;
  let details = "";

  if (reportType === "housing") {
    details = `
        <label class="form-label required" id="descriptionLabel">Select Specific Issue</label>
        <select class="form-select" name="SpecificIssue">
        <option value ="" selected disabled>Select Specific Issue</option>

          <option value="maintenance">House Maintenance Problems</option>
          <option value="rent">Rent Payment Issues</option>
          <option value="eviction">Unauthorized Evictions</option>
        </select>
           <p class="specificIssue-error"></p>
        `;
  } else if (reportType === "community") {
    details = `
        <label class="form-label required" id="descriptionLabel">Select Specific Issue</label>
        <select class="form-select" name="SpecificIssue">
         <option value ="" selected disabled>Select Specific Issue</option>
          <option value="water">Water & Sanitation Problems</option>
          <option value="road">Road & Pathway Damage</option>
          <option value="electricity">Electricity Problems</option>
          <option value="garbage">Garbage Collection Issues</option>
        </select>
        <p class ="specificIssue-error"></p>"`;
  } else if (reportType === "security") {
    details = `
        <label class="form-label required" id="descriptionLabel">Select Specific Issue</label>
        <select class="form-select" name="SpecificIssue">
         <option value ="" selected disabled>Select Specific Issue</option>
          <option value="crime">Crime & Safety Concerns</option>
          <option value="domestic">Domestic Violence & Abuse</option>
          <option value="illegal">Illegal Businesses</option>
        </select>
           <p class="specificIssue-error"></p>
        `;
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
const reportType = document.getElementById("selectReportType");
const description = document.getElementById("description");
const descriptionErrror = document.getElementById("description-error");

const reportTypeError = document.getElementById("selectReportType-error");

const specificIssue = document.getElementByClassName("specificIssue");
const specificIssueError = document.getElementById("specificIssue-error");

const startDate = document.getElementById("startDate");
const startDateEerror = document.getElementById("startDate-error");
var todayDate = new Date().toISOString().split("T")[0];

// const submitButton = document.getElementById("submitReportBtn");
const fromreport = document.querySelector("form");
fromreport.addEventListener("submit", (e) => {
  //validation of the select report type

  let isValid = true;
  if (reportType.value == "") {
    reportTypeError.textContent = " Report Type is not Selected";
    reportTypeError.style.color = "red";
    reportTypeError.style.fontSize = "12px";
    isValid = false;
  } else {
    reportTypeError.textContent = "";
  }

  //validation of the specific issue

  if (specificIssue.Value == "") {
    specificIssueError.textContent = "Specific Issue is not Selected";
    specificIssueError.style.color = "red";
    specificIssueError.style.fontSize = "12px";
    isValid = false;
  } else {
    specificIssueError.textContent = "";
  }
  // validation of the description

  if (description.value == "") {
    descriptionErrror.textContent = "Description is required";
    descriptionErrror.style.color = "red";
    descriptionErrror.style.fontSize = "12px";
    isValid = false;
  } else {
    descriptionErrror.textContent = "";
  }

  //validation of the starting date

  startDate.setAttribute("max", todayDate);
  if ((startDate.value = "")) {
    startDateEerror.textContent = "Start Date is not Selected";
    startDateEerror.style.color = "red";
    startDateEerror.style.fontSize = "12px";
    isValid = false;
  } else if (startDate.value) {
    startDateEerror.textContent = "";
  }
  const maxImages = 4;
  const images = document.getElementById("uploadImages").files;
  const imageError = document.getElementByClassName("image-error");
  if (images.length > maxImages) {
    imageError.textContent = `You can only upload a maximum of ${maxImages} images.`;
    imageError.style.color = "red";
    imageError.style.fontSize = "12px";
    isValid = false;
  } else if (images.length == 0) {
    imageError.textContent = "Please upload at least one image.";
    imageError.style.color = "red";
    imageError.style.fontSize = "12px";
    isValid = false;
  } else {
    imageError.textContent = "";
  }
  if (!isValid) {
    e.preventDefault();
  }
});
