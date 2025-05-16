<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the database connection file
require '../connection.php';

// Fetch user bookings from the database
$user_id = $_SESSION['user_id'];
$query = "
    SELECT 
        b.booking_id, b.booking_time, ps.slot_number, v.license_plate, 
        p.amount, p.payment_time 
    FROM 
        Bookings b
    JOIN 
        ParkingSlots ps ON b.slot_id = ps.slot_id
    JOIN 
        Vehicles v ON b.vehicle_id = v.vehicle_id
    LEFT JOIN 
        Payments p ON b.booking_id = p.booking_id
    WHERE 
        b.user_id = ?
    ORDER BY 
        b.booking_time DESC";
        
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings - Digital Parking System</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>


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
                    <a class="nav-link active" href="user_bookings.php">My Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>


    <div class="container mt-5 content">
        <h3 class="mb-4">My Bookings</h3>
        <?php if ($bookings->num_rows > 0): ?>
            <div class="card">
                <div class="card-header">
                    <h4>Booking Details</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Booking ID</th>
                                <th scope="col">Booking Time</th>
                                <th scope="col">Slot Number</th>
                                <th scope="col">Vehicle</th>
                                <th scope="col">Amount Paid</th>
                                <th scope="col">Payment Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($booking = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['slot_number']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['license_plate']); ?></td>
                                    <td><?php echo isset($booking['amount']) ? htmlspecialchars($booking['amount']) : 'Pending'; ?></td>
                                    <td><?php echo isset($booking['payment_time']) ? htmlspecialchars($booking['payment_time']) : 'Pending'; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                You have no bookings yet.
            </div>
        <?php endif; ?>
    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
