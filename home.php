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
</head>
  
<html>
  <body style="background-color: #1b1d21;" onload="checkItemCound()">

    <nav class="navbar navbar-expand-md fixed-top">
        <a class="navbar-brand extra-links" style="font-size: 1.5rem;" href="#"><b>CPS842 - Movie Recommendation Project</b></a>

        <button id="deleteRatingsButton" class="btn btn-dark nav-item m-1" on_click = "checkItemCount()">New User</button>
        <button id="" class="btn btn-dark nav-item m-1"><a href="home.php">Rate Movies</a></button>
        <button id="updateButtonStatus" class="btn btn-dark nav-item m-1" disabled > <a href="recs.php">See Recommendations</a></button>
    
    </nav>

    <div class="container-fluid pb-5 mb-5 mt-5 mainPart">
      <div class="row col-12 justify-content-center">
        <div id="mainP" class="col-12 rounded justify-content-center d-flex flex-wrap align-self-center">
          <?php
            $sql = "SELECT * FROM item";
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
                          <li class="list-group-item" style="background-color:#707e91; height:10vh;">
                              <div class="btn-group col-12 " role="group" aria-label="Rate Movie">
                                  <p id="rating_stat">Rating: NOT RATED</p>
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
</html>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>


<script>

    var intervalId = setInterval(checkItemCount, 2000);

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
                        location.reload();
                    } else {
                        console.error('Error:', response.statusText);
                    }
                })
                .catch(error => console.error('Error:', error));
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
      }

    });
</script>