<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the database connection file
require '../connection.php';

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM Users WHERE user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Digital Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50; 
            --secondary-color: #f1c40f; 
            --background-color: #ecf0f1;
            --text-color: #2d3436;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        
        .navbar {
            background-color: var(--primary-color);
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar-nav .nav-link.active {
            font-weight: bold; 
            color: #fff; 
        }
        
        .navbar-toggler-icon {
            background-color: var(--secondary-color);
        }

       
        .sidebar {
            height: 100vh;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--primary-color);
            padding-top: 20px;
            transition: width 0.3s;
            display: none; 
        }

        .sidebar a {
            padding: 15px;
            text-align: left;
            font-size: 1.1rem;
            color: #fff;
            display: block;
            transition: 0.3s;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: var(--secondary-color);
            color: var(--primary-color);
        }

        .sidebar i {
            margin-right: 10px;
        }

        
        .main-content {
            margin-left: 0;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: #fff;
            text-align: center;
            padding: 15px;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
            font-weight: bold;
            border-radius: 50px;
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
            color: #fff;
        }

        
        footer {
            background-color: var(--primary-color);
            color: #fff;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            width: 100%;
            bottom: 0;
        }

        
        @media (min-width: 992px) {
            .sidebar {
                display: block; 
            }
            .main-content {
                margin-left: 250px;
            }
        }

        @media (max-width: 992px) {
            .sidebar {
                display: none; 
            }
            .navbar-collapse {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">Digital Parking System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    
    <div class="sidebar">
        <a href="user_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_profile.php"><i class="fas fa-user"></i> Profile</a>
        <a href="user_bookings.php"><i class="fas fa-history"></i> My Bookings</a>
        <a href="parking_slots.php"><i class="fas fa-parking"></i> Parking Slots</a>
        <a href="user_vehicles.php"><i class="fas fa-car"></i> My Vehicles</a>
        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    
    <div class="main-content">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title">Welcome, <?php echo htmlspecialchars($user['username']); ?></h5>
                            <p class="card-text">View and manage your bookings, vehicles, and more.</p>
                            <a href="user_profile.php" class="btn btn-primary">Profile</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            Dashboard Overview
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">My Bookings</h5>
                            <p class="card-text">View your current and past bookings.</p>
                            <a href="user_bookings.php" class="btn btn-success">Booking History</a>
                            
                            <h5 class="mt-4">Parking Slots</h5>
                            <p class="card-text">View available parking slots.</p>
                            <a href="parking_slots.php" class="btn btn-info">View Slots</a>
                            
                            <h5 class="mt-4">My Vehicles</h5>
                            <p class="card-text">Manage your registered vehicles.</p>
                            <a href="user_vehicles.php" class="btn btn-warning">Manage Vehicles</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <footer>
        <p>&copy; 2025 Digital Parking System. All Rights Reserved.</p>
    </footer>

    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
