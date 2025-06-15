<!-- php/patient_login.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/patient_login.css">
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="../../backend/patient_login_process.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
    <script src="../js/patient_login.js"></script>
</body>
</html>
