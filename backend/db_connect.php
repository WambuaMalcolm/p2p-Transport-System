<?php
function getDbConnection() {
    $servername = "localhost";
    $username = "p2p_user";
    $password = "hemiv8"; // Please replace with your actual database password
    $dbname = "p2p_transport";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}



?>