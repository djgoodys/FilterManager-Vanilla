
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
global $con;
   if ($con->connect_error) 
      {
         die("Connection failed: " . $con->connect_error);
       } 
   
    $sql = "UPDATE users SET ".$columnName."='" . $value . "' WHERE user_name='" . $uname . "';";
    
   if ($con->query($sql) === TRUE) 
      {
        $_SESSION[$columnName] = $value;
        //echo "colname=".$columnName ." _SESSION[".$columnName."] =". $value. "<br>";
      } 
      else 
      {
         echo "Error updating user setting: ".$columnName. " error: " . $con->error;
      }
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