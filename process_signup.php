<?php
session_start();
require_once 'conx.php';

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/vendor/autoload.php'; // Ensure PHPMailer is installed correctly

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user details from session
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $phoneNumber = $_SESSION['phoneNumber'];
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Generate a 6-digit OTP code
        $otpCode = rand(100000, 999999);

        // Prepare SQL query to insert user into the database
        $sql = "INSERT INTO users (fname, lname, uphone, email, uPass, uLevel, activation_code, is_active) 
                VALUES (:firstName, :lastName, :phoneNumber, :email, :hashedPassword, 2, :otpCode, 0)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':phoneNumber', $phoneNumber);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':hashedPassword', $hashedPassword);
        $stmt->bindParam(':otpCode', $otpCode);

        // Execute the statement
        if ($stmt->execute()) {
            // Commit the transaction
            $pdo->commit();

            // Log user action (Account Created) into audituser table
            $auditSql = "INSERT INTO audittrail (aAction, userID) VALUES ('Account Created', LAST_INSERT_ID())";
            $auditStmt = $pdo->prepare($auditSql);
            $auditStmt->execute();

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
                $mail->setFrom('brixterangad@gmail.com', 'OTP Verification');  // Sender email
                $mail->addAddress($email);                                   // Add recipient email

                // Content
                $mail->isHTML(true);                                         // Set email format to HTML
                $mail->Subject = 'Account Activation';
                $mail->Body    = "Hello $firstName,<br><br>Your activation code is: <b>$otpCode</b><br><br>Please use this code to activate your account.";
                $mail->AltBody = "Hello $firstName, Your activation code is: $otpCode. Please use this code to activate your account.";

                // Send the email
                $mail->send();

                // Store the email and OTP in session for OTP verification
                $_SESSION['email'] = $email;
                $_SESSION['otp'] = $otpCode;

                // Redirect to OTP verification page
                echo '<script>alert("Registration successful! Please check your email for the OTP."); window.location.href = "signup_verification.php";</script>';

                // Clear form data but keep OTP session
                session_write_close();
            } catch (Exception $e) {
                // Rollback the transaction if the email fails to send
                $pdo->rollBack();
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            // Rollback transaction if insertion fails
            $pdo->rollBack();
            echo '<div class="alert alert-danger">Registration failed!</div>';
        }
    } catch (Exception $e) {
        // Rollback the transaction if an exception occurs
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
?>
