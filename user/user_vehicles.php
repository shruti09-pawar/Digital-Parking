<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the database connection file
require '../connection.php';

// Fetch user vehicles from the database with category name
$user_id = $_SESSION['user_id'];
$query = "
    SELECT Vehicles.vehicle_id, Vehicles.license_plate, Categories.category_name
    FROM Vehicles
    JOIN Categories ON Vehicles.category_id = Categories.category_id
    WHERE Vehicles.user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$vehicles = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Vehicles - Digital Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #f1c40f; 
            --background-color: #ecf0f1;
            --text-color: #2d3436;
            --btn-primary-bg: #1a202c;
            --btn-primary-hover: #34495e;
            --btn-secondary-bg: #6c757d;
            --btn-secondary-hover: #5a6268;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: url('../parking1.jpg') no-repeat center center fixed;
            background-size: cover;
            color: var(--text-color);
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: var(--primary-color) !important;
        }

        .navbar-brand, .nav-link {
            color: #fff;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #fff;
        }

        .container {
            margin-top: 40px;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        h3 {
            background-color: rgba(44, 62, 80, 0.7);
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .btn-primary {
            background-color: var(--btn-primary-bg);
            border: none;
            color: #fff;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: var(--btn-primary-hover);
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-danger:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .table {
            border-radius: 10px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: var(--primary-color);
            color: #fff;
        }

        .table tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }

        .table tbody tr:hover {
            background-color: var(--secondary-color);
            color: var(--text-color);
        }

        .footer {
            background-color: var(--primary-color);
            color: #fff;
            padding: 40px 0;
            position: relative;
            bottom: 0;
            width: 100%;
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
                    <a class="nav-link" href="user_dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="user_profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="user_vehicles.php">Manage Vehicles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">Your Vehicles</h3>
        <div class="mb-4">
            <a href="add_vehicle.php" class="btn btn-success">Add Vehicle</a>
        </div>

        <?php if ($vehicles->num_rows > 0): ?>
            <div class="row">
                <?php while ($vehicle = $vehicles->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($vehicle['license_plate']); ?></h5>
                                <p class="card-text">Category: <?php echo htmlspecialchars($vehicle['category_name']); ?></p>
                                <a href="edit_vehicle.php?vehicle_id=<?php echo $vehicle['vehicle_id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete_vehicle.php?vehicle_id=<?php echo $vehicle['vehicle_id']; ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                You have no registered vehicles.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
