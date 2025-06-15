/* ============================= */
/* ===== PATIENT HEADER JS ===== */
/* ============================= */

document.addEventListener('DOMContentLoaded', () => {
  // ===== Autocomplete & Fuse.js setup =====
  let doctors = [];
  let fuse = null;
  let selectedDept = '';
  const MAX_SUGGESTIONS = 7;
  const searchInput    = document.getElementById('search-input');
  const suggestionsBox = document.getElementById('suggestions');

  fetch('/Telimedicine/backend/get_doctors.php')
    .then(res => res.ok ? res.json() : Promise.reject(res.status))
    .then(data => {
      doctors = data;
      fuse = new Fuse(doctors, {
        keys: ['doctor_name'],
        threshold: 0.4
      });
    })
    .catch(err => console.error('Error fetching doctors:', err));

  function showSuggestions(list) {
    suggestionsBox.innerHTML = list.length
      ? list.map(d => `<div data-id="${d.doctor_id}">${d.doctor_name}</div>`).join('')
      : `<div>No match found</div>`;
    suggestionsBox.style.display = 'block';
  }
  function hideSuggestions() {
    suggestionsBox.style.display = 'none';
  }

  searchInput.addEventListener('input', () => {
    const term = searchInput.value.trim();
    if (!term || !fuse) return hideSuggestions();
    let results = fuse.search(term).map(r => r.item);
    if (selectedDept) {
      results = results.filter(d => d.doctor_dept === selectedDept);
    }
    showSuggestions(results.slice(0, MAX_SUGGESTIONS));
  });

  suggestionsBox.addEventListener('click', e => {
    if (e.target.tagName === 'DIV' && e.target.dataset.id) {
      window.location.href = `/Telimedicine/frontend/php/doctor_profile_patient_pov.php?doctor_id=${e.target.dataset.id}`;
    }
  });

  document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
      hideSuggestions();
    }
  });

  // ===== Filter dropdown =====
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
      filterOptions.forEach(o => o.textContent = o.textContent.replace(/^✔️\s*/, ''));
      selectedDept = this.textContent;
      this.textContent = '✔️ ' + selectedDept;
      hideSuggestions();
      searchInput.focus();
    });
  });

  // ===== User menu toggle =====
  const userBtn      = document.getElementById('user-btn');
  const userDropdown = document.getElementById('user-dropdown');
  userBtn.addEventListener('click', e => {
    e.stopPropagation();
    userDropdown.classList.toggle('show');
  });

  // ===== Patient notifications toggle =====
  const notiWrapper = document.querySelector('.notification-wrapper');
  const notiDropdown = document.getElementById('patient-noti-dropdown');
  notiWrapper.addEventListener('click', e => {
    e.stopPropagation();
    const isOpen = notiDropdown.style.display === 'block';
    notiDropdown.style.display = isOpen ? 'none' : 'block';
    if (!isOpen) {
      const dot = document.querySelector('.notification-wrapper .noti-dot');
      if (dot) dot.remove();
    }
  });

  // ===== Close dropdowns on outside click =====
  document.addEventListener('click', () => {
    filterDropdown.classList.remove('show');
    userDropdown.classList.remove('show');
    if (notiDropdown.style.display === 'block') {
      notiDropdown.style.display = 'none';
    }
  });
});
