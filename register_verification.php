<?php
session_start();
    // Display all session variables
  //  echo '<pre>';
 //   print_r($_SESSION);
   // echo '</pre>';


// Check if email and OTP session variables are set
if (!isset($_SESSION['email']) || !isset($_SESSION['otp'])) {
    // Redirect to the registration page if no session data is available
    header("Location: register.php");
    exit;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredOtp = $_POST['otp']

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
        echo '<div class="alert alert-danger">Invalid OTP. Please try again.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    
</head>
<body>
    <div class="verification-container">
        <h2>OTP Verification</h2>
        <form action="register_verification.php" method="post">
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

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>