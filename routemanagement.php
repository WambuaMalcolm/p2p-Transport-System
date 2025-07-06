<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Add new route
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_route'])) {
    $departure = $conn->real_escape_string($_POST['departure_city']);
    $arrival = $conn->real_escape_string($_POST['arrival_city']);
    $date = $conn->real_escape_string($_POST['departure_date']);
    $time = $conn->real_escape_string($_POST['departure_time']);
    $type = $conn->real_escape_string($_POST['transport_type']);
    $price = $conn->real_escape_string($_POST['price']);
    $seats = $conn->real_escape_string($_POST['available_seats']);
    $vehicle_id = $conn->real_escape_string($_POST['vehicle_id']);
    
    $sql = "INSERT INTO routes (departure_city, arrival_city, departure_date, departure_time, 
            transport_type, price, available_seats, vehicle_id)
            VALUES ('$departure', '$arrival', '$date', '$time', '$type', '$price', '$seats', '$vehicle_id')";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Route added successfully";
    } else {
        $error = "Error adding route: " . $conn->error;
    }
}

// Delete route
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "DELETE FROM routes WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        $success = "Route deleted successfully";
    } else {
        $error = "Error deleting route: " . $conn->error;
    }
}

// Get all routes
$routes = array();
$sql = "SELECT r.*, v.registration_number FROM routes r 
        LEFT JOIN vehicles v ON r.vehicle_id = v.id
        ORDER BY departure_date, departure_time";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $routes[] = $row;
    }
}

include 'admin_header.php';
?>

<div class="container">
    <h2>Manage Routes</h2>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <div class="card mb-4">
        <div class="card-header">
            <h4>Add New Route</h4>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Departure City</label>
                            <input type="text" name="departure_city" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Arrival City</label>
                            <input type="text" name="arrival_city" required class="form-control">
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Departure Date</label>
                            <input type="date" name="departure_date" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Departure Time</label>
                            <input type="time" name="departure_time" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Transport Type</label>
                            <select name="transport_type" required class="form-control">
                                <option value="bus">Bus</option>
                                <option value="train">Train</option>
                                <option value="flight">Flight</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.01" name="price" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Available Seats</label>
                            <input type="number" name="available_seats" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Vehicle</label>
                            <select name="vehicle_id" required class="form-control">
                                <?php
                                $vehicles = $conn->query("SELECT id, registration_number FROM vehicles");
                                while ($vehicle = $vehicles->fetch_assoc()) {
                                    echo "<option value='{$vehicle['id']}'>{$vehicle['registration_number']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="add_route" class="btn btn-primary">Add Route</button>
            </form>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h4>Existing Routes</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Departure</th>
                            <th>Arrival</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Seats</th>
                            <th>Vehicle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($routes as $route): ?>
                        <tr>
                            <td><?php echo $route['id']; ?></td>
                            <td><?php echo htmlspecialchars($route['departure_city']); ?></td>
                            <td><?php echo htmlspecialchars($route['arrival_city']); ?></td>
                            <td><?php echo $route['departure_date']; ?></td>
                            <td><?php echo $route['departure_time']; ?></td>
                            <td><?php echo ucfirst($route['transport_type']); ?></td>
                            <td>$<?php echo number_format($route['price'], 2); ?></td>
                            <td><?php echo $route['available_seats']; ?></td>
                            <td><?php echo htmlspecialchars($route['registration_number']); ?></td>
                            <td>
                                <a href="edit_route.php?id=<?php echo $route['id']; ?>" class="btn btn-sm btn-info">Edit</a>
                                <a href="?delete=<?php echo $route['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include 'admin_footer.php'; ?>