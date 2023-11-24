<!DOCTYPE html>
<?php
require 'DB_Operations/dbConnect.php';	
?>

<head>
  <title>CPS630 Project</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="./CSS/home2.css?v=<?php echo time(); ?>">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://code.angularjs.org/1.5.0/angular.min.js"></script>
<html>

<body style="background-color: #1b1d21;">
<nav class="navbar navbar-expand-md fixed-top">
    <a class="navbar-brand extra-links" style="font-size: 1.5rem;" href="#"><b>CPS842 - Movie Recommendation Project</b></a>


    <button id="deleteRatingsButton" class="btn btn-dark nav-item m-1" on_click = "checkItemCount()">New User</button>
    <button id="" class="btn btn-dark nav-item m-1"><a href="home.php">Rate Movies</a></button>
    <button id="updateButtonStatus" class="btn btn-dark nav-item m-1"> <a href="recs.php">See Recommendations</a></button>
 
</nav>
<div class="container-fluid pb-5 mb-5 mt-5 mainPart">
    <div class="row col-12 justify-content-center">
        <div style="color:white;" class="m-3 p-3">
            <h3>Based on your ratings, these are your reccomendations:</h3>
        </div>
      <div id="mainP" class="col-12 rounded justify-content-center d-flex flex-wrap align-self-center">
        <?php
          $sql = "SELECT rm.item_ID, i.title, i.logo, i.year, i.rating, i.genres, i.directors, i.actors, i.plot, i.age_rating
          FROM rated_movies rm
          JOIN item i ON rm.item_ID = i.item_ID
          WHERE rm.userID = 200";
          $result = ($conn->query($sql));
          $rows = [];
          if ($result->num_rows > 0) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
          }
          if (!empty($rows)) {
            foreach ($rows as $row) {
              ?>
              <div class="grid-item p-2 align-self-center"  id="cardholder">
                <div class="card" style="border:1px solid #333; border-radius:5px; width:33vh;" align="center">
                    <img src="<?php echo $row["logo"]; ?>" class="img-constrained" alt="Logo">
                    <div class="card-body">
                        <h5 class="card-title" style="white-space: nowrap;overflow: hidden;text-overflow: ellipsis;"><?php echo $row['title'] ?></h5>
                        <p class="card-text" style="color: black"> Year: <?php echo $row['year'] ?> | <?php echo $row['rating'] ?> ★</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" style="background-color:#707e91; height:7vh;">Genres: <?php echo $row['genres'] ?></li>
                        <li class="list-group-item" style="background-color:#707e91; height:13vh;">
                           <p> <b>Summary:<br></b>
                           <?php
                                // Get the full summary from the database
                                $fullSummary = $row['plot'];
                                $trimmedSummary = substr($fullSummary, 0, 100);
                                $lastSpace = strrpos($trimmedSummary, ' ');
                                $shortSummary = substr($trimmedSummary, 0, $lastSpace + 1);

                                // Output the shortened summary
                                echo $shortSummary;

                                // If the original summary was longer than the displayed part, add an ellipsis
                                if (strlen($fullSummary) > strlen($shortSummary)) {
                                    echo '...';
                                }
                                ?>
                            </p>
                        </li>
                    </ul>
                </div>
              </div>
            <?php
          }} ?>
            <div class="shopping cart">
            </div>
          </div>
              
      </div>
    </div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>


</html>


<script>

    function checkItemCount() {
        $.ajax({
            url: 'checkItemCount.php',
            type: 'GET',
            success: function (data) {
                var link_act = document.getElementById('updateButtonStatus');
                var activateLink = (parseInt(data) >= 5);
                if (parseInt(data) >= 5) {
                  link_act.disabled = false;
                  clearInterval(intervalId);
                console.error('found!');

                } else {
                  link_act.disabled = true;
                }
            },
            error: function () {
                console.error('Error checking item count');
            }
        });
    }


    setInterval(checkItemCount, 1000);

    document.addEventListener('DOMContentLoaded', function () {
        // Get all cards
        var cards = document.querySelectorAll('.card');

        cards.forEach(function (card) {
            var rateButtons = card.querySelectorAll('.rate-btn');

            rateButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    handleRate(button);
                    checkItemCount();
                });
            });
        });


        var deleteButton = document.getElementById('deleteRatingsButton');

        if (deleteButton) {
            deleteButton.addEventListener('click', function () {
                // Send an AJAX request to delete ratings
                fetch('restart_program.php', {
                    method: 'POST',
                })
                .then(response => {
                    if (response.ok) {
                        console.log('Ratings deleted successfully');
                        // Refresh the page after successful deletion
                        window.location.replace("home.php");
                    } else {
                        console.error('Error:', response.statusText);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        }

        var goingHome = document.getElementById('goHome');

        if (goingHome) {
            deleteButton.addEventListener('click', function () {

                window.location.replace("home.php");

            });
        }

        var goingRec = document.getElementById('goRec');

        if (goingRec) {
            deleteButton.addEventListener('click', function () {

                window.location.replace("recs.php");

            });
        }

        function handleRate(button) {
          var itemId = button.getAttribute('data-id');
          var rating = button.getAttribute('data-rating');
          var card = button.closest('.card');

          var rateButtons = card.querySelectorAll('.rate-btn');

          rateButtons.forEach(function (btn) {
              var btnRating = btn.getAttribute('data-rating');
              if (btnRating <= rating) {
                  btn.classList.add('active');
              } else {
                  btn.classList.remove('active');
              }

              btn.disabled = (btn === button);
          });

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
      }

    });
</script>