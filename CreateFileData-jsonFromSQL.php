<?php

include "dbMirage_connect.php";

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Collect table names
$tables = array();
$result = $con->query("SHOW TABLES");
while ($row = $result->fetch_array()) {
    $tables[] = $row[0];
}

// Array to store all JSON data
$allData = array();

// Loop through each table
foreach ($tables as $table) {
    $sql = "SELECT * FROM $table";
    $result = $con->query($sql);

    $tableData = array();
    while ($row = $result->fetch_assoc()) {
        $tableData[] = $row;
    }

    // Add table data to the main array
    $allData[$table] = $tableData;
}

// Close database connection
$con->close();

// Create JSON file
$json_data = json_encode($allData, JSON_PRETTY_PRINT);
file_put_contents("data.json", $json_data);



echo "JSON file created successfully, containing data from all tables!";
?>


