<?php
session_start();
include 'conx.php';  // Include your database connection

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists and is still valid
    $sql = "SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW()";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token);
    $stmt->execute();
    $resetRequest = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resetRequest) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $new_password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validate password match
            if ($new_password === $confirm_password) {
                // Hash the new password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Update the user's password
                $email = $resetRequest['email'];
                $sql = "UPDATE users SET uPass = :password WHERE uEmail = :email";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                // Delete the token after a successful password reset
                $sql = "DELETE FROM password_resets WHERE token = :token";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':token', $token);
                $stmt->execute();

                echo "Password has been reset successfully!";
                // Redirect to login page
                header("Location: page_login.php");
                exit();
            } else {
                echo "Passwords do not match.";
            }
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body>
    <h2>Reset Your Password</h2>
    <form action="" method="post">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
