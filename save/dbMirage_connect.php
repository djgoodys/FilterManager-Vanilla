<?php

$whitelist = array('127.0.0.1', "::1");

if(!in_array($_SERVER['REMOTE_ADDR'], $whitelist)){
    $_SESSION["server_name"] = "pdb34.awardspace.net";
}
else
{
   $_SESSION["server_name"] = "localhost";
}

//WAS MADE TO LOCAL FOLDER FOR LOCAL USE
$ServerAddress = $_SERVER["SERVER_ADDR"];//"/MirageFilters";

$username = "4094059_demo";
$password = "relays82";
$database = "4094059_demo";

$UsersCon = mysqli_connect($_SESSION["server_name"],$username,$password,$database);
$UsersCon2 = mysqli_connect($_SESSION["server_name"],$username,$password,$database);
//$mysqli = new mysqli($servername, $username, $password, $database);

?>