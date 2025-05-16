<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Include the database connection file
require '../connection.php';

// Check if a slot_id is provided
if (!isset($_GET['slot_id'])) {
    header("Location: parking_slots.php");
    exit();
}

$slot_id = intval($_GET['slot_id']);
$user_id = $_SESSION['user_id'];

// Fetch user's vehicles from the database
$query = "SELECT vehicle_id, license_plate FROM Vehicles WHERE user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$vehicles = $stmt->get_result();

// Define the rate per hour
$rate_per_hour = 200.00;

// Handle form submission for booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $vehicle_id = intval($_POST['vehicle']);
    $session_hours = intval($_POST['session_hours']);
    $amount = $session_hours * $rate_per_hour;
    
    // Insert booking record into the Bookings table
    $query = "INSERT INTO Bookings (user_id, slot_id, vehicle_id, booking_time) VALUES (?, ?, ?, NOW())";
    $stmt = $con->prepare($query);
    $stmt->bind_param("iii", $user_id, $slot_id, $vehicle_id);
    $stmt->execute();
    $booking_id = $stmt->insert_id;

    // Calculate start_time and end_time for the session
    $start_time = new DateTime();
    $end_time = clone $start_time;
    $end_time->modify("+{$session_hours} hours");

    // Insert session record into the Sessions table
    $query = "INSERT INTO Sessions (booking_id, slot_id, start_time, end_time) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("iiss", $booking_id, $slot_id, $start_time->format('Y-m-d H:i:s'), $end_time->format('Y-m-d H:i:s'));
    $stmt->execute();

    // Insert payment record into the Payments table
    $query = "INSERT INTO Payments (booking_id, amount, payment_time) VALUES (?, ?, NOW())";
    $stmt = $con->prepare($query);
    $stmt->bind_param("id", $booking_id, $amount);
    $stmt->execute();

    // Update the parking slot's status to occupied
    $query = "UPDATE ParkingSlots SET is_available = 0 WHERE slot_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $slot_id);
    $stmt->execute();

    // Redirect to the user's bookings page or a confirmation page
    header("Location: user_bookings.php?success=1");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Parking Slot - Digital Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Internal CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('../parking1.jpg'); 
            background-size: cover; /* Make the background cover the whole area */
            margin: 0;
            padding: 0;
        }

        /* Navbar styles */
        .navbar {
            background-color: #2c3e50 !important; 
        }

        .navbar-brand {
            font-weight: bold;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
        }

        .navbar-nav .nav-link.active {
            font-weight: bold; /* Bold for the active link */
            color: #fff; /* White color for active link */
        }

        /* Form container styling */
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-top: 50px;
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Headings styling */
        h3 {
            color: midnightblue;
            font-weight: bold;
        }

        /* Form field styling */
        .form-group label {
            font-weight: bold;
            color: midnightblue;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .form-control:focus {
            border-color: midnightblue;
            box-shadow: 0 0 5px rgba(25, 25, 112, 0.5);
        }

        /* Button styling */
        .btn-primary {
            background-color: midnightblue;
            border-color: midnightblue;
            transition: background-color 0.3s, transform 0.3s;
        }

        .btn-primary:hover {
            background-color: #ffd700;
            border-color: #ffd700;
            color: midnightblue;
            transform: scale(1.05);
        }

        /* Footer styles */
        footer {
            background-color: #2c3e50 !important;
            color: white;
            padding: 10px 0;
            margin-top: 30px;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        footer p {
            margin: 0;
            color: #fff;
        }

        /* Animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Script to calculate payment amount -->
    <script>
        function updateAmount() {
            const ratePerHour = <?php echo $rate_per_hour; ?>;
            const sessionHours = parseInt(document.getElementById('session_hours').value) || 0;
            const amount = sessionHours * ratePerHour;
            document.getElementById('amount').value = amount.toFixed(2);
        }
    </script>
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
                    <a class="nav-link active" href="parking_slots.php">Parking Slots</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <h3 class="mb-4">Book Parking Slot</h3>
        <form method="post">
            <div class="form-group">
                <label for="vehicle">Select Your Vehicle</label>
                <select class="form-control" id="vehicle" name="vehicle" required>
                    <?php while ($vehicle = $vehicles->fetch_assoc()): ?>
                        <option value="<?php echo $vehicle['vehicle_id']; ?>">
                            <?php echo htmlspecialchars($vehicle['license_plate']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="session_hours">Session Time</label>
                <select class="form-control" id="session_hours" name="session_hours" onchange="updateAmount()" required>
                    <option value="0">0 hour</option>
                    <option value="1">1 hour</option>
                    <option value="2">2 hours</option>
                    <option value="3">3 hours</option>
                    <option value="4">4 hours</option>
                    <option value="5">5 hours</option>
                    <option value="6">6 hours</option>
                    <option value="7">7 hours</option>
                    <option value="8">8 hours</option>
                    <option value="9">9 hours</option>
                    <option value="10">10 hours</option>
                    <option value="11">11 hours</option>
                    <option value="12">12 hours</option>
                    <option value="13">13 hours</option>
                    <option value="14">14 hours</option>
                    <option value="15">15 hours</option>
                    <option value="16">16 hours</option>
                    <option value="17">17 hours</option>
                    <option value="18">18 hours</option>
                    <option value="19">19 hours</option>
                    <option value="20">20 hours</option>
                    <option value="21">21 hours</option>
                    <option value="22">22 hours</option>
                    <option value="23">23 hours</option>
                       <option value="24">24 hours</option>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Payment Amount</label>
                <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars(number_format($amount, 2)); ?>" readonly>
            </div>

            <div class="form-group">
                <label for="card_type">Card Type</label>
                <select class="form-control" id="card_type" name="card_type" required>
                    <option value="credit">Credit Card</option>
                    <option value="debit">Debit Card</option>
                </select>
            </div>

            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" required>
            </div>

            <div class="form-group">
                <label for="expiry_date">Expiry Date</label>
                <input type="month" class="form-control" id="expiry_date" name="expiry_date" required>
            </div>

            <button type="submit" class="btn btn-primary">Confirm Booking</button>
        </form>
    </div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


