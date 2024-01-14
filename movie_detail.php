<?php
// movie_detail.php

require 'DB_Operations/dbConnect.php';
session_start();

if (isset($_SESSION['userID'])) {
    // Check if the item_ID is provided in the query parameters
    if (isset($_GET['item_ID'])) {
        $itemID = $_GET['item_ID'];

        // Fetch detailed information for the selected movie
        $sql = "SELECT * FROM item WHERE item_ID = $itemID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $movieDetails = $result->fetch_assoc();
            // Now, display the detailed information as needed
            ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <!-- Include necessary CSS and Bootstrap links -->
            <title><?php echo $movieDetails['title']; ?> - Movie Details</title>
            <!-- Add your custom styles if needed -->
            <link rel="stylesheet" href="./CSS/home.css?v=<?php echo time(); ?>">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
            <style>
              p{
                font-size: 1em;
              }

              .btn{
                font-size: 1.3vw;
              }
            </style>
        </head>


        <body style="background-color: #343a40;">
                
        <nav class="navbar navbar-expand-md fixed-top">
          <div class="row col-12 pt-2">
              <a class="navbar-brand extra-links col" style="font-size: 1.8rem;" href="#"><b>Movie Recommendation Project</b></a>
              <button id="logout" class="btn btn-dark nav-item m-1 col" on_click = "checkItemCount(); changeLocation();">New User</button>
              <button id="goToHome" class="btn btn-dark nav-item m-1 col">Rate Movies</button>
              <button id="recommendButton" class="btn btn-dark nav-item m-1 col" disabled>See Recommendations</button>
              <?php 
              $userID = $_SESSION["userID"];

              ?>
              <div class="row col-11 mb-1 pt-2 extra-links" style="color:white;">
                  <p id="reminder" class="align-self-center col-12"><b><?php echo "Current UserID: ". $_SESSION['userID'];?></p>
              </div>
          </div>
        </nav>
          <!-- Display detailed movie information here -->
          <div id="mainContent">
          <div class="container col d-flex justify-content-center mt-5" >
            <div class="card  text-dark col-10" style="background-color: #6c7883;">
              <div class="row mt-5 mb-5" style="background-color: #6c7883;">
                  <div class="col-3">
                      <img src="<?php echo $movieDetails['logo']; ?>" alt="Movie Logo" class="img-fluid movie-logo" id="logo">

                    </div>
                    <div class="col-7">
                      <div class="movie-info">
                          <h2><?php echo $movieDetails['title']; ?></h2>
                          <p><strong>Year:</strong> <?php echo $movieDetails['year']; ?></p>
                          <p><strong>Rating:</strong> <?php echo $movieDetails['rating']; ?> ★</p>
                          <p><strong>Genres:</strong> <?php echo $movieDetails['genres']; ?></p>
                          <p><strong>Actors:</strong> <?php echo $movieDetails['actors']; ?></p>
                          <p><strong>directors:</strong> <?php echo $movieDetails['directors']; ?></p>
                          <h3>Summary:</h3>
                          <p><?php echo $movieDetails['plot']; ?></p>
                      </div>
                    </div>
                    <div class="back-button col-1">
 
                        <a href="javascript:history.back()" class="btn btn-dark">Back</a>

                    </div>    
              </div>
                  <!-- Movie Logo -->
                  
                  
                  
            </div>
        </div>               

            </body>
            </html>
            <?php
        } else {
            echo "Movie not found.";
        }
    } else {
        echo "Item_ID not provided in the URL.";
    }
} else {
    header("Location: ./login.html");
}
?>
<script>
          // triggered when "New USer" button is clicked
          var logoutButton = document.getElementById('logout');
        if (logoutButton) {
          logoutButton.addEventListener('click', function () {
                location.href = 'login.html';
            });
        }

        // triggered when "Rate Movies" button is clicked
        var homeButton = document.getElementById('goToHome');
        if (homeButton) {
          homeButton.addEventListener('click', function () {
                location.href = 'home.php';
            });
        }
</script>