<?php
include 'db_connect.php';
session_start();

header('Content-Type: application/json');

$conn = getDbConnection();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $_SESSION['user_id'];

    $pickup_location = $data['pickup_location'];
    $dropoff_location = $data['dropoff_location'];
    $booking_time = $data['booking_time'];
    $mode_of_transport = $data['mode_of_transport'];

    $sql = "INSERT INTO bookings (user_id, pickup_location, dropoff_location, booking_time, mode_of_transport) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $userId, $pickup_location, $dropoff_location, $booking_time, $mode_of_transport);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Booking created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>