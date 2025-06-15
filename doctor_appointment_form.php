<?php
// doctor_appointment_form.php
session_start();
include '../../backend/db_connect.php';

// Ensure doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header('Location: doctor_login.php');
    exit;
}
$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor name for notification message
$stmtD = $conn->prepare("SELECT doctor_name FROM signup_doctor WHERE doctor_id = ?");
$stmtD->bind_param("i", $doctor_id);
$stmtD->execute();
$stmtD->bind_result($doctorName);
$stmtD->fetch();
$stmtD->close();

// Validate and load notification + patient info
if (!isset($_GET['noti_id']) || !is_numeric($_GET['noti_id'])) {
    die('Invalid notification ID.');
}
$d_noti_id = (int)$_GET['noti_id'];

$sql = "
  SELECT dn.patient_id, sp.patient_name
  FROM doctor_notification AS dn
  JOIN signup_patient     AS sp ON dn.patient_id = sp.patient_id
  WHERE dn.d_noti_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $d_noti_id);
$stmt->execute();
$stmt->bind_result($patient_id, $patientName);
if (!$stmt->fetch()) {
    die('Notification not found.');
}
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect inputs
    $day      = $_POST['d_app_f_day'];
    $start    = $_POST['d_app_f_starttime'];
    $end      = $_POST['d_app_f_endtime'];
    $date     = $_POST['d_app_f_date'];
    $meetlink = $_POST['d_app_f_meetlink'];

    // 1) Insert into doctor_appointment_form
    $insForm = "
      INSERT INTO doctor_appointment_form
        (d_noti_id, p_noti_id, d_app_f_day,
         d_app_f_starttime, d_app_f_endtime,
         d_app_f_date, d_app_f_meetlink)
      VALUES (?,       0,         ?,
              ?,             ?,             ?,            ?)
    ";
    $pf = $conn->prepare($insForm);
    $pf->bind_param(
      "isssss",
      $d_noti_id,
      $day,
      $start,
      $end,
      $date,
      $meetlink
    );
    $pf->execute();
    // get the new appointment form id
    $d_app_f_id = $conn->insert_id;
    $pf->close();

    // 2) Insert into patient_notification
    $msg     = "{$doctorName} approved your appointment. Please check ‘My Appointments’ tab for details.";
    $created = date('Y-m-d H:i:s');

    $insNoti = "
      INSERT INTO patient_notification
        (d_app_id, patient_id, p_noti_msg, p_noti_created_at)
      VALUES (?,        ?,          ?,           ?)
    ";
    $pn = $conn->prepare($insNoti);
    $pn->bind_param(
      "iiss",
      $d_app_f_id,
      $patient_id,
      $msg,
      $created
    );
    $pn->execute();
    $pn->close();

    // Redirect to doctor dashboard
    header('Location: doctor_dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Appointment Form – TeliLine</title>
  <link rel="stylesheet" href="../css/doctor_header.css" />
  <link rel="stylesheet" href="../css/doctor_footer.css" />
  <link rel="stylesheet" href="../css/doctor_appointment_form.css"/>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
  />
  <script src="../js/doctor_header.js" defer></script>
</head>
<body>

<?php include 'doctor_header.php'; ?>

<main class="container">
  <h2>Appointment Schedule for <?= htmlspecialchars($patientName) ?></h2>
  <div class="schedule-container">
    <form class="schedule-form" method="POST">
      <input type="hidden" name="d_noti_id" value="<?= $d_noti_id ?>">

      <label for="day">Select Day</label>
      <input
        type="text" id="day" name="d_app_f_day"
        placeholder="e.g. Monday" required
      />

      <label>Select Time</label>
      <div class="time-row">
        <input type="time" name="d_app_f_starttime" required />
        <span>to</span>
        <input type="time" name="d_app_f_endtime" required />
      </div>

      <label for="date">Select Date</label>
      <input type="date" id="date" name="d_app_f_date" required />

      <label for="meet">Add Meet Link</label>
      <input
        type="url" id="meet" name="d_app_f_meetlink"
        placeholder="https://meet.example.com/xyz" required
      />

      <button type="submit" class="send-btn">Send</button>
    </form>

    <div class="cancel-container">
      <button
        class="cancel-btn"
        onclick="window.history.back(); return false;"
      >
        Cancel
      </button>
    </div>
  </div>
</main>

<?php include 'doctor_footer.php'; ?>

<script src="../js/doctor_appointment_form.js"></script>
</body>
</html>
