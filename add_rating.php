<?php
// add the rating chosen by current user


$host = 'localhost';
$user = 'root';
$password = '';
$database = 'movie_rec';
session_start();

$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get userID
$userID = $_SESSION["userID"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'];
    $rating = $_POST['rating'];

    $sql = "INSERT INTO rated_movies (userid, item_id, rating) VALUES ($userID, $itemId, $rating)
            ON DUPLICATE KEY UPDATE rating = $rating";

    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
    
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
