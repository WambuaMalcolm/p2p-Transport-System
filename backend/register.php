<?php
include 'db_connect.php';

header('Content-Type: application/json');

$conn = getDbConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $firstname = $data['firstname'];
    $lastname = $data['lastname'];
    $email = $data['email'];

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database prepare failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'An account with this email already exists.']);
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();

    // Check if this is the first user
    $isAdmin = 0;
    $sqlCount = "SELECT COUNT(*) AS user_count FROM users";
    $resultCount = $conn->query($sqlCount);
    if ($resultCount && $resultCount->num_rows > 0) {
        $rowCount = $resultCount->fetch_assoc();
        if ($rowCount['user_count'] == 0) {
            $isAdmin = 1; // Make the first user an admin
        }
    }

    $name = $firstname . ' ' . $lastname;
    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, firstname, lastname, email, password, is_admin) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        echo json_encode(['success' => false, 'message' => 'Database prepare failed: ' . $conn->error]);
        exit();
    }
    $stmt->bind_param("sssssi", $name, $firstname, $lastname, $email, $password, $isAdmin);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'User registered successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>