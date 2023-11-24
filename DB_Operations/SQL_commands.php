<!DOCTYPE html>

<head>
    <title>CPS630 Project</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./CSS/nav.css">
    <link rel="stylesheet" href="./CSS/contactus.css">
</head>
<html>
    <body>
<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "movie_rec";

// Create connection
$conn = new mysqli($servername, $username, $password,$db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo " You are Connected to the database<br>";

// sql to create table
$sql =array();

// $sql[0] = "CREATE TABLE item (
//     item_ID INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(item_ID),
//     title VARCHAR(25) NOT NULL,
//     logo VARCHAR(255) NOT NULL,
//     year INT NOT NULL,
//     rating VARCHAR(25) NOT NULL,
// 	genres VARCHAR(255) NOT NULL,
//     directors VARCHAR(255) NOT NULL,
//     actors VARCHAR(255) NOT NULL,
//     plot VARCHAR(255) NOT NULL,
//     age_rating VARCHAR(25) NOT NULL
//     )";
    
// $sql[1] = "CREATE TABLE USERS (
//     userID INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(userID)
//     )";

$sql[0] = "CREATE TABLE rated_movies (
    liked_movie_id INT NOT NULL AUTO_INCREMENT,
    PRIMARY KEY(liked_movie_id),
    userID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES USERS(userID) ON DELETE CASCADE,
    item_ID INT NOT NULL,
    FOREIGN KEY (item_ID) REFERENCES ITEM(item_ID) ON DELETE CASCADE,
    rating INT NOT NULL,
    UNIQUE (userID, item_ID)
     )";

// $sql[3] = "CREATE TABLE disliked_movies (
//     disliked_movie_id INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(disliked_movie_id),
//     userID INT NOT NULL,
//     FOREIGN KEY (userID) REFERENCES USERS(userID) ON DELETE CASCADE,
//     item_ID INT NOT NULL,
//     FOREIGN KEY (item_ID) REFERENCES ITEM(item_ID) ON DELETE CASCADE
//      )";

// $sql[4] = "CREATE TABLE dislikes_g (
//     dislikes_g_id INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(dislikes_g_id),
//     genres VARCHAR(255) NOT NULL,
//     userID INT NOT NULL,
//     FOREIGN KEY (userID) REFERENCES USERS(userID) ON DELETE CASCADE
//      )";


// $sql[5] = "CREATE TABLE dislikes_d (
//     dislikes_d_id INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(dislikes_d_id),
//     director VARCHAR(255) NOT NULL,
//     userID INT NOT NULL,
//     FOREIGN KEY (userID) REFERENCES USERS(userID) ON DELETE CASCADE
//      )";


// $sql[6] = "CREATE TABLE orders(
//     orderID INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(orderID),
//     dateIssued TIME DEFAULT CURRENT_TIMESTAMP,
//     totalPrice INT,
//     paymentmethod VARCHAR(25),
//     userID INT NOT NULL,
//     FOREIGN KEY (userID) REFERENCES user(userID) ON DELETE CASCADE,
//     receiptID INT NOT NULL,
//     FOREIGN KEY (receiptID) REFERENCES shopping_cart(receiptID)
//  )";
    

// $sql[7] = "CREATE TABLE itemsInShoppingCart (
//     itemID INT NOT NULL,
//     receiptID INT NOT NULL,
//     quantity INT,
//     FOREIGN KEY (itemID) REFERENCES item(itemID) ,
//     FOREIGN KEY (receiptID) REFERENCES shopping_cart(receiptID) ON DELETE CASCADE,
// 	itemsInShoppingCartID INT NOT NULL AUTO_INCREMENT,
// 	PRIMARY KEY(itemsInShoppingCartID)
//     )";

// $sql[8] = "CREATE TABLE trip(
//     tripID INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(tripID),
//     truckID INT NOT NULL,
//     FOREIGN KEY (truckID) REFERENCES truck(truckID) ON DELETE CASCADE,    
//     orderID INT NOT NULL,
//     FOREIGN KEY (orderID) REFERENCES orders(orderID)
//  )";

// $sql[9] = "CREATE TABLE truckToGo (
//     toGoID INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(toGoID),
//     truckID INT NOT NULL,
//     FOREIGN KEY (truckID) REFERENCES truck(truckID),  
//     Monday BOOLEAN DEFAULT 1, 
//     Tuesday BOOLEAN DEFAULT 1,
//     Wednesday BOOLEAN DEFAULT 1,
//     Thursday BOOLEAN DEFAULT 1,
//     Friday BOOLEAN DEFAULT 1,
//     Saturday BOOLEAN DEFAULT 1,
//     Sunday BOOLEAN DEFAULT 1
// )";

