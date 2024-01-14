<?php
session_start();

if (isset($_POST['save'])) {
    extract($_POST);

    include 'dbconnect.php';

    // Use intval to ensure usrID is treated as an integer
    $usrID = intval($_POST['usrID']);

    // Check if usrID is a valid integer
    if ($usrID <= 0) {
        echo "Invalid userID. Please enter a valid integer.";
        header("Location: ../login.html");
        exit();
    }

    // Use a prepared statement to check and insert the user
    $query = "INSERT INTO users (userID) VALUES (?) ON DUPLICATE KEY UPDATE userID=userID";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameter and execute the statement
    mysqli_stmt_bind_param($stmt, "i", $usrID);
    $result = mysqli_stmt_execute($stmt);

    // Check the result of the execution
    if ($result) {
        // Set the user ID in the session
        $_SESSION["userID"] = $usrID;
        header("Location: ../home.php");
        exit();
    } else {
        // Handle the case where the query did not execute successfully
        echo "An error occurred.";
        header("Location: ../login.html");
        exit();
    }
}
?>
