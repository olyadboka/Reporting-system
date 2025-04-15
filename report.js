const counts = document.getElementsByName("count");
const considers = document.getElementsByName("consider");
const moreButtons = document.getElementsByName("more");
const reportImage = document.getElementById("report-images");

considers.forEach((consider, index) => {
  let i = parseInt(counts[index].innerHTML) || 0;

  // Check initial state from the button's class
  if (consider.classList.contains("considered")) {
    consider.innerHTML = "Considered";
    consider.classList.remove("btn-danger");
    consider.classList.add("btn-secondary");
  }

  consider.addEventListener("click", function () {
    const reportId = consider.dataset.reportId; // Ensure this is set in the HTML

    if (!consider.classList.contains("considered")) {
      // Mark as considered
      consider.classList.add("considered");
      consider.classList.remove("btn-danger");
      consider.classList.add("btn-secondary");
      consider.innerHTML = "Considered";

      i++;
      counts[index].innerHTML = i;

      // Update the server
      updateConsiderState(reportId, i, true);
    } else {
      // Unmark as considered
      consider.classList.remove("considered");
      consider.classList.remove("btn-secondary");
      consider.classList.add("btn-danger");
      consider.innerHTML = "Consider";

      i--;
      counts[index].innerHTML = i;

      // Update the server
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
  })
    .then((response) => response.json())
    .then((data) => {
      if (!data.success) {
        console.error("Failed to update consider state:", data.error);
      }
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

moreButtons.forEach((more) => {
  more.addEventListener("click", function () {
    if (!more.classList.contains("considered")) {
      more.classList.add("considered");
      more.classList.remove("btn-tertiary");
      more.classList.add("btn-secondary");
      more.innerHTML = "Less";
      reportImage.style.display = "block";
    } else {
      more.classList.remove("considered");
      more.classList.remove("btn-secondary");
      more.classList.add("btn-tertiary");
      more.innerHTML = "More";
      reportImage.style.display = "none";
    }
  });
});

const links = document.querySelectorAll(".report-links a");

links.forEach((link) => {
  link.addEventListener("click", function (e) {
    e.preventDefault();
    links.forEach((l) => l.classList.remove("active"));
    this.classList.add("active");
  });
});

//for mostly viewed
