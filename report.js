const counts = document.getElementsByName("count");
const considers = document.getElementsByName("consider");
const moreButtons = document.getElementsByName("more");
const reportImage = document.getElementById("report-images");

considers.forEach((consider, index) => {
  let i = parseInt(counts[index].innerHTML) || 0;

  consider.addEventListener("click", function () {
    if (!consider.classList.contains("considered")) {
      consider.classList.add("considered");
      consider.classList.remove("btn-danger");
      consider.classList.add("btn-secondary");
      consider.innerHTML = "Considered";

      i++;
      counts[index].innerHTML = i;
    } else {
      consider.classList.remove("considered");
      consider.classList.remove("btn-secondary");
      consider.classList.add("btn-danger");
      consider.innerHTML = "Consider";

      i--;
      counts[index].innerHTML = i;
    }
  });
});

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
