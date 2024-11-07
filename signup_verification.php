<?php
session_start();

// Check if email and OTP session variables are set
if (!isset($_SESSION['email']) || !isset($_SESSION['otp'])) {
    // Redirect to the registration page if no session data is available
    header("Location: page_register.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp'];

    // Verify OTP
    if ($enteredOtp == $_SESSION['otp']) {
        // OTP is correct, activate user in the database
        require_once 'conx.php';
        $email = $_SESSION['email'];

        $sql = "UPDATE users SET is_active = 1 WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            echo '<script>alert("Account activated successfully!"); window.location.href = "page_login.php";</script>';
        } else {
            echo '<div class="alert alert-danger">Failed to activate the account. Please try again.</div>';
        }
    } else {
        // OTP is incorrect
        echo '<script>alert("Invalid OTP. Please try again."); window.location.href= "signup_verification.php";</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification - Barber Station</title>
    
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
        .verification-container {
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
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #d69824; /* Golden barber color */
            border: none;
        }
        .btn-primary:hover {
            background-color: #b77f1e; /* Darker golden tone */
        }
        .alert-danger {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h2>OTP Verification</h2>
        <form action="signup_verification.php" method="post">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="otp">Enter OTP:</label>
                <input type="text" class="form-control" id="otp" name="otp" required pattern="^\d{6}$" title="Please enter a 6-digit OTP" placeholder="Enter the OTP" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            </div>
            <button type="submit" class="btn btn-primary w-100">Verify OTP</button>
        </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
