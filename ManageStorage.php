<?php
include 'phpfunctions.php';
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Manage filter storage locations</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script>
    function editme(filterTypeID){
        mydisplay=document.getElementById("tr"+filterTypeID).style.display;
        filterType = document.getElementById("tr"+filterTypeID);
        filterType_to_edit = document.getElementById("tredit"+filterTypeID);
        filterType.style.display="none";
        filterType_to_edit.style.display=mydisplay;
    }
</script>
    <script>
function ConfirmDelete($LocationName, $frmID){
if (confirm('Are you sure you want to delete the filter storage location: '+$LocationName+'?')) {
	myForm=document.getElementById($frmID);
	myForm.submit();
  //document.getElementById('myform').submit(); 
} else {
  // Do nothing!
alert('Delete of '+$LocationName+' was canceled');
}
}
</script>
<style>
input[name="location"] {
  width:8em;
height: auto;
border-radius:5px;
}

</style>
</head>
<body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <div class="container">
    <header class="d-flex justify-content-center py-3">
      <ul class="nav nav-pills">
        <li class="nav-item"><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?Action=addLocation" class="nav-link"><button type="button" class="btn btn-success">Add storage location</button></a></li>
        <li class="nav-item"><a href="web_update_filters.php" class="nav-link"><button type="button" class="btn btn-success">Back to filters</button></a></li>
      </ul>
    </header>
  </div>
<?php
//print_r($_POST);
//print_r($_GET);
$RowID ="";
$Action = "";
$Trackable = "";
$Location = "";
if(isset($_POST["location"])){$Location = $_POST["location"];}
if(isset($_GET["location"])){$Location = $_GET["location"];}
if(isset($_GET["Action"])){$Action = $_GET["Action"];}
if(isset($_POST["Action"])){$Action = $_POST["Action"];}
if(isset($_POST["id"])){$RowID = $_POST["id"];}
if(isset($_GET["id"])){$RowID = $_GET["id"];}
?>
<div class="row">
    <div class="p-3 mb-2 bg-info text-white" style="width:50%;text-align: center;margin-left: auto; margin-right: auto;"><h3>MANAGING FILTER STORAGE LOCATIONS</h3></div>
</div>
  <p>
    <?php
        switch ($Action) {
            case "addLocation":
              echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Adding new filter storage location</h5></div>";
              break;
            case "update":
              echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Updating Record</h5></div>";
              break;
            case "edit":
                echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Editing filter storage location</h5></div>";
                break;
            case "delete":
              echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Deleting filter storage location</h5></div>";
              break;
              
            default:
              
          }
    ?>
  </p> 
</div>

<?php
if(strcmp($Action, "update")==0)
{
    $sql="UPDATE storage SET location ='". $Location."' WHERE _id = ".$RowID.";";
    //UPDATE DATA FILE
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data['storage'] as &$object) 
    {
      if ($object['_id'] == $RowID) 
        {
          $object["location"] = $Location; 
        }
    }
    $jsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
}


if($Action == "addLocation"){
    ?>
<table border="3;3" class="table-success" style="width:50%;margin-left: auto; margin-right: auto;">
    <th>Fill in fields then click the add button</th>
<form method="POST" name="frmAddLocation" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
    <input type="hidden" name="Action" value="add_location">
<tr class="p-3 mb-2 bg-success text-white"></tr>
<tr><td style='text-align:center;'><input type="text" name="location" placeholder="Enter filter storage location">

                <td><input class="btn btn-success" type="submit" value="add"></form>
                </table>
<?php
}

if($Action == "delete"){
    $sql = "DELETE FROM storage WHERE _id=".$RowID.";";
    $jsonString = file_get_contents("sites/".$_SESSION["backup_folder"]."/data.json");
    $data = json_decode($jsonString, true);
    $LocationsArray = &$data["storage"]; 
    echo "id=".$RowID;
  $indexToDelete = array_search($RowID, array_column($LocationsArray, "_id"));
  
  if ($indexToDelete !== false) {
      unset($LocationsArray[$indexToDelete]);
      $LocationsArray = array_values($LocationsArray);
      $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
      if (file_put_contents("sites/".$_SESSION["backup_folder"]."/data.json", $updatedJson)) {
          echo "<div style='margin-right:auto;margin-left:auto;width:600px;text-align:center;background-color:black;color:aqua;'>Storage location deleted successfully!</div>";
      } else {
          echo "<div style='text-align:center;background-color:black;color:red;'>Failed to save the updated JSON file.</div>";
      }
  } else {
      echo "<div style='text-align:center;background-color:black;color:red;'>Filter storage location with _id '{$RowID}' not found.</div>";
  }
}

