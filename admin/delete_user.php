<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

require '../connection.php'; // Include your database connection file

// Check if delete_id is set
if (isset($_GET['id'])) {
    $delete_id = $_GET['id'];

    // Start a transaction
    $con->begin_transaction();

    try {
        // First, delete related payments
        $delete_payments_query = "DELETE FROM payments WHERE booking_id IN (SELECT booking_id FROM bookings WHERE user_id = ?)";
        $stmt = $con->prepare($delete_payments_query);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        // Then, delete related bookings
        $delete_bookings_query = "DELETE FROM bookings WHERE user_id = ?";
        $stmt = $con->prepare($delete_bookings_query);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        // Then, delete related vehicles
        $delete_vehicles_query = "DELETE FROM vehicles WHERE user_id = ?";
        $stmt = $con->prepare($delete_vehicles_query);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        // Finally, delete the user
        $delete_user_query = "DELETE FROM users WHERE user_id = ?";
        $stmt = $con->prepare($delete_user_query);
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();

        // Commit the transaction
        $con->commit();

        $_SESSION['success_message'] = "User deleted successfully!";
    } catch (Exception $e) {
        // Rollback the transaction on error
        $con->rollback();
        $_SESSION['error_message'] = "Error deleting user: " . $e->getMessage();
    }

    // Redirect back to manage users page
    header("Location: manage_users.php");
    exit();
} else {
    header("Location: manage_users.php");
    exit();
}
?>
