
<?php
// Read the JSON file into a string
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);

foreach ($data as $object) {
    foreach ($object as $key => $value) {
        echo $key."=". $value."<br>";
    }
}
?>

