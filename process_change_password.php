<?php
session_start();
require_once 'conx.php'; // Database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get new password and confirm password from the form
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $email = $_SESSION['resetEmail'];  // The email stored in the session during password recovery

    // Check if passwords match
    if ($newPassword === $confirmPassword) {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        try {
            // Update the password in the database
            $sql = "UPDATE users SET uPass = :newPassword WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':newPassword', $hashedPassword);
            $stmt->bindParam(':email', $email);

            // Execute the update
            if ($stmt->execute()) {
                // Clear session data related to password reset
                unset($_SESSION['resetEmail']);
                unset($_SESSION['resetOtp']);

                // Inform the user and redirect to login page
                echo '<script>alert("Your password has been changed successfully!"); window.location.href = "page_login.php";</script>';
            } else {
                echo '<div class="alert alert-danger">Failed to change password. Please try again.</div>';
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo '<div class="alert alert-danger">Passwords do not match!</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Barber Station</title>
    
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
        <h2>Change Password</h2>
        <form action="process_change_password.php" method="POST">
            <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter new password" required
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Password must be at least 8 characters long and contain one uppercase letter, one lowercase letter, one digit, and one special character.">
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm new password" required
                pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Password must be at least 8 characters long and contain one uppercase letter, one lowercase letter, one digit, and one special character.">
            </div>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
