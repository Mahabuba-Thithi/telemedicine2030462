<?php
// doctor_profile_patient_pov.php
session_start();
include '../../backend/db_connect.php';

// Require patient login
if (!isset($_SESSION['patient_id'])) {
  header('Location: patient_login.php');
  exit;
}

// Get doctor_id from URL
if (!isset($_GET['doctor_id']) || !is_numeric($_GET['doctor_id'])) {
  die('Invalid doctor ID.');
}
$doctor_id = (int)$_GET['doctor_id'];

// Fetch doctor details
$sql = "SELECT doctor_name, doctor_email, doctor_license_number,
               doctor_dept, doctor_designation, doctor_edu_bg,
               doctor_exp, doctor_avail_time, doctor_avail_day,
               doctor_affiliation
        FROM signup_doctor
        WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result(
  $name, $email, $license, $dept, $designation,
  $edu, $exp, $avail_time, $avail_day, $affiliation
);
if (!$stmt->fetch()) {
  die('Doctor not found.');
}
$stmt->close();

// Fetch patient name for message
$pid = $_SESSION['patient_id'];
$sql2 = "SELECT patient_name FROM signup_patient WHERE patient_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $pid);
$stmt2->execute();
$stmt2->bind_result($patientName);
$stmt2->fetch();
$stmt2->close();

// Handle appointment request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $msg      = "{$patientName} has requested for an appointment";
  $created  = date('Y-m-d H:i:s');
  $d_app_id = 0; // placeholder if you add a real appointments table later

  $insert = "INSERT INTO doctor_notification 
               (patient_id, doctor_id, d_app_id, d_noti_msg, d_noti_created_at)
             VALUES (?, ?, ?, ?, ?)";
  $st = $conn->prepare($insert);
  $st->bind_param("iiiss", $pid, $doctor_id, $d_app_id, $msg, $created);
  $st->execute();
  $st->close();

  // set flash flag and redirect to avoid resubmit
  $_SESSION['flash_appointment_sent'] = true;
  header("Location: doctor_profile_patient_pov.php?doctor_id={$doctor_id}");
  exit;
}

// Capture and clear flash
$showToast = false;
if (!empty($_SESSION['flash_appointment_sent'])) {
  $showToast = true;
  unset($_SESSION['flash_appointment_sent']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Doctor Profile – TeliLine</title>
  <!-- Bootstrap 5 CSS -->
  <link rel="stylesheet" href="../css/patient_dashboard.css" />
  <link rel="stylesheet" href="../css/patient_header.css" />
  <link rel="stylesheet" href="../css/patient_footer.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/doctor_profile_patient_pov.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="../js/patient_dashboard.js" defer></script>
  <script src="../js/patient_header.js" defer></script>
</head>
<body>

<?php include 'patient_header.php'; ?>

<!-- Toast container -->
<div aria-live="polite" aria-atomic="true"
     class="position-fixed top-0 end-0 p-3" style="z-index: 1100">
  <div id="appointment-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">TeliLine</strong>
      <small>Now</small>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Your appointment request has been sent.
    </div>
  </div>
</div>

<main class="container my-5 pt-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Dr. <?= htmlspecialchars($name) ?></h2>
    <form method="POST" style="margin:0">
      <button type="submit" id="make-appointment" class="btn btn-primary">
        Make Appointment
      </button>
    </form>
  </div>

  <div class="card">
    <div class="card-body">
      <dl class="row mb-0">
        <dt class="col-sm-3">Email</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($email) ?></dd>

        <dt class="col-sm-3">License Number</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($license) ?></dd>

        <dt class="col-sm-3">Department</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($dept) ?></dd>

        <dt class="col-sm-3">Designation</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($designation) ?></dd>

        <dt class="col-sm-3">Education</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($edu)) ?></dd>

        <dt class="col-sm-3">Experience</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($exp)) ?></dd>

        <dt class="col-sm-3">Available Time</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($avail_time) ?></dd>

        <dt class="col-sm-3">Available Days</dt>
        <dd class="col-sm-9"><?= htmlspecialchars($avail_day) ?></dd>

        <dt class="col-sm-3">Affiliation</dt>
        <dd class="col-sm-9"><?= nl2br(htmlspecialchars($affiliation)) ?></dd>
      </dl>
    </div>
  </div>
</main>

<?php include 'patient_footer.php'; ?>

<!-- Bootstrap 5 JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/doctor_profile_patient_pov.js" defer></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    <?php if ($showToast): ?>
      const toastEl = document.getElementById('appointment-toast');
      const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
      toast.show();
    <?php endif; ?>
  });
</script>
</body>
</html>
