<?php
require_once 'config.php'; // Include database configuration

// Get search parameters from GET request
$from = isset($_GET['from']) ? $conn->real_escape_string($_GET['from']) : '';
$to = isset($_GET['to']) ? $conn->real_escape_string($_GET['to']) : '';
$date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';
$type = isset($_GET['type']) ? $conn->real_escape_string($_GET['type']) : '';

// Build SQL query based on parameters
$sql = "SELECT * FROM routes WHERE 1=1";

if (!empty($from)) {
    $sql .= " AND departure_city LIKE '%$from%'";
}

if (!empty($to)) {
    $sql .= " AND arrival_city LIKE '%$to%'";
}

if (!empty($date)) {
    $sql .= " AND departure_date = '$date'";
}

if (!empty($type)) {
    $sql .= " AND transport_type = '$type'";
}

$sql .= " ORDER BY departure_time ASC";

$result = $conn->query($sql);

$routes = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $routes[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($routes);

$conn->close();
// End of searchfunctionality.php
// This file handles the search functionality for transport routes. 
// It connects to the database, retrieves search parameters, and returns matching routes in JSON format.
// File: searchfunctionality.php
include 'style.php';
// End of the PHP script
?>