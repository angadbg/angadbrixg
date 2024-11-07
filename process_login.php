<?php
session_start();

// Include the database connection file
include_once 'conx.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare a SQL statement to fetch user details based on email
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists
        if ($user) {
            // Check if the account is active
            if ($user['is_active'] == 0) {
                // Store the user's email and OTP in the session for OTP verification
                $_SESSION['email'] = $user['email'];
                $_SESSION['otp'] = $user['activation_code'];

                echo "<script>alert('Your account is not activated. Please check your email for the activation code.'); window.location.href = 'signup_verification.php';</script>";
                exit();
            }

            // Check if the account is locked
            $cooldown = $user['lock_until'];
            $failedAttempts = $user['failed_try'];
            if ($failedAttempts >= 3 && time() < strtotime($cooldown)) {
                echo "<script>alert('You have reached the maximum number of login attempts. Please try again later.'); window.location.href = 'page_login.php';</script>";
                exit();
            }

            // Verify the password
            if (password_verify($password, $user['uPass'])) {
                // Reset failed attempts and lock_until
                $sql_reset_attempts = "UPDATE users SET failed_try = 0, lock_until = NULL WHERE email = :email";
                $stmt_reset_attempts = $pdo->prepare($sql_reset_attempts);
                $stmt_reset_attempts->bindParam(':email', $email);
                $stmt_reset_attempts->execute();
            
                // Store user details in session variables
                $_SESSION['userID'] = $user['userID']; // Fix the naming here
                $_SESSION['uLevel'] = $user['uLevel'];
            
                // Redirect based on user level
                if ($_SESSION['uLevel'] == 1) {
                    header("Location: page_admin.php");
                } elseif ($_SESSION['uLevel'] == 2) {
                    header("Location: index.php");
                } elseif ($_SESSION['uLevel'] == 3) {
                    header("Location: staff.php");
                }
                exit();
            } else {
                // Increment failed attempts
                $failedAttempts += 1;
                $sql_update_attempts = "UPDATE users SET failed_try = :failedAttempts, last_failed_try = CURRENT_TIMESTAMP WHERE email = :email";
                $stmt_update_attempts = $pdo->prepare($sql_update_attempts);
                $stmt_update_attempts->bindParam(':failedAttempts', $failedAttempts);
                $stmt_update_attempts->bindParam(':email', $email);
                $stmt_update_attempts->execute();

                // Check if the user should be locked out
                if ($failedAttempts >= 3) {
                    $cooldownTime = date('Y-m-d H:i:s', time() + 120); // 2 minutes lockout
                    $sql_update_lock = "UPDATE users SET lock_until = :lockTime WHERE email = :email";
                    $stmt_update_lock = $pdo->prepare($sql_update_lock);
                    $stmt_update_lock->bindParam(':lockTime', $cooldownTime);
                    $stmt_update_lock->bindParam(':email', $email);
                    $stmt_update_lock->execute();

                    // Redirect with error message
                    header("Location: page_login.php?error=You have reached the maximum number of login attempts. Please try again after 2 minutes.");
                    exit();
                }

                // Redirect with error message for incorrect password
                header("Location: page_login.php?error=Incorrect password!");
                exit();
            }
        } else {
            // Redirect with error message if user does not exist
            header("Location: page_login.php?error=User does not exist.");
            exit();
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
} else {
    header("Location: page_login.php");
    exit();
}
?>
