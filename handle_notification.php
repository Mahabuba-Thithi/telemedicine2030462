<?php
// handle_notification.php
session_start();
include '../../backend/db_connect.php';

if (!isset($_SESSION['doctor_id'])) {
  http_response_code(403);
  echo json_encode(['error'=>'Not logged in']);
  exit;
}

if (!isset($_GET['action'], $_GET['d_noti_id'])) {
  http_response_code(400);
  echo json_encode(['error'=>'Invalid request']);
  exit;
}

$action    = $_GET['action'];        // 'accept' or 'reject'
$d_noti_id = (int)$_GET['d_noti_id'];

// Delete the notification
$del = $conn->prepare("DELETE FROM doctor_notification WHERE d_noti_id = ?");
$del->bind_param("i", $d_noti_id);
$ok = $del->execute();
$del->close();

if (!$ok) {
  http_response_code(500);
  echo json_encode(['error'=>'DB delete failed']);
  exit;
}

// Return JSON success
header('Content-Type: application/json');
echo json_encode(['success'=>true]);
exit;
