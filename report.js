const counts = document.getElementsByName("count");
const considers = document.getElementsByName("consider");
const moreButtons = document.getElementsByName("more");
const reportImage = document.getElementById("report-images");

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

// for the pagination part

document.addEventListener("DOMContentLoaded", function () {
  const reportLinks = document.querySelectorAll(".report-links li a");
  const reportBoxes = document.querySelectorAll(".post-box");
  const prevBtn = document.getElementById("prevBtn");
  const nextBtn = document.getElementById("nextBtn");
  const pageInfo = document.getElementById("pageInfo");
  const reportsPerPage = 2;
  let currentPage = 1;
  let totalPages = Math.ceil(reportBoxes.length / reportsPerPage);

  function updatePaginationButtons() {
    prevBtn.disabled = currentPage === 1;
    nextBtn.disabled = currentPage === totalPages;

    if (prevBtn.disabled) {
      prevBtn.classList.remove("btn-primary");
      prevBtn.classList.add("btn-secondary");
    } else {
      prevBtn.classList.remove("btn-secondary");
      prevBtn.classList.add("btn-primary");
    }

    if (nextBtn.disabled) {
      nextBtn.classList.remove("btn-primary");
      nextBtn.classList.add("btn-secondary");
    } else {
      nextBtn.classList.remove("btn-secondary");
      nextBtn.classList.add("btn-primary");
    }
  }

  function showCurrentPage() {
    const visibleBoxes = Array.from(reportBoxes).filter(
      (box) => box.dataset.visible === "true"
    );
    totalPages = Math.max(1, Math.ceil(visibleBoxes.length / reportsPerPage));

    if (currentPage > totalPages) {
      currentPage = totalPages;
    }

    reportBoxes.forEach((box, index) => {
      box.style.display = "none";
    });

    const startIndex = (currentPage - 1) * reportsPerPage;
    const endIndex = startIndex + reportsPerPage;

    let visibleCount = 0;
    reportBoxes.forEach((box, index) => {
      if (box.dataset.visible === "true") {
        if (visibleCount >= startIndex && visibleCount < endIndex) {
          box.style.display = "block";
        }
        visibleCount++;
      }
    });

    pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
    updatePaginationButtons();
  }

  prevBtn.addEventListener("click", function () {
    if (currentPage > 1) {
      currentPage--;
      showCurrentPage();
    }
  });

  nextBtn.addEventListener("click", function () {
    if (currentPage < totalPages) {
      currentPage++;
      showCurrentPage();
    }
  });

  reportLinks.forEach((link) => {
    link.addEventListener("click", function (event) {
      event.preventDefault();
      const category = this.textContent.trim();
      currentPage = 1;

      reportBoxes.forEach((box) => {
        const boxCategory = box
          .querySelector(".post-box--report-type h1")
          .textContent.replace("Type: ", "")
          .trim();
        const countElement = box.querySelector("p[name='count']");
        const count = parseInt(countElement.textContent.trim());
        const priorty = box.querySelector("p[name='priority']");

        if (category === "Reports") {
          box.dataset.visible = "true";
        } else if (category === "Most Viewed" && count > 100) {
          priorty.textContent = "High";
          priorty.style.backgroundColor = "red";

          box.dataset.visible = "true";
        } else if (category === "Answered" && count > 50 && count <= 100) {
          priorty.textContent = "Solved";
          priorty.style.backgroundColor = "green";

          box.dataset.visible = "true";
        } else if (category === "My Reports" && count <= 50) {
          box.dataset.visible = "true";
        } else {
          box.dataset.visible = "false";
        }
      });

      showCurrentPage();
    });
  });

  showCurrentPage();
});
