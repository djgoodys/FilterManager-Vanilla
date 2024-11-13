<?php
  
//include 'dbMirage_connect.php';
include 'phpfunctions.php';
//print_r($_POST);
//echo "site=".$_SESSION["backup_folder"];
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Manage filter types</title>
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
function ConfirmDelete($TypeName, $frmID){
if (confirm('Are you sure you want to delete the filter type: '+$TypeName+'?')) {
	myForm=document.getElementById($frmID);
	myForm.submit();
  //document.getElementById('myform').submit(); 
} else {
  // Do nothing!
alert('Delete of '+$TypeName+' was canceled');
}
}
</script>
<style>
input[name="type"] {
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
        <li class="nav-item"><a href="<?php echo $_SERVER['SCRIPT_NAME'] ?>?Action=addfiltertype" class="nav-link"><button type="button" class="btn btn-success">Add new filter type</button></a></li>
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
if(isset($_POST["trackable"])){$Trackable = $_POST["trackable"];}
if(isset($_POST["type"])){$Type = $_POST["type"];}
if(isset($_GET["Action"])){$Action = $_GET["Action"];}
if(isset($_POST["Action"])){$Action = $_POST["Action"];}
if(isset($_POST["id"])){$RowID = $_POST["id"];}
if(isset($_GET["id"])){$RowID = $_GET["id"];}
?>
<div class="row">
    <div class="p-3 mb-2 bg-info text-white" style="width:50%;text-align: center;margin-left: auto; margin-right: auto;"><h3>MANAGING FILTER TYPES</h3></div>
</div>
  <p>
    <?php
        switch ($Action) {
            case "addfiltertype":
              echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Adding new filter type</h5></div>";
              break;
            case "update":
              echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Updating Record</h5></div>";
              break;
            case "edit":
                echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Editing filter type</h5></div>";
                break;
            case "delete":
              echo "<div class='col bg-dark text-white w-50' style='text-align: center;margin-left: auto; margin-right: auto;'><h5 style='text-align: center;margin-left: auto; margin-right: auto;'>Deleting filter type</h5></div>";
              break;
              
            default:
              
          }
    ?>
  </p> 
</div>

<?php
if(strcmp($Action, "update")==0)
{
    $jsonString = file_get_contents("sites/".$_SESSION["backup_folder"]."/data.json");
    $data = json_decode($jsonString, true);
    foreach ($data['filter_types'] as &$object) 
    {
      if ($object['_id'] == $RowID) 
        {
          $object["type"] = $Type;
          $object["trackable"] = $Trackable;
        }
    }
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);  
}
if($Action == "addfiltertype"){
    ?>
<table border="3;3" class="table-success" style="width:50%;margin-left: auto; margin-right: auto;">
    <th>Fill in fields then<br> click the add button</th>
<form method="POST" name="frmFilterTypesaddnew" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
    <input type="hidden" name="Action" value="AddFilterType">
<tr class="p-3 mb-2 bg-success text-white"><td class="font-weight-bold">New Filter Type</td><td class="font-weight-bold">Track it with<br> Inventory Control</td><td></td></tr>
<tr><td style='text-align:center;'><input type="text" name="type" placeholder="Enter name">
    <td class='font-weight-bold'>
        <div class='form-check'>
         <input class='form-check-input' type='radio' name='trackable' id='flexRadioDefault1' value='yes' checked>
          <label class='form-check-label' for='flexRadioDefault1'>yes</label>
         <div class='form-check'>
          <input class='form-check-input' type='radio' name='trackable' value='no' id='flexRadioDefault2'>
            <label class='form-check-label' for='flexRadioDefault2'>no</label></td></div>
</td>
                <td><input class="btn btn-success" type="submit" value="add"></form>
                </table>
<?php
}

if($Action == "delete"){
  $sql = "DELETE FROM filter_types WHERE _id=".$RowID.";";
  $jsonString = file_get_contents("sites/".$_SESSION["backup_folder"]."/data.json");
  $data = json_decode($jsonString, true);
  $filtersArray = &$data["filter_types"]; 
  $idToDelete = $RowID; 
$indexToDelete = array_search($idToDelete, array_column($filtersArray, "_id"));

if ($indexToDelete !== false) {
    unset($filtersArray[$indexToDelete]);
    $filtersArray = array_values($filtersArray);
    $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
    if (file_put_contents("sites/".$_SESSION["backup_folder"]."/data.json", $updatedJson)) {
        echo "<div style='text-align:center;background-color:black;color:aqua;'>Filter type deleted successfully!</div>";
    } else {
        echo "<div style='text-align:center;background-color:black;color:red;'>Failed to save the updated JSON file.</div>";
    }
} else {
    echo "<div style='text-align:center;background-color:black;color:red;'>Filter type with _id '{$idToDelete}' not found.</div>";
}
}

