<?php
// doctor-dashboard.php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['doctor_id'])) {
    header('Location: doctor_login.php');
    exit;
}

// Include DB connection
include '../../backend/db_connect.php';

// Fetch the logged-in doctor’s name
$docId = $_SESSION['doctor_id'];
$sql = "SELECT doctor_name FROM signup_doctor WHERE doctor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $docId);
$stmt->execute();
$stmt->bind_result($doctorName);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Doctor Dashboard – TeliLine</title>
  <link rel="stylesheet" href="../css/doctor_dashboard.css"/>
  <link rel="stylesheet" href="../css/doctor_header.css" />
  <link rel="stylesheet" href="../css/doctor_footer.css" />
  <script src="../js/doctor_dashboard.js" defer></script>
  <script src="../js/doctor_header.js" defer></script>
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
</head>
<body>

<?php include 'doctor_header.php'; ?>

<!-- Slider -->
<main>
  <div class="slider">
    <div class="slide active">
      <img src="../images/slide1.jpg" alt="Slide 1">
    </div>
    <div class="slide">
      <img src="../images/slide2.jpg" alt="Slide 2">
    </div>
    <div class="slide">
      <img src="../images/slide3.jpg" alt="Slide 3">
    </div>
  </div>
</main>

<!-- Footer -->
<?php include 'doctor_footer.php'; ?>

</body>
</html>
