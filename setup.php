<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "movie_rec";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully\n";
} else {
    echo "Error creating database: " . $conn->error;
}

// Create connection
$conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo " You are Connected to the database<br>";

// sql to create table
$sql =array();

$sql[0] = "CREATE TABLE item (
    item_ID INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(item_ID),
    title VARCHAR(25) NOT NULL,
    logo VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    rating VARCHAR(25) NOT NULL,
	genres VARCHAR(255) NOT NULL,
    directors VARCHAR(255) NOT NULL,
    actors VARCHAR(255) NOT NULL,
    plot VARCHAR(255) NOT NULL,
    age_rating VARCHAR(25) NOT NULL
    )";
    
$sql[1] = "CREATE TABLE USERS (
    userID INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(userID)
    )";

$sql[2] = "CREATE TABLE rated_movies (
    liked_movie_id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(liked_movie_id),
    userID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES USERS(userID) ON DELETE CASCADE,
    item_ID INT NOT NULL,
    FOREIGN KEY (item_ID) REFERENCES ITEM(item_ID) ON DELETE CASCADE,
    rating INT NOT NULL,
    UNIQUE (userID, item_ID)
     )";

$sql[3] = "CREATE TABLE recommended_movies (
    liked_movie_id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(liked_movie_id),
    userID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES USERS(userID) ON DELETE CASCADE,
    item_ID INT NOT NULL,
    FOREIGN KEY (item_ID) REFERENCES ITEM(item_ID) ON DELETE CASCADE,
    rating INT NOT NULL,
    UNIQUE (userID, item_ID)
     )";

foreach($sql as $sql){
    if ($conn->query($sql)) {
        echo "Table Records created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
}

// populate the tables
$result = shell_exec("python add_movies.py");
$result = shell_exec("python add_users_and_rating.py");


// Close connection
$conn->close();



?>