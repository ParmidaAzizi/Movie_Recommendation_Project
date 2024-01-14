<?php

session_start();

$userID = $_SESSION["userID"];

$result = shell_exec("python user_based.py $userID"); 

?>
