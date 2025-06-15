/* ============================= */
/* ===== PATIENT APPOINTMENTS === */
/* ============================= */

document.addEventListener('DOMContentLoaded', () => {
  // For each appointment card, decide if Join button should be active
  const cards = document.querySelectorAll('.appointment-card');
  const now = new Date();

  cards.forEach(card => {
    const dateStr  = card.dataset.date;      // e.g. "2025-05-15"
    const startStr = card.dataset.start;     // e.g. "14:00:00"
    const endStr   = card.dataset.end;       // e.g. "14:30:00"
    const link     = card.dataset.link;

    // Parse appointment date
    const [y,m,d] = dateStr.split('-').map(Number);
    // Parse times
    const [sh, sm, ss] = startStr.split(':').map(Number);
    const [eh, em, es] = endStr.split(':').map(Number);

    // Construct Date objects in local timezone
    const start = new Date(y, m-1, d, sh, sm, ss);
    const end   = new Date(y, m-1, d, eh, em, es);

    const btn = card.querySelector('.join-btn');
    // Activate if now is between start and end
    if (now >= start && now <= end) {
      btn.disabled = false;
      btn.classList.add('active');
      btn.addEventListener('click', () => {
        window.open(link, '_blank');
      });
    }
  });
});
