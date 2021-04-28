<?php
require_once('includes/mysqli_connect.php')

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
                            <h1 class="display">Products</h1>
                        </div>
                    </div>
                </div>
                <hr class="my-2">
            </div>
        </div>



        <div id="prodcontainer" class="container-fluid">
            <div class="row ">
                <div id="filter" class="col-3">
                    <h3>Filters</h3>
                    <hr>
                    Filter by store:
                    <br>
                    <form method="post">
                        <select size="1" name="storeselec">
                        <!-- SELECT FROM ABAILABLE STORES-->
                        <?php
                          $query = "SELECT store_name,store_id FROM store";
                          $response = @mysqli_query($dbc , $query);
                          if($response){
                              while($dat = mysqli_fetch_array($response)){
                                  echo "<option value= ".$dat['store_id'].">".$dat["store_name"]."</option> <br>";
                                 }
                            }else{
                                echo "Error";
                            }

                        ?>
                        </select>
                        <br>
                        <br>
                        <input type="submit" name="filtersubmit"><input type="reset">
                    </form>

                    <?php

                        //each flag creates a different sql statement depending on scenario
                        $flag = false;
                        //Str will display different messages depending on scenario the default is this message as this is how the page will load
                        $str = "Showing All products";
                        $flag2 = false;
                    //first check if there is a filter applied to search
                    if(isset($_POST['filtersubmit'])){
                        //flag 2 is true when user uses filter by store
                        $flag2 = true;
                        $store = $_POST['storeselec'];
                        //set store name for str
                        if($store == 2){
                            $strname = "Save on foods";
                        }else if($store == 1){
                            $strname = "Superstore";
                        }
                    }

                    ?>
                    <br>
                    <br>
                </div>
                <div class="col-9" >
                    <h3>Search for products:</h3>
                    <p>**products are displayed from cheapest to most expensive</p>
                    <form method="post" >
                        <p align="left">
                            <input type="text" name="searchprod" placeholder="Enter the name of the poduct" size="55">
                            <input type="submit" name="submitprods"><input type="reset" value="Reset">
                        </p>
                    </form>

                    <?php

                    if(isset($_POST["submitprods"])){
                        $name = $_POST['searchprod'];

                        $namesearch = "%".$name."%";
                        //create a flag to indicate weather tehe user is searchiong for a spiecific product
                        $flag = true;
                        //check if search bar has been left empty to show ALL products
                        if(empty($name) == true){
                            //do SQL statement to show all products
                            $sql = "SELECT * FROM product ORDER BY ? ";
                            //indicate that user is not searching for a specific products so flag = false
                            $flag = false;
                        }else{
                            //user typed specific data to search for so preapare sql accordingly
                            $sql = "SELECT * FROM product WHERE prod_name LIKE ? ORDER BY prod_price ASC";
                            //Change str to appropiate msg
                            $str = "Showing products with '".$name."' in their name";
                        }
                        //initialize preapared statement
                        $stmt = mysqli_stmt_init($dbc);
                        //if statement fails ->
                        if(!mysqli_stmt_prepare($stmt , $sql)){
                            echo "fail";

                        }else{
                            //else execute prepared statement with appropiate $sql variable set depending on flags (scenarios)
                            mysqli_stmt_bind_param($stmt , "s" , $namesearch);
                            mysqli_stmt_execute($stmt);
                        }
                    }
                    ?>
                    <!--HTML where the information will be displayed          -->
                    <div class="container-fluid , prods">
                    <?php
                        //check if filters are applied
                        if($flag2 ==  true){
                            //do appropiate sql statement to filter
                            $sql = "SELECT * FROM product WHERE store_id = ? ORDER BY prod_price ASC";
                            $str = "Showing products from ".$strname;
                            $stmt = mysqli_stmt_init($dbc);
                            //try to prepare statement
                            if(!mysqli_stmt_prepare($stmt , $sql)){
                                echo"error";
                            //execute statement
                            }else{
                                mysqli_stmt_bind_param($stmt , "s" , $store);
                                mysqli_execute($stmt);
                                $result =mysqli_stmt_get_result($stmt);
                            }

                        //if flag == false means that the user is not searching fr a specific product so ALL products will be displayed
                        }else if($flag == false){

                            $sql = "SELECT * FROM product ORDER BY prod_price ASC ";
                            $stmt = mysqli_stmt_init($dbc);
                            if(!mysqli_stmt_prepare($stmt , $sql)){
                                echo "fail";
                            }else{
                                mysqli_stmt_execute($stmt);
                                $result = mysqli_stmt_get_result($stmt);
                            }
                        }else{

                        $result = mysqli_stmt_get_result($stmt);

                        }


                        //if user is searching for specific product check if the product name or sufix/prefix esists in database
                        if(mysqli_num_rows($result) > 0){
                                //print custom message
                                echo "<h3>".$str."</h3>";
                                //Print the results depending on flag
                                while($dat = mysqli_fetch_array($result)){
                                echo '<div class="row , product">';
                                echo '<div class="col-2-md">';
                                echo '<img src="'.$dat["prodImageURL"].'">';
                                echo '</div>';
                                echo '<div class="col-5-md">';
                                echo 'Product Name:'.$dat["prod_name"];
                                echo '<br>Product Price: $'.$dat["prod_price"];
                                echo '<br>Cheapest Store: '.($dat["store_id"]==1?'Superstore':'SaveOn Food').'</div>';
                                if(isset($_SESSION['username']))
                                  echo '<div class = "col-2-md">
                                          <form method="post" action="">
                                            <button type="submit" value="'.$dat["prod_id"].'" name="btn">Add</button>
                                          </form>
                                          </div>';
                                echo '</div>';
                              }
                        //no prod found under search
                        }else{
                            echo "<div id='errorpw'>No products found with ".$name."<div>";
                        }


                          ?>




                </div>
            </div>
        </div>
    </div>
    </main>






         <!-- include footer -->
     <?php
        include("includes/footer.php");

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
    ?>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"> </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>



</body>
</html>
