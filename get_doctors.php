<?php
// backend/get_doctors.php
header('Content-Type: application/json');
include 'db_connect.php';

$sql = "SELECT doctor_id, doctor_name, doctor_dept FROM signup_doctor";
$result = $conn->query($sql);

$doctors = [];
while ($row = $result->fetch_assoc()) {
    $doctors[] = $row;
}

echo json_encode($doctors);
$conn->close();
