<?php
session_start();
include '../../backend/db_connect.php';

if (!isset($_SESSION['patient_id'])) {
  header('Location: patient_login.php');
  exit;
}

$patient_id = $_SESSION['patient_id'];
$sql = "SELECT patient_name FROM signup_patient WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Patient Dashboard – TeliLine</title>
  <link rel="stylesheet" href="../css/patient_dashboard.css" />
  <link rel="stylesheet" href="../css/patient_header.css" />
  <link rel="stylesheet" href="../css/patient_footer.css" />
  <script src="https://cdn.jsdelivr.net/npm/fuse.js/dist/fuse.min.js"></script>
  <script src="../js/patient_dashboard.js" defer></script>
  <script src="../js/patient_header.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<?php include 'patient_header.php'; ?>

<main>
  <div class="slider">
    <div class="slide active">
      <img src="../images/slide1.jpg" alt="Slide 1" />
    </div>
    <div class="slide">
      <img src="../images/slide2.jpg" alt="Slide 2" />
    </div>
    <div class="slide">
      <img src="../images/slide3.jpg" alt="Slide 3" />
    </div>
  </div>
</main>

<?php include 'patient_footer.php'; ?>

</body>
</html>
