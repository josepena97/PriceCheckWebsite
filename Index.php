<!-- connect to database connector -->
<?php
require_once('includes/mysqli_connect.php');
?>
<!DOCTYPE html>
<html>
<script type="text/javascript" src="scraper.js"></script>
<script src="http://code.jquery.com/jquery3.1.1.min.js"></script>
<script type="text/javascript">
    window.jQuery || document.write('<script src="js/jquery-3.1.1.min.js"><\/script>');

</script>
<script src="scraper.js"></script>
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
                            <h1 class="display">Home</h1>
                        </div>
                    </div>
                </div>
            <hr class="my-2">
            </div>
            <div class="d-flex justify-content-center">
                <p class="lead">
                    <a class="btn btn-primary btn-lg mt-4" href="ListProducts.php" role="button">Search All Products</a>
                </p>
            </div>
        </div>

    <!-- Main body in container -->
        <div class="container-fluid">
            <div class="row">
                <div id="stores" class="col-3">
                    <h4>We work with:</h4>
                    <ul>
                        <?php
                            $query = "SELECT store_name, store_URL FROM store";
                            $response = @mysqli_query($dbc , $query);
                            if($response){
                                while($dat = mysqli_fetch_array($response)){
                                    echo '<li><a href="'.$dat["store_URL"].'">'.$dat["store_name"].'</a></li>';
                                }
                            }else{
                                echo "Error";
                            }
                        ?>
                    </ul>
                </div>
                <div  class="col-9">

                    <div class="container-fluid , prods">
                      <!-- this will list the top 3 cheapest product of the market-->
                      <?php

                        echo "<h2> Todays top 3 deals:</h2> <hr> ";
                        $sql = "SELECT * FROM product ORDER BY prod_price ASC LIMIT 3 ";
                        $stmt = mysqli_stmt_init($dbc);
                         if(!mysqli_stmt_prepare($stmt , $sql)){
                            echo "fail";
                         }else{
                             mysqli_stmt_execute($stmt);
                             $result = mysqli_stmt_get_result($stmt);
                         }

                        if($result){
                          while($dat = mysqli_fetch_array($result)){
                          echo '<div class="row , product">';
                          echo '<div class="col-2-md">';
                          echo '<img src="'.$dat["prodImageURL"].'">';
                          echo '</div>';
                          echo '<div class="col-5-md">';
                          echo 'Product Name:'.$dat["prod_name"];
                          echo '<br>Product Price: $'.$dat["prod_price"];
                          echo '<br>Cheapest Store: '.($dat["store_id"]==1?'Superstore':'SaveOn Food').'</div>';
                          //this will add the product to the favorite if logged in
                          if(isset($_SESSION['username']))
                            echo '<div class = "col-2-md">
                                    <form method="post" action="">
                                      <button type="submit" value="'.$dat["prod_id"].'" name="btn">Add</button>
                                    </form>
                                    </div>';
                          echo '</div>';
                          }
                        }else{
                          echo "Error";
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

        //This will add the product to the favorite list if  logged in
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
