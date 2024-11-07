<?php
session_start();
require_once 'conx.php';

// Check if the user is logged in
if (!isset($_SESSION['userID']) || $_SESSION['uLevel'] != 2) {
    echo '<script>alert("You must be logged in to make a reservation."); window.location.href = "page_login.php";</script>';
    exit();
}

// Get user ID from session
$userID = $_SESSION['userID'];

// Define service prices
$servicePrices = [
    "Hair Cut" => 180.00,
    "Kids Haircut" => 200.00,
    "Beard Trim" => 120.00,
    "Shave" => 120.00,
    "Scalp Treatment" => 250.00,
    "Hair Color" => 350.00,
    "Cut & Rinse" => 250.00,
    "Haircut & Beard Trim" => 250.00,
    "Haircut & Scalp Treatment" => 400.00,
    "Haircut & Hair Color" => 500.00,
    "Haircut & Bleach w/ Color" => 800.00
];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservationDate = $_POST['reservationDate'];
    $reservationTime = $_POST['reservationTime'];
    $services = $_POST['services'];

    // Get the price based on selected service
    $totalCost = isset($servicePrices[$services]) ? $servicePrices[$services] : 150.00; // Default fallback

    // Validate reservation date
    if (strtotime($reservationDate) <= strtotime('tomorrow')) {
        echo '<div class="alert alert-danger">Reservations cannot be made for today, tomorrow, or any past date. Please select a date after tomorrow.</div>';
    } else {
        // Check if the date and time are already reserved
        $sqlCheck = "SELECT COUNT(*) FROM booking WHERE reservationDate = :reservationDate AND reservationTime = :reservationTime";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->bindParam(':reservationDate', $reservationDate);
        $stmtCheck->bindParam(':reservationTime', $reservationTime);
        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() > 0) {
            echo '<div class="alert alert-danger">This date and time are already reserved. Please choose another.</div>';
        } else {
            try {
                // Insert reservation details
                $sql = "INSERT INTO booking (userID, services, reservationDate, reservationTime, totalCost, paymentStatus) 
                        VALUES (:userID, :services, :reservationDate, :reservationTime, :totalCost, 'pending')";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID);
                $stmt->bindParam(':services', $services);
                $stmt->bindParam(':reservationDate', $reservationDate);
                $stmt->bindParam(':reservationTime', $reservationTime);
                $stmt->bindParam(':totalCost', $totalCost);

                if ($stmt->execute()) {
                    // Store reservation ID in session and redirect to generate_payment_link.php
                    $_SESSION['reservationID'] = $pdo->lastInsertId();
                    echo '<script>alert("Reservation successful! Proceed to payment."); window.location.href = "generate_payment_link.php";</script>';
                } else {
                    echo '<div class="alert alert-danger">Failed to create reservation. Please try again.</div>';
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Reservation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        .reservation-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
        }

        .form-group label {
            font-weight: bold;
        }

        .custom-input {
            width: 100%;
            border-radius: 5px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .btn-primary {
            background-color: #d69824;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            border-radius: 25px;
        }

        .btn-primary:hover {
            background-color: #d6850f;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Set minimum date to the day after tomorrow
            let dayAfterTomorrow = new Date();
            dayAfterTomorrow.setDate(dayAfterTomorrow.getDate() + 2); // Add two days
            let dayAfterTomorrowString = dayAfterTomorrow.toISOString().split('T')[0];
            document.getElementById("reservationDate").setAttribute('min', dayAfterTomorrowString);
        });
    </script>
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

<div class="reservation-container">
    <h2 class="reservation-header">Reserve a Slot</h2>
    <form action="page_reservation.php" method="POST">
        <div class="form-group">
            <label for="services">Select Service</label>
            <select class="form-control custom-input" id="services" name="services" required>
                <option value="" disabled selected>Select a Service</option>
                <optgroup label="Individual Services">
                    <option value="Hair Cut">Hair Cut - 180</option>
                    <option value="Kids Haircut">Kids Haircut - 200</option>
                    <option value="Beard Trim">Beard Trim - 120</option>
                    <option value="Shave">Shave - 120</option>
                    <option value="Scalp Treatment">Scalp Treatment - 250</option>
                    <option value="Hair Color">Hair Color - 350</option>
                </optgroup>
                <optgroup label="Package Services">
                    <option value="Cut & Rinse">Cut & Rinse - 250</option>
                    <option value="Haircut & Beard Trim">Haircut & Beard Trim - 250</option>
                    <option value="Haircut & Scalp Treatment">Haircut & Scalp Treatment - 400</option>
                    <option value="Haircut & Hair Color">Haircut & Hair Color - 500</option>
                    <option value="Haircut & Bleach w/ Color">Haircut & Bleach w/ Color - 800</option>
                </optgroup>
            </select>
        </div>

        <div class="form-group">
            <label for="reservationDate">Select Date</label>
            <input type="date" class="form-control custom-input" id="reservationDate" name="reservationDate" required>
        </div>
        <div class="form-group">
            <label for="reservationTime">Select Time (8:00 AM to 10:00 PM)</label>
            <select class="form-control custom-input" id="reservationTime" name="reservationTime" required>
                <option value="08:00">08:00 AM</option>
                <option value="09:00">09:00 AM</option>
                <option value="10:00">10:00 AM</option>
                <option value="11:00">11:00 AM</option>
                <option value="12:00">12:00 PM</option>
                <option value="13:00">01:00 PM</option>
                <option value="14:00">02:00 PM</option>
                <option value="15:00">03:00 PM</option>
                <option value="16:00">04:00 PM</option>
                <option value="17:00">05:00 PM</option>
                <option value="18:00">06:00 PM</option>
                <option value="19:00">07:00 PM</option>
                <option value="20:00">08:00 PM</option>
                <option value="21:00">09:00 PM</option>
                <option value="22:00">10:00 PM</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Proceed to Payment</button>
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
