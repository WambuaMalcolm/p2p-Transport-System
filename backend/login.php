<?php
include 'db_connect.php';
session_start();

header('Content-Type: application/json');

$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $email = $data['email'];
    $password = $data['password'];

    $sql = "SELECT id, firstname, is_admin, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['is_admin'] = $user['is_admin'];
            echo json_encode(['success' => true, 'message' => 'Login successful', 'is_admin' => $user['is_admin']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid password']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No user found with that email']);
    }

    $stmt->close();
    $conn->close();
}
?>