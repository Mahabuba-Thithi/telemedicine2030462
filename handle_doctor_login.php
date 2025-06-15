<?php
// backend/handle_doctor_login.php
session_start();
include 'db_connect.php';  // your existing DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Grab & sanitize inputs
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    // 2. Look up the doctor by email
    $sql = "SELECT doctor_id, doctor_password 
            FROM signup_doctor 
            WHERE doctor_email = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // 3. If found, verify password
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($doctor_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // 4. Correct — log them in
                $_SESSION['doctor_id'] = $doctor_id;
                header("Location: ../frontend/php/doctor_dashboard.php");
                exit();
            } else {
                // Wrong password
                $error = "Invalid password.";
            }
        } else {
            // No such email
            $error = "Email not found.";
        }

        $stmt->close();
    } else {
        $error = "Database error: " . htmlspecialchars($conn->error);
    }

    $conn->close();

    // 5. On error, redirect back with message (or just show it)
    // Simple approach: show alert then back
    echo "<script>
            alert(" . json_encode($error) . ");
            window.location.href = '../frontend/php/doctor_login.php';
          </script>";
    exit();
}
?>
