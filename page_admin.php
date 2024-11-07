<?php
session_start();
require_once 'conx.php';

// Check if the user is logged in and has appropriate access level (for example, admin access)
if (!isset($_SESSION['userID']) || $_SESSION['uLevel'] != 1) { // Assuming uLevel 1 is admin
    header('Location: page_login.php');
    exit();
}

try {
    // Fetch total pending reservations
    $sql = "SELECT COUNT(*) AS pendingCount FROM booking WHERE reservationStatus = 'pending'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $pendingCount = $stmt->fetch(PDO::FETCH_ASSOC)['pendingCount'];

    // Fetch total accepted reservations
    $sql = "SELECT COUNT(*) AS acceptedCount FROM booking WHERE reservationStatus = 'accepted'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $acceptedCount = $stmt->fetch(PDO::FETCH_ASSOC)['acceptedCount'];

    // Fetch total users
    $sql = "SELECT COUNT(*) AS totalUsers FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['totalUsers'];

    // Fetch total amount from accepted reservations
    $sql = "SELECT SUM(totalCost) AS totalAmount FROM booking WHERE reservationStatus = 'accepted'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $totalAmount = $stmt->fetch(PDO::FETCH_ASSOC)['totalAmount'];

} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            background-color: #d69824;
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            padding: 20px;
            box-sizing: border-box;
        }
        .sidebar h2 {
            margin: 0;
            font-size: 1.5em;
            text-align: center;
            color: white;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 20px 0;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover {
            background-color: #f2c94c;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            background-color: #f8f8f8;
        }
        .content h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .dashboard-item {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        .dashboard-item:hover {
            transform: translateY(-10px);
        }
        .dashboard-item h2 {
            margin: 0;
            font-size: 2.5em;
            color: #d69824;
        }
        .dashboard-item p {
            margin: 10px 0;
            color: #333;
        }
        .dashboard-item .icon {
            font-size: 3em;
            color: #888;
            margin-bottom: 10px;
        }
        /* Custom Styles for Icons */
        .yellow { color: #f0ad4e; }
        .red { color: #d9534f; }
        .green { color: #5cb85c; }
        
        /* Add styling for top logo and navigation links */
        header {
            background-color: #d69824;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
        header img {
            height: 50px;
        }
        header nav ul {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s;
        }
        header nav ul li a:hover {
            color: #cccccc;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>Admin Dashboard</h2>
        <ul>
            <li><a href="#">Dashboard</a></li>
            <li><a href="users_audit.php">Users</a></li>
            <li><a href="auditlog.php">Audit Log</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Admin Dashboard</h1>
        <div class="dashboard-grid">
            <div class="dashboard-item">
                <div class="icon yellow">&#9203;</div>
                <h2><?php echo $pendingCount; ?></h2>
                <p>Pending Reservations</p>
            </div>
            <div class="dashboard-item">
                <div class="icon green">&#128101;</div>
                <h2><?php echo $acceptedCount; ?></h2>
                <p>Accepted Reservations</p>
            </div>
            <div class="dashboard-item">
                <div class="icon green">&#128100;</div>
                <h2><?php echo $totalUsers; ?></h2>
                <p>Total Users</p>
            </div>
            <div class="dashboard-item">
                <div class="icon">&#128176;</div>
                <h2>₱<?php echo number_format($totalAmount, 2); ?></h2>
                <p>Total Amount</p>
            </div>
        </div>
    </div>
</body>
</html>
