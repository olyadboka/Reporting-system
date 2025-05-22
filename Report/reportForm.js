// Report Type Change Handler
document.getElementById("reportType").addEventListener("change", function () {
  const reportType = this.value;
  let details = "";

  if (reportType === "housing") {
    details = `
      <label class="form-label required" id="specificIssueLabel">Select Specific Issue</label>
      <select class="form-select" name="SpecificIssue">
        <option value="" selected disabled>Select Specific Issue</option>
        <option value="maintenance">House Maintenance Problems</option>
        <option value="rent">Rent Payment Issues</option>
        <option value="eviction">Unauthorized Evictions</option>
      </select>
      <p class="specificIssue-error"></p>
    `;
  } else if (reportType === "community") {
    details = `
      <label class="form-label required" id="specificIssueLabel">Select Specific Issue</label>
      <select class="form-select" name="SpecificIssue">
        <option value="" selected disabled>Select Specific Issue</option>
        <option value="water">Water & Sanitation Problems</option>
        <option value="road">Road & Pathway Damage</option>
        <option value="electricity">Electricity Problems</option>
        <option value="garbage">Garbage Collection Issues</option>
      </select>
      <p class="specificIssue-error"></p>
    `;
  } else if (reportType === "security") {
    details = `
      <label class="form-label required" id="specificIssueLabel">Select Specific Issue</label>
      <select class="form-select" name="SpecificIssue">
        <option value="" selected disabled>Select Specific Issue</option>
        <option value="crime">Crime & Safety Concerns</option>
        <option value="domestic">Domestic Violence & Abuse</option>
        <option value="illegal">Illegal Businesses</option>
      </select>
      <p class="specificIssue-error"></p>
    `;
  }

  document.getElementById("reportDetails").innerHTML = details;
});

// Translations
const translations = {
  en: {
    reportTitle: "Report Form",
    reporterLabel: "Name",
    reporterIdLabel: "ID",
    addressLabel: "Address",
    selectReportTypeLabel: "Select Report Type",
    optionHousing: "Housing & Rental Issues",
    optionCommunity: "Community & Infrastructure Issues",
    optionSecurity: "Security & Law Enforcement Issues",
    specificIssueLabel: "Select Specific Issue",
    descriptionLabel: "Description",
    startDateLabel: "When the problem has Started?",
    uploadImagesLabel: "Upload Images",
    uploadImagesLabel2: "Upload Images",
    uploadImagesLabel3: "Upload Images",
    uploadImagesLabel4: "Upload Images",
    uploadVideosLabel: "Upload Videos",
    uploadFilesLabel: "Upload File/s",
    submitReportBtn: "Submit Report",
  },
  am: {
    reportTitle: "የሪፖርት ቅጽ",
    reporterLabel: "ስም",
    reporterIdLabel: "መለያ",
    addressLabel: "አድራሻ",
    selectReportTypeLabel: "የሪፖርት አይነት ይምረጡ",
    optionHousing: "የቤት እና ኪራይ ችግሮች",
    optionCommunity: "የማህበረሰብ እና መሠረተ ልማት ችግሮች",
    optionSecurity: "የደህንነት እና ህግ ማስፈጸሚያ ችግሮች",
    specificIssueLabel: "የተወሰነ ችግር ምረጥ",
    descriptionLabel: "መግለጫ",
    startDateLabel: "ችግሩ መቼ ጀምሯል?",
    uploadImagesLabel: "ምስሎች ይጫኑ",
    uploadImagesLabel2: "ምስሎች ይጫኑ",
    uploadImagesLabel3: "ምስሎች ይጫኑ",
    uploadImagesLabel4: "ምስሎች ይጫኑ",
    uploadVideosLabel: "ቪዲዮዎች ይጫኑ",
    uploadFilesLabel: "ፋይሎች ይጫኑ",
    submitReportBtn: "ሪፖርት ያስገቡ",
  },
  or: {
    reportTitle: "Formii Gabaasa",
    reporterLabel: "Maqaa",
    reporterIdLabel: "ID",
    addressLabel: "Teessoo",
    selectReportTypeLabel: "Gosa Gabaasaa Filadhu",
    optionHousing: "Rakkoo Manaa fi Kiraa",
    optionCommunity: "Rakkoo Hawaasaa fi Ijaarsaa",
    optionSecurity: "Rakkoo Nageenyaa fi Seeraa Hojii Irratti",
    specificIssueLabel: "Filannoo Rakkoo Addaa",
    descriptionLabel: "Ibsa",
    startDateLabel: "Rakkoon yoom eegale?",
    uploadImagesLabel: "Suuraalee Fe'ii",
    uploadImagesLabel2: "Suuraalee Fe'ii",
    uploadImagesLabel3: "Suuraalee Fe'ii",
    uploadImagesLabel4: "Suuraalee Fe'ii",
    uploadVideosLabel: "Viidiyoo Fe'ii",
    uploadFilesLabel: "Fayila Fe'ii",
    submitReportBtn: "Gabaasa Galchi",
  },
};

// Language Change Handler
document
  .getElementById("languageSelector")
  .addEventListener("change", function () {
    const selectedLang = this.value;
    const langData = translations[selectedLang];

    // Update all translatable elements
    Object.keys(langData).forEach((key) => {
      const elements = document.querySelectorAll(`[id="${key}"]`);
      elements.forEach((element) => {
        element.textContent = langData[key];
      });

      // Special case for buttons with value attribute
      const inputElements = document.querySelectorAll(
        `input[value="${langData[key]}"]`
      );
      inputElements.forEach((element) => {
        element.value = langData[key];
      });
    });
  });

// Initialize with English
document.addEventListener("DOMContentLoaded", function () {
  document
    .getElementById("languageSelector")
    .dispatchEvent(new Event("change"));
});
