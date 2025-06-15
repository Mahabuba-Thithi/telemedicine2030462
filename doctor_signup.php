<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor_Signup</title>
    <link rel="stylesheet" href="../css/doctor_signup.css">
</head>
<body>
    <h2 class="title">Signup</h2>
    <form class="signup-form" method="POST" action="../../backend/handle_doctor_signup.php" method="POST">
        <div class="form-container">
            <div class="left-column">
                <label>Name:</label>
                <input type="text" name="name" required>

                <label>Designation:</label>
                <input type="text" name="designation" required>

                <label>Educational Background:</label>
                <input type="text" name="education" required>

                <label>Experience:</label>
                <input type="text" name="experience" required>

                <label>Available Time:</label>
                <input type="text" name="available_time" required>

                <label>Available Days:</label>
                <input type="text" name="avail_day" placeholder="e.g. Mon, Wed, Fri" required>

            </div>

            <div class="right-column">
                <label>Clinic/Hospital Affiliation:</label>
                <input type="text" name="affiliation" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Password:</label>
                <input type="password" name="password" required>

                <label>Date of Birth:</label>
                <input type="date" name="dob" required>

                <label>Licence Number:</label>
                <input type="text" name="licence" required>

                <label>Department:</label>
                <select name="department" required>
                    <option value="">Select</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Neurology">Neurology</option>
                </select>
            </div>
        </div>
        <button type="submit" class="submit-btn">Signup</button>
    </form>
    <script src="../js/doctor_signup.js"></script>
</body>
</html>
