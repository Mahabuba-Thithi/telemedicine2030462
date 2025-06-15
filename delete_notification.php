<?php
// delete_notification.php
session_start();
include '../../backend/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['d_noti_id'])) {
  $id = (int)$_POST['d_noti_id'];
  $stmt = $conn->prepare("DELETE FROM doctor_notification WHERE d_noti_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();
}

// Redirect back to the referring page or dashboard
$ref = $_SERVER['HTTP_REFERER'] ?? 'doctor-dashboard.php';
header("Location: {$ref}");
exit;
