<?php
session_start();
require_once 'conx.php';

// Check if the user is logged in
if (!isset($_SESSION['userID'])) {
    echo '<script>alert(" You must be logged in first."); window.location.href = "page_login.php";</script>';
    exit();
}

// Get user ID from session
$userID = $_SESSION['userID'];

// Fetch user details
$sqlUser = "SELECT fname, lname, uphone, email FROM users WHERE userID = :userID";
$stmtUser = $pdo->prepare($sqlUser);
$stmtUser->bindParam(':userID', $userID);
$stmtUser->execute();
$userData = $stmtUser->fetch(PDO::FETCH_ASSOC);

// Check if form is submitted to update name
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];

    // Update user details
    $sqlUpdate = "UPDATE users SET fname = :fname, lname = :lname WHERE userID = :userID";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->bindParam(':fname', $fname);
    $stmtUpdate->bindParam(':lname', $lname);
    $stmtUpdate->bindParam(':userID', $userID);

    if ($stmtUpdate->execute()) {
        echo '<script>alert("Profile updated successfully!"); window.location.href = "myaccount.php";</script>';
    } else {
        echo '<div class="alert alert-danger">Failed to update profile. Please try again.</div>';
    }
}

// Fetch reservation history
$sqlReservations = "SELECT reservationDate, reservationTime, services, totalCost, reservationStatus 
                    FROM booking WHERE userID = :userID ORDER BY created_at DESC";
$stmtReservations = $pdo->prepare($sqlReservations);
$stmtReservations->bindParam(':userID', $userID);
$stmtReservations->execute();
$reservationHistory = $stmtReservations->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f7f7f7;
        }
        
        .account-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 800px;
            margin: 50px auto;
        }

        .account-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .custom-input {
            width: 100%; 
            max-width: 400px; 
            margin: 0 auto;
        }

        .btn-primary {
            width: 100%;
            max-width: 200px;
            background-color: #28a745;
            border: none;
            padding: 10px;
            border-radius: 25px;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .history-table {
            margin-top: 40px;
        }

        .table th, .table td {
            text-align: center;
        }

        .table {
            margin-top: 20px;
        }

        .table-header {
            background-color: #d69824;
            color: white;
        }

        footer {
            margin-top: 50px;
            padding: 20px 0;
            background-color: #333;
            color: white;
            text-align: center;
        }

        .footer-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .footer-column h3 {
            color: #d69824;
        }
    </style>
</head>
<body>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Col Nayler Barber Shop</title>
    <link rel="stylesheet" href="styles.css">
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

    <div class="account-container">
        <h2 class="account-header">My Account</h2>

        <!-- Profile Form -->
        <form action="myaccount.php" method="POST">
            <div class="form-group text-center">
                <label for="fname">First Name</label>
                <input type="text" class="form-control custom-input" id="fname" name="fname" value="<?= htmlspecialchars($userData['fname']) ?>" required>
            </div>
            <div class="form-group text-center">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control custom-input" id="lname" name="lname" value="<?= htmlspecialchars($userData['lname']) ?>" required>
            </div>
            <div class="form-group text-center">
                <label for="uphone">Phone Number (Not Editable)</label>
                <input type="text" class="form-control custom-input" id="uphone" value="<?= htmlspecialchars($userData['uphone']) ?>" disabled>
            </div>
            <div class="form-group text-center">
                <label for="email">Email (Not Editable)</label>
                <input type="text" class="form-control custom-input" id="email" value="<?= htmlspecialchars($userData['email']) ?>" disabled>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>

        <!-- Reservation History -->
        <h3 class="mt-5 text-center">Reservation History</h3>
        <table class="table table-bordered history-table">
            <thead class="table-header">
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Service</th>
                    <th>Total Cost</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reservationHistory): ?>
                    <?php foreach ($reservationHistory as $reservation): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservation['reservationDate']) ?></td>
                            <td><?= htmlspecialchars($reservation['reservationTime']) ?></td>
                            <td><?= htmlspecialchars($reservation['services']) ?></td>
                            <td><?= htmlspecialchars($reservation['totalCost']) ?></td>
                            <td><?= htmlspecialchars($reservation['reservationStatus']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No reservation history found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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
