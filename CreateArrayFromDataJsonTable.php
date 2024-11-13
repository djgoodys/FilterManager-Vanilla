$jsonString = file_get_contents("data.json");
$data = json_decode($jsonString, true);

// Extract the "filter_size" values into an array
$filterSizes = [];
foreach ($data["filters"] as $filterObject) {
    $filterSizes[] = $filterObject["filter_size"];
}

// Example usage of the $filterSizes array
echo "Filter sizes: " . implode(", ", $filterSizes);