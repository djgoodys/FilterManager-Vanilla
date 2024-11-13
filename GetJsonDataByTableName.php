<?php
include "../dbMirage_connect.php";

$jsonData = file_get_contents("data.json");
$data = json_decode($jsonData, true);
$topLevelKeys = array_keys($data);
echo "Top-level array keys:<br>";
foreach ($topLevelKeys as $key) {
   echo "- $key\n";
}
echo "<br>";

//GET DATA BY TABLE NAME

$result = $con->query("SHOW TABLES");

// Create HTML select element
echo "<form action='".$_SERVER["SCRIPT_NAME"]."' method='post'>";
echo "<select name='table_name'>";
echo "<option value=''>Select a table</option>";
while ($row = $result->fetch_array()) {
    $tableName = $row[0];
    echo "<option value='$tableName'>$tableName</option>";
}
echo "</select><input type='submit'></form>";
if(isset($_POST["table_name"])){
$TableName=$_POST["table_name"];

// Read JSON data from the file
$jsonData = file_get_contents("data.json");
$data = json_decode($jsonData, true);

// Specify the top-level array group you want to retrieve
$targetArrayName = $TableName; // Replace with the actual name

// Check if the target array exists
if (isset($data[$targetArrayName])) {
   $targetData = $data[$targetArrayName];

   // Process the retrieved data
   echo "Data for $targetArrayName:\n";
   foreach ($targetData as $item) {
       // Access and display key-value pairs
       foreach ($item as $key => $value) {
           echo "- $key: $value\n";
       }
       echo "\n";
   }
} else {
   echo "Array $targetArrayName not found in the JSON file.\n";
}
}
?>
