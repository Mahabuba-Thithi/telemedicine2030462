<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>prescription - TeliLine</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <link rel="stylesheet" href="../css/doctor_header.css" />
  <link rel="stylesheet" href="../css/doctor_footer.css" />
  <link rel="stylesheet" href="../css/prescription.css"/>
  <script src="../js/doctor_header.js" defer></script>
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/> -->
</head>
<body>

<?php include 'doctor_header.php'; ?>

 <!-- Main Content -->
 <main class="main-container">
    <h2>Suggestion Prescription</h2>
    <textarea id="prescriptionText" placeholder="Write your suggested prescription here..."></textarea>
    <button id="sendBtn">Send</button>
  </main>
  
<?php include 'doctor_footer.php'; ?>

<script src="../js/prescription.js" defer></script>

</body>
</html>
