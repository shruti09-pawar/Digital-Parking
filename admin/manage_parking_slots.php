<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

require '../connection.php'; // Include your database connection file

// Handle slot deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete related session entries first
    $delete_session_query = "DELETE FROM Sessions WHERE slot_id = ?";
    $stmt = $con->prepare($delete_session_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // Delete related bookings first
    $delete_bookings_query = "DELETE FROM Bookings WHERE slot_id = ?";
    $stmt = $con->prepare($delete_bookings_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    // Delete the parking slot
    $delete_query = "DELETE FROM ParkingSlots WHERE slot_id = ?";
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();

    header("Location: manage_parking_slots.php");
    exit();
}


// Handle slot creation
if (isset($_POST['add_slot'])) {
    $slot_number = $_POST['slot_number'];
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    $insert_query = "INSERT INTO ParkingSlots (slot_number, is_available) VALUES (?, ?)";
    $stmt = $con->prepare($insert_query);
    $stmt->bind_param("si", $slot_number, $is_available);
    $stmt->execute();
    header("Location: manage_parking_slots.php");
    exit();
}

// Handle search
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $fetch_query = "SELECT * FROM ParkingSlots WHERE slot_number LIKE ?";
    $stmt = $con->prepare($fetch_query);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("s", $search_param);
    $stmt->execute();
    $slots_result = $stmt->get_result();
} else {
    // Fetch all parking slots if no search query is provided
    $fetch_query = "SELECT * FROM ParkingSlots";
    $slots_result = $con->query($fetch_query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Parking Slots - Digital Parking System</title>
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
                <h4>Manage Parking Slots</h4>
            </div>
            <div class="card-body">

                <!-- Search Form -->
                <form method="GET" action="manage_parking_slots.php" class="form-inline mb-4">
                    <input type="text" class="form-control mr-2" name="search" placeholder="Search Slot Number" value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                <!-- Add Parking Slot Form -->
                <form method="POST" action="">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <input type="text" class="form-control" name="slot_number" placeholder="Slot Number" required>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_available" id="is_available" checked>
                                <label class="form-check-label" for="is_available">
                                    Available
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" name="add_slot" class="btn btn-success btn-block">Add Slot</button>
                        </div>
                    </div>
                </form>

                <!-- Parking Slots Table -->
                <table class="table table-striped mt-4">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Slot Number</th>
                            <th>Availability</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $slots_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['slot_id']; ?></td>
                                <td><?php echo $row['slot_number']; ?></td>
                                <td><?php echo $row['is_available'] ? 'Available' : 'Occupied'; ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
                                    <a href="edit_slot.php?id=<?php echo $row['slot_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="manage_parking_slots.php?delete_id=<?php echo $row['slot_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this slot?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
