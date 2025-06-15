<?php
// patient_header.php
if (!isset($_SESSION)) session_start();
include '../../backend/db_connect.php';

// Logged-in patient
$patient_id = $_SESSION['patient_id'] ?? 0;

// Fetch patient name
$username = 'User';
if ($patient_id) {
    $q = "SELECT patient_name FROM signup_patient WHERE patient_id = ?";
    $s = $conn->prepare($q);
    $s->bind_param("i", $patient_id);
    $s->execute();
    $s->bind_result($fetchedName);
    if ($s->fetch()) $username = $fetchedName;
    $s->close();
}

// Fetch notifications (including p_noti_id)
$notis = [];
if ($patient_id) {
    $q = "
      SELECT p_noti_id, p_noti_msg, p_noti_created_at
      FROM patient_notification
      WHERE patient_id = ?
      ORDER BY p_noti_created_at DESC
    ";
    $s = $conn->prepare($q);
    $s->bind_param("i", $patient_id);
    $s->execute();
    $res = $s->get_result();
    while ($row = $res->fetch_assoc()) {
        $notis[] = $row;
    }
    $s->close();
}
$hasNoti = (bool)$notis;
?>

<header>
  <nav class="navbar">
    <div class="nav-left">
      <a href="patient_dashboard.php" class="logo">TeliLine</a>
      <ul class="nav-links">
        <li><a href="patient_dashboard.php">Home</a></li>
        <li><a href="patient_myappointment.php">My Appointments</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>

    <div class="nav-center">
      <div class="search-wrapper">
        <input type="text" id="search-input" placeholder="enter doctor name">
        <div id="suggestions" class="suggestions-box"></div>
      </div>
      <div class="filter-container">
        <button id="filter-btn" class="filter-btn" aria-label="Filter">
          <div class="filter-icon"><span></span><span></span><span></span></div>
        </button>
        <ul id="filter-dropdown" class="dropdown-content">
          <li><a href="#" class="filter-option">Cardiology</a></li>
          <li><a href="#" class="filter-option">Dermatology</a></li>
          <li><a href="#" class="filter-option">Neurology</a></li>
          <li>
            <button
              class="filter-option view-dept-btn"
              onclick="window.location.href='patient_view_dept.php'"
              style="font-weight:bold; width:100%; text-align:left; padding:8px 15px; border:none; background:transparent; cursor:pointer;">
              View All Dept
            </button>
          </li>
        </ul>
      </div>
    </div>

    <div class="nav-right">
      <div class="notification-wrapper" onclick="togglePatientNoti()">
        <i class="fas fa-bell notification-icon"></i>
        <?php if ($hasNoti): ?>
          <span class="noti-dot"></span>
        <?php endif; ?>
      </div>
      <span id="username"><?= htmlspecialchars($username) ?></span>
      <div class="user-container">
        <button id="user-btn" class="user-btn" aria-label="User Menu">&#128100;</button>
        <ul id="user-dropdown" class="dropdown-content">
          <!-- UPDATED My Profile link: -->
          <li><a href="patient_myprofile.php">My Profile</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div id="patient-noti-dropdown" class="dropdown-content patient-noti-dropdown">
    <?php if ($hasNoti): ?>
      <ul>
        <?php foreach ($notis as $n): ?>
          <li>
            <?php
              $plain = strip_tags($n['p_noti_msg']);
              if (stripos($plain, 'prescription') !== false):
            ?>
              <div><?= htmlspecialchars($plain) ?></div>
              <a href="/Telimedicine/backend/generate_prescription_pdf.php?p_noti_id=<?= (int)$n['p_noti_id'] ?>"
                 target="_blank">
                Click to download
              </a>
            <?php else: ?>
              <div><?= htmlspecialchars($plain) ?></div>
            <?php endif; ?>
            <span class="time"><?= htmlspecialchars($n['p_noti_created_at']) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php else: ?>
      <div class="no-noti">No new notifications</div>
    <?php endif; ?>
  </div>
</header>

<script>
  function togglePatientNoti() {
    document.getElementById('patient-noti-dropdown').classList.toggle('show');
  }
</script>
