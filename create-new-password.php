<!DOCTYPE html>
<html>
<?php 

require("includes/header.php");
        
    $str = "";
    //once reset password submit is clicked->
    if(isset($_POST["resetpw-submit"])){
        $selector = $_POST["selector"];
        $validator = $_POST["validator"];
        $password = $_POST["pwd"];
        $passwordrepeat = $_POST["pwdrepeat"];
        //check if password are inputed and they match eahchother 
        if(empty($password ) || empty($passwordrepeat)){
            echo "empty passwords";
        }else if($password != $passwordrepeat){
             echo "no password match";
        }
        
        $currentdate = date("U");
        //db connection
        require('includes/mysqli_connect.php'); 
        //check if there are tokens in database with correct token(created) and date in db 
        
        $sql = "SELECT * FROM pwdreset WHERE pwdResetSelector=? AND pwdResetExpires >=".$currentdate;
        $stmt = mysqli_stmt_init($dbc);
        if(!mysqli_stmt_prepare($stmt , $sql)){
            echo "fail";
        //if prepared statement works ->
        }else{
            mysqli_stmt_bind_param($stmt, "s" , $selector);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if(!$row = mysqli_fetch_assoc($result)){
                echo"error no rows";
                exit();
            }else{
                
                //match token of db with one in form
                //check if the are in hexa and conver tot binary
                $tokenbin = hex2bin($validator);
                $tokencheck = password_verify($tokenbin , $row["pwdResetToken"]);
                
                if($tokencheck === false){
                    echo "error wrong token";
                    
                }else if($tokencheck === true){
                    //token matches the one from db :)
                    //now we change password...
                    $tokenmail = $row["pwdResetEmail"];
                    
                    $sql = "SELECT * FROM user WHERE email=?";
                    $stmt = mysqli_stmt_init($dbc);
                    if(!mysqli_stmt_prepare($stmt , $sql)){
                        echo "fail";
                        //if prepared statement works ->
                    }else{
                        mysqli_stmt_bind_param($stmt, "s" , $tokenmail);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        //check if there is user with that email in database 
                        
                        if(!$row = mysqli_fetch_assoc($result)){
                            echo"error no user";
                            exit();
                        }else{
                            //update user pasword

                            $sql = "UPDATE user SET password=? WHERE email =?;";
                            $stmt = mysqli_stmt_init($dbc);
                            
                            if(!mysqli_stmt_prepare($stmt , $sql)){
                                echo "fail update";
                            
                            //if prepared statement works ->
                            }else{
                                //hash password again for security
                                $newpwdhash = password_hash($password , PASSWORD_DEFAULT);
                                //update password to new user password 
                                
                                mysqli_stmt_bind_param($stmt, "ss" ,$newpwdhash ,$tokenmail);
                                mysqli_stmt_execute($stmt);

                                
                        
                                //delete token create for recovery from db                                
                                $sql ="DELETE FROM pwdreset WHERE pwdResetEmail=?";
                                $stmt = mysqli_stmt_init($dbc);
                                
                            if(!mysqli_stmt_prepare($stmt , $sql)){
                                echo "delete fail";
                            //if prepared statement works ->
                            }else{
                                mysqli_stmt_bind_param($stmt, "s" , $tokenmail);
                                mysqli_stmt_execute($stmt);
                                $str = "<div id='succes'>Your password has been successfully changed.  </div><a href='Index.php'> go back home </a>";
                                }
                            }
                        }           
                    }
                }
            }
        }   
    }else{
        
    }
?>

<body>
<main>

    <?php 
    
    $selector = $_REQUEST["selector"];
    $validator = $_REQUEST["validator"];
    //check if tokens in url are there !empty  
    if(empty($selector) || empty($validator)){
        echo "COULD NOT VALIDATE YOUR REQUEST   ";
    }else{
        //check if these are legit hexadecimal tokens
    if(ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false){
    
    ?>
    
    <form onsubmit="return checkpass();" method="post" id="forms">
          <fieldset>
                <div class="row">
                    <div class="col-6 , colrigth">
                        <label for="pwd">New password:</label>
                        <br>
                        <label for="pwd/repeat">Re-enetr your new password: </label>    
                    </div>
                    <div class="col-3">
                        <input type="hidden" name="selector" value="<?php echo $selector ?>">
                        <input type="hidden" name="validator" value="<?php echo $validator ?>">
                        
                        <input id="password" type="password" placeholder="Insert new password" name="pwd"  onkeyup="checkpass(); return false;" required>
                        
                        <input type="password" name="pwdrepeat" placeholder="Repeat your NEW password" required>
                        
                        
                    </div> 
                    </div>
                    <div class="row">
                        <div class="col-8 , colrigth">
                            <br>
                            <div id="errorpw"></div>
                            <?php echo($str)?>
                        </div>
                    </div>
                    <div class="col-7 , colrigth">
                        <br>
                        <input type="submit" value="Change password" name="resetpw-submit">
                    </div>
          </fieldset>
        </form>
        <?php
    }
}
    
    
    ?>
</main>
    
    <?php

    require("includes/footer.php")
    
    ?>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"> </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
        <!-- this script checks if password is 6 character long -->
        <script>
          const form = document.getElementById('form')
          const password = document.getElementById('password')
          const errore = document.getElementById('errorpw')
          
          //check is pssword is 6characters long
          
              
        
          function checkpass(){
              if(password.value.length < 6){
                
                errore.innerText = "Password must be at least 6 characters long ";
                return false;
            }else {
                errore.innerText = "";
                return true;
            }
          }
     
          
          

      </script>

    
    </body>
    </html>