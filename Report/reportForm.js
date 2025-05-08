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
