// frontend/js/general_entry_dashboard.js

let currentIndex = 0;
const slides = document.querySelectorAll('.slide');

function showSlide(idx) {
  if (idx >= slides.length) currentIndex = 0;
  else if (idx < 0) currentIndex = slides.length - 1;
  else currentIndex = idx;
  document.querySelector('.slides').style.transform =
    `translateX(-${currentIndex * 100}%)`;
}

function changeSlide(direction) {
  showSlide(currentIndex + direction);
}

document.addEventListener('DOMContentLoaded', () => {
  showSlide(0);
});

function togglePopup(id) {
  document.getElementById(id).classList.toggle('hidden');
}

document.getElementById('loginBtn')
  .addEventListener('click', () => togglePopup('loginPopup'));
document.getElementById('signupBtn')
  .addEventListener('click', () => togglePopup('signupPopup'));
