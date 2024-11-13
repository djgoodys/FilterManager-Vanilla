<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
//echo "no session";
  session_start();
}
include "functions.php";

$Action = "";
if(isset($_GET["field"])){$field= $_GET["field"];}
if(isset($_GET["value"])){$value= $_GET["value"];}
if(isset($_GET["action"])){$Action = $_GET["action"];}
if(isset($_GET["color"])){$Color = $_GET["color"];}
if(isset($_GET["font_family"])){$FontFamily = $_GET["font_family"];}
//print_r($_GET);
echo "field=". $field." value=".$value. "<br>";
if($Action == "update_server_variables"){
   echo "action=".$Action."<br>";
switch ($field) {
  case "font_size":
    UpdateUserSettings("font_size", $value);
    $_SESSION["font_size"] = $value;
    break;

  case "background_color":
    UpdateUserSettings("background_color", $value);
    echo "from ajax_receiver.php value=".$value;
    $_SESSION["background_color"] = $value;  
    break;

    case "font_family":
    UpdateUserSettings("font_family", $value);
    $_SESSION["font_family"] = $value;

    case "font_color":
    UpdateUserSettings("font_color", $value);
    $_SESSION["font_color"] = $value;
}


  $jsonString = file_get_contents('table_users.json');
  $data = json_decode($jsonString, true);
  echo $field. " value=".$value. " user". $_SESSION["user_name"];
  
  foreach ($data as &$object) {
      if ($object['user_name'] == $_SESSION["user_name"]) {
        echo "<br>found=".$object[$field]."<br>";
          $object[$field] = $value;
          break;
      }
}
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('table_users.json', $jsonString);
}

