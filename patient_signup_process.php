<?php
// patient_signup_process.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connect.php'; // Database connection

    // Get the form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // SQL query to insert the data
    $sql = "INSERT INTO signup_patient (patient_name, patient_email, patient_password, patient_DOB, patient_address, patient_phone_no)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare and bind parameters to prevent SQL injection
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $name, $email, $password, $dob, $address, $phone);

    // Execute the query
    if ($stmt->execute()) {
        header("Location: ../frontend/php/patient_login.php");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
