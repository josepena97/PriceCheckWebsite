<?php
//We could do
//$servername = "localhost";
//$dbUsername = "root";
//$dbPassword = "";
//$dbName = "name of the project";





//Here we open connection to database 
define ('DB_USERNAME' , 'root');
define ('DB_PASSWORD' , '');
define ('DB_HOST' , 'localhost');
define ('DB_NAME' , '360_project');
//
$dbc = mysqli_connect(DB_HOST , DB_USERNAME , DB_PASSWORD , DB_NAME)
    or die('Cannt conect to database: '.mysqli_connect_error());



//$dbc = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USERNAME, DB_PASSWORD);

?>