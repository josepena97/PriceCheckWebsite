
    <!DOCTYPE html>
    <html>

   <?php 
        require_once('includes/mysqli_connect.php');
        require "includes/header.php";
    ?>        
<body>
    <main>
     <div class="jumbotron">
                <div class="container">
                    <div class="row mb-3">
                        <div class="col-10 offset-1">
                            <div class="d-flex justify-content-center">
                                <h1 class="display">Sign Up</h1>
                            </div>
                        </div>
                    </div>
                <hr class="my-2">
                </div>
            </div>
        <div class="container-fluid text-center">

            <!-- save data to database -->
              <?php 
                //once form is submited
                if(isset($_POST['submit'])){
                            
                //check if all required fields are set
                if(isset($_POST['firstName']) && isset($_POST["lastName"]) && isset($_POST["email"]) && isset($_POST["userName"]) && isset($_POST["password"])){
                     $fname = $_POST["firstName"];
                     $lname = $_POST["lastName"];
                     $email = $_POST["email"];
                     $usern = $_POST["userName"];
                     $pword = $_POST["password"];
                     $streetn = $_POST["streetName"];
                     $city = $_POST["city"];
                     $prov = $_POST["province"];
                     $posc = $_POST["zipcode"];        
                    //check if email already in db
                    $sql = "SELECT *  FROM user WHERE email =?;";
                    //create prepaerd stmt
                    $stmt = mysqli_stmt_init($dbc);
                    if(!mysqli_stmt_prepare($stmt , $sql)){
                        echo "fail";
                    }else{
                        mysqli_stmt_bind_param($stmt, "s" , $email);
                        mysqli_stmt_execute($stmt);
                                    
                        $result = mysqli_stmt_get_result($stmt);
                    }
                    //if there are results under the statement it means the mail is already linked to an account
                    if(mysqli_num_rows($result) > 0){             
                        echo"<div id='errorpw'>Email already exists, there can only be one account linked tho one email</div>";               
                    
                    //if it doesnt exist, now check if username is abailable
                    }else{
                        //check if username already exists
                         $sql = "SELECT *  FROM user WHERE username =?;";
                        //create prepaerd stmt
                        $stmt = mysqli_stmt_init($dbc);
                        if(!mysqli_stmt_prepare($stmt , $sql)){
                            echo "fail";
                        }else{
                            mysqli_stmt_bind_param($stmt, "s" , $usern);
                            mysqli_stmt_execute($stmt);     
                            $result = mysqli_stmt_get_result($stmt);
                        }
                    //if usernmae exists there will be rows returned 
                    if(mysqli_num_rows($result) > 0){
                        echo"<div id='errorpw'>Username already exists, please choose another username</div>";
                                    
                    }
                    //if it doesnt exist, create user
                    else{
                                    
                        //Hash password for security
                        $hasedpw = password_hash($pword , PASSWORD_DEFAULT);
                                    
                                    
                        //insert values into database
                        //prepared statement
                        $sql2 = "INSERT INTO user(first_name , last_name ,email ,username,password,street,city,province,postal_code) VALUES(? ,? ,? ,? ,? ,? ,? ,? ,? )";
                                    
                        $stmt2 = mysqli_stmt_init($dbc);
                        if(!mysqli_stmt_prepare($stmt2,$sql2)){
                            echo "error";
                        }else{
                            mysqli_stmt_bind_param($stmt2 , "sssssssss", $fname , $lname , $email , $usern , $hasedpw ,$streetn,$city,$prov,$posc);
                            mysqli_stmt_execute($stmt2);
                            echo ("<div id='succes'>Your account was succesfully created</div>");
                            echo ('<a href="login.php"><h2>Log In</h2></a>');
                        }
                    }
                }
            }
        }
    ?>
            <div id="format">
            <form onsubmit="return (checkpass() && checkpassre());" id="form"  method="post">
                <fieldset class="bordered">
                    <div class="container-fluid">
                        <div class="row">
                            <legend>Identification</legend>
                            <div class="col-6 , colrigth">
                                <label for="fname">*First Name:</label>
                                <br>
                                <label for="lname">*Last Name:</label>
                                <br>
                                <label for="exampleInputEmail1">*Email Address:</label>
                                <br>

                                <label for="username">*Username:</label>
                                <br>
                                <label for="password">*Password:</label>
                                <br>
                                <label for="passwordre">*Confirm Password:</label>
                                <div style="font-size:13px; color: grey">* Required information</div>
                                <div id="errorpw"> </div>
                                
                                </div>
                            <div class="col-3">
                                <input type="text" id="fname" placeholder="FirstName" name="firstName" required  >

                                <input type="text" id="lname" placeholder="LastName" name="lastName" required>
                                <input id="exampleInputEmail1" type="email"  aria-describedby="emailHelp" placeholder="Enter@email.com" name="email"  required>
                                <input id="username" type="text" placeholder="UserName" name="userName"required >
                                
                                <input id="password" type="password" placeholder="Password" name="password" onkeyup="checkpass(); return false;" required>
                                
                                <input id="passwordre" type="password" placeholder="re enter your Password" name="passwordre" required>

                                
                            </div>
                        </div>
                    </div>
                    <hr>
                  
                    <div class="container-fluid">
                        <div class="row">
                            <legend>Address</legend>
                            <div class="col-6 , colrigth">
                                <label for="street">Street Name:</label>
                                <br>
                                <label for="city">City:</label>
                                <br>
                                <label for="prov">Province:</label>
                                <br>
                                <label for="zip">Zip Code:</label>
                                </div>
                            <div class="col-3">
                                <input type="text" id="street" placeholder="streetName" name="streetName">
                                <input type="text" id="city" placeholder="City" name="city">
                                <input type="text" id="prov" placeholder="Province" name="province">
                                <input type="text" id="zip" placeholder="Zip Code" name="zipcode">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <input type="submit" value="Submit" name="submit" /><input type="reset" value="Reset">
              </fieldset>
            </form>
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
        <!-- this script checks if password is 6 character long -->
        <script>
          const form = document.getElementById('form')
          const password = document.getElementById('password')
          const passwordre = document.getElementById('passwordre')
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
        //function to check if password and re entered password match 
         function checkpassre(form){
             if(password.value != passwordre.value){
                errore.innerText = "Password dont match";
                
                return false;
            }else {
                errore.innerText = " ";
                return true;
            }
          }
      </script>
    </body>
    </html>
