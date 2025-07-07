<?php
include 'db_connect.php';

header('Content-Type: application/json');

$conn = getDbConnection();

// In a real application, you would have admin authentication here

$sql = "SELECT b.id, u.firstname, u.lastname, u.email, b.pickup_location, b.dropoff_location, b.booking_time FROM bookings b JOIN users u ON b.user_id = u.id";
$result = $conn->query($sql);

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode(['success' => true, 'bookings' => $bookings]);

$conn->close();
?>