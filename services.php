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
    <div class="slider1" style="--width: 100px; --height: 50px; --quantity: 15;">
        <div class="list">
            <div class="item" style="--position: 1"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 2"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 3"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 4"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 5"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 6"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 7"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 8"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 9"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 10"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 11"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 12"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 13"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 14"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 15"><img src="img/barberlogo.png" alt=""></div>
        </div>
    </div>


    <section class="services-section">
        <div class="services-content">
            <h1>Barber Station Services</h1>
            <div class="price-list">
                <h2>Individual Services</h2>
                <ul>
                    <li>Hair Cut <span>180</span></li>
                    <li>Kids Haircut <span>200</span></li>
                    <li>Beard Trim <span>120</span></li>
                    <li>Shave <span>120</span></li>
                    <li>Scalp Treatment <span>250</span></li>
                    <li>Hair Color <span>350</span></li>
                </ul>
            </div>
            
            <div class="package-services">
                <h2>Package Services</h2>
                <ul>
                    <li>Cut & Rinse <span>250</span></li>
                    <li>Haircut & Beard Trim <span>250</span></li>
                    <li>Haircut & Scalp Treatment <span>400</span></li>
                    <li>Haircut & Hair Color <span>500</span></li>
                    <li>Haircut & Bleach w/ Color <span>800</span></li>
                </ul>
            </div>
        </div>
        <div class="slider1" style="--width: 100px; --height: 50px; --quantity: 15;">
        <div class="list">
            <div class="item" style="--position: 1"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 2"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 3"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 4"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 5"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 6"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 7"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 8"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 9"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 10"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 11"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 12"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 13"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 14"><img src="img/barberlogo.png" alt=""></div>
            <div class="item" style="--position: 15"><img src="img/barberlogo.png" alt=""></div>
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
