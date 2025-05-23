async function fetchReports() {
  try {
    const response = await fetch("api_get_reports.php");
    if (!response.ok) {
      throw new Error("Network response was not ok");
    }
    const reports = await response.json();
    displayReports(reports);
  } catch (error) {
    console.error("Error fetching reports:", error);
    document.getElementById("report-list").innerHTML =
      "<p>Failed to load reports.</p>";
  }
}

function displayReports(reports) {
  const reportList = document.getElementById("report-list");
  reportList.innerHTML = "";

  if (reports.length === 0) {
    reportList.innerHTML = "<p>No reports found.</p>";
    return;
  }

  fetch("api_get_reports.php")
    .then((response) => response.text()) // get as raw text
    .then((text) => {
      try {
        const data = JSON.parse(text); // manually parse JSON
        populateTable(data);
      } catch (error) {
        console.error("Invalid JSON from server:", text);
        document.getElementById(
          "report-table-body"
        ).innerHTML = `<tr><td colspan="4">Error: Invalid data format</td></tr>`;
      }
    })
    .catch((error) => {
      console.error("Fetch error:", error);
      document.getElementById(
        "report-table-body"
      ).innerHTML = `<tr><td colspan="4">Error loading reports</td></tr>`;
    });

  function populateTable(data) {
    const tbody = document.getElementById("report-table-body");
    tbody.innerHTML = "";

    data.forEach((report) => {
      const row = document.createElement("tr");
      row.innerHTML = `
          <td data-label="Report ID">${report.id}</td>
          <td data-label="Title">${report.title}</td>
          <td data-label="Date">${report.date}</td>
          <td data-label="Actions">
              <button onclick="viewReport(${report.id})">View</button>
              <button onclick="deleteReport(${report.id})">Delete</button>
          </td>
      `;
      tbody.appendChild(row);
    });
  }

  document
    .getElementById("report-form")
    .addEventListener("submit", async function (event) {
      event.preventDefault();

      const formData = new FormData(this);
      const response = await fetch("submit_report.php", {
        method: "POST",
        body: formData,
      });

      const messageDiv = document.getElementById("form-message");
      if (response.ok) {
        messageDiv.textContent = "Report submitted successfully.";
      } else {
        messageDiv.textContent = "Error submitting report.";
      }
    });
}
