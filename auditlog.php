<?php
session_start();
require_once 'conx.php'; // Include your database connection

// Check if user is logged in and has appropriate access level (for example, admin access)
if (!isset($_SESSION['userID']) || $_SESSION['uLevel'] != 1) { // Assuming uLevel 1 is admin
    header('Location: page_login.php');
    exit();
}

// Initialize the search query variables
$searchFname = '';
$searchLname = '';
$searchAction = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the search values from the form
    $searchFname = isset($_POST['fname']) ? $_POST['fname'] : '';
    $searchLname = isset($_POST['lname']) ? $_POST['lname'] : '';
    $searchAction = isset($_POST['action']) ? $_POST['action'] : '';
}

// Fetch audit log entries with optional filtering
try {
    $sql = "SELECT a.aAudID, a.aAction, a.aTimestamp, a.userID, u.fname, u.lname 
            FROM audittrail a
            JOIN users u ON a.userID = u.userID
            WHERE 1=1";

    if (!empty($searchFname)) {
        $sql .= " AND u.fname LIKE :fname";
    }
    if (!empty($searchLname)) {
        $sql .= " AND u.lname LIKE :lname";
    }
    if (!empty($searchAction)) {
        $sql .= " AND a.aAction LIKE :action";
    }

    $sql .= " ORDER BY a.aTimestamp DESC";
    $stmt = $pdo->prepare($sql);

    // Bind the search parameters if provided
    if (!empty($searchFname)) {
        $stmt->bindValue(':fname', '%' . $searchFname . '%');
    }
    if (!empty($searchLname)) {
        $stmt->bindValue(':lname', '%' . $searchLname . '%');
    }
    if (!empty($searchAction)) {
        $stmt->bindValue(':action', '%' . $searchAction . '%');
    }

    $stmt->execute();
    $auditLogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Audit Log</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Montserrat', sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 1000px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .search-form {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .search-form input {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .search-form button {
            background-color: #d69824;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .table {
            margin-top: 20px;
        }
        .table th {
            background-color: #d69824;
            color: white;
        }
        .btn-primary {
            background-color: #d69824;
            border: none;
        }
        .btn-primary:hover {
            background-color: #b77f1e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Audit Log</h2>

        <!-- Search and Filtering Form -->
        <form method="POST" class="search-form">
            <input type="text" name="fname" placeholder="First Name" value="<?= htmlspecialchars($searchFname) ?>">
            <input type="text" name="lname" placeholder="Last Name" value="<?= htmlspecialchars($searchLname) ?>">
            <input type="text" name="action" placeholder="Action" value="<?= htmlspecialchars($searchAction) ?>">
            <button type="submit">Search</button>
        </form>

        <!-- Audit Log Table -->
        <?php if (count($auditLogs) > 0): ?>
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Audit ID</th>
                        <th>Action</th>
                        <th>Timestamp</th>
                        <th>User ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($auditLogs as $log): ?>
                        <tr>
                            <td><?= htmlspecialchars($log['aAudID']) ?></td>
                            <td><?= htmlspecialchars($log['aAction']) ?></td>
                            <td><?= htmlspecialchars($log['aTimestamp']) ?></td>
                            <td><?= htmlspecialchars($log['userID']) ?></td>
                            <td><?= htmlspecialchars($log['fname']) ?></td>
                            <td><?= htmlspecialchars($log['lname']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No audit log entries found.</div>
        <?php endif; ?>
        <div class="text-center">
            <a href="page_admin.php" class="btn btn-primary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
