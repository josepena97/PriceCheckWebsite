<?php
//This scrip will update the user information once it is submitted form the profile.php

require_once('includes/mysqli_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<h1> ENTRO </h1>";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  echo "Debugging: <ul>";

  //the following if statements check which buttom of the information was submitted
  if(isset($_POST["firstName"])){
    $postvar ="'".$_POST["firstName"]."'";
    $update = 'first_name';
    echo "<li> Update:".$update." Set: ".$postvar."</li>";
  }
  if(isset($_POST["lastName"])){
    $postvar ="'".$_POST["lastName"]."'";
    $update = 'last_name';
    echo "<li> Update:".$update." Set: ".$postvar."</li>";
  }
  if(isset($_POST["streetName"])){
    $postvar ="'".$_POST["streetName"]."'";
    $update = 'street';
    echo "<li> Update:".$update." Set: ".$postvar."</li>";
  }
  if(isset($_POST["city"])){
    $postvar ="'".$_POST["city"]."'";
    $update = 'city';
    echo "<li> Update:".$update." Set: ".$postvar."</li>";
  }
  if(isset($_POST["province"])){
    $postvar = "'".$_POST["province"]."'";
    $update = 'province';
    echo "<li> Update:".$update." Set: ".$postvar."</li>";
  }
  if(isset($_POST["zipcode"])){
    $postvar = "'".$_POST["zipcode"]."'";
    $update = 'postal_code';
    echo "<li> Update:".$update." Set: ".$postvar."</li>";
  }


  $sql = "UPDATE user SET ".$update." = ".$postvar." WHERE username =?;";
  echo "<li>SQL: ".$sql.$_SESSION['username']."</li>";

  //create prepared stmt
  $stmt = mysqli_stmt_init($dbc);
  if(!mysqli_stmt_prepare($stmt , $sql)){
  echo "fail";
  }else{
  mysqli_stmt_bind_param($stmt, "s" , $_SESSION['username']);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  }
}
//
header("Location: profile.php");
?>