// $sql[10] = "CREATE TABLE discount(
//     discountID INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(discountID),
//     itemID INT NOT NULL,
//     FOREIGN KEY (itemID) REFERENCES item(itemID) ON DELETE CASCADE
//  )";
//  $sql[11] = "CREATE TABLE review(
//     reviewID INT NOT NULL AUTO_INCREMENT,
//     PRIMARY KEY(reviewID),
//     userID INT NOT NULL ,
//     FOREIGN KEY (userID) REFERENCES user(userID),
//     userName  VARCHAR(300),
//     itemID INT NOT NULL,
//     FOREIGN KEY (itemID) REFERENCES item(itemID),
//     userRN INT(1) NOT NULL  ,
//     reviewTime DATETIME NOT NULL,
//     userReview VARCHAR(300)
//  )";



//     $sql[12]="INSERT INTO `truck` (`truckID`, `driverFirstName`, `driverLastName`, `PlateNum`) VALUES
//         (3, 'Mickey', 'Mouse', 'ABC-1234'),
//         (4, 'Goofy', 'NoLastName', 'BCD-2345'),
//         (5, 'Donald', 'Duck', 'CDE-3456'),
//         (6, 'Tom', 'Holland', 'HKD_382'),
//         (7, 'Dave', 'Mccary', 'XZR-1773')";

//     $sql[13]= "INSERT INTO `trucktogo` (`toGoID`, `truckID`, `Monday`, `Tuesday`, `Wednesday`, `Thursday`, `Friday`, `Saturday`, `Sunday`) VALUES
//         (3, 3, 1, 0, 0, 1, 0, 0, 0),
//         (4, 4, 2, 1, 0, 1, 1, 0, 1),
//         (5, 5, 1, 1, 1, 1, 1, 1, 1),
//         (6, 6, 0, 0, 0, 1, 0, 0, 1),
//         (7, 7, 1, 1, 1, 0, 0, 2, 0)";

//     $sql[14]="INSERT INTO `store` (`location`, `city`, `postalCode`, `depCode`) VALUES
//         ('Markham St', 'Markham', 'L4K 5A9', 1),
//         ('130 Spadina Avenue', 'Toronto', 'M5V 2K8', 2),
//         ('925 Bloor St W', 'Toronto', 'M6H 1L5', 3),
//         ('Mississauga St.', 'Mississauga', 'L5C 2V2', 4),
//         ('Concord St.', 'Concord', 'L3R 3L5', 5),
//         ('1422 Gerrard St E', 'Toronto', 'M4L 1Z6', 6),
//         ('2681 Danforth Ave', 'Toronto', 'M4C 1L4', 7)";
    
//     $sql[15]="INSERT INTO `item` (`itemName`, `madeIn`, `itemPic`, `itemID`, `quantity`, `price`, `depCode`) VALUES
//     ('Blue-Gold Paint Cake', 'Blue', 'https://img.ltwebstatic.com/images3_pi/2022/10/09/1665298032ac95d0cf86ee7d5047e4e55a8700752e_thumbnail_900x.webp', 2, 12, 60, 3),
//     ('Pastel Balloons Cake', 'Pink ', 'https://img.ltwebstatic.com/images3_pi/2022/03/08/164671221468f8b0c6dae874cd2cc89a30daad7f2a_thumbnail_900x.webp', 3, 6, 87, 3),
//     ('Mermaid Tail Cake', 'Yellow', 'https://img.ltwebstatic.com/images3_pi/2022/09/06/166245183706fe96793373d924ea00129fbe6c58b4_thumbnail_900x.webp', 4, 2, 94, 1),
//     ('Under The Sea Cupcake (5p', 'Purple', 'https://img.ltwebstatic.com/images3_pi/2022/05/18/1652858929ea4ef56e54b36403b040226960d2485c_thumbnail_900x.webp', 5, 5, 75, 1),
//     ('Teddy Bear Cake', 'Brown, White', 'https://img.ltwebstatic.com/images3_pi/2023/02/02/167530179665b010fde6777e1599552f4a3de85830_thumbnail_900x.webp', 6, 3, 85, 2),
//     ('Black Pearl Cake', 'Black', 'https://img.ltwebstatic.com/images3_pi/2023/01/08/1673148933cb78c2db0ac6c29073b49a377812f6ce_thumbnail_900x.webp', 7, 2, 120, 5),
//     ('Little Animals Cake', 'Orange', 'https://img.ltwebstatic.com/images3_pi/2021/11/04/1635996040e84771fe7bc06d050492150308fe49fb_thumbnail_900x.webp', 8, 5, 65, 2),
//     ('Pearl Crown Cake', 'Pink', 'https://img.ltwebstatic.com/images3_pi/2021/08/19/1629370399d40fbec401d56413fff146e29fbfa741_thumbnail_900x.webp', 9, 2, 75, 1)";
    
//     $sql[16]="INSERT INTO `discount` (`itemID`) VALUES
//     (5),
//     (6),
//     (2)";

   


    foreach($sql as $sql){
    if ($conn->query($sql)) {
        echo "Table Records created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }
}
    $conn -> close();
?>
    </body>
</html>
