<?php
session_start();
require_once 'conx.php'; // Include your database connection

// Include PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/vendor/autoload.php'; // Ensure PHPMailer is installed correctly

// Check if user is logged in and has appropriate access level
if (!isset($_SESSION['userID']) || $_SESSION['uLevel'] != 3) {
    header('Location: page_login.php');
    exit();
}

// Handle accept/reject actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservationID = $_POST['reservationID'];
    $action = $_POST['action']; // 'accept' or 'reject'

    // Update the reservation status in the database
    if ($action == 'accept') {
        $sql = "UPDATE booking SET reservationStatus = 'accepted' WHERE reservationID = :reservationID";
    } elseif ($action == 'reject') {
        $sql = "UPDATE booking SET reservationStatus = 'rejected' WHERE reservationID = :reservationID";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':reservationID', $reservationID);
    
    if ($stmt->execute()) {
        // Log the action to the audittrail table
        $audit_sql = "INSERT INTO audittrail (aAction, aTimestamp, userID) VALUES (:action, CURRENT_TIMESTAMP, :userID)";
        $audit_stmt = $pdo->prepare($audit_sql);
        $audit_action = ucfirst($action) . "ed reservation ID: " . $reservationID; // Create the log message
        $audit_stmt->bindParam(':action', $audit_action);
        $audit_stmt->bindParam(':userID', $_SESSION['userID']); // Store the staff member's user ID who performed the action
        $audit_stmt->execute();
        
        // Fetch user information for the booking
        $sqlUser = "SELECT u.email, u.fname, b.services, b.reservationDate, b.reservationTime
                    FROM booking b
                    JOIN users u ON b.userID = u.userID
                    WHERE b.reservationID = :reservationID";
        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->bindParam(':reservationID', $reservationID);
        $stmtUser->execute();
        $userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

        // Send an email notification to the user
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'zerovstore@gmail.com'; // Replace with your email
            $mail->Password = 'fxqe wqwy silt ysln';  // Replace with your email password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('brixterangad@gmail.com', 'Barber Shop'); // Replace with your email
            $mail->addAddress($userData['email']); // Add recipient email

            // Email content
            $mail->isHTML(true);
            $mail->Subject = "Your Reservation has been " . ucfirst($action);
            $mail->Body = "Hello " . $userData['fname'] . ",<br><br>Your booking for <b>" . $userData['services'] . "</b> on <b>" . $userData['reservationDate'] . "</b> at <b>" . $userData['reservationTime'] . "</b> has been <b>" . $action . "</b>.<br><br>";
            $mail->AltBody = "Hello " . $userData['fname'] . ", Your booking for " . $userData['services'] . " on " . $userData['reservationDate'] . " at " . $userData['reservationTime'] . " has been " . $action . ". ";

            // Send the email
            $mail->send();

            // Set a success message
            $successMessage = 'Reservation successfully ' . $action . 'ed, and notification email sent.';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo '<div class="alert alert-danger">Failed to update reservation status. Please try again.</div>';
    }
}

// Fetch pending reservations
try {
    $sql = "SELECT * FROM booking WHERE reservationStatus = 'pending'"; // Fetch only pending reservations
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pendingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit();
}
?>
