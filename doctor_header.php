<?php
// doctor_header.php
if (!isset($_SESSION)) session_start();
include '../../backend/db_connect.php';

// Doctor must be logged in already
$doctor_id = $_SESSION['doctor_id'] ?? 0;

// Fetch doctor name for display
$doctorName = 'Doctor';
if ($doctor_id) {
    $qdn = "SELECT doctor_name FROM signup_doctor WHERE doctor_id = ?";
    $sdn = $conn->prepare($qdn);
    $sdn->bind_param("i", $doctor_id);
    $sdn->execute();
    $sdn->bind_result($fetchedName);
    if ($sdn->fetch()) {
        $doctorName = $fetchedName;
    }
    $sdn->close();
}

// Fetch notifications (latest first) joined with patient name
$notis = [];
if ($doctor_id) {
  $q = "
    SELECT dn.d_noti_id,
           dn.d_noti_created_at,
           sp.patient_name
    FROM doctor_notification AS dn
    JOIN signup_patient    AS sp
      ON dn.patient_id = sp.patient_id
    WHERE dn.doctor_id = ?
    ORDER BY dn.d_noti_created_at DESC
  ";
  $s = $conn->prepare($q);
  $s->bind_param("i", $doctor_id);
  $s->execute();
  $res = $s->get_result();
  while ($row = $res->fetch_assoc()) {
    $notis[] = $row;
  }
  $s->close();
}

$hasNoti = count($notis) > 0;
?>

<header>
  <nav class="navbar">
    <div class="navbar-left">
      <a href="doctor_dashboard.php" class="logo"><strong>TeliLine</strong></a>
      <a href="doctor_dashboard.php">Home</a>
      <a href="doctor_my_appointment.php">My Appointments</a>
      <a href="about_us.php">About Us</a>
      <a href="contact.php">Contact</a>
    </div>
    
    <div class="navbar-right">
      <div class="notification-wrapper" onclick="toggleNotiDropdown()">
        <i class="fas fa-bell notification-icon"></i>
        <?php if ($hasNoti): ?>
          <span class="noti-dot"></span>
        <?php endif; ?>
      </div>
      <!-- always shows the fetched doctor name -->
      <span class="username"><?= htmlspecialchars($doctorName) ?></span>
      <div class="user-dropdown">
        <i class="fas fa-user-circle" onclick="toggleDropdown()"></i>
        <ul id="dropdown-menu">
          <!-- Updated My Profile link: -->
          <li><a href="doctor_myprofile.php">My Profile</a></li>
          <li><a href="doctor_my_patients_tab.php">My Patients</a></li>
          <li><a href="logout.php">Logout</a></li>
        </ul>
      </div>
    </div>

    <div id="noti-dropdown" class="noti-dropdown">
      <?php if ($hasNoti): ?>
        <ul>
          <?php foreach ($notis as $n): ?>
            <li>
              <div class="noti-text">
                <strong><?= htmlspecialchars($n['patient_name']) ?></strong>
                has requested an appointment
                <span class="time"><?= htmlspecialchars($n['d_noti_created_at']) ?></span>
              </div>
              <div class="noti-actions">
                <a href="doctor_appointment_form.php?noti_id=<?= (int)$n['d_noti_id'] ?>"
                   class="btn btn-sm btn-accept">Accept</a>
                <form method="POST" action="delete_notification.php" style="display:inline">
                  <input type="hidden" name="d_noti_id" value="<?= (int)$n['d_noti_id'] ?>">
                  <button type="submit" class="btn btn-sm btn-reject">Reject</button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <div class="no-noti">No new notifications</div>
      <?php endif; ?>
    </div>
  </nav>
</header>
