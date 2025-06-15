<!-- php/patient_signup.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient_Signup</title>
    <link rel="stylesheet" href="../css/patient_signup.css">
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
        <form action="../../backend/patient_signup_process.php" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="date" name="dob" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <button type="submit">Signup</button>
        </form>
    </div>
    <script src="../js/patient_signup.js"></script>
</body>
</html>
