<?php
require_once 'conx.php'; // Include your database connection
session_start();

// Check if the user is logged in and has admin access
if (!isset($_SESSION['userID']) || $_SESSION['uLevel'] != 1) {
    echo '<script>alert("You are not authorized to perform this action."); window.location.href = "page_login.php";</script>';
    exit();
}

// Check if userID is provided in the URL
if (isset($_GET['userID'])) {
    $userID = $_GET['userID'];

    try {
        // Begin a transaction to ensure both deletions happen together
        $pdo->beginTransaction();

        // Step 1: Delete any related audit trail entries for the user
        $sqlDeleteAudit = "DELETE FROM audittrail WHERE userID = :userID";
        $stmtAudit = $pdo->prepare($sqlDeleteAudit);
        $stmtAudit->bindParam(':userID', $userID);
        $stmtAudit->execute();

        // Step 2: Delete any related bookings for the user
        $sqlDeleteBookings = "DELETE FROM booking WHERE userID = :userID";
        $stmtBookings = $pdo->prepare($sqlDeleteBookings);
        $stmtBookings->bindParam(':userID', $userID);
        $stmtBookings->execute();

        // Step 3: Delete the user from the users table
        $sqlDeleteUser = "DELETE FROM users WHERE userID = :userID";
        $stmtUser = $pdo->prepare($sqlDeleteUser);
        $stmtUser->bindParam(':userID', $userID);
        $stmtUser->execute();

        // Commit the transaction after successful deletion
        $pdo->commit();

        // User, bookings, and audit trail entries successfully deleted
        echo '<script>alert("User and related records deleted successfully!"); window.location.href = "users_audit.php";</script>';
    } catch (PDOException $e) {
        // Roll back the transaction in case of error
        $pdo->rollBack();
        echo "Database Error: " . $e->getMessage();
    }
} else {
    // If no userID is provided
    echo '<script>alert("Invalid request."); window.location.href = "users_audit.php";</script>';
}
?>
