<?php
// patient_profile_doctor_pov.php
session_start();
include '../../backend/db_connect.php';

// Ensure doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
  header('Location: doctor_login.php');
  exit;
}

// Validate patient_id
if (!isset($_GET['patient_id']) || !is_numeric($_GET['patient_id'])) {
  die('Invalid patient ID.');
}
$patient_id = (int)$_GET['patient_id'];

// Fetch patient details
$sql = "
  SELECT patient_name, patient_email, patient_DOB, patient_address, patient_phone_no
  FROM signup_patient
  WHERE patient_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$stmt->bind_result($name, $email, $dob, $address, $phone);
if (!$stmt->fetch()) {
  die('Patient not found.');
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Profile – TeliLine</title>

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <!-- Header & Footer CSS -->
  <link rel="stylesheet" href="../css/doctor_dashboard.css"/>
  <link rel="stylesheet" href="../css/doctor_header.css" />
  <link rel="stylesheet" href="../css/doctor_footer.css" />
  
  <!-- This Page’s CSS -->
  <link rel="stylesheet" href="../css/patient_profile_doctor_pov.css" />

  <!-- Header JS -->
  <script src="../js/doctor_header.js" defer></script>
  <!-- This Page’s JS -->
  <script src="../js/patient_profile_doctor_pov.js" defer></script>
</head>
<body>

<?php include 'doctor_header.php'; ?>

<main class="profile-container">
  <div class="profile-header">
    <h2>Patient Profile</h2>
    <a href="prescription.php?patient_id=<?= $patient_id ?>" class="btn-prescription">
      Add Prescription
    </a>  
  </div>

  <div class="profile-details">
    <div class="detail-row">
      <span class="label">Name:</span>
      <span class="value"><?= htmlspecialchars($name) ?></span>
    </div>
    <div class="detail-row">
      <span class="label">Email:</span>
      <span class="value"><?= htmlspecialchars($email) ?></span>
    </div>
    <div class="detail-row">
      <span class="label">Date of Birth:</span>
      <span class="value"><?= htmlspecialchars($dob) ?></span>
    </div>
    <div class="detail-row">
      <span class="label">Address:</span>
      <span class="value"><?= nl2br(htmlspecialchars($address)) ?></span>
    </div>
    <div class="detail-row">
      <span class="label">Phone:</span>
      <span class="value"><?= htmlspecialchars($phone) ?></span>
    </div>
  </div>
</main>

<?php include 'doctor_footer.php'; ?>

</body>
</html>
