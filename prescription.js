// doctor_header toggle (keep if you need it)
function toggleDropdown() {
  const dropdown = document.getElementById('dropdown');
  dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

// Prescription send handler
document.getElementById('sendBtn').addEventListener('click', () => {
  const prescription = document.getElementById('prescriptionText').value.trim();
  const urlParams = new URLSearchParams(window.location.search);
  const patient_id = urlParams.get('patient_id');

  if (!prescription) {
    alert('Please write something in the prescription.');
    return;
  }

  // Show loading state (optional)
  document.getElementById('sendBtn').disabled = true;

  fetch('../../backend/save_prescription.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `prescription=${encodeURIComponent(prescription)}&patient_id=${patient_id}`
  })
  .then(res => res.text())
  .then(data => {
    if (data.trim() === 'success') {
      showPopup('Prescription sent successfully!');
      setTimeout(() => {
        window.location.href = 'doctor_dashboard.php';
      }, 2000);
    } else {
      alert('Error: ' + data);
      document.getElementById('sendBtn').disabled = false;
    }
  })
  .catch(err => {
    alert('Network error');
    document.getElementById('sendBtn').disabled = false;
  });
});

function showPopup(message) {
  const popup = document.createElement('div');
  popup.innerText = message;
  Object.assign(popup.style, {
    position: 'fixed',
    top: '20px',
    right: '20px',
    padding: '10px 20px',
    background: '#4CAF50',
    color: 'white',
    borderRadius: '5px',
    zIndex: '9999'
  });
  document.body.appendChild(popup);
  setTimeout(() => popup.remove(), 1500);
}
