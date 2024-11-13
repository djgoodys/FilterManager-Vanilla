<html>
<head>
<style>
tr {
  background-color: transparent; 
  text-align: left;       
  font-weight: bold;      
}
td {
    padding:8px;
    border-bottom: 2px solid whitesmoke;
}
</style>
</head>
<?php
if(isset($_GET["id"])){$UnitID = $_GET["id"];}
if(isset($_GET["folder"])){$backup_folder = $_GET["folder"];}
$jsonString = file_get_contents('sites/'.$backup_folder.'/data.json');
$data = json_decode($jsonString, true);
$Found = false;
$imagePath = "images/fm2.jpg";
foreach ($data["equipment"] as $object) {
        if($object["_id"] == $UnitID)
        {
        $Found = true;
        
        echo "<title>Info on ".$object["unit_name"]."</title>
        <style>body {background-image: url('images/fm2.jpg');background-repeat: no-repeat; background-size: 100% 90%;background-position:center center;background-color:black;}></style>
        <Table style='font-size:2em;font-weight:bold;margin-left:auto;margin-right:auto;margin-top:500px;background-color:rgba(65, 176, 95, 0.7);color: white;width:80%;height:600px;'>
        <tr><td>Unit Name</td><td>".$object["unit_name"]."</td></tr>
        <tr><td>Location</td><td>".$object["location"]."</td></tr>
        <tr><td>Area Served</td><td>".$object["area_served"]."</td>
        <tr><td>Location</td><td>".$object["Location"]."</td></tr>
        <tr><td>Filter Size</td><td>".$object["filter_size"]."</td></tr>
         <tr><td>Filters Due</td><td>".$object["filters_due"]."</td></tr>
         <tr><td>Belts</td><td>".$object["belts"]."<tr><td>Notes</td><td style='max-width:140px;overflow:auto;'>".$object["notes"]."</td></tr><tr><td>Filter Rotation</td><td>".$object["filter_rotation"]."</td></tr><tr><td>Filter Type</td><td>".$object["filter_type"]."</td></tr>
         <tr><td>Filters Last<br> Changed</td><td>".$object["filters_last_changed"]."</td></tr>
         <tr><td>Assigned to</td><td>".$object["assigned_to"]."</td></tr></table>";
        break;
        }
        else
        {
            $Found = false;
        }
}
        if($Found == false)
        {
        echo $UnitID. " was not found.";
        }
    echo "</body></html>";
