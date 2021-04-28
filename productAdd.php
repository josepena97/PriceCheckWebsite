<?php
require_once('includes/mysqli_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(isset($_POST["btn"])){
    $id = $_POST["btn"];
    $sql = "INSERT INTO favoritelist (prod_id, user_id)
            VALUES (?, ?); ";

    $stmt = mysqli_stmt_init($dbc);

    if(!mysqli_stmt_prepare($stmt , $sql)){
      echo "fail";
    }else{
      mysqli_stmt_bind_param($stmt, "ii" , $id, $_SESSION['user_id']);
      mysqli_stmt_execute($stmt);
    }
  }
}
//header("Location: profile.php");
?>
