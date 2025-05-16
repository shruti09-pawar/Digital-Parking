<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the database connection file
require '../connection.php';

// Check if vehicle_id is provided in the URL
if (!isset($_GET['vehicle_id'])) {
    echo "<script>alert('Vehicle ID not provided!'); window.location.href='user_vehicles.php';</script>";
    exit();
}

$vehicle_id = $_GET['vehicle_id'];

// Fetch vehicle details from the database
$query = "SELECT * FROM Vehicles WHERE vehicle_id = ? AND user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("ii", $vehicle_id, $_SESSION['user_id']);
$stmt->execute();
$vehicle = $stmt->get_result()->fetch_assoc();

if (!$vehicle) {
    echo "<script>alert('Vehicle not found or you do not have permission to edit this vehicle.'); window.location.href='user_vehicles.php';</script>";
    exit();
}

// Handle vehicle update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $license_plate = $_POST['license_plate'];
    $category_id = $_POST['category_id'];

    // Update the vehicle details in the database
    $update_query = "UPDATE Vehicles SET license_plate = ?, category_id = ? WHERE vehicle_id = ? AND user_id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("siii", $license_plate, $category_id, $vehicle_id, $_SESSION['user_id']);

    if ($stmt->execute()) {
        echo "<script>alert('Vehicle updated successfully!'); window.location.href='user_vehicles.php';</script>";
    } else {
        echo "<script>alert('An error occurred. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Vehicle - Digital Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        /* Color Theme Matching the Home Page */
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
            background: rgba(0, 0, 0, 0.3); /* Dark overlay to ensure readability */
            z-index: -1;
        }

        /* Custom styles for Navbar */
        .navbar {
            background-color: var(--primary-color) !important; /* Midnight blue */
        }
        
        .navbar-brand {
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #fff !important; 
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar-nav .nav-link:hover {
            color: #fff !important;
        }

        .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #fff !important;
        }

        .navbar-toggler {
            border: none !important;
        }

        .navbar-toggler-icon {
            background-color: var(--secondary-color) !important; /* Yellow color for toggle icon */
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
    </style>
</head>
<body>
    <div class="overlay"></div>
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
                    <a class="nav-link active" href="user_vehicles.php">Manage Vehicles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Vehicle</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="form-group">
                                <label for="license_plate">License Plate</label>
                                <input type="text" class="form-control" id="license_plate" name="license_plate" value="<?php echo htmlspecialchars($vehicle['license_plate']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="category_id">Vehicle Category</label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <?php
                                    // Fetch all categories to display in the dropdown
                                    $category_query = "SELECT * FROM Categories";
                                    $categories_result = $con->query($category_query);
                                    while ($category = $categories_result->fetch_assoc()):
                                    ?>
                                        <option value="<?php echo $category['category_id']; ?>" <?php echo ($vehicle['category_id'] == $category['category_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($category['category_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Update Vehicle</button>
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
