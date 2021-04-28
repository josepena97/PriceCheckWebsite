<?php
require_once('includes/mysqli_connect.php');

?>
<!DOCTYPE html>
<html>

<?php
    require "includes/header.php";

    ?>
<body>

<main>
<div id="content">
  <form method="POST" action="" enctype="multipart/form-data">
  	  <input type="file" name="image"> <br>
  		<button type="submit" name="upload">Upload</button>
  </form>
</div>
<?php

  // If upload button is clicked ...
  if(isset($_SESSION['username'])){
    if (isset($_POST['upload'])) {
    	// Get image name
    	$image = $_FILES['image']['name'];

    	// image file directory
    	$target = "images/".basename($image);

      $sql = "UPDATE user
              SET image=?
              WHERE username=?";
      $stmt = mysqli_stmt_init($dbc);

      echo $image."<br>".$target."<br>";
      if(!mysqli_stmt_prepare($stmt , $sql)){
        echo "fail";
      }else{
        mysqli_stmt_bind_param($stmt, "ss" , $image, $_SESSION['username']);
        mysqli_stmt_execute($stmt);
      }
      if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    		$msg = "Image uploaded successfully";
        header("Location: profile.php");
    	}else{
    		$msg = "Failed to upload image";
    	}
    }
  }

?>
</body>
</html>
