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

    <div class="main-content">
        <div class="cta">
        <div class="logo-container1">
            <img src="img/barberlogo.png" alt="Barber Station Logo">
        </div>
            <p>NO NEED TO WAIT FOR HOURS <span class="highlight">GET THE CLEAN CUT LOOK NOW</span></p>
            <div class="cta-buttons">
                <button><a href="page_reservation.php">OUR SERVICES</a></button>
                <button><a href="page_reservation.php">BOOK YOUR HAIRCUT</a></button>
            </div>
            
        </div>
        <div class="slider-wrapper">
            <div class="slider">
                <img id="slide-1" src="img/barber1.jpg" />
                <img id="slide-2" src="img/barber2.jpg"  />
                <img id="slide-3" src="img/barber3.jpg" />
            </div>
            <div class="slider-nav">
                <a href="#slide-1"></a>
                <a href="#slide-2"></a>
                <a href="#slide-3"></a>
            </div>
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


    <!-- Cut Section -->
    <section class="cut-section">
    <section id="studios">
<h2>Main Cuts In Barberstation</h2>
</section>
        <div class="cut-container">
            <div class="cut-image">
                <img src="img/adult.png" alt="Barber Image 1">
            </div>
            <div class="cut-description">
                <h2>Hair Cut</h2>
                <p>Get the perfect look with our Hair Cut service, designed for those who want style, precision, and attention to detail. Our expert barbers are trained in the latest trends and classic styles, ensuring that every cut is tailored to your unique look and personality. Whether you're after a sharp fade, a clean classic cut, or a trendy modern style, we’ve got you covered.</p>
                <div class="cta-buttons">
                <button><a href="page_reservation.php">BOOK YOUR HAIRCUT</a></button>
            </div>
        </div>
    </section>

    <!-- Second Cut Section (Reverse Layout) -->
    <section class="cut-section">
        <div class="cut-container reverse-layout">
            <div class="cut-description">
                <h2>Kids Cut</h2>
                <span class="highlight">Getting your little one a fresh new haircut has never been easier and more enjoyable! Our Barber Kids Cut service is designed to provide a fun, relaxed, and comfortable environment for children. Our skilled barbers are patient and experienced in working with kids, ensuring they get the style they love while keeping them calm and happy throughout the process.</span>
                <div class="cta-buttons">
                <button><a href="page_reservation.php">BOOK YOUR HAIRCUT</a></button>
            </div>
            </div>
            <div class="cut-image">
                <img src="img/kids.png" alt="Barber Image 2">
            </div>
        </div>
    </section>

    <!-- Updated Footer -->
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
