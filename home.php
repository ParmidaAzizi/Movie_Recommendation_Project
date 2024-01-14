<!DOCTYPE html>
<?php
// get connected to DB and the SESSION
require 'DB_Operations/dbConnect.php';
session_start();

// if already logedin, then continue to "home.php", else redirect to "login.html"
if (isset($_SESSION['userID'])) {
	
?>

<head>
  <title>Movie Recommendation System</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="./CSS/home.css?v=<?php echo time(); ?>">
  <!-- <link rel="stylesheet" href="./CSS/navbar.css?v=<?php echo time(); ?>"> -->
    <script src="https://code.angularjs.org/1.5.0/angular.min.js"></script>
</head>
  
<html>
  <body onload="checkItemCount()">

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
            <?php 
            
            $sql = "SELECT COUNT(*) AS rowCount FROM rated_movies WHERE userID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userID);
            $stmt->execute();
            
            // Fetch the result
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            
            // Get the number of rows
            $rowCount = $row['rowCount'];
            
            if ($rowCount>= 10){?>
                <p id="reminder" class="align-self-center col-12"><b><?php echo "Current UserID: ". $_SESSION['userID'];?></p>
            <?php
            } else{?>
                <p id="reminder" class="align-self-center col-12"><b><?php echo "Current UserID: ". $_SESSION['userID'];?> | You have rated <u><?php echo $rowCount ?></u> movies, you need at least 10 ratings to see recommendations! </b></p>
            <?php
            }
            ?>
            </div>
        </div>
    </nav>

    <div id="mainContent" class="pb-5 mb-5">
      <div class=" row col-12 justify-content-center">
        <!-- the div bellow if a card for shoing movie informaton, retrieved from the DB -->
        <div id="mainP" class="col-12 rounded justify-content-center d-flex flex-wrap align-self-center">
          <?php
            // get userID of current user
            // only retrieve the movies not already rated by this user
            $sql = "SELECT * FROM item WHERE item_ID NOT IN (SELECT item_ID FROM rated_movies WHERE userID = $userID)";
            $result = ($conn->query($sql));
            $rows = [];
            if ($result->num_rows > 0) {

              $rows = $result->fetch_all(MYSQLI_ASSOC);
            }
            if (!empty($rows)) {
              foreach ($rows as $row) {
                ?>
                <div class="grid-item p-1 align-self-center"  id="cardholder">
                  <div class="card" align="center">
                  <a href="movie_detail.php?item_ID=<?php echo $row['item_ID']; ?>" class="movie-link" >

                    <img src="<?php echo $row["logo"]; ?>" class="img-constrained" alt="Logo" id="clickableImage">

                        <div class="card-body">
                          <h5 class="card-title" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><?php echo $row['title'] ?></h5>
                          <p class="card-text"> Year: <?php echo $row['year'] ?> | <?php echo $row['rating'] ?> ★</p>
                        </div>
                    </a>

                    <ul class="list-group list-group-flush">
                          <li class="list-group-item extra-links" style="background-color:#262b32; height:7vh;">Genres: <?php echo $row['genres'] ?></li>
                          <li class="list-group-item" style="background-color:#262b32; height:10vh;">
                          <div class="btn-group" role="group" aria-label="Rate Movie">                                  
                                  <?php
                                  $userRating = isset($row['user_rating']) ? $row['user_rating'] : 0;
                                  // Display rating buttons
                                  for ($i = 1; $i <= 5; $i++) {
                                      $activeClass = ($i == $userRating) ? 'active' : ''; 
                                      $disabledAttr = ($userRating > 0) ? 'disabled' : ''; 
                                      if ($i ==5){
                                        echo "<button type='button' class='btn round-right btn-dark rate-btn $activeClass' data-id='{$row['item_ID']}' data-rating='$i' $disabledAttr>$i</button>";
                                      } elseif ($i == 1){
                                        echo "<button type='button' class='btn rounded btn-dark rate-btn $activeClass' data-id='{$row['item_ID']}' data-rating='$i' $disabledAttr>$i</button>";

                                      } else{
                                        echo "<button type='button' class='btn btn-dark rate-btn $activeClass' data-id='{$row['item_ID']}' data-rating='$i' $disabledAttr>$i</button>";

                                      }
                                  }
                                  ?>
                              </div>
                          </li>
                      </ul>
                  </div>

                </div>
              <?php
              }} ?>
        </div>       
      </div>
    </div>

  </body>

<?php

	}	
	else{
		header("Location: ./login.html");
	}
    ?>

</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>


