<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

require '../connection.php'; // Include your database connection file

// Fetch total bookings
$total_bookings_query = "SELECT COUNT(*) AS total_bookings FROM Bookings";
$total_bookings_result = $con->query($total_bookings_query);
$total_bookings = $total_bookings_result->fetch_assoc()['total_bookings'];

// Fetch total payments
$total_payments_query = "SELECT SUM(amount) AS total_payments FROM Payments";
$total_payments_result = $con->query($total_payments_query);
$total_payments = $total_payments_result->fetch_assoc()['total_payments'];

// Fetch available parking slots
$available_slots_query = "SELECT COUNT(*) AS available_slots FROM ParkingSlots WHERE is_available = 1";
$available_slots_result = $con->query($available_slots_query);
$available_slots = $available_slots_result->fetch_assoc()['available_slots'];

// Fetch the most used parking slots
$most_used_slots_query = "
    SELECT ps.slot_number, COUNT(b.slot_id) AS usage_count
    FROM Bookings b
    JOIN ParkingSlots ps ON b.slot_id = ps.slot_id
    GROUP BY b.slot_id
    ORDER BY usage_count DESC
    LIMIT 5
";
$most_used_slots_result = $con->query($most_used_slots_query);

// Fetch the most active users
$most_active_users_query = "
    SELECT u.username, COUNT(b.user_id) AS booking_count
    FROM Bookings b
    JOIN Users u ON b.user_id = u.user_id
    GROUP BY b.user_id
    ORDER BY booking_count DESC
    LIMIT 5
";
$most_active_users_result = $con->query($most_active_users_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reports - Digital Parking System</title>
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

        .footer .contact-info {
            margin-bottom: 20px;
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
                    <a class="nav-link active" href="view_reports.php">View Reports</a>
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
                <h4>Reports</h4>
            </div>
            <div class="card-body">
                <!-- Total Bookings -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Total Bookings</h5>
                                <h3><?php echo $total_bookings; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Total Payments (₹)</h5>
                                <h3><?php echo number_format($total_payments, 2); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5>Available Parking Slots</h5>
                                <h3><?php echo $available_slots; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Most Used Parking Slots -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="text-center">Most Used Parking Slots</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Slot Number</th>
                                            <th>Usage Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $most_used_slots_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['slot_number']); ?></td>
                                                <td><?php echo htmlspecialchars($row['usage_count']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Most Active Users -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="text-center">Most Active Users</h5>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Booking Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($row = $most_active_users_result->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                                <td><?php echo htmlspecialchars($row['booking_count']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
