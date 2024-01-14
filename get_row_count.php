<?php
// get connected to DB and the SESSION
require 'DB_Operations/dbConnect.php';
session_start();

if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    $sql = "SELECT COUNT(*) AS rowCount FROM rated_movies WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $rowCount = $row['rowCount'];

    echo $rowCount;

    $stmt->close();
} else {
    echo "0";  // or any default value
}
?>
