const counts = document.getElementsByName("count");
const considers = document.getElementsByName("consider");
const moreButtons = document.getElementsByName("more");
const reportImages = document.querySelectorAll(".hidden-details");
const cancelButtons = document.querySelectorAll(".cancel-report-btn");
const cancelModal = document.getElementById("cancelModal");
const cancelCancelBtn = document.getElementById("cancelCancelBtn");
const confirmCancelBtn = document.getElementById("confirmCancelBtn");
const cancelReason = document.getElementById("cancelReason");
let currentReportId = null;

// Cancel Report functionality
cancelButtons.forEach((btn) => {
  btn.addEventListener("click", function () {
    currentReportId = this.dataset.reportId;
    cancelModal.style.display = "flex";
  });
});

cancelCancelBtn.addEventListener("click", function () {
  cancelModal.style.display = "none";
  cancelReason.value = "";
});

confirmCancelBtn.addEventListener("click", function () {
  if (!cancelReason.value.trim()) {
    alert("Please provide a reason for cancelling the report.");
    return;
  }

  fetch("./report.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `report_id=${currentReportId}&cancel_reason=${encodeURIComponent(
      cancelReason.value
    )}&cancel_report=true`,
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Report cancelled successfully.");
        location.reload();
      } else {
        alert("Failed to cancel report: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      alert("An error occurred while cancelling the report.");
    });

  cancelModal.style.display = "none";
  cancelReason.value = "";
});

// Consider functionality
considers.forEach((consider, index) => {
  let i = parseInt(counts[index].innerHTML) || 0;

  if (consider.classList.contains("considered")) {
    consider.innerHTML = "Considered";
    consider.classList.remove("btn-danger");
    consider.classList.add("btn-secondary");
  }

  consider.addEventListener("click", function () {
    const reportId = consider.dataset.reportId;

    if (!consider.classList.contains("considered")) {
      consider.classList.add("considered");
      consider.classList.remove("btn-danger");
      consider.classList.add("btn-secondary");
      consider.innerHTML = "Considered";

      i++;
      counts[index].innerHTML = i;

      updateConsiderState(reportId, i, true);
    } else {
      consider.classList.remove("considered");
      consider.classList.remove("btn-secondary");
      consider.classList.add("btn-danger");
      consider.innerHTML = "Consider";

      i--;
      counts[index].innerHTML = i;

      updateConsiderState(reportId, i, false);
    }
  });
});

function updateConsiderState(reportId, newCount, isConsidered) {
  fetch("./report.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `report_id=${reportId}&new_count=${newCount}&is_considered=${
      isConsidered ? 1 : 0
    }`,
  }).catch((error) => {
    console.error("Error:", error);
  });
}

// More/Less button functionality
moreButtons.forEach((more, index) => {
  more.addEventListener("click", function () {
    const reportImage = reportImages[index];

    if (!more.classList.contains("considered")) {
      more.classList.add("considered");
      more.classList.remove("btn-tertiary");
      more.classList.add("btn-secondary");
      more.innerHTML = "Less";
      reportImage.style.display = "flex";
    } else {
      more.classList.remove("considered");
      more.classList.remove("btn-secondary");
      more.classList.add("btn-tertiary");
      more.innerHTML = "More";
      reportImage.style.display = "none";
    }
  });
});

// Image zoom functionality
document.querySelectorAll(".zoomable-image").forEach((img) => {
  img.addEventListener("click", function () {
    const overlay = document.createElement("div");
    overlay.style.position = "fixed";
    overlay.style.top = 0;
    overlay.style.left = 0;
    overlay.style.width = "100vw";
    overlay.style.height = "100vh";
    overlay.style.backgroundColor = "rgba(0, 0, 0, 0.85)";
    overlay.style.display = "flex";
    overlay.style.alignItems = "center";
    overlay.style.justifyContent = "center";
    overlay.style.zIndex = 9999;

    const largeImg = document.createElement("img");
    largeImg.src = img.src;
    largeImg.style.maxWidth = "90%";
    largeImg.style.maxHeight = "90%";
    largeImg.style.borderRadius = "12px";
    largeImg.style.boxShadow = "0 0 20px rgba(255,255,255,0.3)";

    overlay.appendChild(largeImg);
    document.body.appendChild(overlay);

    overlay.addEventListener("click", () => {
      document.body.removeChild(overlay);
    });
  });
});
