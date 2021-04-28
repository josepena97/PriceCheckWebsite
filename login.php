<?php
//str is what will be prited fpr the user to see
$str = "";
//once submit button for login is pressed ->
if(isset($_POST["login-submit"])){
  //make connection to database
    require('includes/mysqli_connect.php');
    //get data from form
    $uname = $_POST["uname"];
    $pword = $_POST["password"];

    $sql = "SELECT *  FROM user WHERE username =?;";
    //create prepaerd stmt
    $stmt = mysqli_stmt_init($dbc);
    if(!mysqli_stmt_prepare($stmt , $sql)){
        echo "fail";
    //if prepared statement works ->
    }else{
            mysqli_stmt_bind_param($stmt, "s" , $uname);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        //if there is a user with same name as the one entered(match) ->
        if(mysqli_num_rows($result) > 0){

            $row = mysqli_fetch_assoc($result);
            //check if password is equal to that in database using function that compares hashed pasword to entered one
            $pwck = password_verify($pword , $row["password"]);
            if($pwck == true ){
                //check if user is disabled by admin //check user state in db
                if(strcmp($row['state'] , "active") == 0){
                $str = ("<div id='succes'>LogIn Successful</div>");

                //once loged in, create session
                session_start();
                //store username as session variable for future use and prove that user is logged in
                $_SESSION['username'] = $row['username'];
                $_SESSION['fname'] = $row['first_name'];
                $_SESSION['lname'] = $row['last_name'];
                $_SESSION['user_id'] = $row['user_id'];

                if(isset($row['street']) && isset($row['city']) && isset($row['province']) && isset($row['postal_code'])){
                    $_SESSION['street'] = $row['street'];
                    $_SESSION['city'] = $row['city'];
                    $_SESSION['province'] = $row['province'];
                    $_SESSION['pc'] = $row['postal_code'];
                }
                }else{
                  $str = "<div id='errorpw'>Your user has been disabled, contact administration</div>";
                }
              //if password / username dont match to those in database
            }else{
                $str = "<div id='errorpw'>Wrong Username/ pasword combination</div>";
            }
        //If no username match is found
        }else{
            $str ="<div id='errorpw'>Wrong Username</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <?php
        require "includes/header.php";
    ?>
<body>

  <main>
    <div class="jumbotron">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-10 offset-1">
                        <div class="d-flex justify-content-center">
                            <h1 class="display">Login</h1>
                        </div>
                    </div>
                </div>
            <hr class="my-2">
            </div>

    </div>

   <?php
      //output messages to the user
        echo"<div class='row'>";
          echo '<div class="col-7 , colrigth">';
        echo ($str);
          echo "</div>";
    ?>

    <div class="container-fluid text-center , bordered">
      <div id="format">
        <form method="post">
          <fieldset>

                <div class="row">
                    <div class="col-6 , colrigth">
                        <label for="username">Username:</label>
                        <br>
                        <label for="password">Password: </label>

                    </div>

                    <div class="col-3">
                        <input type="text" id="username" placeholder="Username" name="uname" required>
                        <input type="password" id="password" aria-describedby="passwordHelpInline" placeholder="password" name="password" required>
                    </div>
                    </div>
                    <p>
                        <a href="recovery.php" style="font-size:0.7em;">Forgot your password?</a>
                    </p>
                <input type="submit" value="Submit" name="login-submit">
          </fieldset>
        </form>
      </div>

    <br>
    <div class="container text-center " id="splitborder">
        <br>
        <h4>
          New to Price Checkr?
        </h4>

        <button type="button" class="btn btn-outline-secondary"><a href="signUp.php">Sign Up</a></button>
    </div>
 </div>
</main>
      <!-- include footer -->
    <?php
        include("includes/footer.php")
    ?>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"> </script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

</body>
</html>
