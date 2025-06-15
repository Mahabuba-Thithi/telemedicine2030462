<?php
// backend/handle_doctor_signup.php
session_start();
include 'db_connect.php';  // connects $conn to telimedicine DB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Collect & sanitize
    $name            = trim($_POST['name']);
    $designation     = trim($_POST['designation']);
    $education       = trim($_POST['education']);
    $experience      = trim($_POST['experience']);
    $available_time  = trim($_POST['available_time']);
    $avail_day       = trim($_POST['avail_day']);       // new text field
    $affiliation     = trim($_POST['affiliation']);
    $email           = trim($_POST['email']);
    $password_plain  = $_POST['password'];
    $dob             = $_POST['dob'];
    $licence         = trim($_POST['licence']);
    $dept            = trim($_POST['department']);

    // 2. Hash password
    $password_hashed = password_hash($password_plain, PASSWORD_DEFAULT);

    // 3. Prepare & execute INSERT (now including doctor_name)
    $sql = "INSERT INTO signup_doctor
      (doctor_name,
       doctor_email,
       doctor_password,
       doctor_DOB,
       doctor_license_number,
       doctor_dept,
       doctor_designation,
       doctor_edu_bg,
       doctor_exp,
       doctor_avail_time,
       doctor_avail_day,
       doctor_affiliation)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param(
          "ssssssssssss",
          $name,
          $email,
          $password_hashed,
          $dob,
          $licence,
          $dept,
          $designation,
          $education,
          $experience,
          $available_time,
          $avail_day,
          $affiliation
        );

        if ($stmt->execute()) {
            // 4. On success, send to doctor login
            header("Location: ../frontend/php/doctor_login.php");
            exit();
        } else {
            // 5. On error (e.g. duplicate email), show a message
            echo "Signup failed: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    } else {
        echo "Database error: " . htmlspecialchars($conn->error);
    }

    $conn->close();
}
?>
