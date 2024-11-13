<?php

//define('DB_USER', "djgoodys"); // db user
//define('DB_PASSWORD', "Miragetower3!"); // db password (mention your db password here)
//define('DB_DATABASE', "Mirage"); // database name
//define('DB_SERVER', "www.filtermanager.net"); // db server

//global $con;

$ServerAddress = "/MirageFilters";//WAS MADE TO LOCAL FOLDER FOR LOCAL USE
// Check connection
//if(mysqli_connect_errno()){
//	echo "Failed to connect to MySQL: " . mysqli_connect_error();
//}
$servername = "pdb34.awardspace.net";
$username = "4094059_demo";
$password = "relays82";
$database = "4094059_demo";
$con = mysqli_connect($servername,$username,$password,$database);
$con2 = mysqli_connect($servername,$username,$password,$database);
//try {
 //   $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
 //   $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 //   } catch(PDOException $e) {    
  //  echo "Connection failed: " . $e->getMessage();
 //   }
?>