<?php
session_start();

// Check if session variables exist
if (!isset($_SESSION['firstName']) || !isset($_SESSION['lastName']) || !isset($_SESSION['phoneNumber']) || !isset($_SESSION['email'])) {
    // If session variables don't exist, redirect to registration page
    header("Location: page_register.php");
    exit;
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Registration - Barber Station</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('img/barbershop-bg.jpg'); /* Barbershop-themed background */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            font-family: 'Montserrat', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.9); /* Slight transparency */
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        }
        .form-signin img {
            margin-bottom: 20px;
        }
        .form-signin h1 {
            color: #333;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-floating {
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #d69824; /* Golden barber color */
            border: none;
        }
        .btn-primary:hover {
            background-color: #b77f1e; /* Darker golden tone */
        }
        .form-signin a {
            color: #d69824;
        }
        .form-signin a:hover {
            color: #b77f1e;
        }
    </style>
</head>
<body>
    <div class="container">
        <main class="form-signin w-100 m-auto">
            <form action="process_signup.php" method="post">
                <center><img class="mb-4" src="img/barberlogo.png" alt="Barber Station Logo" width="200" height="200"></center>
                <h1 class="h3 mb-3 fw-normal">Confirm Registration Details</h1>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" readonly value="<?php echo htmlspecialchars($_SESSION['firstName']); ?>">
                            <label for="firstName">First Name</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" readonly value="<?php echo htmlspecialchars($_SESSION['lastName']); ?>">
                            <label for="lastName">Last Name</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating">
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Phone Number" readonly value="<?php echo $_SESSION['phoneNumber']; ?>">
                    <label for="phone">Phone Number</label>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" readonly value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                    <label for="email">Email address</label>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Sign Up</button>
                <div class="mt-3 text-center">
                    <a href="page_register.php?firstName=<?php echo urlencode($_SESSION['firstName']); ?>&lastName=<?php echo urlencode($_SESSION['lastName']); ?>&phoneNumber=<?php echo urlencode($_SESSION['phoneNumber']); ?>&email=<?php echo urlencode($_SESSION['email']); ?>">Back to Sign Up Page</a>
                </div>
            </form>
        </main>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
