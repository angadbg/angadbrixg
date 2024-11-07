<?php
session_start();
require_once 'conx.php'; // Database connection

// Uncomment to debug session values
// echo "User ID: " . $_SESSION['userID'] . "<br>";
// echo "User Level: " . $_SESSION['uLevel'] . "<br>";

// Initialize variables
$pendingCount = 0;
$totalPayments = 0;

try {
    // Fetch pending reservations
    $sql = "SELECT * FROM booking WHERE reservationStatus = 'pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pendingReservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $pendingCount = count($pendingReservations);

    // Fetch total payments for the current month
    $sqlPayments = "SELECT SUM(totalCost) as totalPayments FROM booking WHERE paymentStatus = 'completed' AND created_at >= CURDATE() - INTERVAL DAYOFMONTH(CURDATE())-1 DAY";
    $stmtPayments = $pdo->prepare($sqlPayments);
    $stmtPayments->execute();
    $paymentData = $stmtPayments->fetch(PDO::FETCH_ASSOC);
    $totalPayments = $paymentData['totalPayments'] ? $paymentData['totalPayments'] : 0;

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
} catch (Exception $e) {
    echo "General Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('img/barbershop-bg.jpg'); /* Barbershop-themed background */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: 'Montserrat', sans-serif;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9); /* Slight transparency for the dashboard */
            border-radius: 15px;
            padding: 40px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
            max-width: 800px;
            width: 100%;
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .card {
            background-color: #f8f9fa;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-size: 1.5rem;
            color: #d69824; /* Golden barber color */
        }
        .card-body h1 {
            font-size: 3rem;
            color: #333;
        }
        .btn-primary {
            background-color: #d69824;
            border: none;
        }
        .btn-primary:hover {
            background-color: #b77f1e;
        }
        .btn-danger {
            background-color: #d9534f;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Staff Dashboard</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Pending Requests</h5>
                        <h1><?php echo $pendingCount; ?></h1>
                        <a href="view_pending_requests.php" class="btn btn-primary">View Requests</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Total Payments This Cycle</h5>
                        <h1>₱<?php echo number_format($totalPayments, 2); ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- Logout Button -->
        <div class="text-center mt-4">
            <form action="logout.php" method="POST">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
