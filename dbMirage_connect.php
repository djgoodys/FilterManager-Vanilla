<?php

//define('DB_USER', "djgoodys"); // db user
//define('DB_PASSWORD', "Miragetower3!"); // db password (mention your db password here)
//define('DB_DATABASE', "Mirage"); // database name
//define('DB_SERVER', "www.filtermanager.net"); // db server

$whitelist = array('127.0.0.1', "::1");

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    $servername = "pdb34.awardspace.net";
}
else
{
   $servername = "localhost";
}
//global $con;
//WAS MADE TO LOCAL FOLDER FOR LOCAL USE
$ServerAddress = "/MirageFilters";


$username = "4094059_mirage";
$password = "relays82";
$database = "4094059_mirage";

//$servername = "pdb34.awardspace.net";
//$username = "4094059_mirage";
//$password = "relays82";
//$database = "4094059_mirage";
$con = mysqli_connect($servername,$username,$password,$database);
$con2 = mysqli_connect($servername,$username,$password,$database);
$mysqli = new mysqli($servername, $username, $password, $database);

?>