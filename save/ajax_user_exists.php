<?php

$Action = "";
if(isset($_GET["action"])){$Action=$_GET["action"];}
//echo "action=".$Action;
if($Action=="check_uname"){
 // echo "uname=".$_GET['username'];
$username = $_GET['username'];
$data = json_decode(file_get_contents("table_users.json"), true);
$userExists = false;
if($Action=="check_uname"){
foreach ($data as $user) {
            //echo $user["user_name"] ."==". $username;
    if ($user["user_name"] == $username) {
      $userExists = true;
       //echo "Username already exists.";
      break;
    }
  }

// Send response based on existence
if ($userExists) {
  echo "unavailable";
} else {
  echo "available";
}
}
}

if($Action=="check_bname"){
$bname = $_GET['bname'];

$data = json_decode(file_get_contents("table_users.json"), true);
$bnameExists = false;

foreach ($data as $user) {
 
    if (strtolower($user["backup_folder"]) == strtolower($bname)) {
       //echo "bname=".$bname . " ". $user["backup_folder"];
      $bnameExists = true;
       //echo "bname already exists.";
      break;
    }
  }

// Send response based on existence
if ($bnameExists == true) {
  echo "unavailable";
} else {
  echo "available";
}
}

