
<html>

<script>
   function showFilterTypes($item_id){
      if(document.getElementById('ckShowFilterTypes'+$item_id).checked)
      {
      myselect = document.getElementById('slctFilterTypes'+$item_id);
      document.getElementById('ckShowFilterTypes'+$item_id).style.display = "none";
      myselect.className = "dropdown";
       }
      else
      {
      myselect = document.getElementById('slctFilterTypes'+$item_id);
      document.getElementById('ckShowFilterTypes'+$item_id).style.display = "block";
      myselect.className= "d-none";
      }
   }

   </script>
</html>
<?php

$Theme="Light-tdPlain";
if(isset($_SESSION["theme"])){$Theme = $_SESSION["theme"];}

function UpdateUserSettings($columnName, $value, $uname)
   {
      $jsonData = file_get_contents("table_users.json");
      $data = json_decode($jsonData, true);
      foreach ($data as &$user) {
         //echo "uname=".$user["user_name"];
         if (isset($user["user_name"]) && $user["user_name"] == $uname) {
            switch($columnName){
            case "font_size": 
               $user['font_size'] = $value; 
            break; 
            case "font_family": 
               $user['font_family'] = $value; 
            break; 
            }
      }
}

$updatedJsonData = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents("table_users.json", $updatedJsonData);

 
}
function setVariable($key, $value){
  echo "<input type='text' name='$key' value='$value' id='$key' style='display:none';>";
}
?>
<style>
  * {
    font-family:<?php echo $_SESSION["font_family"] ?>;
  }
  .myfontfamily {
  font-family: <?php echo $_SESSION["font_family"] ?>;
}
  </style>