<script>
    var intervalId;
    
    intervalId = setInterval(checkItemCount, 2000);


    // the function is to check whether minimum ratings per user is met
    // it is checked every 2 seconds or when a new rating is recieved
    // var intervalId = setInterval(checkItemCount, 2000);
    function checkItemCount() {
        $.ajax({
            url: 'checkItemCount.php',
            type: 'GET',
            success: function (data) {
                var link_act = document.getElementById('recommendButton');
                var activateLink = (parseInt(data) >= 10);
                if (parseInt(data) >= 10) {
                    link_act.disabled = false;
                    clearInterval(intervalId);
                    console.error('found!');
                } else {
                    link_act.disabled = true;
                    console.error('not found!');

                }
            },
            error: function () {
                console.error('Error checking item count');
            }
        });
    };

    // triggered when user clicks "See Recommendations"
    // it runs "runPythonScript.php" which in turn runs "user_based.py" to get the recommendations
    document.getElementById('recommendButton').addEventListener('click', function () {
        $.ajax({
            url: 'runPythonScript.php', // Replace with the actual path to your server-side script
            type: 'GET',
            success: function (response) {
                console.log('Python script started:', response);
            },
            error: function () {
                console.error('Error starting Python script');
            }
        });
        location.href = "movie_recs.php"
    });

    // triggered when user rates a movie
    // Wait for the DOM to be fully loaded before executing the script   
    document.addEventListener('DOMContentLoaded', function () {
        // Get all cards
        var cards = document.querySelectorAll('.card');

        // Iterate through each card
        cards.forEach(function (card) {
            // Get all buttons with the class 'rate-btn' within the current card (1 to 5)
            var rateButtons = card.querySelectorAll('.rate-btn');
            
            // Iterate through each rate button within the current card and add 
            // a click event listener to each rate button. if clicked, then 
            // call the handleRate function. also call the checkItemCount 
            // function after handling the rate to update item count
            rateButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    handleRate(button);
                    checkItemCount();
                });
            });
        });

        function handleRate(button) {
            // Get the unique identifier (data-id) and rating (data-rating) of the clicked button
            var itemId = button.getAttribute('data-id');
            var rating = button.getAttribute('data-rating');
            
            // Find the closest parent element with the class 'card' to the clicked button
            var card = button.closest('.card');

            // Get all rate buttons within the current card
            var rateButtons = card.querySelectorAll('.rate-btn');


            // Iterate through each rate button within the current card
            // Get all rate buttons within the current card and change the active status of the clicked
            // rate button
            rateButtons.forEach(function (btn) {
                var btnRating = btn.getAttribute('data-rating');
                if (btnRating <= rating) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }

                btn.disabled = (btn === button);
            });

            // Send the rating data to the server using AJAX
            var formData = new FormData();
            formData.append('itemId', itemId);
            formData.append('rating', rating);

            fetch('add_rating.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    console.log('Success');
                } else {
                    console.error('Error:', response.statusText);
                }
            })
            .catch(error => console.error('Error:', error));

            // Find the closest parent element with the class 'card' to the clicked button
    var card = button.closest('.card');

    // Add the 'rated-card' class to the card
    card.classList.add('rated-card');

    // Get all rate buttons within the current card
    var rateButtons = card.querySelectorAll('.rate-btn');

    // Iterate through each rate button within the current card and disable them
    rateButtons.forEach(function (btn) {
        btn.disabled = (btn === button);
    });

    // Send the rating data to the server using AJAX
    var formData = new FormData();
    formData.append('itemId', itemId);
    formData.append('rating', rating);

    fetch('add_rating.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => {
        if (response.ok) {
            console.log('Success');
        } else {
            console.error('Error:', response.statusText);
        }
    })
    .catch(error => console.error('Error:', error));

            // Send the rating data to the server using AJAX
            var formData = new FormData();
            formData.append('itemId', itemId);
            formData.append('rating', rating);

            fetch('add_rating.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (response.ok) {
                    // Update the displayed count after successful rating
                    updateRowCount();
                    console.log('Success');
                } else {
                    console.error('Error:', response.statusText);
                }
            })
            .catch(error => console.error('Error:', error));
        }
        function updateRowCount() {
            // Fetch the updated row count using AJAX
            fetch('get_row_count.php')
                .then(response => response.text())
                .then(rowCount => {
                    console.log('Fetched rowCount:', rowCount);

                    // Update the 'reminder' element based on the new row count
                    var reminderElement = document.getElementById('reminder');
                    if (parseInt(rowCount) > 10) {
                        console.log('Condition: rowCount > 10');
                        // If rowCount is greater than 10, display the UserID only
                        reminderElement.innerHTML = 
                            "<b>Current UserID: " + <?php echo $_SESSION['userID']; ?> + "</b>";
                    } else {
                        console.log('Condition: rowCount <= 10');
                        // If rowCount is 10 or less, display the complete message
                        reminderElement.innerHTML = 
                            "<b>Current UserID: " + <?php echo $_SESSION['userID']; ?> + 
                            " | You have rated <u>" + rowCount + 
                            "</u> movies, you need at least 10 ratings to see recommendations!</b>";
                    }
                })
                .catch(error => console.error('Error:', error));
        }

    });

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


</script>