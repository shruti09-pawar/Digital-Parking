<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

require '../connection.php'; // Include your database connection file

// Check if the user ID is set in the URL
if(isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch the user's current data
    $select_query = "SELECT * FROM Users WHERE user_id = ?";
    $stmt = $con->prepare($select_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // If the user does not exist, redirect to manage_users.php
    if(!$user) {
        header("Location: manage_users.php");
        exit();
    }
}

// Handle the form submission for updating the user
if(isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // If a new password is provided, hash it and update it
    if(!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $update_query = "UPDATE Users SET username = ?, password_hash = ?, email = ?, phone = ? WHERE user_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("ssssi", $username, $password, $email, $phone, $user_id);
    } else {
        // If no new password is provided, only update other fields
        $update_query = "UPDATE Users SET username = ?, email = ?, phone = ? WHERE user_id = ?";
        $stmt = $con->prepare($update_query);
        $stmt->bind_param("sssi", $username, $email, $phone, $user_id);
    }

    $stmt->execute();

    // Redirect to manage_users.php after updating
    header("Location: manage_users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Digital Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #f1c40f;
            --background-color: #ecf0f1;
            --text-color: #2d3436;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../parking1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-color);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3); /* Dark overlay */
            z-index: -1;
        }

        .container {
            margin-top: 100px;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 1s ease-in-out;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            text-align: center;
            padding: 20px;
        }

        .card-body {
            background-color: #fff;
            padding: 40px;
        }

        .form-control {
            border-radius: 50px;
            padding: 15px;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        .card-footer {
            background-color: var(--background-color);
            text-align: center;
            padding: 20px;
        }

        .card-footer a {
            color: var(--primary-color);
            font-weight: bold;
            text-decoration: none;
        }

        .card-footer a:hover {
            color: var(--secondary-color);
        }

        /* Add subtle fade-in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                margin-top: 50px;
            }

            .card-header h4 {
                font-size: 1.8rem;
            }
        }

        /* Navbar Styling */
        .navbar {
            background-color: var(--primary-color);
        }

        .navbar-brand, .nav-link {
            color: #fff !important;
        }

        .navbar-toggler-icon {
            background-color: var(--secondary-color);
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
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
                    <a class="nav-link" href="manage_users.php">Manage Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Overlay -->
    <div class="overlay"></div>

    <!-- Edit User Form -->
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit User</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                            <!-- <div class="form-group">
                                <label for="password">New Password (Leave blank to keep current password)</label>
                                <input type="password" class="form-control" name="password">
                            </div> -->
                            <button type="submit" name="update_user" class="btn btn-primary btn-block">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
