<?php
session_start();

// Check if OTP form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get OTP from the form
    $enteredOtp = $_POST['otp'];

    // Check if OTP matches the one stored in session
    if (isset($_SESSION['resetOtp']) && $enteredOtp == $_SESSION['resetOtp']) {
        // OTP is valid, redirect to change password page
        echo '<script>alert("OTP verified successfully! You can now change your password."); window.location.href = "process_change_password.php";</script>';
    } else {
        // OTP is invalid
        echo '<script>alert("Invalid OTP! Please try again"); window.location.href = "verify_otp.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - Barber Station</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('img/barbershop-bg.jpg'); /* Barbershop-themed background */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Slight transparency for the form */
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
            max-width: 400px;
            width: 100%;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: #d69824; /* Golden barber color */
            border: none;
        }
        .btn-primary:hover {
            background-color: #b77f1e; /* Darker golden tone */
        }
        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <form action="verify_otp.php" method="POST">
            <div class="form-group">
                <label for="otp">Enter OTP Code</label>
                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter the OTP sent to your email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
