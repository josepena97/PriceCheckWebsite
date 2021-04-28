<?php
/*
This script will delete the favorite products
that are desired to be deleted form the profile
*/

require_once('includes/mysqli_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


echo "<h1> ENTRO </h1>";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  echo "Debugging: <ul>";
  echo $$_POST["name"];
  if(isset($_POST["name"])){
    $postvar ="'".$_POST["firstName"]."'";
    $update = 'first_name';
    echo "<li> Update:".$update." Set: ".$postvar."</li>";
  }

header("Location: profile.php");
?>
