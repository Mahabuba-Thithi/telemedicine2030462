<?php
// doctor_my_appointment.php
session_start();
include '../../backend/db_connect.php';

// Ensure doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header('Location: doctor_login.php');
    exit;
}
$doctor_id = $_SESSION['doctor_id'];

// Handle cancellation
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['d_app_f_id'])) {
    $delId = (int)$_POST['d_app_f_id'];
    $del = $conn->prepare("DELETE FROM doctor_appointment_form WHERE d_app_f_id = ?");
    $del->bind_param("i", $delId);
    $del->execute();
    $del->close();
    header("Location: doctor_my_appointment.php");
    exit;
}

// Fetch this doctor’s appointments
$sql = "
  SELECT
    daf.d_app_f_id,
    daf.d_app_f_day,
    daf.d_app_f_date,
    daf.d_app_f_starttime,
    daf.d_app_f_endtime,
    daf.d_app_f_meetlink,
    sp.patient_name,
    sp.patient_email
  FROM doctor_appointment_form AS daf
  JOIN doctor_notification AS dn
    ON daf.d_noti_id = dn.d_noti_id
  JOIN signup_patient AS sp
    ON dn.patient_id = sp.patient_id
  WHERE dn.doctor_id = ?
  ORDER BY daf.d_app_f_date DESC, daf.d_app_f_starttime DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$res = $stmt->get_result();
$apps = $res->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Appointments – TeliLine</title>

  <!-- Icons -->
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
  <!-- Header & Footer -->
  <link rel="stylesheet" href="../css/doctor_header.css" />
  <link rel="stylesheet" href="../css/doctor_footer.css" />
  <!-- Page CSS -->
  <link rel="stylesheet" href="../css/doctor_my_appointment.css" />

  <!-- Header JS -->
  <script src="../js/doctor_header.js" defer></script>
</head>
<body>
<?php include 'doctor_header.php'; ?>

<main>
  <h1 class="main-title">My Appointments</h1>

  <div class="grid-container">
    <?php if (empty($apps)): ?>
      <p style="grid-column:1/-1; text-align:center;">No appointments found.</p>
    <?php else: ?>
      <?php foreach ($apps as $a): ?>
        <div class="appointment-box">
          <h3>Patient: <?= htmlspecialchars($a['patient_name']) ?></h3>
          <p><strong>Date:</strong> <?= htmlspecialchars($a['d_app_f_date']) ?></p>
          <p><strong>Day:</strong> <?= htmlspecialchars($a['d_app_f_day']) ?></p>
          <p><strong>Time:</strong>
            <?= htmlspecialchars($a['d_app_f_starttime']) ?> –
            <?= htmlspecialchars($a['d_app_f_endtime']) ?>
          </p>
          <p>
            <strong>Meet Link:</strong>
            <a href="<?= htmlspecialchars($a['d_app_f_meetlink']) ?>" target="_blank">
              <?= htmlspecialchars($a['d_app_f_meetlink']) ?>
            </a>
          </p>
          <div class="btn-row">
            <form method="POST" style="margin:0">
              <input type="hidden" name="d_app_f_id" value="<?= $a['d_app_f_id'] ?>">
              <button type="submit" class="cancel-btn">Cancel Appointment</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</main>

<?php include 'doctor_footer.php'; ?>

</body>
</html>
