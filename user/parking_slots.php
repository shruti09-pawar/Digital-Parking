<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the database connection file
require '../connection.php';

// Fetch available parking slots from the database
$query = "SELECT * FROM ParkingSlots ORDER BY slot_number ASC";
$stmt = $con->prepare($query);
$stmt->execute();
$slots = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Slots - Digital Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c3e50; /* Midnight Blue */
            --secondary-color: #f1c40f; /* Yellow */
            --background-color: #ecf0f1;
            --text-color: #2d3436;
            --btn-primary-bg: #1a202c; /* Darker shade of blue for buttons */
            --btn-primary-hover: #34495e; /* Lighter shade of blue for hover effect */
            --btn-secondary-bg: #6c757d; /* Gray color for secondary button */
            --btn-secondary-hover: #5a6268; /* Darker gray for hover effect */
        }

        body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: url('../parking1.jpg') no-repeat center center fixed; 
                background-size: cover; /* Ensure the image covers the entire background */
                color: var(--text-color);
                display: flex;
                flex-direction: column;
        }

        .navbar {
            background-color: var(--primary-color) !important; /* Use !important to ensure the custom color is applied */
        }


        .navbar-brand, .nav-link {
            color: #fff;
        }

        .navbar-brand {
            font-weight: bold;
        }

            .navbar-nav .nav-link.active {
                font-weight: bold; /* Bold for the active link */
                color: #fff; /* White color for active link */
                }

            /* Style for the heading */
            .content h3 {
                background-color: rgba(44, 62, 80, 0.7); /* Semi-transparent background */
                color: #fff; /* Text color */
                padding: 10px; /* Padding for some space */
                border-radius: 5px; /* Rounded corners */
                text-align: center; /* Center the text */
                margin-bottom: 20px; /* Spacing below the header */
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

            .card-header {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: #fff;
                text-align: center;
                padding: 20px;
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

            .btn-secondary {
                background-color: var(--btn-secondary-bg);
                border: none;
                color: #fff;
                transition: background-color 0.3s, transform 0.3s;
            }

            .btn-secondary:hover {
                background-color: var(--btn-secondary-hover);
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

            .table tbody tr:nth-child(even) {
                background-color: #ffffff;
                transition: background-color 0.3s;
            }

            .table tbody tr:hover {
                background-color: var(--secondary-color);
                color: var(--text-color);
            }

            .alert-info {
                background-color: var(--primary-color);
                color: #fff;
            }

            .footer {
                background-color: var(--primary-color);
                color: #fff;
                padding: 40px 0;
                border-top: 5px solid var(--secondary-color);
                position: relative;
                bottom: 0;
                width: 100%;
            }

            .footer::before {
                content: "";
                position: absolute;
                top: -10px;
                left: 0;
                width: 100%;
                height: 10px;
                background: linear-gradient(180deg, transparent, var(--primary-color));
            }

            .footer h5 {
                font-weight: bold;
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
                    <a class="nav-link active" href="parking_slots.php">Parking Slots</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 content">
        <h3 class="mb-4">Available Parking Slots</h3>
        <?php if ($slots->num_rows > 0): ?>
            <div class="row">
                <?php while ($slot = $slots->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-header">
                                Slot <?php echo htmlspecialchars($slot['slot_number']); ?>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    Status: 
                                    <?php 
                                    $availability = $slot['is_available'];
                                    echo $availability ? 'Available' : 'Occupied';
                                    ?>
                                </p>
                                <?php if ($availability): ?>
                                    <a href="book_slot.php?slot_id=<?php echo $slot['slot_id']; ?>" class="btn btn-primary">Book Now</a>
                                <?php else: ?>
                                    <button class="btn btn-secondary" disabled>Unavailable</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No parking slots available at the moment.
            </div>
        <?php endif; ?>
    </div>


    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome Icons -->
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> -->
</body>
</html>
