<?php
session_start();
include '../../backend/db_connect.php';

// Ensure patient is logged in
if (!isset($_SESSION['patient_id'])) {
  header('Location: patient_login.php');
  exit;
}
$patient_id = $_SESSION['patient_id'];

// Fetch this patient’s appointments (most recent first)
$sql = "
  SELECT
    daf.d_app_f_id,
    dn.doctor_id,
    sd.doctor_name,
    sd.doctor_email,
    daf.d_app_f_day,
    daf.d_app_f_date,
    daf.d_app_f_starttime,
    daf.d_app_f_endtime,
    daf.d_app_f_meetlink
  FROM doctor_appointment_form AS daf
  JOIN doctor_notification        AS dn
    ON daf.d_noti_id = dn.d_noti_id
  JOIN signup_doctor              AS sd
    ON dn.doctor_id = sd.doctor_id
  WHERE dn.patient_id = ?
  ORDER BY daf.d_app_f_date DESC, daf.d_app_f_starttime DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$res = $stmt->get_result();
$appointments = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Appointments – TeliLine</title>

  <!-- Styles -->
  <link rel="stylesheet" href="../css/patient_dashboard.css" />
  <link rel="stylesheet" href="../css/patient_header.css" />
  <link rel="stylesheet" href="../css/patient_footer.css" />
  <link rel="stylesheet" href="../css/patient_myappointment.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />

  <!-- Search + filter -->
  <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.js"></script>
  <script src="../js/patient_dashboard.js" defer></script>
  <!-- Header toggles -->
  <script src="../js/patient_header.js" defer></script>
  <!-- Appointment page logic -->
  <script src="../js/patient_myappointment.js" defer></script>
</head>
<body>

<?php include 'patient_header.php'; ?>

<main class="container">
  <h2 class="section-title">My Appointments</h2>

  <?php if (empty($appointments)): ?>
    <p style="text-align:center; margin-top:40px;">No appointments found.</p>
  <?php else: ?>
    <?php foreach ($appointments as $app): ?>
      <div class="appointment-card"
           data-date="<?= htmlspecialchars($app['d_app_f_date']) ?>"
           data-start="<?= htmlspecialchars($app['d_app_f_starttime']) ?>"
           data-end="<?= htmlspecialchars($app['d_app_f_endtime']) ?>"
           data-link="<?= htmlspecialchars($app['d_app_f_meetlink']) ?>">
        <div class="appointment-info">
          <p><strong>Doctor:</strong> <?= htmlspecialchars($app['doctor_name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($app['doctor_email']) ?></p>
          <p><strong>Day:</strong> <?= htmlspecialchars($app['d_app_f_day']) ?></p>
          <p><strong>Date:</strong> <?= htmlspecialchars($app['d_app_f_date']) ?></p>
          <p><strong>Time:</strong>
            <?= htmlspecialchars($app['d_app_f_starttime']) ?> –
            <?= htmlspecialchars($app['d_app_f_endtime']) ?>
          </p>
        </div>
        <div class="appointment-action">
          <button class="join-btn" disabled>Join</button>
        </div>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>
</main>

<?php include 'patient_footer.php'; ?>

</body>
</html>
