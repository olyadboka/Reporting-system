
// const observer = new IntersectionObserver((entries) => {
//   entries.forEach((entry) => {
//     if (entry.isIntersecting) {
//       entry.target.classList.add("show");
//       observer.unobserve(entry.target);
//     }
//   });
// });
// const hiddenElements = document.querySelectorAll('.hidden');
// hiddenElements.forEach((el) => observer.observe(el));


const observer = new IntersectionObserver((entries) => {
    for (let i=0; i < entries.length; i++) {
      if (entries[i].isIntersecting) {
        entries[i].target.classList.add('show');
        } else {
        entries[i].target.classList.remove('show');
        }
        }
    }) ;
    const hiddenElements = document.querySelectorAll('.hidden');
    hiddenElements.forEach((el) => observer.observe(el));
  