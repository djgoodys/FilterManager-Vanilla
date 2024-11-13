<?php
if(session_id() == "")
{
session_start();
}
else
{
// Anything you want
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"
    name="viewport" content="width=device-width, initial-scale=1">
    <title>Filter manager. edit rotTIONS</title>
    <style>
        * {
            box-sizing: border-box;
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

        #myTable tr.header {
            /* Add a grey background color to the table header and on hover */
            background-color: #f1f1f1;
            position: fixed;
            top: 0px; display:none;
        }
    </style>
</head>
<body>
hello
<?php

header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
//echo var_dump($_GET);
if(ISSET($_GET["_id"])) {
    //echo "from GET id=" . $_GET["_id"] ;
}
if(ISSET($_POST["unit_name"])) {
    //echo "from post=" . $_POST["_id"] . " name: " . $_POST["unit_name"];
}
include 'db/db_connect.php';

$UnitName = "unit_name";
$Rotation = "rotation";
$id = "id";
echo "im here";
$sql = "SELECT _id,unit_name,filter_rotation FROM equipment;";
$result = $con->query($sql);
//echo "rows=".$result->num_rows;
if ($result->num_rows > 0) 
   {
      echo "<table style='myTable' id='myTable' border='1'>";
      while($row = $result->fetch_assoc()) 
         {
?>
            <tr><td><form action="/webEditRotations.php" method="post">
            <?php

            echo "Unit name: <div style='background-color: black; color:white;'>". $row["unit_name"]."</div>";
            echo "<input type='hidden' id='". $row["_id"]. "' name='_id' value='". $row["_id"]. "'>";
            echo "Filter Rotation<input type='text' style='background-color: black; color:white;' id='" . $row["filter_rotation"] . "' name='filter_rotation' value='". $row["filter_rotation"]. "'>";
            //echo "Filter last changed<input type='text' id='" . $row["filters_last_changed"] . "' name='filters_last_changed' value='". $row["filters_last_changed"]. "'></td></tr>";
            echo "<input type='hidden' id='updatenow' name='updatenow' value='true'>";
            echo "<input type='submit' value='Update' style='height:50px; width:180px'></form></td></tr>";
            echo "</td></tr>";
        }
        $con->close();
   }
        
if(ISSET($_POST["updatenow"])=="true")
   {

      // include 'db/db_connect.php';
       $response = array();
        // echo var_dump($_POST);
        //eecho var_dump($_GET);
      if (isset($_GET['_id'])) 
         {
            $RecID = $_GET['_id'];
            $UnitName = $_GET['unit_name'];
            $Rotation = $_GET['rotation'];
         }
     //echo "Unit name=" . $_POST['unit_name'];//&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){

//if(isset($_POST['_id'])&&isset($_POST['unit_name'])&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){
     if (isset($_POST['_id'])) 
         {
             $RecID = $_POST['_id'];
             $UnitName = $_POST['unit_name'];
             $Rotation = $_POST['filter_rotation'];
         }

     //Query to update a movie
     //$query = "UPDATE equipment SET unit_name=?,area_served=?,filter_size=?,filters_due=?,location=?,filter_rotation=?,belts=?,notes=? WHERE _id='".$UnitName."'";
     $query = "UPDATE equipment SET filter_rotation='" . $Rotation . "' WHERE _id=" . $RecID;
     if (mysqli_query($con, $query)) 
         {
            header("Location: http://djgoodys-001-site1.htempurl.com//webEditRotations?_id=". $RecID . "&unit_name=" . $UnitName);
             exit();
            
         } 
         else 
         {
             echo "Error updating record: " . mysqli_error($con);
         }


 }

        ?>

</body>
</html>
<?php
?>