
<?php
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
$index = -1;
foreach ($data as $key => $object) {
    if ($object['user_name'] === $UserToDelete) {
        $index = $key;
        break;
    }
}
if ($index > -1) {
    unset($data[$index]);
    $data = array_values($data);
}

$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('path/to/table_users.json', $jsonString);

