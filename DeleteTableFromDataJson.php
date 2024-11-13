<?php
// Read the JSON data from the file
$jsonData = file_get_contents("data.json");
$data = json_decode($jsonData, true);

// Check if the "tasks" object exists
if (isset($data["tasks"])) {
    // Unset the "tasks" object
    unset($data["tasks"]);

    // Save the modified data back to the JSON file
    $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("data.json", $updatedJson);

    echo "Object named 'tasks' removed successfully!";
} else {
    echo "Object named 'tasks' not found in the JSON file.";
}
?>