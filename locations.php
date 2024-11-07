<?php
session_start(); // Always include this at the top to start a session
?>
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

    <!-- Location and Office Hours Section -->
    <section class="location-office-hours">
        <div class="info-grid">
            <div class="info-box">
                <h3>EMPLOYMENT</h3>
                <p>admin@barberstation.com</p>
                <p>OWNER: <strong><a href="https://www.facebook.com/eeddmmuunndd">EJ VENTURA</strong></a></p>
            </div>
            <div class="info-box">
                <h3>OFFICE HOURS</h3>
                <p><strong>Monday-Saturday:</strong> 11:00am – 7:00pm</p>
                <p><strong>Sunday:</strong> Closed</p>
            </div>
            <div class="info-box">
                <h3>HEAD OFFICE</h3>
                <p>0339 F. Vergel De Dios St. Concepcion,
                Baliuag, Bulacan</p>
                <p><strong>+63 961 728 0500</strong></p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
    <div class="footer-container">
        <!-- Contact Us Section -->
        <div class="footer-column contact">
            <h3>CONTACT US</h3>
            <p>For all customers and general enquiries, please contact Barberstation office and we will be more than happy to help.</p>
            <p><strong>+63 961 728 0500</strong></p>
            <p><a href="mailto:admin@colnaylerbarber.com">admin@barberstation.com</a></p>
            <p>© 2024 Barberstation | Site by BRIX</p>
        </div>

        <!-- Newsletter Section -->
        <div class="footer-column newsletter">
            <h3>NEWSLETTER</h3>
            <p>Subscribe to our mail newsletter and stay up to date with exclusive offers and discounts coming straight to your mailbox:</p>
            <form action="#" method="POST">
                <input type="email" name="email" placeholder="email address" required>
                <button type="submit">Subscribe</button>
            </form>
        </div>
    </div>
    </footer>

</body>
</html>