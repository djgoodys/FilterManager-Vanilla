
<?php
$jsonString = file_get_contents('data.json');
$data = json_decode($jsonString, true);
$Users = array();
foreach ($data["users"] as $object) {
    $Users[] = $object['user_name'];
}
print_r($Users);
?>
