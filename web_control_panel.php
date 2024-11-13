<?php
if(session_id() == "")
{
session_start();
}
else
{
// Anything you want
}
include 'dbMirage_connect.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"
     name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Filters Control Panel</title>

<style>
* {
  box-sizing: border-box;
}
#mytextarea
input, textarea {
    font-size:2em;
    font-family:"Helvetica Neue", Helvetica, sans-serif; 
}
#myInput {
  background-image: url('/css/searchicon.png'); /* Add a search icon to input */
  background-position: 10px 12px; /* Position the search icon */
  background-repeat: no-repeat; /* Do not repeat the icon image */
  width: 100%; /* Full-width */
  font-size: 16px; /* Increase font-size */
  padding: 12px 20px 12px 40px; /* Add some padding */
  border: 1px solid #ddd; /* Add a grey border */
  margin-bottom: 12px; /* Add some space below the input */
}

#myTable {
  border-collapse: collapse; /* Collapse borders */
  width: 100%; /* Full-width */
  border: 1px solid #ddd; /* Add a grey border */
  font-size: 18px; /* Increase font-size */
}

#myTable th, #myTable td {
  text-align: left; /* Left-align text */
  padding: 12px; /* Add padding */
}

#myTable tr {
  /* Add a bottom border to all table rows */
  border-bottom: 1px solid #ddd; 
}

#myTable tr.header, #myTable tr:hover {
  /* Add a grey background color to the table header and on hover */
  background-color: #f1f1f1;
}
</style>
</head>
<body>


<?php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

  //or, if you DO want a file to cache, use:
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
//echo var_dump($_GET);

if(ISSET($_GET["_id"])) 
   {
       $UnitToFind = $_GET["_id"];
       $UnitId = $_GET["_id"];
   }
//echo "unit to find=" .$UnitToFind;
if(ISSET($_GET["unit_name"])) 
   {
      $UnitName = $_GET["unit_name"];
   }

if(ISSET($_GET["action"]))
   {
      $Action=$_GET["action"];
   }

$AssignedTo="";
$Action="";
$AreaServed = "area_served";
$Location = "location";
$FilterSize = "filter_size";
$FiltersDue = "filters_due";
$FiltersLastChanged = "filters_last_changed";
$Rotation = "rotation";
$Belts = "belts";
$notes = "notes";
$query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation, filters_last_changed,filter_type, assigned_to FROM equipment WHERE _id='" . $UnitToFind . "';";
if(isset($_GET["action"])){$Action=$_GET["action"];}
//echo "action=".$Action;
echo "<font size='5'><Table width='100%' border='1'>";
?>
<tr>
    <th width="10%"><?php echo $_GET["unit_name"]; ?> info: <br><a href="webEditUnit.php?_id=<?php echo $UnitToFind ?>&unit_name=<?php $UnitName ?>">EDIT UNIT</a>
                <br>
                <?php
                if (!$result = $con->query($query)) {
                die ('There was an error running query[' . $con->error . ']');
                }else{
                while ($row = $result->fetch_assoc()) {
                   //echo "num rows=".mysqli_num_rows($result);
                if(!$row["assigned_to"] ==""){
                   ?>
                <a href="web_control_panel.php?action=clearassignedto&_id=<?php echo $row["_id"] ?>&unit_name=<?php echo $UnitName ?>">Clear Assigbed to</a></th>
                <?php
                }
                ?>
    <td width="90%">    
    <?php    
     
        
         // while ($row = $result->fetch_assoc()) {
            echo "Unit name: " . $row["unit_name"] . "<BR>";
                     echo "Assigned to: " . $row["assigned_to"] . "<BR>";
           // echo "id: " . $row["_id"] . "<br>";
           // echo "Unit name: " . $UnitName . "<br>";
            echo "Location: " . $row["location"] . "<br>";
            echo "Area served: " . $row["area_served"] . "<br>";
            echo "Filter size: " . $row["filter_size"] . "<br>";
            echo "Filter Type : " . $row["filter_type"] . "<br>";
            echo "Filters due date: " . $row["filters_due"] . "<br>";
            echo "Filters last changed: " . $row["filters_last_changed"] . "<br>";
            echo "Filter rotation: " . $row["filter_rotation"] . "<br>";
            echo "Belt size: " . $row["belts"] . "<br>";
            echo "</td></tr><tr><td align='center' style='font-weight: bold'; font-size:4px;>Notes-></td><td><textarea rows='5' class='mytextarea'>" . $row["notes"] . "</textarea></td></tr><tr><td><form action='http://djgoodys-001-site1.htempurl.com/listequipment.php' method='post'><input type='submit' value='Back to unit list' id='btnGoToUnitlist' name='btnGoUnitList'  style='height:50px; width:120px'><input type='hidden' name='action' value='listUnits'></form></td><td><form action='webTask_history.php' method='get'><input type='hidden' id='cookie' name='cookie' value='reset'  style='height:50px; width:120px'>
            <input type='submit' value='Task history...'  style='height:50px; width:240px'></form></td></tr>";
        }
        $con->close(); 

        echo "</table></font>";
}
        
        if(isset($_GET["unit_id"])){$UnitId=$_GET["unit_id"];}
        
   if(strcmp($Action, "clearassignedto")==0)
      {
         if ($con->connect_error) 
            {
               die("Connection failed: " . $con->connect_error);
               echo "connect error=".$con->connect_error;
            }
         $sql = "UPDATE equipment SET assigned_to = ' ' WHERE _id=".$UnitId.";";
         
         if ($con->query($sql) === TRUE) {
         echo "Records were updated successfully.";
      } 
      else 
      {
         echo "ERROR: Was not able to execute $sql. " . mysqli_error($con);
      }
   
   // Close connection
   mysqli_close($con);

   }
 
?>

</body>
</html>