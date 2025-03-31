<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    ////echo "no session";
      session_start();
    }
if(isset($_GET["line_number"])){$LineNumber = $_GET["line_number"];}
////echo "line number=".$LineNumber;
function updateDidYouKnow($LineNumber) {
    $fileName = "table_users.json";
  $data = json_decode(file_get_contents($fileName), true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    return false;
  }

  foreach ($data as &$user) { 
    if ($user['user_name'] == $_SESSION["user_name"]) {
        foreach ($user['didYouKnow'] as $index => $value) {
            
            if ($index == intval($LineNumber)) {
                //echo  "LineNumber=".$LineNumber."<br>".$user['didYouKnow'] [$index]."<br>";
                $user['didYouKnow'] [$index] = "dont_show"; 
              break; 
            }
          }
          
    
      break; // Exit loop after finding the user
    }
  }

  // Write updated data to JSON file
  $jsonData = json_encode($data, JSON_PRETTY_PRINT); // Encode with formatting
  file_put_contents($fileName, $jsonData);

  return true; // Return true on successful update
}

// Example usage

$updateResult = updateDidYouKnow($LineNumber);

if ($updateResult) {
  //echo "DidYouKnow field updated successfully for user: " . $_SESSION["user_name"];
} else {
  //echo "Error updating DidYouKnow field.";
}

?>
