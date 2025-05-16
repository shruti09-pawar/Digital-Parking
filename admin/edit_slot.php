<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

require '../connection.php'; // Include your database connection file

// Check if the slot ID is set in the URL
if (isset($_GET['id'])) {
    $slot_id = $_GET['id'];

    // Fetch the current slot data
    $select_query = "SELECT * FROM ParkingSlots WHERE slot_id = ?";
    $stmt = $con->prepare($select_query);
    $stmt->bind_param("i", $slot_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $slot = $result->fetch_assoc();

    // If the slot does not exist, redirect to manage_parking_slots.php
    if (!$slot) {
        header("Location: manage_parking_slots.php");
        exit();
    }
}

// Handle the form submission for updating the parking slot
if (isset($_POST['update_slot'])) {
    $slot_number = $_POST['slot_number'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    $update_query = "UPDATE ParkingSlots SET slot_number = ?, is_available = ? WHERE slot_id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("sii", $slot_number, $is_available, $slot_id);
    $stmt->execute();

    // Redirect to manage_parking_slots.php after updating
    header("Location: manage_parking_slots.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Parking Slot - Digital Parking System</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
                    <a class="nav-link" href="manage_parking_slots.php">Manage Parking Slots</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4>Edit Parking Slot</h4>
            </div>
            <div class="card-body">
                <!-- Edit Parking Slot Form -->
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="slot_number">Slot Number</label>
                            <input type="text" class="form-control" name="slot_number" value="<?php echo htmlspecialchars($slot['slot_number']); ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="is_available">Availability</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_available" id="is_available" <?php echo $slot['is_available'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="is_available">
                                    Available
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" name="update_slot" class="btn btn-primary">Update Slot</button>
                </form>
            </div>
        </div>
    </div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
