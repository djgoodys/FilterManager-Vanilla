
<?php
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
foreach ($data as &$object) {
    if ($object['user_name'] === "dj") {
        // Change the value of the specified key
        $object['password'] = 'new value';
    }
}
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('table_users.json', $jsonString);
?>