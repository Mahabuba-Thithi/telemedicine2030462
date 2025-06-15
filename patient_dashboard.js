/* ============== */
/* Patient Dashboard JS */
/* ============== */

/////////////////////////////////////////////////
/* Slider Section */
/////////////////////////////////////////////////

let currentSlide = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(i) {
  slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % slides.length;
  showSlide(currentSlide);
}

setInterval(nextSlide, 3000);
