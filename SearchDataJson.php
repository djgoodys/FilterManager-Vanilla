<?php
function Search($tablename, $fields, $searchWord){
$jsonString = file_get_contents("data.json");
$data = json_decode($jsonString, true);
$searchResults = [];
foreach ($data["filters"] as $filterObject) {
    // Check for matches in the specified fields
    if (
        strpos($filterObject["_id"], $searchWord) !== false ||
        strpos($filterObject["filter_size"], $searchWord) !== false ||
        strpos($filterObject["filter_type"], $searchWord) !== false ||
        strpos($filterObject["par"], $searchWord) !== false ||
        strpos($filterObject["notes"], $searchWord) !== false ||
        strpos($filterObject["pn"], $searchWord) !== false ||
        strpos($filterObject["storage"], $searchWord) !== false
    ) {
        // Add the matching object to the results array
        $searchResults[] = $filterObject;
    }
}
return $searchResults;
}
?>
