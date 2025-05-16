<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Digital Parking System</title>
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
            padding: 10px 20px;
        }

        .navbar-brand, .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar-nav .nav-link:hover {
            color: var(--secondary-color);
        }

        .navbar-toggler {
            border: none;
        }

        .navbar-toggler-icon {
            background-image: url('data:image/svg+xml;charset=utf8,%3Csvg xmlns="http://www.w3.org/2000/svg" fill="%23f1c40f" viewBox="0 0 30 30"%3E%3Cpath stroke="rgba(0, 0, 0, 0.5)" stroke-width="2" d="M4 7h22M4 15h22M4 23h22"/%3E%3C/svg%3E');
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
            margin-left: 250px;
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
                    <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-white" aria-current="page" href="#">
                                <span class="font-weight-bold">Digital Parking System</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="admin_dashboard.php">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="manage_users.php">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="manage_parking_slots.php">
                                <i class="fas fa-parking"></i> Manage Slots
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="manage_vehicles.php">
                                <i class="fas fa-car"></i> Manage Vehicles
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="manage_bookings.php">
                                <i class="fas fa-book"></i> Manage Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="view_reports.php">
                                <i class="fas fa-file-alt"></i> View Reports
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="settings.php">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="../logout.php">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Admin Dashboard</h1>
                </div>

                <div class="jumbotron">
                    <h1 class="display-4">Welcome, Admin!</h1>
                    <p class="lead">This is your dashboard where you can manage users, parking slots, vehicles, and bookings.</p>
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">Manage Parking Slots</div>
                                <div class="card-body">
                                    <p class="card-text">View and manage all parking slots.</p>
                                    <a href="manage_parking_slots.php" class="btn btn-primary">Go to Parking Slots</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">Manage Bookings</div>
                                <div class="card-body">
                                    <p class="card-text">View and manage all bookings.</p>
                                    <a href="manage_bookings.php" class="btn btn-primary">Go to Bookings</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">Manage Vehicles</div>
                                <div class="card-body">
                                    <p class="card-text">View and manage all vehicles.</p>
                                    <a href="manage_vehicles.php" class="btn btn-primary">Go to Vehicles</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 Digital Parking System</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
