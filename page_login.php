<!doctype html>
<html lang="en" data-bs-theme="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Barber Station</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('img/barbershop-bg.jpg'); /* Replace with a high-quality barbershop-themed background */
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
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.85); /* Slightly transparent white */
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3); /* Soft shadow for depth */
        }
        .form-signin {
            text-align: center;
        }
        .form-signin img {
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
            font-size: 0.85em;
        }
        .error-message {
            color: red;
            font-size: 0.85em;
            margin-top: 10px;
        }
        .form-check-input:checked {
            background-color: #d69824;
            border-color: #d69824;
        }
        .form-signin h1 {
            color: #333;
            font-weight: bold;
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
            <form action="process_login.php" method="post">
                <center><img class="mb-4" src="img/barberlogo.png" alt="" width="150" height="150"></center>
                <h1 class="h3 mb-3 fw-normal">Login to Your Account</h1>

                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="Email Address" required pattern="[a-z0-9._%+\-]+@[a-z0-9.\-]+\.[a-z]{2,}$" title="Ex. name@gmail.com">
                    <label for="floatingInput">Email address</label>
                </div>

                <div class="form-floating">
                    <input type="password" class="form-control" id="floatingPassword" name="password" placeholder="Password" required>
                    <label for="floatingPassword">Password</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" onclick="togglePasswordVisibility('floatingPassword')" id="showPassword">
                        <label class="form-check-label" for="showPassword">SHOW PASSWORD</label>
                    </div>
                </div>

                <!-- Display error message here -->
                <?php if (isset($_GET['error'])): ?>
                    <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>

                <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
                
                <div class="mt-3 text-center">
                    <a href="process_forgotpass.php">Forgot your password?</a>
                </div>
                <div class="mt-3 text-center">
                    <a href="page_register.php">Don't have an account yet?</a>
                </div>
                <div class="mt-3 text-center">
                    <a href="index.php">Back to Homepage</a>
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
