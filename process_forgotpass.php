<?php
session_start();
require_once 'conx.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/vendor/autoload.php'; // Ensure PHPMailer is installed correctly

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form
    $email = $_POST['email'];

    try {
        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Generate a 6-digit OTP code for password reset
            $resetOtpCode = rand(100000, 999999);

            // Store the OTP and email in session
            $_SESSION['resetEmail'] = $email;
            $_SESSION['resetOtp'] = $resetOtpCode;

            // Send OTP Email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();                                             // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                        // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
                $mail->Username   = 'zerovstore@gmail.com';                  // SMTP username (your Gmail address)
                $mail->Password   = 'fxqe wqwy silt ysln';                   // SMTP password (Gmail password or App password)
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;          // Enable TLS encryption
                $mail->Port       = 587;                                     // TCP port to connect to

                // Recipients
                $mail->setFrom('brixterangad@gmail.com', 'Password Reset');    // Sender email
                $mail->addAddress($email);                                   // Add recipient email

                // Content
                $mail->isHTML(true);                                         // Set email format to HTML
                $mail->Subject = 'Password Reset Request';
                $mail->Body    = "Hello,<br><br>Your password reset code is: <b>$resetOtpCode</b><br><br>Please use this code to reset your password.";
                $mail->AltBody = "Hello, Your password reset code is: $resetOtpCode. Please use this code to reset your password.";

                // Send the email
                $mail->send();

                // Redirect to OTP verification page for password reset
                echo '<script>alert("A password reset code has been sent to your email. Please check your inbox."); window.location.href = "verify_otp.php";</script>';

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo '<script>alert("Email Address not found."); window.location.href = "process_forgotpass.php";</script>';
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Barber Station</title>
    
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
        <h2>Forgot Password</h2>
        <form action="process_forgotpass.php" method="POST">
            <div class="form-group">
                <label for="email">Enter Your Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Send Reset Code</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
