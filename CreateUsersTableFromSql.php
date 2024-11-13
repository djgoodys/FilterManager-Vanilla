
<?php
include "dbMirage_connect.php";

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Select data from the MySQL database
$sql = "SELECT _id, user_name, password, field2, field3, Email, admin, font_family, font_size, theme, backup_folder FROM users";
$result = $con->query($sql);

// Create an array to store the data
$data = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo $row["user_name"];
        $data[] = $row;
    }
}

// Convert the array to JSON format
$jsonString = json_encode($data);

// Write the JSON string to the file
file_put_contents('table_users.json', $jsonString);

// Close the MySQL connection
$con->close();
?>
