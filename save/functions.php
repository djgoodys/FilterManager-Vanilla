
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




?>
<!--<style>
  * {
    font-family:<?php echo $_SESSION["font_family"] ?>;
  }
  .myfontfamily {
  font-family: <?php echo $_SESSION["font_family"] ?>;
}
  </style>-->