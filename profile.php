<!--
This file creates the profile page
-->
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
    <div class="jumbotron">
        <div class="container-fluid">
            <div class="row mb-3">
                <div class="col-10 offset-1">
                    <div class="d-flex justify-content-center">
                        <h1 class="display">Your Profile</h1>
                    </div>
                </div>
            </div>
            <hr class="my-2">
        </div>
    </div>




    <div class="container-fluid" id="mainStyle">
        <div class="row">
            <div class="col-md-5">
                <?php
                if(isset($_SESSION['username'])){
                    $sql = "SELECT image FROM user WHERE username=?;";
                    $stmt = mysqli_stmt_init($dbc);

                    if(!mysqli_stmt_prepare($stmt , $sql)){
                        echo "fail";
                    }else{
                        mysqli_stmt_bind_param($stmt, "s" , $_SESSION['username']);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $rt = mysqli_fetch_array($result, MYSQLI_NUM);
                        if(empty($rt[0])){
                            echo '<a href="uploadImage.php"><img src="https://az-pe.com/wp-content/uploads/2018/05/kemptons-blank-profile-picture.jpg" height="100" width="100"></a>';
                        }else{
                            echo '<a href="uploadImage.php"><img src="images/'.$rt[0].'" height="200" width="200"></a>';
                        }
                    }
                } else {
                    echo '<img src="https://az-pe.com/wp-content/uploads/2018/05/kemptons-blank-profile-picture.jpg" height="100" width="100">';
                }
                ?>
            </div>
            <div class="col-md-7">
                <!--display users username in header if is logged in-->
                <?php
                if(isset($_SESSION['username'])){
                    $sql = "SELECT first_name, last_name FROM user WHERE username=?;";
                    $stmt = mysqli_stmt_init($dbc);

                    if(!mysqli_stmt_prepare($stmt , $sql)){
                        echo "fail";
                    }else{
                        mysqli_stmt_bind_param($stmt, "s" , $_SESSION['username']);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $rt = mysqli_fetch_array($result, MYSQLI_NUM);
                        echo "<h1>".$rt[0]." ".$rt[1]."</h1>";
                    }
                }
                ?>
            </div>
        </div>
    </div>


    <div class="row wrapper" id="barresponse">
        <div class="float-left col-md-3 collapse width " id="sidebar">
            <div class="list-group">
                <a href="#personalInformation" class="list-group-item border rounded" data-toggle="collapse" data-parent="#sidebar">Personal Information</a>
                <div class="collapse" id="personalInformation">

                    <!--Displays the interactive side bar that edit user information if logged in -->
                    <?php
                    if(isset($_SESSION['username'])){
                        $personalInfo = array(
                            array("fName", "First Name", "firstName"),
                            array("lName", "Last name", "lastName"),
                            array("stName", "Street Name", "streetName"),
                            array("ct", "City", "city"),
                            array("prv", "Province", "province"),
                            array("zCode", "Zip Code", "zipcode"),
                        );

                        $n = sizeof($personalInfo);
                        for($i = 0; $i < $n; $i++){

                            echo '<a href="#'.$personalInfo[$i][0].'" class="list-group-item border rounded" data-toggle="collapse">-'.$personalInfo[$i][1].'</a>
                              <div class="collapse" id="'.$personalInfo[$i][0].'">
                                <form method="post" action="profileUpdate.php">
                                  <input type="text" name="'.$personalInfo[$i][2].'" placeholder="Enter New Information "/>
                                  <input type="submit" value="Submit">
                                </form>
                              </div>';
                        }
                    }
                    ?>
                </div>
                <a href="#favoriteList" class="list-group-item border rounded" data-toggle="collapse" data-parent="#sidebar">Favorite List</a>
                <div class="collapse" id="favoriteList">

                    <!--Displays the interactive side bar that edit user favorite list if logged in -->
                    <?php
                    if(isset($_SESSION['username'])){
                        $sql = "SELECT P.prod_name, F.list_id
                                FROM product P, favoritelist F, user U
                                WHERE P.prod_id=F.prod_id and U.user_id = F.user_id and U.username = ?;";
                        $stmt = mysqli_stmt_init($dbc);

                        if(!mysqli_stmt_prepare($stmt , $sql)){
                            echo "fail";
                        }else{
                            mysqli_stmt_bind_param($stmt, "s" , $_SESSION['username']);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            $i = 0;
                            while($fvl = mysqli_fetch_array($result, MYSQLI_NUM)){
                                echo '<a href="#it'.$i.'" class="list-group-item border rounded" data-toggle="collapse">-'.$fvl[0].'</a>';
                                echo '<div class="collapse" id="it'.$i.'">
                          <form method="post" action="profileDelete.php">
                            <button type="submit" value="'.$fvl[1].'" name="btn">Delete</button>
                          </form>
                          </div>';
                                $i++;
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>



        <div class="col-lg-9  mx-auto">
            <a href="#" data-target="#sidebar" data-toggle="collapse">Edit</a>
            <div class="row" id="clientInfo">
                <div class="col-lg-3 col-md-12" id="stores">
                    <h2 class="text-center">Information</h2>
                    <div class="container">
                        <ul>
                            <!--Displays edit user information list if logged in -->
                            <?php
                            if(isset($_SESSION['username'])){
                                $sql = "SELECT first_name, last_name, street, city, province, postal_code
                              FROM user
                              WHERE username=?;";
                                $stmt = mysqli_stmt_init($dbc);

                                if(!mysqli_stmt_prepare($stmt , $sql)){
                                    echo "fail";
                                }else{
                                    mysqli_stmt_bind_param($stmt, "s" , $_SESSION['username']);
                                    mysqli_stmt_execute($stmt);
                                    $result = mysqli_stmt_get_result($stmt);
                                    $rt = mysqli_fetch_array($result, MYSQLI_NUM);

                                    echo "<li> First name: ".$rt[0]."</li>
                              <li> Last name: ".$rt[1]."</li>
                              </ul> <p>Address</p> <ul>
                              <li> Street Name: ".$rt[2]."</li>
                              <li> City: ".$rt[3]."</li>
                              <li> Province: ".$rt[4]."</li>
                              <li> Postal code: ".$rt[5]."</li>";
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-1 col-md-12"></div>
                <div class="col-lg-8 col-md-12 text-center" id="stores">
                    <h2>Favorite List</h2>
                    <div class="container border, prods">
                        <!--Displays edit user favorite list list if logged in -->
                        <?php
                        if(isset($_SESSION['username'])){

                            $sql = "SELECT P.prodImageURL, P.prod_name, P.prod_price, P.store_id
                                  FROM product P, favoritelist F, user U
                                  WHERE P.prod_id=F.prod_id and U.user_id = F.user_id and U.username = ?;";
                            $stmt = mysqli_stmt_init($dbc);

                            if(!mysqli_stmt_prepare($stmt , $sql)){
                                echo "fail";
                            }else{
                                mysqli_stmt_bind_param($stmt, "s" , $_SESSION['username']);
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                                while($dat = mysqli_fetch_array($result, MYSQLI_NUM)){
                                    echo '<div class="row , product">';
                                    echo '<div class="col-2-md">';
                                    echo '<img src="'.$dat[0].'">';
                                    echo '</div>';
                                    echo ' <div class="col-8"-md>';
                                    echo 'Product Name:'.$dat[1];
                                    echo '<br>Product Price: $'.$dat[2];
                                    echo '<br>Cheapest Store: '.($dat[3]==1?'Superstore':'SaveOn Food');
                                    echo '</div></div>';
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- include footer -->
<?php
include("includes/footer.php");

?>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>
