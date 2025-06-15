<?php
session_start();
include 'db_connect.php';

// 1) Require patient login
if (!isset($_SESSION['patient_id'])) {
    http_response_code(403);
    exit('Access denied.');
}

// 2) Validate p_noti_id from URL
if (empty($_GET['p_noti_id']) || !ctype_digit($_GET['p_noti_id'])) {
    http_response_code(400);
    exit('Invalid prescription link.');
}
$p_noti_id  = (int) $_GET['p_noti_id'];
$patient_id = $_SESSION['patient_id'];

// 3) Fetch prescription via notification
$sql = "
    SELECT dp.pres_message AS pres_text,
           sd.doctor_name,
           sd.doctor_designation,
           sp.patient_name
      FROM d_prescription AS dp
      JOIN patient_notification AS pn ON dp.p_noti_id = pn.p_noti_id
      JOIN signup_doctor    AS sd ON dp.doctor_id = sd.doctor_id
      JOIN signup_patient   AS sp ON pn.patient_id = sp.patient_id
     WHERE dp.p_noti_id = ?
       AND pn.patient_id = ?
     LIMIT 1
";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die('Prepare failed: ' . $conn->error);
}
$stmt->bind_param('ii', $p_noti_id, $patient_id);
$stmt->execute();
$stmt->bind_result($pres_text, $doctor_name, $doctor_designation, $patient_name);

if (!$stmt->fetch()) {
    http_response_code(404);
    exit('Prescription not found.');
}
$stmt->close();

// 4) Build PDF with FPDF (core Helvetica)
require __DIR__ . '/fpdf/fpdf.php';
$pdf = new FPDF();
$pdf->AddPage();

// Title
$pdf->SetFont('Helvetica', '', 16);
$pdf->Cell(0, 10, 'Prescription', 0, 1, 'C');
$pdf->Ln(5);

// Doctor & Patient info
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(0, 8, "Dr. $doctor_name, $doctor_designation", 0, 1);
$pdf->Cell(0, 8, "Patient: $patient_name", 0, 1);
$pdf->Ln(5);

// Prescription text
$pdf->MultiCell(0, 7, $pres_text);

// Force download
$pdf->Output('D', "Prescription_{$p_noti_id}.pdf");
exit;
