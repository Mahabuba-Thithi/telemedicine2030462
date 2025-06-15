/* ============================= */
/* ===== HEADER SECTION JS ===== */
/* ============================= */

document.addEventListener('DOMContentLoaded', () => {
  const userDiv = document.querySelector('.user-dropdown');
  const userIcon = userDiv.querySelector('i.fa-user-circle');
  const notiWrapper = document.querySelector('.notification-wrapper');
  const notiDiv = document.getElementById('noti-dropdown');

  // Toggle the user‐options dropdown
  userIcon.addEventListener('click', (e) => {
    e.stopPropagation();
    userDiv.classList.toggle('show');
  });

  // Toggle the notification dropdown and remove the red dot
  notiWrapper.addEventListener('click', (e) => {
    e.stopPropagation();
    const isOpen = notiDiv.style.display === 'block';
    notiDiv.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) {
      const dot = document.querySelector('.notification-wrapper .noti-dot');
      if (dot) dot.remove();
    }
  });

  // Close both dropdowns if you click outside
  document.addEventListener('click', () => {
    if (userDiv.classList.contains('show')) {
      userDiv.classList.remove('show');
    }
    if (notiDiv.style.display === 'block') {
      notiDiv.style.display = 'none';
    }
  });
});
