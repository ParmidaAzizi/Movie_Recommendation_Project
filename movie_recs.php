<!DOCTYPE html>
<?php
require 'DB_Operations/dbConnect.php';	
session_start();

?>

<head>
  <title>CPS842 Project</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./CSS/home.css?v=<?php echo time(); ?>">
  <script src="https://code.angularjs.org/1.5.0/angular.min.js"></script>
<html>

<body >
    
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

    <div id="mainContent" class="pb-5 mb-5">
        <div class="row col-12 justify-content-center">
            <div style="color:white;" class="ml-4 pl-2">
                <h3>Based on your ratings, these are your top recommendations:</h3>
            </div>
            <div id="mainP" class="col-12 rounded justify-content-center d-flex flex-wrap align-self-center">
                <?php
                    $userID = $_SESSION['userID'];
                    $sql = "SELECT rm.item_ID, i.title, i.logo, i.year, i.rating, i.genres, i.directors, i.actors, i.plot, i.age_rating
                    FROM recommended_movies rm
                    JOIN item i ON rm.item_ID = i.item_ID
                    WHERE rm.userID = $userID
                    ORDER BY rm.rating DESC";
                    $result = ($conn->query($sql));
                    $rows = [];
                    if ($result->num_rows > 0) {
                        $rows = $result->fetch_all(MYSQLI_ASSOC);
                    }
                    if (!empty($rows)) {
                        foreach ($rows as $row) {
                        ?>
                    <!-- the div bellow if a card for showing movie informaton, retrieved from the DB -->
                    <div class="grid-item p-2 align-self-center"  id="cardholder">
                        <a href="movie_detail.php?item_ID=<?php echo $row['item_ID']; ?>" class="movie-link" alt="<?php echo $row['plot'] ?>" >
                        
                        <div class="card" style="border:1px solid #333; border-radius:5px; width:35vh;" align="center">

                            <img src="<?php echo $row["logo"]; ?>" class="img-constrained" alt="Logo">
                            <div class="card-body">
                                <h5 class="card-title" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><?php echo $row['title'] ?></h5>
                                <p class="card-text"> Year: <?php echo $row['year'] ?> | <?php echo $row['rating'] ?> ★</p>
                            </div>
                        
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item" style="background-color:#262b32; height:7vh;"><b>Genres: </b><?php echo $row['genres'] ?></li>
                                <li class="list-group-item " style="background-color:#262b32; height:16vh; overflow: hidden;">
                                    <p style="overflow: hidden; text-overflow: ellipsis; max-height:95%;"> <b>Summary:<br></b>
                                        <?php
                                        // Get the full summary from the database
                                        $fullSummary = $row['plot'];
                                        echo $fullSummary

                                        // older method to show parts of the summary
                                        // $trimmedSummary = substr($fullSummary, 0, 100);
                                        // $lastSpace = strrpos($trimmedSummary, ' ');
                                        // $shortSummary = substr($trimmedSummary, 0, $lastSpace + 1);

                                        // // Output the shortened summary
                                        // echo $shortSummary;

                                        // // If the original summary was longer than the displayed part, add an ellipsis
                                        // if (strlen($fullSummary) > strlen($shortSummary)) {
                                        //     echo '...';
                                        // }
                                        ?>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        </a>
                    </div>
                <?php
                }} 
                ?>
            </div>     
        </div>
    </div>
</body>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>

</html>


<script>
    
        // function for redirection:

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

        // triggered when "see recommendations" button is clicked
        var recButton = document.getElementById('recommendButton');
        if (recButton) {
          recButton.addEventListener('click', function () {
                location.href = 'movie_recs.php';
            });
        }

</script>