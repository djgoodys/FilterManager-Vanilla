<form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
<input type="text" name="table_name"><input type="submit" name="create_table">
</form>
<?php
if(isset($_POST["table_name"])){$TableName = $_POST["table_name"];}
if(isset($_POST["create_table"]))
{
// Connect to the database
$servername = "localhost";
$username = "4094059_mirage";
$password = "relays";
$dbname = "4094059_mirage";

//$con = new mysqli($servername, $username, $password, $dbname);
include "../dbMirage_connect.php";
// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT * FROM ".$TableName;
$result = $con->query($sql);

// Create an array to store the data
$data = array();

// Fetch the data and add it to the array
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

// Encode the data as JSON
$json_data = json_encode($data);


// Write the JSON data to a file
$file = fopen("table_".$TableName.".json", "w");
fwrite($file, $json_data);
fclose($file);

// Close the database connection
$con->close();
}
?>
