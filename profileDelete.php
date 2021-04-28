<?php
/*
The following script will delete a product from favorite
*/
require_once('includes/mysqli_connect.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


echo "<h1> ENTRO </h1>";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  echo "Debugging: <ul>";
  echo $_POST["btn"];
  if(isset($_POST["btn"])){
    $id = $_POST["btn"];
    $sql = "DELETE FROM favoritelist WHERE list_id=?; ";
    $stmt = mysqli_stmt_init($dbc);

    if(!mysqli_stmt_prepare($stmt , $sql)){
      echo "fail";
    }else{
      mysqli_stmt_bind_param($stmt, "i" , $id);
      mysqli_stmt_execute($stmt);
    }
  }
}
header("Location: profile.php");
?>
