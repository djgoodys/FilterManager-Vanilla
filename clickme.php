<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
      session_start();
    }


    $fileName = "table_users.json";
  $data = json_decode(file_get_contents($fileName), true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    return false;
  }

  foreach ($data as &$user) { 
    if ($user['user_name'] == $_SESSION["user_name"]) {
                $user['clickme'] = "false";
            }
          }

  $jsonData = json_encode($data, JSON_PRETTY_PRINT); 
  file_put_contents($fileName, $jsonData);


?>
