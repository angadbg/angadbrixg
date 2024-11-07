<?php
require_once 'conx.php';

// Start session
session_start();

// Initialize variables
$alertMessage = '';

// Initialize form data variables
$firstName = '';
$lastName = '';
$phoneNumber = '';
$email = '';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if phone number exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE uphone = :phoneNumber");
    $stmt->execute(['phoneNumber' => $phoneNumber]);
    $phoneExists = $stmt->fetch();

    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $emailExists = $stmt->fetch();

    // If phone number or email exists, set alert message
    if ($phoneExists || $emailExists) {
        $alertMessage = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error:</strong> ' . ($phoneExists ? 'Phone number already exists.' : '') . ' ' . ($emailExists ? 'Email already exists.' : '') . '
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                         </div>';
    } else {
        // Store form data in session variables
        $_SESSION['firstName'] = $firstName;
        $_SESSION['lastName'] = $lastName;
        $_SESSION['phoneNumber'] = $phoneNumber;
        $_SESSION['email'] = $email;
        $_SESSION['password'] = $password;

        // Redirect to the next page
        header("Location: page_register_signup.php");
        exit;
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Barber Station</title>
    
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
            max-width: 500px;
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
        .form-check-label {
            color: #555;
        }
        .form-signin a {
            color: #d69824;
        }
        .form-signin a:hover {
            color: #b77f1e;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <main class="form-signin w-100 m-auto">
            <?php echo $alertMessage; ?> <!-- Display alert messages (e.g., email or phone already exists) -->
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <center><img class="mb-4" src="img/barberlogo.png" alt="Barber Station Logo" width="150" height="150"></center>
                <h1 class="h3 mb-3 fw-normal">Create Your Account</h1>

                <div class="row">
                    <div class="col">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="First Name" required pattern="^[A-Z][a-z]*( [A-Z][a-z]*){0,2}$" title="Ex. Juan Gabriel" value="<?php echo htmlspecialchars($firstName); ?>">
                            <label for="firstName">First Name</label>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-floating">
                            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Last Name" required pattern="^[A-Z][a-z]*( [A-Z][a-z]*){0,2}$" title="Ex. Dela Cruz" value="<?php echo htmlspecialchars($lastName); ?>">
                            <label for="lastName">Last Name</label>
                        </div>
                    </div>
                </div>

                <div class="form-floating">
                    <input type="tel" class="form-control" id="phoneNumber" name="phoneNumber" placeholder="Phone Number" required pattern="^09\d{9}$" title="Phone number must be an 11-digit number starting with 09" value="<?php echo htmlspecialchars($phoneNumber); ?>">
                    <label for="phoneNumber">Phone Number</label>
                </div>

                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com" required pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="Ex: name@gmail.com" value="<?php echo htmlspecialchars($email); ?>">
                    <label for="floatingInput">Email address</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}" title="Password must be at least 8 characters long and contain one uppercase letter, one lowercase letter, one digit, and one special character.">
                    <label for="floatingPassword">Password</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" onclick="togglePasswordVisibility('floatingPassword')">
                        <label class="form-check-label" for="showPassword">SHOW PASSWORD</label>
                    </div>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Next</button>
                <div class="mt-3 text-center">
                    <a href="page_login.php">Back to login</a>
                </div>
            </form>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePasswordVisibility(passwordFieldId) {
            var passwordField = document.getElementById(passwordFieldId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }
    </script>
</body>
</html>
