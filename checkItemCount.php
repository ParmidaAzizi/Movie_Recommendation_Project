<?php
// check to see if minimum ratings for the user is met

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

// Fetch the count of items from your database table
$sql = "SELECT COUNT(*) as itemCount FROM rated_movies WHERE userID = {$userID}";
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    $row = $result->fetch_assoc();
    $itemCount = $row['itemCount'];

    // Return the item count
    echo $itemCount;
    echo '<script>';
    echo "console.log($itemCount);";
    echo '</script>';
} else {
    // Return an error
    echo '<script>';
    echo "console.log('error');";
    echo '</script>';
}

$conn->close();
?>
