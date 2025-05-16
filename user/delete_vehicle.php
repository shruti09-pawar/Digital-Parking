<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the database connection file
require '../connection.php';

// Check if vehicle_id is provided
if (isset($_GET['vehicle_id'])) {
    // Sanitize the vehicle_id to ensure it's a valid integer
    $vehicle_id = intval($_GET['vehicle_id']);

    // Prepare the delete statement
    $query = "DELETE FROM Vehicles WHERE vehicle_id = ?";
    $stmt = $con->prepare($query);
    
    if ($stmt) {
        // Bind the parameter
        $stmt->bind_param("i", $vehicle_id);

        // Execute the query and check for success
        if ($stmt->execute()) {
            // Redirect back to the vehicles page with a success message
            header("Location: user_vehicles.php?message=Vehicle deleted successfully");
        } else {
            // Redirect back with an error message
            header("Location: user_vehicles.php?error=Unable to delete vehicle");
        }
        // Close the statement
        $stmt->close();
    } else {
        // Redirect back with an error message if the statement could not be prepared
        header("Location: user_vehicles.php?error=Database error");
    }
} else {
    // Redirect back if vehicle_id is not provided
    header("Location: user_vehicles.php?error=No vehicle ID provided");
}

// Close the database connection
$con->close();
?>
