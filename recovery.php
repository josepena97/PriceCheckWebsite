    <?php
        //To send emials to the user we will use PHPMailer downloaded from github. this will allow us to send mails without having a mailing server
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;
        use PHPMailer\PHPMailer\SMTP;
        $str = "";
        //Here we will have the code for recovery
        //check if user clicked submit for password recovery
        if(isset($_POST["reset-submit"])){
            //connect to database
            require('includes/mysqli_connect.php');
            //We used tokens 
            //create
            $selector = bin2hex(random_bytes(8));
            //to autenticate user
            $token = random_bytes(32);

            $url = "localhost/360PROJECT_Client-side/create-new-password.php?selector=".$selector."&validator=".bin2hex($token);

            //we need token to last for limited time
            //this is half an hour ahead from now (1800s)
            $expires = date("U") + 1800; 


            //get mail inputed by user
            $mail = $_POST["email"];
            //deleteall previos tokens related to this email in order to maintain only one requres per user and a cleaner db
            $sql = "DELETE FROM pwdreset WHERE pwdResetEmail=?";

            $stmt = mysqli_stmt_init($dbc);

            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo"error";
                exit();
            }else{
                //we delete any entry that include this user and its token to ensure the current is the only entryb linked to user
                mysqli_stmt_bind_param($stmt , "s" ,$mail);
                mysqli_stmt_execute($stmt);

            }
            //statemetn to Insert toekn in db

            $sql = "INSERT INTO pwdreset (pwdResetEmail,pwdResetSelector,pwdResetToken,pwdResetExpires) VALUES(?,?,?,?);";
            $stmt = mysqli_stmt_init($dbc);

            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo"error";
                exit();


            }else{
                //keys will be inserted hashed in the db
                //protect token
                $hashtoken = password_hash($token, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt , "ssss" ,$mail,$selector,$hashtoken,$expires);
                //now we have token in database
                mysqli_stmt_execute($stmt);
            }
            
            $to = $mail;
            //We used PHPMailer to send mail with recovery link
            require "phpmailer/src/Exception.php";
            require "phpmailer/src/PHPMailer.php";
            require "phpmailer/src/SMTP.php";

            $mail = new PHPMailer(true);
            
            try {
                //Server settings
                //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output

                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'pricecheckr360@gmail.com';             // SMTP username
                $mail->Password   = 'pricecheck123@';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; 
                // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
                $mail->Port       = 587;                                    

                //Recipients
                $mail->setFrom('pricecheckr360@gmail.com', 'PriceCheckR');
                $mail->addAddress($to);     // Add a recipient
                $mail->addReplyTo('pricecheckr360@gmail.com', 'Information');

                // Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'PriceCheckr Password Recovery';
                //send ur for user to copy and paste in search bar

                $mail->Body    = "Copy the following link and paste it into your URL to recover your account password<br> link:   <br>".$url;

                $mail->send();

                $str = "<div id='succes'>An email to recover your password has ben sent to: ".$to."</div>";

        }catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $str = "<div id='errorpw'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}"."</div>";

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
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-10 offset-1">
                    <div class="d-flex justify-content-center">
                        <h1 class="display">Account Recovery</h1> 
                    </div>
                </div>
            </div>
            <hr class="my-2">
        </div>
    </div>    
    <div class="row" form-group>
        <div class="col-10 , colrigth">
            <?php
            echo $str;
            ?>
            <br>
            <br>
        </div>  
    </div>
    <div id="format" class="container-fluid">
        
        <form method="post">
            <fieldset>
                <div class="row , form-group">
                    <div class="col-6 , colrigth">
                        
                        <label>Email Address:</label>
                        <div style="font-size: 14px">*Enter the email the emial address of the account you want to recover</div>
                    </div>
                <div id="format" class="col-5">
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="enter@email.com" name="email">
                </div>
                </div>
                <br>
                <div class="row , form-group">
                    <div class="col-6 , colrigth">   
                    </div>
                <div id="format" class="col-5">
                    <input type="submit" value="Submit" name="reset-submit" />
                </div>
                
                    
                </div> 
            </fieldset>
        </form>
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