if($Action == "add_location"){
$Exists = searchInJSONFile("storage", "location", $Location);
  if($Exists === false){
  $jsonString = file_get_contents("sites/".$_SESSION["backup_folder"]."/data.json");
  $data = json_decode($jsonString, true);
  $StorageArray = &$data["storage"]; // Reference for direct modification
  $newId = getUniqueID("storage");
$newItem = [
    "_id" => $newId,
    "location" => $Location
];
$StorageArray[] = $newItem;
$updatedJson = json_encode($data, JSON_PRETTY_PRINT);
if (file_put_contents("sites/".$_SESSION["backup_folder"]."/data.json", $updatedJson)) {
    echo "<div style='text-align:center;background-color:black;color:aqua;'>New filter storage location added successfully!</div>";
} else {
    echo "<div style='text-align:center;background-color:black;color:red;'>Failed to save the updated JSON file.</div>";
}
}
else
{
echo "<div style='text-align:center;background-color:black;color:red;'>".$Location. " already exists</div>";
}
}

?>
<table border="3;3" class="table-success" style="width:50%;margin-left: auto; margin-right: auto;">
    <th class="p-2 mb-2 bg-info" style="color:GOLD;text-align:right;">EXISTING FILTER STORAGE LOCATIONS:</th><th class="p-2 mb-2 bg-info text-white"></th>
<tr class="p-3 mb-2 bg-success text-white"><td class="font-weight-bold" style="text-align:center;">Storage location</td><td style="text-align:center;" class="font-weight-bold">Edit/Save</td></tr>
             <?php
            $jsonString = file_get_contents("sites/".$_SESSION["backup_folder"]."/data.json");
            $data = json_decode($jsonString, true);
            $LocationsArray = $data["storage"];
            if(count($LocationsArray)==0){echo "<div style='width:50%;margin-left:auto;margin-right:auto;background-color:red;color:white;'>There are no filter types in your database. Examples: Paper, Plastic, Extened Use, Washable, etc...You can create any name you wish. Select if you want type tracked for inventory or not. Then click add button.</div>" ;}
            //$row = $LocationsArray;
             foreach($LocationsArray as $row)
                {
                            ?>
                            <tr id="tr<?php echo $row["_id"] ?>" style='text-align:center;'>
                            <td class='font-weight-bold'><?php echo $row['location'] ?></td>
                            <td class='font-weight-bold'><img src="images/edit.png" style="width:2em;height:auto;" title="Edit this location" onclick="editme(<?php echo $row["_id"] ?>);"></td>
                            </tr>
                            <tr id="tredit<?php echo $row["_id"] ?>" style='display:none;width:100%' >
                            <td class='font-weight-bold;' style="width:33%;text-align:center;"><form id='frmEdit<?php echo $row["_id"] ?>' action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='post'><input type='text' style='text-align:center;' value="<?php echo $row['location'] ?>" name='location'></td>
                            
                              <input type='hidden' name='id' value="<?php echo $row["_id"] ?>"><input type='hidden' name='Action' value='update'>
                            <td class='font-weight-bold' style="width:33%;text-align:center;"><input type="image" alt="Submit" src="images/save.png" title="Save edit" style="width:2em;height:auto;"></form>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <form id='frmDelete<?php echo $row["_id"] ?>' action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='post'><input type='hidden' name='Action' value='delete'><input type='hidden' name='id' value='<?php echo $row["_id"] ?>'><img src="images/delete.png" style="width:2em;height:auto;" title="Delete this filter storage location" onclick="ConfirmDelete('<?php echo $row['location'] ?>','frmDelete<?php echo $row["_id"] ?>');"></form></td></tr>                              
                            <?php
                        }
                
            ?>
</table>
<?php
if(strcmp($Action, "edit")==0)//NOT IN USE LOOK ABOVE
{
    ?>
<table border="3;3" class="table-success" style="width:50%;">
    <th>Make neccessary edits.</th><th>Then click the update button.</th>
<form method="POST" name="frmFilterLocationsEdit" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
    <input type="hidden" name="Action" value="update">
<tr class="p-3 mb-2 bg-success text-white" style="text-align:center;"><td class="font-weight-bold" style="text-align:center;">filter storage location</td></tr>
             <?php
                $sql = "SELECT * FROM storage WHERE _id=".$RowID.";";
            if ($result = $con->query($sql)) 
                {
                    while ($row = $result->fetch_assoc()) 
                        {
                            echo "<tr style='text-align:center;'><td class='font-weight-bold'><input type='text' value='".$row['location'] ."' name='type'></td>
                            <td class='font-weight-bold'>
                            <div class='form-check'>
                            <input class='form-check-input' type='radio' name='trackable' id='flexRadioDefault1' value='yes' ";
                            if($row['trackable'] == "yes"){echo " checked";}
                            echo "><label class='form-check-label' for='flexRadioDefault1'>yes</label>
                            <div class='form-check'>
                                <input class='form-check-input' type='radio' name='trackable' value='no' id='flexRadioDefault2' ";
                                if($row['trackable'] == "no"){echo " checked";}
                                echo "><label class='form-check-label' for='flexRadioDefault2'>no</label></td></div>
                                <input type='hidden' name='id' value='".$row["_id"]."'>"; 
                                                          
                        }
                }
                ?>
                <td><input class="btn btn-success" type="submit" value="Submit"></form>
                </table>
                <?php
}

?>
</body>
</html>