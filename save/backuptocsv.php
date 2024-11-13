<html>
    <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <body style="background-color:green;color:white;";>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<?php 
include('dbMirage_connect.php'); 

$filename = "equipment_" . date('Y-m-d') . ".csv"; 
$arrEnd = array('table','end');
$arrTable = array('table','equipment');
$url = $filename;
if(file_exists($filename)){
    echo "<div style='background-color:black;color:WHITE;'>A BACK UP HAS ALREADY BEEN PERFORMED TODAY. DATA SAVED IN FILE: ".$filename."<br>CLICK BUTTON BELOW TO DOWNLOAD FROM SERVER.</DIV>
    <a href='".$url ."'><button type='button' class='btn btn-info');'>DOWN LOAD BACK UP FILE</button><a>";
}
else
{
    //UNIT DATA BACKUP
    $f = fopen($filename, 'c'); 
    $query = $con->query("SELECT * FROM equipment ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
    $delimiter = ","; 
    fputcsv($f, $arrTable, $delimiter); 
    $fields = array('_id', 'unit_name','location','area_served','filter_size','filters_due','belts','notes','filter_rotation','filter_type','filters_last_changed', 'assigned_to', 'image'); 
    fputcsv($f, $fields, $delimiter); 
    while($row = $query->fetch_assoc())
        { 
            $lineData = array($row['_id'], $row['unit_name'], $row['location'], $row['area_served'], $row['filter_size'],$row['filters_due'],$row['belts'], $row['notes'], $row['filter_rotation'], $row['filter_type'], $row['filters_last_changed'], $row['assigned_to'], $row['image']); 
            //print_r($lineData);
            fputcsv($f, $lineData, $delimiter); 
        } 
    fputcsv($f, $arrEnd, $delimiter);
    }
    //USERS DATA BACK UP
    $query = $con->query("SELECT * FROM users ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
    $delimiter = ","; 
    $arrTable = array("table","users");
    fputcsv($f, $arrTable, $delimiter);
    $fields = array('_id', 'user_name','password','Email', 'font_family', 'font_size', 'theme', 'admin'); 
    fputcsv($f, $fields, $delimiter); 
    while($row = $query->fetch_assoc())
        { 
            $lineData = array($row['_id'], $row['user_name'], $row['password'], $row['userscol'], $row['Email'], $row['font_family'], $row['font_size'], $row['theme'], $row['admin']); 
            //print_r($lineData);
            fputcsv($f, $lineData, $delimiter); 
        } 
    fputcsv($f, $arrEnd, $delimiter);
    }
    //FILTERS DATA BACK UP
    $arrTable = array("table","filters");
    fputcsv($f, $arrTable, $delimiter);
    $query = $con->query("SELECT * FROM filters ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
    $delimiter = ","; 
    $fields = array('_id', 'filter_size','filter_type','filter_count','par','notes','date_updated', 'pn', 'storage'); 
    fputcsv($f, $fields, $delimiter); 
    while($row = $query->fetch_assoc())
        { 
            $lineData = array($row['_id'], $row['filter_size'], $row['filter_type'], $row['filter_count'], $row['par'], $row['notes'], $row['date_updated'], $row['pn'], $row['storage']); 

            //print_r($lineData);
            fputcsv($f, $lineData, $delimiter); 
        } 
    fputcsv($f, $arrEnd, $delimiter);
    }
        //FILTER TYPE DATA BACK UP
        $arrTable = array("table","filtertype");
        fputcsv($f, $arrTable, $delimiter);
        $query = $con->query("SELECT * FROM filter_types ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
    $delimiter = ","; 
    $fields = array('_id', 'type','trackable'); 
    fputcsv($f, $fields, $delimiter); 
    while($row = $query->fetch_assoc())
        { 
            $lineData = array($row['_id'], $row['type'], $row['trackable']); 

            //print_r($lineData);
            fputcsv($f, $lineData, $delimiter); 
        } 
    fputcsv($f, $arrEnd, $delimiter);
    }

        //FILTER ORDERS DATA BACK UP
        $arrTable = array("table","filterorders");
        fputcsv($f, $arrTable, $delimiter);
        $query = $con->query("SELECT * FROM filter_orders ORDER BY order_date ASC"); 
    if($query->num_rows > 0)
    { 
    $delimiter = ","; 
    $fields = array('_id', 'order_date','need_by_date, shoparea, requested_by, cer, sole_vender, suggested_vender, quote_number, order_array'); 
    fputcsv($f, $fields, $delimiter); 
    while($row = $query->fetch_assoc())
        { 
            $lineData = array($row['_id'], $row['order_date'], $row['need_by_date'], $row['shoparea'], $row['requested_by'], $row['cer'], $row['sole_vender'], $row['suggested_vender'], $row['quote_number'], $row['order_array']); 

            //print_r($lineData);
            fputcsv($f, $lineData, $delimiter); 
        } 
    fputcsv($f, $arrEnd, $delimiter);
    }

//STORAGE DATA BACK UP
    $arrTable = array("table","storage");
    fputcsv($f, $arrTable, $delimiter);
$query = $con->query("SELECT * FROM storage ORDER BY _id ASC"); 
if($query->num_rows > 0)
{ 
$delimiter = ","; 
$fields = array('_id', 'location'); 
fputcsv($f, $fields, $delimiter); 
while($row = $query->fetch_assoc())
    { 
        $lineData = array($row['_id'], $row['location']); 
        //print_r($lineData);
        fputcsv($f, $lineData, $delimiter); 
    } 
    fputcsv($f, $arrEnd, $delimiter);
    fclose($f);
}

//MISC DATA BACK UP
    $arrTable = array("table","misc");
    fputcsv($f, $arrTable, $delimiter);
$query = $con->query("SELECT * FROM misc LIMIT 1"); 
if($query->num_rows > 0)
{ 
$delimiter = ","; 
$fields = array('_id', 'last_backup', 'company_name', 'company_image'); 
fputcsv($f, $fields, $delimiter); 
while($row = $query->fetch_assoc())
    { 
        $lineData = array($row['_id'], $row['last_backup'], $row['company_name'], $row['company_image']); 
        //print_r($lineData);
        fputcsv($f, $lineData, $delimiter); 
    } 
    fputcsv($f, $arrEnd, $delimiter);
    fclose($f);
}

      //UPDATE MISC TABLE ON LAST DATABASE BACK UP
      $Today=date('Y-m-d');
             $query = "INSERT INTO misc(last_backup) VALUES('" . $Today . "');";
             if (mysqli_query($con, $query)) {
                 echo "<div style='background-color:black;color:white;'>Export complete. DATA exported to file name:". $filename ."</div>";
                 echo "<BUTTON style='background-color:black;color:white;'><a href='".$url."'>DOWNLOAD THE DATA BACK FILE AND SAVE IT</A></BUTTON>";
                } else {
                 echo "<div style='background-color:black;color:red;'>Error creating back up file " . mysqli_error($con) ."</div>";
             } 
}

?>