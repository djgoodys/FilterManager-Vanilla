<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
     session_start();
   }
      if(!isset($_SESSION["backup_folder"]))
   {
      header('Location: '."start.php");
   }

   ?>
<html>
<title>FilterManager.net Importing filter sizes.</title>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>

<?php
$Action="";
if(isset($_POST["action"])){$Action = $_POST["action"];}
//print_r($_POST);
if($Action == "")
{
    // Decode the JSON text
    $jsonString = file_get_contents('data_example.json');
    $filtersData = json_decode($jsonString, true);

    // Extract the filter sizes into an array
    $filterSizes = array_column($filtersData['filters'], 'filter_size');

    // Create an HTML form with a select element for filter sizes
    ?>
    <div style="background-color: black;color:white;width:100%;text-align: center;">IMPORTING FILTERS FROM EXAMPLES</div>
    <div style="background-color: green;color:white;width:100%;text-align: center;">Select the filter sizes to add to your inventory and click button.</div>
        <form action="<?php $_SERVER["SCRIPT_NAME"] ?>" method="post">
<table style="margin-top:10px;width:100%;border: 1px solid saddlebrown;"><tr>
    <input type="hidden" name="action" value="process_new_filters">

            <?php $x = 0; foreach ($filterSizes as $filterSize) :$x=$x+1; ?>
                <td><input type="checkbox" name="filter_size[]" value="<?php echo htmlspecialchars($filterSize); ?>"><?php echo htmlspecialchars($filterSize); 
                if($x == 10){echo "</td></tr><tr>";$x=0;}else{echo "</td>";} ?>
            <?php endforeach; ?>
    </table>
            <button type="submit" style="width:140px;margin-left:500px;margin-right: auto;" class="btn btn-success">Import filter sizes</button>
        </form>
<?php 
}

if(strcmp($Action,"process_new_filters") == 0){
$filterSizes = $_POST['filter_size'];
echo "Starting import<br>";
// Load the existing JSON data
$jsonData = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$filtersData = json_decode($jsonData, true);
// Generate a new ID for the filter
  // Assuming sequential IDs starting from 70

// Create the new filter data with empty values
$numFilterSizes = count($filterSizes);

for ($i = 0; $i < $numFilterSizes; $i++) {
    $newFilterSize = $filterSizes[$i];
    echo "Added ".$newFilterSize."<br>";
    $newFilterId = count($filtersData['filters']) + 1;

$newFilterData = [
    "_id" => $newFilterId,
    "filter_size" => $newFilterSize,
    "filter_type" => "Paper",
    "filter_count" => "0",
    "par" => "0",
    "notes" => "",
    "date_updated" => date('Y-m-d'),
    "pn" => null,
    "storage" => null
];

// Add the new filter to the "filters" array
$filtersData['filters'][] = $newFilterData;

// Encode the updated data back into JSON
$updatedJson = json_encode($filtersData, JSON_PRETTY_PRINT);

// Save the updated JSON data back to the file
file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $updatedJson);
}
echo "Import Complete. <a href='web_update_filters.php'>CLICK HERE</a> to go filter inventory control";
}
?>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>