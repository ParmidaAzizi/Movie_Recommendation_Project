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
// Assuming you have a users table with a column 'user_id' and a 'ratings' table
// with columns 'item_id', 'user_id', and 'rating'

// Replace 100 with the actual user ID you want to delete ratings for
$userId = 100;

$sql = "DELETE FROM rated_movies WHERE userID = $userId";

if ($conn->query($sql) === TRUE) {
    echo 'success';
} else {
    echo 'error';
}

$conn->close();
?>
