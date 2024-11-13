<?php 
 
// Load the database configuration file 
include('dbMirage_connect.php'); 
 
// Fetch records from database 
$query = $con->query("SELECT * FROM equipment ORDER BY _id ASC"); 
 
if($query->num_rows > 0){ 
    $delimiter = ","; 
    $filename = "equipment_" . date('Y-m-d') . ".csv"; 
     
     $f = fopen($filename, 'c'); 

    $fields = array('_id', 'unit_name','location','area_served','filter_size','filters_due','belts','notes','filter_rotation','filter_type','filters_last_changed', 'assigned_to', 'image'); 
    fputcsv($f, $fields, $delimiter); 
    while($row = $query->fetch_assoc()){ 
        $lineData = array($row['_id'], $row['unit_name'], $row['location'], $row['area_served'], $row['filter_size'],$row['filters_due'],$row['belts'], $row['notes'], $row['filter_rotation'], $row['filter_type'], $row['filters_last_changed'], $row['assigned_to'], $row['image']); 

         //print_r($lineData);
        fputcsv($f, $lineData, $delimiter); 
    } 
     
  
      fclose($f);
    $url = $ServerAddress.$filename;
  echo "<a href=".$url.">Download Data BackUP</a>";
 }
    ?>
