<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

include 'admin_header.php';
?>

<div class="dashboard-container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h2>
    
    <div class="dashboard-menu">
        <a href="manage_routes.php" class="dashboard-item">
            <i class="fas fa-route"></i>
            <span>Manage Routes</span>
        </a>
        <a href="manage_bookings.php" class="dashboard-item">
            <i class="fas fa-ticket-alt"></i>
            <span>Manage Bookings</span>
        </a>
        <a href="manage_vehicles.php" class="dashboard-item">
            <i class="fas fa-bus"></i>
            <span>Manage Vehicles</span>
        </a>
        <a href="manage_users.php" class="dashboard-item">
            <i class="fas fa-users"></i>
            <span>Manage Users</span>
        </a>
        <a href="reports.php" class="dashboard-item">
            <i class="fas fa-chart-bar"></i>
            <span>Reports</span>
        </a>
        <a href="admin_logout.php" class="dashboard-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<?php include 'admin_footer.php'; ?>