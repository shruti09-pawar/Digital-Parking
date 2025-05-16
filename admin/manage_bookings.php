<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

require '../connection.php'; // Include your database connection file

// Handle booking deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM Bookings WHERE booking_id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: manage_bookings.php");
    exit();
}

// Fetch all bookings
$fetch_query = "SELECT b.booking_id, b.booking_time, u.username, ps.slot_number, v.license_plate
                FROM Bookings b
                JOIN Users u ON b.user_id = u.user_id
                JOIN ParkingSlots ps ON b.slot_id = ps.slot_id
                JOIN Vehicles v ON b.vehicle_id = v.vehicle_id";
$bookings_result = $con->query($fetch_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings - Digital Parking System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/styles.css" rel="stylesheet">
    <style>
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
                    <a class="nav-link active" href="manage_bookings.php">Manage Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5 content">
        <h3 class="mb-4">Manage Bookings</h3>
        <?php if ($bookings_result->num_rows > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h4>Booking Details</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">User</th>
                                <th scope="col">Parking Slot</th>
                                <th scope="col">Vehicle</th>
                                <th scope="col">Booking Time</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $bookings_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                                    <td><?php echo htmlspecialchars($row['slot_number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['license_plate']); ?></td>
                                    <td><?php echo htmlspecialchars($row['booking_time']); ?></td>
                                    <td>
                                        <a href="manage_bookings.php?delete_id=<?php echo $row['booking_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this booking?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No bookings found.
            </div>
        <?php endif; ?>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
