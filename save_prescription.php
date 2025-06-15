<?php
session_start();
include 'db_connect.php';

// 1) Validate doctor & form data
if (!isset($_SESSION['doctor_id'], $_POST['prescription'], $_POST['patient_id'])) {
    http_response_code(400);
    exit('Invalid request');
}

$doctor_id  = $_SESSION['doctor_id'];
$patient_id = (int) $_POST['patient_id'];
$pres_text  = trim($_POST['prescription']);

// 2) Get doctor’s name & designation
$stmt = $conn->prepare("
    SELECT doctor_name, doctor_designation
      FROM signup_doctor
     WHERE doctor_id = ?
");
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$stmt->bind_result($docName, $docDesignation);
$stmt->fetch();
$stmt->close();

// 3) Insert a new notification (placeholder link uses p_noti_id=0)
$placeholder = htmlspecialchars($docName)
             . " (" . htmlspecialchars($docDesignation) . ") gave you a prescription. "
             . '<a href="/Telimedicine/backend/generate_prescription_pdf.php?p_noti_id=0">'
             . 'Click to download</a>';

$stmt = $conn->prepare("
    INSERT INTO patient_notification
      (d_app_id, patient_id, p_noti_msg, p_noti_created_at)
    VALUES
      (?, ?, ?, NOW())
");
$d_app_id = 0;
$stmt->bind_param("iis", $d_app_id, $patient_id, $placeholder);
$stmt->execute();
$new_p_noti_id = $stmt->insert_id; 
$stmt->close();

// 4) Insert the prescription row linking to that notification
$stmt = $conn->prepare("
    INSERT INTO d_prescription
      (doctor_id, p_noti_id, pres_message)
    VALUES
      (?, ?, ?)
");
$stmt->bind_param("iis", $doctor_id, $new_p_noti_id, $pres_text);
$stmt->execute();
$stmt->close();

// 5) Update notification to use the real p_noti_id in its download link
$finalMsg = htmlspecialchars($docName)
          . " (" . htmlspecialchars($docDesignation) . ") gave you a prescription. "
          . '<a href="/Telimedicine/backend/generate_prescription_pdf.php?p_noti_id='
          . $new_p_noti_id . '">Click to download</a>';

$stmt = $conn->prepare("
    UPDATE patient_notification
       SET p_noti_msg = ?
     WHERE p_noti_id = ?
");
$stmt->bind_param("si", $finalMsg, $new_p_noti_id);
$stmt->execute();
$stmt->close();

echo 'success';
