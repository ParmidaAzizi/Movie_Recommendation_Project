<?php
// Your database connection code
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'movie_rec';

$conn = new mysqli($host, $user, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['itemId'];
    $rating = $_POST['rating'];

    // Add code to validate and sanitize input data

    // Assuming you have a users table with a column 'user_id' and a 'ratings' table
    // with columns 'item_id', 'user_id', and 'rating'

    // Add or update the rating in the database
    $userId = 100; // Replace with the actual user ID (you might get it from your authentication system)
    $sql = "INSERT INTO rated_movies (userid, item_id, rating) VALUES ($userId, $itemId, $rating)
            ON DUPLICATE KEY UPDATE rating = $rating";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $conn->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

$conn->close();
?>
