<?php
// frontend/php/general_entry_dashboard.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TeliLine Dashboard</title>
  <link rel="stylesheet" href="../css/general_entry_dashboard.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar">
    <div class="nav-left">
      <a href="general_entry_dashboard.php" class="logo">TeliLine</a>
      <a href="general_entry_dashboard.php" class="nav-item active">Home</a>
      <a href="about_us.php" class="nav-item">About Us</a>
      <a href="contact.php" class="nav-item">Contact</a>
    </div>
    <div class="nav-right">
      <button id="loginBtn" class="nav-btn">Login</button>
      <button id="signupBtn" class="nav-btn">Sign Up</button>
    </div>
  </nav>

  <!-- Login/Signup Popups -->
  <div id="loginPopup" class="popup hidden">
    <div class="popup-content">
      <p>Login</p>
      <button onclick="location.href='doctor_login.php'">Doctor</button>
      <button onclick="location.href='patient_login.php'">Patient</button>
      <span class="close" onclick="togglePopup('loginPopup')">&times;</span>
    </div>
  </div>
  <div id="signupPopup" class="popup hidden">
    <div class="popup-content">
      <p>Sign up </p>
      <button onclick="location.href='doctor_signup.php'">Doctor</button>
      <button onclick="location.href='patient_signup.php'">Patient</button>
      <span class="close" onclick="togglePopup('signupPopup')">&times;</span>
    </div>
  </div>

  <!-- Slider -->
  <section class="slider">
    <div class="slides">
      <img src="../images/slide1.jpg" class="slide" alt="Slide 1">
      <img src="../images/slide2.jpg" class="slide" alt="Slide 2">
      <img src="../images/slide3.jpg" class="slide" alt="Slide 3">
    </div>
    <button class="prev" onclick="changeSlide(-1)">&#10094;</button>
    <button class="next" onclick="changeSlide(1)">&#10095;</button>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-info">
      <p>© 2025 TeliLine. All rights reserved.</p>
      <p>123 Health St, Dhaka, Bangladesh | +880 1234-567890</p>
      <p>
        <a href="#">Facebook</a> |
        <a href="#">Twitter</a> |
        <a href="#">LinkedIn</a>
      </p>
    </div>
  </footer>

  <script src="../js/general_entry_dashboard.js"></script>
</body>
</html>
