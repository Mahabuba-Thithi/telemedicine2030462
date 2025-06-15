<?php
// patient_myprofile.php
session_start();
include '../../backend/db_connect.php';

// Ensure patient is logged in
if (!isset($_SESSION['patient_id'])) {
  header('Location: patient_login.php');
  exit;
}
$patient_id = $_SESSION['patient_id'];

// Fetch patient details
$sql = "
  SELECT patient_name, patient_email, patient_DOB, patient_address, patient_phone_no
    FROM signup_patient
   WHERE patient_id = ?
   LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$stmt->bind_result($name, $email, $dob, $address, $phone);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Profile – TeliLine</title>

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
  <link rel="stylesheet" href="../css/patient_myprofile.css"/>

  <!-- Header JS -->
  <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.js"></script>
  <script src="../js/patient_dashboard.js" defer></script>
  <script src="../js/patient_header.js" defer></script>
</head>
<body>

  <?php include 'patient_header.php'; ?>

  <main class="profile-container">
    <h2>My Profile</h2>
    <div class="profile-card">
      <div class="field">
        <span class="label"><i class="fas fa-user"></i> Name:</span>
        <span class="value"><?= htmlspecialchars($name) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-envelope"></i> Email:</span>
        <span class="value"><?= htmlspecialchars($email) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-calendar"></i> Date of Birth:</span>
        <span class="value"><?= htmlspecialchars($dob) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-map-marker-alt"></i> Address:</span>
        <span class="value"><?= nl2br(htmlspecialchars($address)) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-phone"></i> Phone:</span>
        <span class="value"><?= htmlspecialchars($phone) ?></span>
      </div>
    </div>
  </main>

  <?php include 'patient_footer.php'; ?>

  <!-- This Page’s JS -->
  <script src="../js/patient_myprofile.js" defer></script>
</body>
</html>
