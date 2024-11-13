<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Filter Type</title>
</head>
<style>
input.myCheckBox {
  width: 40px;
  height: 40px;
  background-color: orange;
}
</style>
<?php
include('dbMirage_connect.php');

$UnitName="";
$TaskID="";
$Page="";
if(isset($_GET["username"])){$UserName=$_GET["username"];}
if(isset($_GET["unit_name"])){$UnitName=$_GET["unit_name"];}
if(isset($_GET["filter_rotation"])){$FilterRotation=$_GET["filter_rotation"];}
if(isset($_GET["unit_id"])){$unitId=$_GET["unit_id"];}
if(isset($_GET["filters_used"])){$FiltersUsed=$_GET["filters_used"];}
if(isset($_GET["filter_size"])){$FilterSize=$_GET["filter_size"];}
if(isset($_GET["task_id"])) {$TaskID=$_GET["task_id"];}
if(isset($_GET["page"])){$Page=$_GET["page"];}
if(isset($_GET["action"])){$Action = $_GET["action"];}
if(isset($_GET["filters_due"])){ $FiltersDue=$_GET["filters_due"];}
if(isset($_GET["filter_type"])){ $FilterType=$_GET["filter_type"];}
if(isset($_POST["filters_used"])){$FiltersUsed=$_POST["filters_used"];}
if(isset($_POST["username"])){$UserName=$_POST["username"];}
if(isset($_POST["page"])){$Page=$_POST["page"];}
if(isset($_POST["action"])){$Action = $_POST["action"];}
if(isset($_POST["task_id"])) {$TaskID=$_POST["task_id"];}
if(isset($_POST["unit_id"])){ $unitId=$_POST["unit_id"];}
if(isset($_POST["filter_size"])){$FilterSize=$_POST["filter_size"];}
if(isset($_POST["filter_rotation"])){ $FilterRotation=$_POST["filter_rotation"];}
if(isset($_POST["filters_due"])){ 
$FiltersDue = $_POST["filters_due"];
}
if(isset($_POST["filters_used"])){ $FiltersUsed=$_POST["filters_used"];}
//echo "Page=". $Page;
?>
<body style="background-color:green;color:white;">
<script type="text/javascript" src="javafunctions.js"></script>
<script>
function enableSubmit(){
mybutton = document.getElementById("submitfilters");
myselect = document.getElementById("fselect");
if(myselect.options[myselect.selectedIndex].text != "Select"){
mybutton.disabled = false;
}
if(myselect.options[myselect.selectedIndex].text === "Select")
{
mybutton.disabled = true;
}
if(myselect.options[myselect.selectedIndex].text === "Paper")
{
mybutton.disabled = false;
}
}
</script>
<script type="text/javascript" src="javafunctions.js">

alert("jj");
    mytheme = getCookie("theme");
    alert(mytheme);

</script>
<center>
<br><br><br><br><br><br>
    <form action="<?php echo $Page ?>" method="POST">
    <input type="checkbox" onchange="this.form.submit();" class="myCheckBox" name="no_filters_used" value="<?php echo $FilterType ?>"><font size="80">NO FILTERS WERE USED<br>
    Select Filter Type<br>  Used for <?php echo $UnitName ?>
<br>
    <select name="filter_type" id="fselect" onchange="enableSubmit()" style="background-color: lightblue;height:90px;width:400px;font-size:50px;">
    <option>Select filter type</option>
        <?php
        $sql = "select * from filter_types;";
        if (!$result = $con->query($sql)) {
            die ('There was an error running query[' . $con->error . ']');
        }
            while ($row = $result->fetch_assoc()) 
            { 
                echo "<option value=".$row["type"].">".$row["type"]."</option>";
            }
        ?>
        <option value="<?php echo $FilterType ?>">No filters changed</option>
    </select>

    <input type="hidden" name="username" value="<?php echo $UserName ?>">
    <input type="hidden" name="filter_rotation" value="<?php echo $FilterRotation ?>">
     <input type="hidden" name="unit_id" value="<?php echo $unitId ?>">
     <input type="hidden" name="filters_used" value="<?php echo $FilterSize ?>">
     <?php
     if(strcmp($Page,"ListEquipment.php")==0){
     $Action="unitdone";
     }
     else {
	$Action="completetask";
}
?>
     <input type="hidden" name="action" value="<?php echo $Action ?>">
      <input type="hidden" name="task_id" value="<?php echo $TaskID ?>">
       <input type="hidden" name="filter_rotation" value="<?php echo $FilterRotation ?>">
       <input type="hidden" name="filters_due" value="<?php echo $FiltersDue ?>">
       <input type="hidden" name="filters_used" value="<?php echo $FilterSize ?>">
<br>
    <input type="submit" disabled value="Submit"id="submitfilters" style="height:100px;width:400px;font-size:40px;font-weight:bold;">
    </form>
</body>
</html>