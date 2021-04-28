<?php
/*
The following script will set up the database
by creating the tables
*/
require_once('includes/mysqli_connect.php');


//$cmd = "node scraper.js";


//$result = exec($cmd, $outputB);

//Debugging purposes
//var_dump($outputB);

//$cmd = "node scraper1.js";

//$result = exec($cmd, $outputB);

/* Debugging purposes
echo "result: ";
var_dump($result);
echo "\noutput: ";

var_dump($outputB);
*/

$fileName = "data/360_project.sql";
$file = file_get_contents($fileName, true);
$lines = explode(";", $file);
echo("<ol>");
foreach ($lines as $line){
  $line = trim($line);
  if($line != ""){
    echo("<li>".$line . ";</li><br/>");
    $dbc->query($line);
  }
}

//echo("</p><h2>Database loading complete!</h2>");

//This array contains the information files from the groceries websites
$textCnt  = array("inputFile/productSuper.txt", "inputFile/priceSuper.txt",
    "inputFile/productSaveOns.txt", "inputFile/priceSaveOns.txt", "inputFile/productImages.txt");

//This is the translator of the location from one webpage to another
$productStoreId=array(2,7,0,4,15,5,3,6,11,12,17);

$products = file($textCnt[0]);
$prices = file($textCnt[1]);
$products1 = file($textCnt[2]);
$prices1 = file($textCnt[3]);
$images = file($textCnt[4]);

$arraySize = count($products);

//This loop will upload all the information of the products to the database
for($i = 0; $i<$arraySize; $i++) {
    if($prices[$i] < $prices1[$productStoreId[$i]]){
        //echo $i."SUPER";
        $prFloat = (float) substr($prices[$i], 1);
        $sql = "INSERT INTO product (prod_name, prod_price, store_id, brand, prodImageURL) VALUES('".$products[$i]."', '".$prFloat."', '1', 'Campbell', '".$images[$i]."')";
    } else {
        //echo $i."SAVE".$prices1[$productStoreId[$i]];
        $prFloat = (float) substr($prices1[$productStoreId[$i]], 1);
        $sql = "INSERT INTO product (prod_name, prod_price, store_id, brand, prodImageURL) VALUES('".$products[$i]."', '".substr($prices1[$productStoreId[$i]], 1)."', '2', 'Campbell', '".$images[$i]."')";
    }

    if($dbc->query($sql) === TRUE){
        //echo "PRICE=>$ ".$prFloat;
        //echo $i."-".$productStoreId[$i]."##".$products[$i] ." >>> ".substr($prices[$i], 1)." == ".$prices1[$productStoreId[$i]]."<br/>";

    } else {
        echo "ERROR". $dbc->error ."<br>";
        echo $i."-".$productStoreId[$i]."##".$products[$i] ." >>> ".substr($prices[$i], 1)." == ".$prices1[$productStoreId[$i]]."<br/>";
    }

}

//Create admin user with hashed password
$password = password_hash("admin123" , PASSWORD_DEFAULT);
$sql = "INSERT INTO user (first_name, last_name, email, username, password) VALUES('admin', 'admin', 'pricechekr360@gmail.com', 'admin', ? );";

$stmt = mysqli_stmt_init($dbc);
if(!mysqli_stmt_prepare($stmt , $sql)){
    echo "error prepare";
}else{
    mysqli_stmt_bind_param($stmt, "s",$password);
    mysqli_stmt_execute($stmt);
}

header ("Location: Index.php");
