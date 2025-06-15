/* ============== */
/* Navbar & Search JS */
/* ============== */

// ===== Slider =====
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

// ===== Autocomplete & Fuse.js setup =====
let doctors = [];
let fuse = null;
let selectedDept = '';
const MAX_SUGGESTIONS = 7;

const searchInput    = document.getElementById('search-input');
const suggestionsBox = document.getElementById('suggestions');

// 1) Fetch doctor data (including IDs)
fetch('/Telimedicine/backend/get_doctors.php')
  .then(res => res.ok ? res.json() : Promise.reject(res.status))
  .then(data => {
    doctors = data;
    fuse = new Fuse(doctors, { keys: ['doctor_name'], threshold: 0.4 });
  })
  .catch(err => console.error('Error fetching doctors:', err));

// ===== Helpers to show/hide suggestions =====
function showSuggestions(list) {
  suggestionsBox.innerHTML = list.length
    ? list.map(d => `<div data-id="${d.doctor_id}">${d.doctor_name}</div>`).join('')
    : `<div>No match found</div>`;
  suggestionsBox.style.display = 'block';
}
function hideSuggestions() {
  suggestionsBox.style.display = 'none';
}

// ===== On input: fuzzy search + dept filter =====
searchInput.addEventListener('input', () => {
  const term = searchInput.value.trim();
  if (!term || !fuse) {
    hideSuggestions();
    return;
  }
  let results = fuse.search(term).map(r => r.item);
  if (selectedDept) {
    results = results.filter(d => d.doctor_dept === selectedDept);
  }
  showSuggestions(results.slice(0, MAX_SUGGESTIONS));
});

// ===== Redirect on suggestion click =====
suggestionsBox.addEventListener('click', e => {
  const el = e.target;
  if (el.tagName === 'DIV' && el.dataset.id) {
    const id = el.dataset.id;
    window.location.href = `/Telimedicine/frontend/php/doctor_profile_patient_pov.php?doctor_id=${id}`;
  }
});

// ===== Hide suggestions when clicking outside =====
document.addEventListener('click', e => {
  if (
    !searchInput.contains(e.target) &&
    !suggestionsBox.contains(e.target)
  ) {
    hideSuggestions();
  }
});

// ===== Filter dropdown toggle & checkmarks =====
const filterBtn      = document.getElementById('filter-btn');
const filterDropdown = document.getElementById('filter-dropdown');
const filterOptions  = document.querySelectorAll('.filter-option');

filterBtn.addEventListener('click', e => {
  e.stopPropagation();
  filterDropdown.classList.toggle('show');
});

filterOptions.forEach(opt => {
  opt.addEventListener('click', function(e) {
    e.preventDefault(); e.stopPropagation();
    filterOptions.forEach(o => o.textContent = o.textContent.replace(/^✅\s*/, ''));
    selectedDept = this.textContent;
    this.textContent = '✅ ' + selectedDept;
    hideSuggestions();
    searchInput.focus();
  });
});

// ===== User dropdown toggle =====
const userBtn      = document.getElementById('user-btn');
const userDropdown = document.getElementById('user-dropdown');

userBtn.addEventListener('click', e => {
  e.stopPropagation();
  userDropdown.classList.toggle('show');
});

document.addEventListener('click', () => {
  filterDropdown.classList.remove('show');
  userDropdown.classList.remove('show');
});

// ===== Legacy toggles if needed =====
function toggleFilter() {
  filterDropdown.style.display =
    filterDropdown.style.display === 'block' ? 'none' : 'block';
}
function toggleDropdown() {
  userDropdown.style.display =
    userDropdown.style.display === 'block' ? 'none' : 'block';
}

/* ============== */
/* Page-Specific JS */
/* ============== */

// Handle Make Appointment button
document
  .getElementById('make-appointment')
  .addEventListener('click', () => {
    console.log('Make Appointment clicked');
  });
