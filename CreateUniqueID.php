
<?php

$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
$maxId = 0;
foreach ($data as $object) {
    if ($object['_id'] > $maxId) {
        $maxId = $object['_id'];
    }
}

$newUser = array(
    '_id' => $maxId + 1,
    'name' => 'John Doe',
    'email' => 'johndoe@example.com',
    'age' => 30
);

$data[] = $newUser;
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('path/to/table_users.json', $jsonString);
?>