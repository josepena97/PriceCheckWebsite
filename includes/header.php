<?php


    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <title>Price Checkr </title>
        
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">    
    </head>
    
<body>
<header> 
    <nav class="navbar navbar-expand-lg navbar-dark bg-theme">
    
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
        
            <div class="collapse navbar-collapse" id="navbarSupportedContent">    
                <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img id="immg" src="includes/BURGER_ICON.png" >
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="Index.php">Home</a>
                                <a class="dropdown-item" href="ListProducts.php">List All Products</a>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a href="Index.php"> <img src="includes/home.png" id="home_image"> </a>
                        </li> 
                </ul>
            </div>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <strong><span class="logo">Price Checkr</span></strong>
                    </li>
                </ul>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent"> 
                <ul class="navbar-nav ml-auto">
<!--                    <li class="nav-item dropdown">-->
<!--                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">-->
<!--                        Welcome-->
<!--                            display users username in header if is logged in-->
                <?php
                if(isset($_SESSION['username'])){
                      //Welcome and information realated to user will only appera if user is signed in
                      //else we will only have log in and sign up in the header
                    echo '<li class="nav-item dropdown">';
                    echo '<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                    echo "Welcome ";
                    echo $_SESSION['username'];
                    echo "</a>";
                }else{
                    echo"";
                         }
                ?>  
                        
                    <!--Dynamic header depeding on if user is logged in or not                       -->
                <?php
                if(isset($_SESSION['username'])){
                    echo '<div class="dropdown-menu" aria-labelledby="navbarDropdown">';
                    if(strcmp($_SESSION["username"] , "admin")== 0){
                         echo '<a class="dropdown-item" href="admin.php">Admin page</a>';
                    }
                    echo '<a class="dropdown-item" href="profile.php">My profile</a>';
                    echo "</li>";
                    echo "</a>";
                                
                }else{
                    echo"";
                         }
                ?>
                            
<!--                 depending on if user is logged in or not,different header options will be displayed for an                              interactive webpage-->
                <?php
                //check if user is loged in
                if(isset($_SESSION['username'])){
                echo'<li class="nav-item">
                            <a class="nav-link" href="includes/logout.inc.php">Log Out</a>
                     </li>';
                    
                }else{
                    echo('<li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="signUp.php">Sign Up</a>
                        </li> ');
                }
                ?>
                                                                 
                </ul>
            </div>
        </nav>
</header>
</body>










</html>