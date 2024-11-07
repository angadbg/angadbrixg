<?php
session_start();
require_once 'conx.php'; // Include your database connection

// Check if the user is logged in and has appropriate access level (for example, admin access)
if (!isset($_SESSION['userID']) || $_SESSION['uLevel'] != 1) { // Assuming uLevel 1 is admin
    header('Location: page_login.php');
    exit();
}

// Fetch users from the database
try {
    $sql = "SELECT userID, email, uLevel, is_active FROM users";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Users Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 1000px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 30px;
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
        .btn-warning {
            background-color: #ffc107;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Users Management</h2>
        <?php if (count($users) > 0): ?>
            <table class="table table-bordered table-hover mt-4">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['userID']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php 
                                    switch($user['uLevel']) {
                                        case 1: echo 'Admin'; break;
                                        case 2: echo 'Customer'; break;
                                        case 3: echo 'Staff'; break;
                                        default: echo 'Unknown'; break;
                                    }
                                ?>
                            </td>
                            <td><?php echo $user['is_active'] ? 'Active' : 'Inactive'; ?></td>
                            <td>

                                <a href="delete_user.php?userID=<?php echo $user['userID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete Account</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No users found.</div>
        <?php endif; ?>
        <div class="text-center">
            <a href="page_admin.php" class="btn btn-primary mt-4">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
