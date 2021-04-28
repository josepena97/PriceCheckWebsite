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
                            <h1 class="display">Product Name</h1> 
                        </div>

                    </div>
                </div>
                <hr class="my-2">
            </div>
        </div>  
        
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-12">
                        <div class="row , product">
                            <div class="col-1">
                                prod img
                            </div>
                            <div class="col-5">
                                Product Name:<br>
                                Product Price:<br>
                                Cheapest Store:
                                <hr class="my-2">
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="row">
                    <div class="col-12">
                        GRAPH OF PRICE 
                    </div>
                </div>
            </div>
 
    </main>

    <!-- include footer -->
     <?php 
        include("includes/footer.php")    
    ?>
    
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"> </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    
</body>
</html>