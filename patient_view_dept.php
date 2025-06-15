<?php
// patient_view_dept.php
session_start();
include '../../backend/db_connect.php';

// Ensure patient is logged in
if (!isset($_SESSION['patient_id'])) {
  header('Location: patient_login.php');
  exit;
}

// Hard-coded departments for now
$departments = [
  ['name'=>'Cardiology',  'brief'=>'Deals with conditions of the heart and blood vessels.'],
  ['name'=>'Dermatology', 'brief'=>'Treats skin, hair, and nail disorders.'],
  ['name'=>'Neurology',   'brief'=>'Focuses on the brain and nervous system.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>All Departments – TeliLine</title>

  <!-- Font Awesome -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <!-- Header & Footer CSS -->
  <link rel="stylesheet" href="../css/patient_dashboard.css"/>
  <link rel="stylesheet" href="../css/patient_header.css"/>
  <link rel="stylesheet" href="../css/patient_footer.css"/>
  <!-- This Page’s CSS -->
  <link rel="stylesheet" href="../css/patient_view_dept.css"/>

  <!-- Header JS -->
  <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.js"></script>
  <script src="../js/patient_dashboard.js" defer></script>
  <script src="../js/patient_header.js" defer></script>
</head>
<body>

  <?php include 'patient_header.php'; ?>

  <main class="dept-container">
    <h2>All Departments</h2>
    <div class="dept-grid">
      <?php foreach ($departments as $d): ?>
        <div class="dept-card">
          <h3><?= htmlspecialchars($d['name']) ?></h3>
          <p><?= htmlspecialchars($d['brief']) ?></p>
          <button
            class="view-doc-btn"
            onclick="window.location.href='patient_all_doctors_tab.php?dept=<?= urlencode($d['name']) ?>'"
          >
            View Doctors
          </button>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <?php include 'patient_footer.php'; ?>

  <!-- This Page’s JS -->
  <script src="../js/patient_view_dept.js" defer></script>
</body>
</html>
