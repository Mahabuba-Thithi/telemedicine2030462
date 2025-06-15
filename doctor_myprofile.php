<?php
// doctor_myprofile.php
session_start();
include '../../backend/db_connect.php';

// Ensure doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
  header('Location: doctor_login.php');
  exit;
}
$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor details
$sql = "
  SELECT doctor_name,
         doctor_email,
         doctor_DOB,
         doctor_license_number,
         doctor_dept,
         doctor_designation,
         doctor_edu_bg,
         doctor_exp,
         doctor_avail_time,
         doctor_avail_day,
         doctor_affiliation
    FROM signup_doctor
   WHERE doctor_id = ?
   LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result(
  $name, $email, $dob, $license,
  $dept, $designation, $edu, $exp,
  $availTime, $availDay, $affiliation
);
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
  <link rel="stylesheet" href="../css/doctor_dashboard.css"/>
  <link rel="stylesheet" href="../css/doctor_header.css"/>
  <link rel="stylesheet" href="../css/doctor_footer.css"/>
  <!-- This Page’s CSS -->
  <link rel="stylesheet" href="../css/doctor_myprofile.css"/>

  <!-- Header JS -->
  <script src="../js/doctor_header.js" defer></script>
</head>
<body>

  <?php include 'doctor_header.php'; ?>

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
        <span class="label"><i class="fas fa-calendar"></i> DOB:</span>
        <span class="value"><?= htmlspecialchars($dob) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-id-card"></i> License #:</span>
        <span class="value"><?= htmlspecialchars($license) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-stethoscope"></i> Department:</span>
        <span class="value"><?= htmlspecialchars($dept) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-user-md"></i> Designation:</span>
        <span class="value"><?= htmlspecialchars($designation) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-graduation-cap"></i> Education:</span>
        <span class="value"><?= nl2br(htmlspecialchars($edu)) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-briefcase"></i> Experience:</span>
        <span class="value"><?= nl2br(htmlspecialchars($exp)) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-clock"></i> Available Time:</span>
        <span class="value"><?= htmlspecialchars($availTime) ?> on <?= htmlspecialchars($availDay) ?></span>
      </div>
      <div class="field">
        <span class="label"><i class="fas fa-hospital"></i> Affiliation:</span>
        <span class="value"><?= nl2br(htmlspecialchars($affiliation)) ?></span>
      </div>
    </div>
  </main>

  <?php include 'doctor_footer.php'; ?>

</body>
</html>
