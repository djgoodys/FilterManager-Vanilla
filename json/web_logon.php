<?php
$Action ="";
if($Action == "logon"){
$file = "table_users.json";
$data = file_get_contents($file);
$users = json_decode($data, true);
$found = false;
foreach ($users as $user) {
  if ($user["user_name"] == $user_name && $user["password"] == $password) {
    // Match found, print the user details
    echo "User name: " . $user["user_name"] . "\n";
    echo "Password: " . $user["password"] . "\n";
    echo "Email: " . $user["Email"] . "\n";
    $found = true;
    break;
  }
}

// If no match was found, print a message
if (!$found) {
  echo "No user with that user_name and password was found.\n";
}
}
?>
