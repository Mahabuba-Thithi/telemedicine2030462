<?php
// patient_all_doctors_tab.php
session_start();
include '../../backend/db_connect.php';

// Ensure patient is logged in
if (!isset($_SESSION['patient_id'])) {
  header('Location: patient_login.php');
  exit;
}

// Validate & fetch department from query
$dept = $_GET['dept'] ?? '';
if (!$dept) {
  header('Location: patient_view_dept.php');
  exit;
}

// Fetch all doctors in this department
$sql = "
  SELECT doctor_id, doctor_name, doctor_email, doctor_designation
    FROM signup_doctor
   WHERE doctor_dept = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $dept);
$stmt->execute();
$res = $stmt->get_result();
$doctors = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($dept) ?> Doctors – TeliLine</title>

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
  <!-- Header & Footer CSS -->
  <link rel="stylesheet" href="../css/patient_dashboard.css"/>
  <link rel="stylesheet" href="../css/patient_header.css"/>
  <link rel="stylesheet" href="../css/patient_footer.css"/>
  <!-- This Page’s CSS -->
  <link rel="stylesheet" href="../css/patient_all_doctors_tab.css"/>

  <!-- Header JS -->
  <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.js"></script>
  <script src="../js/patient_dashboard.js" defer></script>
  <script src="../js/patient_header.js" defer></script>
</head>
<body>

  <?php include 'patient_header.php'; ?>

  <main class="docs-container">
    <h2>Department: <?= htmlspecialchars($dept) ?></h2>
    <div class="docs-grid">
      <?php foreach ($doctors as $doc): ?>
        <div class="doc-card">
          <h3><?= htmlspecialchars($doc['doctor_name']) ?></h3>
          <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($doc['doctor_email']) ?></p>
          <p><i class="fas fa-user-md"></i> <?= htmlspecialchars($doc['doctor_designation']) ?></p>
          <button class="view-profile-btn" data-id="<?= (int)$doc['doctor_id'] ?>">
            View Profile
          </button>
        </div>
      <?php endforeach; ?>
      <?php if (empty($doctors)): ?>
        <p class="no-docs">No doctors found in this department.</p>
      <?php endif; ?>
    </div>
  </main>

  <?php include 'patient_footer.php'; ?>

  <!-- This Page’s JS -->
  <script src="../js/patient_all_doctors_tab.js" defer></script>
</body>
</html>
