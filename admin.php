<!-- connect to database connector -->
<?php
//start session since admin page dont have header included
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 
    require_once('includes/mysqli_connect.php'); 
    if(strcmp($_SESSION["username"] , "admin") == 0){        
    }else{
        //If the user is not logged in as admin the page will not allow him to acces admin page adn will redirect him to the index page
        header("Location: Index.php?");
        //http://localhost/360PROJECT_Client-side/Index.php
    }

?>

    
<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Price Checkr - Admin</title>

  <link rel="stylesheet" href="reset.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <header>
    <h1>Administration</h1>
      <br>
    <a href="Index.php">Back Home</a>
  </header>
 
<main>
      <br>
    <hr>
    <h3>Display Informatio here</h3>
    Select what to search for (username/productname/storename) you can input a letter or part of the name you are looking // Leave blank to show ALL entries (of selected table) in database
    
    <form method="post" >
      <p align="left">
        <select size="1" name="categoryName">
            <option value="product">Products</option>
            <option value="store">Stores</option>
            <option value="user">Users</option>
          
        </select>
        <input type="text" name="search" placeholder="What to Search" size="50">
       <br>
        <input type="submit" value="Submit" name="admin-submit"><input type="reset" value="Reset"></p>
         
    </form>
    
    <?php
    //Here we get the desired infromation from db
    if(isset($_POST["admin-submit"])){

        if(isset($_POST["categoryName"])){
        //what table to show    
        $inpu = $_POST['categoryName'];
        $presearch = $_POST['search'];
        //To allow WHERE LIKE %% Function in SQL we do this 
        $search = "%".$presearch."%";  
        //check value of selected drop down menu option and return values acordingly
        //Retrieve Product information
        if(strcmp($inpu , "product") == 0){
            if(empty($search) == true){
                
                $sql = "SELECT prod_name , prod_price , prod_id FROM product ORDER BY ?";
                
            }else{
                
                $sql = "SELECT prod_name , prod_price , prod_id FROM product WHERE prod_name LIKE ?";
                }
            $stmt = mysqli_stmt_init($dbc);
            //try to prepare statement
            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo "fail";
            
            }else{
                mysqli_stmt_bind_param($stmt , "s" , $search);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                    //num is counter that will be displayed to indicate the number of appearances
                    $num = 0;
                while($dat = mysqli_fetch_array($result)){
                    echo $num.": Product Name: ".$dat["prod_name"]." | Product Price:".$dat["prod_price"]." | Product Id: ".$dat["prod_id"]."<br>";
                    $num++;
                }
            }
        }
        //if user is looking for darta in store ->
        if(strcmp($inpu , "store") == 0){
            if(empty($search) == true){
                $sql = "SELECT store_name , store_id  FROM store ORDER BY ?";
            }else{
                $sql = "SELECT store_name , store_id  FROM store WHERE store_name LIKE ?";
                }
        
            $stmt = mysqli_stmt_init($dbc);
        
            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo "fail";
            
            }else{
                mysqli_stmt_bind_param($stmt , "s" , $search);
                mysqli_stmt_execute($stmt);
            
                $result = mysqli_stmt_get_result($stmt);
                $num = 0;
                while($dat = mysqli_fetch_array($result)){
                    echo $num.":  Store Name: ".$dat["store_name"]." | Store Id: ".$dat["store_id"]."<br>";
                    $num++;
                }
            } 
        }
        //if admin is looking for information about users -> 
        if(strcmp($inpu , "user") == 0){
            //Return all users if no special name is entered to search for
            if(empty($search) == true){
                
                $sql = "SELECT username , email , user_id FROM user ORDER BY ?";
                
            }else{
                
                $sql = "SELECT username , email , user_id FROM user WHERE username LIKE ?";
            }
            
            $stmt = mysqli_stmt_init($dbc);
            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo "fail";
            }else{
                mysqli_stmt_bind_param($stmt , "s" , $search);
                mysqli_stmt_execute($stmt);
            
                $result = mysqli_stmt_get_result($stmt);
                $num = 0;
                while($dat = mysqli_fetch_array($result)){
                    echo $num.": Username: ".$dat["username"]." | Email: ".$dat["email"]." | User id: ".$dat["user_id"]."<br>";
                    $num++;
                }
            }   
        }
    }       
}?>
    <hr>
    <hr>
    <h3>Disable users here</h3>
    <form method="post" >
      <p align="left">
        <input type="text" name="delete" placeholder="Type username of user to disable it" size="50">
       <br>
        <input type="submit" value="Submit" name="admin-delete"><input type="reset" value="Reset">
    </p>
         
    </form>
    
    <?php
    //we disable users by setting their state to "disabled", wich will be recognied when the user tryes to log in again in th elog in page
    if(isset($_POST["admin-delete"])){
        
        $username1 = $_POST["delete"];
        
        if(empty($_POST["delete"])){
            echo "No user to delete please type username to delete";
        }else{
            
            $sql = "UPDATE user SET state = 'disabled' WHERE username = ?";
            $stmt = mysqli_stmt_init($dbc);
            if(!mysqli_stmt_prepare($stmt ,$sql)){
                echo "prepare error";
            }else{
                mysqli_stmt_bind_param($stmt , "s" , $username1);
                mysqli_stmt_execute($stmt);
                echo "User ".$username1." Disabled";
            }
        }
    }?>
    
    <hr>
    <h3>Enable users here</h3>
    <form method="post" >
      <p align="left">
        <input type="text" name="enable" placeholder="Type username of user to enable it" size="50">
       <br>
        <input type="submit" value="Submit" name="admin-enable"><input type="reset" value="Reset">
    </p>     
    </form>
    <?php
    //we will give the admin the posibility to enable disabled users by changing thir state to "active" which will allow them to login again
    if(isset($_POST["admin-enable"])){
        
        $username1 = $_POST["enable"];
        
        if(empty($_POST["enable"])){
            echo "No user to enable please type username to delete";
        }else{
            $sql = "UPDATE user SET state = 'active' WHERE username = ?";
            $stmt = mysqli_stmt_init($dbc);
            if(!mysqli_stmt_prepare($stmt ,$sql)){
                echo "prepare error";
            }else{
                mysqli_stmt_bind_param($stmt , "s" , $username1);
                mysqli_stmt_execute($stmt);
                echo "User ".$username1." Enabled";
            }
        }
    }?>
    <hr>
    <hr>
    <h3>Add products here</h3>
    <form method="post" >
        <p align="left">
            <input type="text" name="prodname" placeholder="Product name" required >
            <input type="number" step="0.01" name="prodprice" placeholder="Product price" required>
            <input type="text" name="prodbrand" placeholder="Product brand" required>
        

            <select size="1" name="storeselec">
    <!--            SELECT FROM ABAILABLE STORES IN DB  -->
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
            <input type="submit" value="Submit" name="admin-add"><input type="reset" value="Reset">
        </p>     
    </form>
    
    <?php
    //Add new products to db
        if(isset($_POST["admin-add"])){
            $pname = $_POST['prodname'];
            $pprice = $_POST['prodprice'];
            $pbrand = $_POST['prodbrand'];
            $pstore = $_POST['storeselec'];

            $sql = "INSERT INTO product( prod_name, prod_price, brand, store_id) VALUES (?,?,?,?)";
            $stmt = mysqli_stmt_init($dbc);

            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo "error";
            }else{
                mysqli_stmt_bind_param($stmt , "sdss" , $pname,$pprice,$pbrand,$pstore);
                mysqli_stmt_execute($stmt);
                echo "Product added!";
            }
        }
    ?>
    
    <!--Edit information about products here -->
    <hr>
    <h3>Edit products here</h3>
    <form method="post" >
        <p align="left">
            input the id of the product you want to edit: 
            <input type="text" name="prodidedit" placeholder="Product id" required>
            <br>
            input the new information about prduct:
            <input type="text" name="prodnameedit" placeholder="Product name"  >
            <input type="number" step="0.01" name="prodpriceedit" placeholder="Product price" >
            <input type="text" name="prodbrandedit" placeholder="Product brand" >
            <select size="1" name="storeselecedit">
            <!--        SELECT FROM AVAILABLE STORES IN DB-->
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
            <input type="submit" value="Submit" name="admin-edit"><input type="reset" value="Reset">
        </p>    
    </form>
    
    <?php
        //we allow admin to UPDATE any information of any product based on its unique product id
        //image is not allowed to change because image of product is not dependant on us but is retreived from original webpage
        if(isset($_POST["admin-edit"])){

            $pnameedit = $_POST['prodnameedit'];
            $ppriceedit = $_POST['prodpriceedit'];
            $pbrandedit = $_POST['prodbrandedit'];
            $pstoreedit = $_POST['storeselecedit'];
            $prodid = $_POST['prodidedit'];

            $sql = "UPDATE product SET prod_name = ?, prod_price = ? , brand = ? , store_id = ? WHERE prod_id = ?" ;
            $stmt = mysqli_stmt_init($dbc);

            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo "error";
            }else{
                mysqli_stmt_bind_param($stmt , "sdsss" , $pnameedit , $ppriceedit , $pbrandedit , $pstoreedit , $prodid);
                mysqli_stmt_execute($stmt);
                echo "Product edited!";
            }
        }
    ?>
    
    <hr>
    <h3>Delete products here</h3>
    <form method="post" >
      <p align="left">
          input the id of the product you want to delete: 
        <input type="text" name="prodidelete" placeholder="Product id" required>
        <br>
        <input type="submit" value="Submit" name="admin-proddelete"><input type="reset" value="Reset">
    </p>
         
    </form>
    
    <?php
        //Admin can delete products from database based on the product id 
        if(isset($_POST["admin-proddelete"])){

            $prodiddel = $_POST['prodidelete'];

            $sql = "DELETE FROM product WHERE prod_id =?" ;
            $stmt = mysqli_stmt_init($dbc);

            if(!mysqli_stmt_prepare($stmt , $sql)){
                echo "error";
            }else{
                mysqli_stmt_bind_param($stmt , "s" ,$prodiddel);
                mysqli_stmt_execute($stmt);
                echo "Product deleted!";
            }
        }
    ?>
    
  </main>
</body>
<?php
  //close connection to db  
    mysqli_close($dbc);
?>