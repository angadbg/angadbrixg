<?php
session_start();
require_once 'conx.php';

if (!isset($_SESSION['reservationID'])) {
    // If no reservation ID, redirect back to reservation page
    header('Location: page_reservation.php');
    exit();
}

$reservationID = $_SESSION['reservationID'];
$totalCost = 150.00; // Fixed reservation cost

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle receipt upload
    if (isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'payments/';
        $uploadFile = $uploadDir . basename($_FILES['receipt']['name']);

        // Validate the file type (you can add more validation as needed)
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileType, $allowedTypes)) {
            // Move uploaded file to the uploads directory
            if (move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadFile)) {
                // Assume payment is processed successfully (can integrate with actual payment gateway)
                $sql = "UPDATE booking SET paymentStatus = 'completed', receiptPath = :receiptPath WHERE reservationID = :reservationID";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':receiptPath', $uploadFile);
                $stmt->bindParam(':reservationID', $reservationID);
                
                if ($stmt->execute()) {
                    // Clear the session reservation ID
                    unset($_SESSION['reservationID']);
                    echo '<script>alert("Payment successful!"); window.location.href = "index.php";</script>';
                } else {
                    echo '<div class="alert alert-danger">Payment failed. Please try again.</div>';
                }
            } else {
                echo '<div class="alert alert-danger">File upload failed. Please try again.</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background-color: #f7f7f7;
            font-family: 'Montserrat', sans-serif;
        }

        .payment-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
        }

        .payment-header {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }

        .payment-header::after {
            content: '';
            display: block;
            width: 80px;
            margin: 20px auto 0;
            height: 4px;
            background-color: #d69824;
            border-radius: 4px;
        }

        .file-upload-container {
            display: flex;
            justify-content: center; /* Center horizontally */
            margin-top: 10px;
        }

        .file-upload {
            width: 50%;
            max-width: 250px; /* Set a max width */
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border-radius: 25px;
        }

        .btn-success:hover {
            background-color: #218838;
        }

    </style>
</head>

<body>
<header>
        <div class="logo-container">
            <img src="img/barberlogo.png" alt="Barber Station Logo">
        </div>
        <nav class="nav-bar">
            <ul class="main-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="about_us.php">About Us</a></li>
                <li><a href="locations.php">Location</a></li>
                <li><a href="services.php">Services</a></li>
                <li><a href="contact_us.php">Contact Us</a></li>
                <li><a href="profile.php">My Account</a></li>
                <?php if (isset($_SESSION['userID'])): ?>
                    <li><a href="logout.php">Log Out</a></li>
                <?php else: ?>
                    <li><a href="page_login.php">Log In</a></li>
                <?php endif; ?>

            </ul>
        </nav>
    </header>

    <div class="payment-container">
        <h2 class="payment-header">Payment</h2>
        <p>Reservation cost: ₱150.00</p>

        <!-- Fake QR Code -->
        <div class="text-center mb-4">
            <img src="img/qr.png" alt="Fake QR Code" style="width: 200px; height: 200px;">
            <p>Pay at Garage Music Studio</p>
        </div>

        <form action="page_payment.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="receipt">Upload Receipt:</label>
                <div class="file-upload-container">
                    <input type="file" class="form-control-file file-upload" id="receipt" name="receipt" accept=".jpg,.jpeg,.png,.gif" required>
                </div>
            </div>
            <button type="submit" class="btn btn-success">Complete Payment</button>
        </form>
    </div>

    <footer>
        <div class="footer-container">
            <!-- Contact Us Section -->
            <div class="footer-column contact">
                <h3>CONTACT US</h3>
                <p>For all customers and general inquiries, please contact Barberstation, and we will be more than happy to help.</p>
                <p><strong>+63 961 728 0500</strong></p>
                <p><a href="mailto:admin@barberstation.com">admin@barberstation.com</a></p>
                <p>© 2024 Barberstation | Site by BRIX</p>
            </div>

            <!-- Newsletter Section -->
            <div class="footer-column newsletter">
                <h3>NEWSLETTER</h3>
                <p>Subscribe to our newsletter to stay up to date with exclusive offers and discounts straight to your inbox:</p>
                <form action="#" method="POST">
                    <input type="email" name="email" placeholder="Email Address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </footer>
</body>
</html>
