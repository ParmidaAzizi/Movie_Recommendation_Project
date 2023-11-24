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

// Fetch the count of items from your database table
$sql = "SELECT COUNT(*) as itemCount FROM rated_movies WHERE userID = 100";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    $row = $result->fetch_assoc();
    $itemCount = $row['itemCount'];

    // Return the item count
    echo $itemCount;
} else {
    // Return an error
    echo 'error';
}

$conn->close();
?>