if($Action == "AddFilterType"){
  //SEE IF TYPE ALREADY exists
  $Exists = searchInJSONFile("filter_types", "type", $Type);
  if($Exists === false){
  $jsonString = file_get_contents("sites/".$_SESSION["backup_folder"]."/data.json");
  $data = json_decode($jsonString, true);
  $filterTypesArray = &$data["filter_types"]; // Reference for direct modification
  $newId = getUniqueID("filter_types");
$newItem = [
    "_id" => $newId,
    "type" => $Type, 
    "trackable" => $Trackable
];
$filterTypesArray[] = $newItem;
$updatedJson = json_encode($data, JSON_PRETTY_PRINT);
if (file_put_contents("sites/".$_SESSION["backup_folder"]."/data.json", $updatedJson)) {
    echo "<div style='text-align:center;background-color:black;color:aqua;'>New filter type added successfully!</div>";
} else {
    echo "<div style='text-align:center;background-color:black;color:red;'>Failed to save the updated JSON file.</div>";
}
}
else
{
echo "<div style='text-align:center;background-color:black;color:red;'>".$Type. " already exists</div>";
}
}

?>
<table border="3;3" class="table-success" style="width:50%;margin-left: auto; margin-right: auto;">
    <th class="p-2 mb-2 bg-info" style="color:GOLD;text-align:right;">EXISTING FILTER TYPES:</th><th class="p-2 mb-2 bg-info text-white"></th><th class="p-2 mb-2 bg-info text-aqua"></th><th class="p-2 mb-2 bg-info text-aqua"></th>
<tr class="p-3 mb-2 bg-success text-white"><td class="font-weight-bold" style="text-align:center;">Filter Type</td><td style="text-align:center;" class="font-weight-bold">Tracked by Inventroy Control</td><td style="text-align:center;" class="font-weight-bold">Edit/Save</td></tr>
             <?php
            $jsonString = file_get_contents("sites/".$_SESSION["backup_folder"]."/data.json");
            $data = json_decode($jsonString, true);
            $filterTypesArray = $data["filter_types"];
            if(count($filterTypesArray)==0){echo "<div style='width:50%;margin-left:auto;margin-right:auto;background-color:red;color:white;'>There are no filter types in your database. Examples: Paper, Plastic, Extened Use, Washable, etc...Y ou can create any name you wish. Select if you want type tracked for inventory or not. Then click add button.</div>" ;}
            //$row = $filterTypesArray;
             foreach($filterTypesArray as $row)
                        {
                            ?>
                            <tr id="tr<?php echo $row["_id"] ?>" style='text-align:center;'>
                            <td class='font-weight-bold'><?php echo $row['type'] ?></td>
                            <td class='font-weight-bold'><?php echo $row['trackable'] ?></td>
                            <td class='font-weight-bold'><img src="images/edit.png" style="width:2em;height:auto;" title="Edit this filter type" onclick="editme(<?php echo $row["_id"] ?>);"></td>
                            </tr>
                            <tr id="tredit<?php echo $row["_id"] ?>" style='display:none;width:100%' >
                            <td class='font-weight-bold;' style="width:33%;text-align:center;"><form id='frmEdit<?php echo $row["_id"] ?>' action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='post'><input type='text' style='text-align:center;' value="<?php echo $row['type'] ?>" name='type'></td>
                            <td class='font-weight-bold' style="width:33%;text-align:center;"><div class='form-check'><input class='form-check-input' type='radio' name='trackable' id='flexRadioDefault1' value='yes'
                            <?php if($row['trackable'] == "yes"){echo " checked";} ?>>
                            <label class='form-check-label' for='flexRadioDefault1'>yes</label>
                            <div class='form-check'>
                             <input class='form-check-input' type='radio' name='trackable' value='no' id='flexRadioDefault2' <?php if($row['trackable'] == "no"){echo " checked";} ?>><label class='form-check-label' for='flexRadioDefault2'>no</label></div></td>
                              <input type='hidden' name='id' value="<?php echo $row["_id"] ?>"><input type='hidden' name='Action' value='update'>
                            <td class='font-weight-bold' style="width:33%;text-align:center;"><input type="image" alt="Submit" src="images/save.png" title="Save edit" style="width:2em;height:auto;"></form>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <form id='frmDelete<?php echo $row["_id"] ?>' action='<?php echo $_SERVER['SCRIPT_NAME'] ?>' method='post'><input type='hidden' name='Action' value='delete'><input type='hidden' name='id' value='<?php echo $row["_id"] ?>'><img src="images/delete.png" style="width:2em;height:auto;" title="Delete this filter type" onclick="ConfirmDelete('<?php echo $row["type"] ?>','frmDelete<?php echo $row["_id"] ?>');"></form></td></tr>                              
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
<form method="POST" name="frmFilterTypesEdit" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>">
    <input type="hidden" name="Action" value="update">
<tr class="p-3 mb-2 bg-success text-white" style="text-align:center;"><td class="font-weight-bold" style="text-align:center;">Filter Type</td><td class="font-weight-bold">Tracked by Inventroy Control</td><td></td></tr>
             <?php
                $sql = "SELECT * FROM filter_types WHERE _id=".$RowID.";";
            if ($result = $con->query($sql)) 
                {
                    while ($row = $result->fetch_assoc()) 
                        {
                            echo "<tr style='text-align:center;'><td class='font-weight-bold'><input type='text' value='".$row['type'] ."' name='type'></td>
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