<?php
$jsonData = file_get_contents("data.json");
$data = json_decode($jsonData, true);
$equipment = $data["equipment"];

// Find the object with the matching "_id"
$targetObject = null;
foreach ($equipment as $key => $obj) {
    if ($obj["_id"] == $_GET["_id"]) {
        $targetObject = $obj; // Found the object to edit
        //unset($equipment[$key]); // Remove it from the original position
        break;
    }
}

// If the object was found, update values using $_GET variables
if ($targetObject) {
    foreach ($targetObject as $key => $value) {
       echo $key." ".$value."<br>";
       unit_name AH-2
       if($key == "unit_name"){$UnitName = $value;}
        if($key == "location"){$Location = $value;}
        if($key == "area_served"){$Area = $value;}
        if($key == "filter_size"){$FilterSize = $value;}
        if($key == "filters_due"){$Location = $value;}
        if($key == "location"){$Location = $value;}
        if($key == "location"){$Location = $value;}
        if($key == "area_served"){$AreaServed = $value3;}
        if($key == "filter_size"){$FilterSize = $value3;}
		if($key == "filters_due"){$FiltersDue = $value3;}
		if($key == "belts"){$Belts = $value3;}
		if($key == "notes"){$Notes = $value3;}
		if($key == "filter_rotation"){$FilterRotation = $value3;}
		if($key == "filter_type"){$FilterType = $value3;}
		if($key == "filters_last_changed"){$FiltersLastChanged = $value3;}
		if($key == "assigned_to"){$AssignedTo = $value3;}
		if($key == "image"){$Image = $value3;}
    }
}
if(isset($_GET["update"])){
$jsonData = file_get_contents("data.json");
$data = json_decode($jsonData, true);
$equipment = $data["equipment"];

// Find the object with the matching "_id"
$targetObject = null;
foreach ($equipment as $key => $obj) {
    if ($obj["_id"] == $_GET["_id"]) {
        $targetObject = $obj; // Found the object to edit
        unset($equipment[$key]); // Remove it from the original position
        break;
    }
}

// If the object was found, update values using $_GET variables
if ($targetObject) {
    foreach ($_GET as $key => $value) {
        $targetObject[$key] = $value; // Update properties
    }

    // Add the updated object back to the equipment array
    $equipment[] = $targetObject;

    // Save the modified data back to the JSON file
    $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents("data.json", $updatedJson);

    echo "Object updated successfully!";
} else {
    echo "Object with _id " . $_GET["_id"] . " not found.";
}
}
?>

