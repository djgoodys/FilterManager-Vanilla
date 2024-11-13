
<?php
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
$Users = array();
foreach ($data as $object) {
    $Users[] = $object['user_name'];
}
//print_r($Users);
?>
