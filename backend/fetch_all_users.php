<?php
include 'db_connect.php';

header('Content-Type: application/json');

$conn = getDbConnection();

// In a real application, you would have admin authentication here

$sql = "SELECT id, firstname, lastname, email, reg_date FROM users";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode(['success' => true, 'users' => $users]);

$conn->close();
?>