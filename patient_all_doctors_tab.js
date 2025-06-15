// patient_all_doctors_tab.js

// Attach click handlers to each "View Profile" button
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.view-profile-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const docId = btn.dataset.id;
      // Redirect patient to the doctor's profile page
      window.location.href = `doctor_profile_patient_pov.php?doctor_id=${docId}`;
    });
  });
});
