<?php
// doctor_my_patients_tab.php
session_start();
include '../../backend/db_connect.php';

// 1) Ensure doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
  header('Location: doctor_login.php');
  exit;
}
$doctor_id = $_SESSION['doctor_id'];

// 2) Fetch only patients connected to this doctor
$sql = "
  SELECT DISTINCT sp.patient_id,
         sp.patient_name,
         sp.patient_email
    FROM doctor_notification AS dn
    JOIN signup_patient      AS sp
      ON dn.patient_id = sp.patient_id
   WHERE dn.doctor_id = ?
   ORDER BY dn.d_noti_created_at DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$res = $stmt->get_result();
$patients = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Patients – TeliLine</title>

  <!-- Font Awesome -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <!-- Styles -->
  <link rel="stylesheet" href="../css/doctor_dashboard.css"/>
  <link rel="stylesheet" href="../css/doctor_header.css" />
  <link rel="stylesheet" href="../css/doctor_footer.css" />
  <link rel="stylesheet" href="../css/doctor_my_patients_tab.css" />

  <!-- Scripts -->
  <script src="../js/doctor_header.js" defer></script>
  <script src="../js/doctor_my_patients_tab.js" defer></script>
</head>
<body>

<?php include 'doctor_header.php'; ?>

<main class="container">
  <h2 class="section-title">My Patients</h2>

  <?php if (empty($patients)): ?>
    <p class="no-patients">You have no connected patients yet.</p>
  <?php else: ?>
    <div class="patient-grid">
      <?php foreach ($patients as $p): ?>
        <div class="patient-card">
          <p><strong>Name:</strong> <?= htmlspecialchars($p['patient_name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($p['patient_email']) ?></p>
          <a href="patient_profile_doctor_pov.php?patient_id=<?= $p['patient_id'] ?>"
             class="view-btn">
            View Profile
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include 'doctor_footer.php'; ?>

</body>
</html>
