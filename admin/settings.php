<?php 
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

require '../connection.php'; // Include your database connection file

// Fetch current admin details
$username = $_SESSION['username'];
$admin_query = "SELECT * FROM Admins WHERE username = ?";
$stmt = $con->prepare($admin_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$admin_result = $stmt->get_result();
$admin_data = $admin_result->fetch_assoc();

// Handle form submissions
$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $new_email = $_POST['email'];
        $new_phone = $_POST['phone'];

        $update_query = "UPDATE Admins SET email = ?, phone = ? WHERE username = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("sss", $new_email, $new_phone, $username);

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
        } else {
            $error_message = "Failed to update profile. Please try again.";
        }

        $stmt->close();
    } elseif (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        // Verify the current password
        if (password_verify($current_password, $admin_data['password_hash'])) {
            $new_password_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $update_password_query = "UPDATE Admins SET password_hash = ? WHERE username = ?";
            $stmt = $con->prepare($update_password_query);
            $stmt->bind_param("ss", $new_password_hash, $username);

            if ($stmt->execute()) {
                $success_message = "Password changed successfully!";
            } else {
                $error_message = "Failed to change password. Please try again.";
            }

            $stmt->close();
        } else {
            $error_message = "Current password is incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Digital Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
    <style>
        /* Color Theme Matching the Login and Profile Pages */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #f1c40f;
            --background-color: #ecf0f1;
            --text-color: #2d3436;
        }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content {
            flex: 1;
        }

        .navbar {
            background-color: var(--primary-color);
        }

        .navbar-brand, .nav-link {
            color: #fff;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .footer {
            background-color: var(--primary-color);
            color: #fff;
            padding: 40px 0;
            border-top: 5px solid ;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        .footer h5 {
            font-weight: bold;
        }

        .footer .social-icons a {
            color: #fff;
            margin: 0 10px;
            font-size: 1.5rem;
            transition: color 0.3s, transform 0.3s;
        }

        .footer .social-icons a:hover {
            color: var(--secondary-color);
            transform: scale(1.1);
        }

        .footer .contact-info a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer .contact-info a:hover {
            color: var(--secondary-color);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Digital Parking System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="settings.php">Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 content">
        <div class="card">
            <div class="card-header">
                <h4>Settings</h4>
            </div>
            <div class="card-body">
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <!-- Update Profile -->
                <h5>Update Profile</h5>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($admin_data['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($admin_data['phone']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                </form>

                <hr>

                <!-- Change Password -->
                <h5>Change Password</h5>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
