<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/doctor_login.css">
</head>
<body>
    <h2 class="title">Login</h2>
    <form class="login-form" method="POST" action="../../backend/handle_doctor_login.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit" class="login-btn">Login</button>
    </form>
    <script src="../js/doctor_login.js"></script>
</body>
</html>
