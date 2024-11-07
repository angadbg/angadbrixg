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

            // Set a success message in session and redirect
            $_SESSION['successMessage'] = 'Reservation successfully ' . $action . 'ed, and notification email sent.';
            header('Location: view_pending_requests.php'); // Redirect to the same page to avoid form resubmission
            exit();
        } catch (Exception $e) {
            $_SESSION['errorMessage'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $_SESSION['errorMessage'] = "Failed to update reservation status. Please try again.";
        header('Location: view_pending_requests.php');
        exit();
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Reservation Requests</title>
    
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
            background-color: rgba(255, 255, 255, 0.9); /* Slight transparency */
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
            max-width: 900px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .table {
            margin-top: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background-color: #d69824; /* Barber-themed golden color */
            color: white;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-primary {
            background-color: #d69824;
            border: none;
            margin-top: 20px;
        }
        .btn-primary:hover {
            background-color: #b77f1e;
        }
        .success-message {
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 80%;
            max-width: 600px;
        }
    </style>
</head>
<body>
    <!-- Success/Error Message -->
    <?php if (isset($_SESSION['successMessage'])): ?>
        <div class="alert alert-success text-center success-message">
            <?php echo htmlspecialchars($_SESSION['successMessage']); unset($_SESSION['successMessage']); ?>
        </div>
    <?php elseif (isset($_SESSION['errorMessage'])): ?>
        <div class="alert alert-danger text-center success-message">
            <?php echo htmlspecialchars($_SESSION['errorMessage']); unset($_SESSION['errorMessage']); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <h2>Pending Booking Requests</h2>
        <?php if (count($pendingReservations) > 0): ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>User ID</th>
                        <th>Services</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Total Cost</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pendingReservations as $reservation): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['reservationID']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['userID']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['services']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['reservationDate']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['reservationTime']); ?></td>
                            <td>₱<?php echo number_format($reservation['totalCost'], 2); ?></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="reservationID" value="<?php echo $reservation['reservationID']; ?>">
                                    <button type="submit" name="action" value="accept" class="btn btn-success btn-sm">Accept</button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No pending requests at this time.</div>
        <?php endif; ?>
        <div class="text-center">
            <a href="staff.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
