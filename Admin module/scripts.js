document.addEventListener("DOMContentLoaded", () => {
  loadReports();
  loadUsers();
  loadStatistics();
  handleFormSubmission();
});

// Load Reports
function loadReports() {
  fetch("get_reports.php")
    .then((response) => response.json())
    .then((data) => {
      const reportList = document.getElementById("report-list");
      reportList.innerHTML = "";

      if (data.length === 0) {
        reportList.innerHTML = "<p>No reports found.</p>";
        return;
      }

      data.forEach((report) => {
        const div = document.createElement("div");
        div.classList.add("report-item");
        div.innerHTML = `
                    <h3>${report.title}</h3>
                    <p>${report.description}</p>
                    <small><strong>Date:</strong> ${report.date}</small>
                `;
        reportList.appendChild(div);
      });
    })
    .catch((error) => {
      console.error("Error fetching reports:", error);
    });
}

// Load Users
function loadUsers() {
  fetch("get_users.php")
    .then((response) => response.json())
    .then((data) => {
      const userList = document.getElementById("user-list");
      userList.innerHTML = "";

      if (data.length === 0) {
        userList.innerHTML = "<p>No users found.</p>";
        return;
      }

      data.forEach((user) => {
        const div = document.createElement("div");
        div.classList.add("user-item");
        div.innerHTML = `
                    <p><strong>Name:</strong> ${user.name}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Role:</strong> ${user.role}</p>
                `;
        userList.appendChild(div);
      });
    })
    .catch((error) => {
      console.error("Error fetching users:", error);
    });
}

// Load Statistics
function loadStatistics() {
  fetch("get_statistics.php")
    .then((response) => response.json())
    .then((data) => {
      const statsDiv = document.getElementById("statistics-data");
      statsDiv.innerHTML = `
                <p><strong>Total Reports:</strong> ${data.totalReports}</p>
                <p><strong>Total Users:</strong> ${data.totalUsers}</p>
                <p><strong>Reports This Month:</strong> ${data.reportsThisMonth}</p>
            `;
    })
    .catch((error) => {
      console.error("Error fetching statistics:", error);
    });
}

// Submit Report
function handleFormSubmission() {
  const form = document.getElementById("report-form");
  const messageDiv = document.getElementById("form-message");

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(form);

    fetch("submit_report.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((responseText) => {
        messageDiv.textContent = responseText;
        form.reset();
        loadReports(); // Refresh the reports list
      })
      .catch((error) => {
        console.error("Error submitting report:", error);
        messageDiv.textContent = "Failed to submit report.";
      });
  });
}
