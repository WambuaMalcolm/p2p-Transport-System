<?php
include 'db_connect.php';
session_start();

header('Content-Type: application/json');

$conn = getDbConnection();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT firstname, lastname, email, is_admin FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    echo json_encode(['success' => true, 'user' => $user, 'is_admin' => $_SESSION['is_admin']]);
} else {
    echo json_encode(['success' => false]);
}
?>