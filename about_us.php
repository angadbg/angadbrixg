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
    <!-- About Us Section -->
    <section class="about-us">
        <div class="about-content">
            <div class="brand-story">
                <h1 class="title">The <strong>Barberstation</strong> Brand</h1>
                <p>Welcome to Barberstation, the original barber shop serving the Bulacan community since 2021. As the market leader in men's hairdressing, we are a local icon in our industry. We specialize in men's and children's haircuts, offering everything from traditional styles to the latest looks.</p>
                <div class="scroll-down">
                    <a href="#history">
                        <span class="scroll-text">Scroll Down</span>
                    </a>
                </div>
                <!-- Owner Description -->
                <div class="owner-description">
                    <h2>Owned by Ej Ventura</h2>
                    <p>Ej Ventura, a master barber, is known for his exceptional skills and dedication to the craft. With years of experience, Ej has quickly earned a reputation as one of the best barbers in the city. His attention to detail and commitment to providing the perfect cut has made Barberstation the go-to destination for clients who want nothing but the best. Under his leadership, the shop continues to thrive, combining traditional techniques with the latest trends in grooming.</p>
                </div>
            </div>
            <div class="brand-image">
                <img src="img/barber5.png" alt="Barberstation in action">
                <p class="image-caption">Ej Ventura providing a fresh cut in 2023</p>
            </div>
        </div>
    </section>

    <!-- History Section -->
    <section class="history-section" id="history">
        <h2>OUR HISTORY</h2>
        <div class="timeline">
            <div class="timeline-entry">
                <h3>2023</h3>
                <p>1st Store Opens</p>
                <p>Opened our first store in Baliuag, Bulacan, revolutionizing the industry with our unique barbering approach.</p>
            </div>
            <div class="timeline-entry">
                <h3>2024</h3>
                <p>Expansion and New Employees</p>
                <p>In 2024, Barberstation expanded its team with talented new barbers. Despite being new in the industry, we have become a local favorite due to our attention to detail and top-tier grooming services.</p>
            </div>
        </div>
    </section>

    <!-- Updated Footer -->
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
