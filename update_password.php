<?php
require 'connection.php'; // Include your database connection file

if (isset($_POST['token']) && isset($_POST['password'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];

    // Validate token and get user data
    $stmt = $con->prepare("SELECT user_id FROM users WHERE token = ?");
    $stmt->bind_param('s', $token);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $user_id = $user['user_id'];

        // Hash the new password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Update the password and clear the token
        $update_stmt = $con->prepare("UPDATE users SET password_hash = ?, token = NULL WHERE user_id = ?");
        $update_stmt->bind_param('si', $password_hash, $user_id);
        $update_stmt->execute();

        if ($update_stmt->affected_rows > 0) {
            echo "Password updated successfully!";
        } else {
            echo "Error updating password.";
        }

        $update_stmt->close();
    } else {
        echo "Invalid or expired token.";
    }

    $stmt->close();
}
?>
