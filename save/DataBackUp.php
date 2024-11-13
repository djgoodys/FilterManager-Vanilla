<?php
if(session_id() == ''){
      session_start();
   }
   ?>
<html>
    <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <body style="background-color:green;color:white;";>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<?php 

$BackUpFolder = $_SESSION['backup_folder'];
$filename = "sites/".$BackUpFolder."/FMdata.fm";
$con = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
$BackupFolder="";
//$filename = $row["backup_folder"]."/FMdata.fm";
$NumRecords = 0;
$url = $_SESSION["server_name"]."/sites/".$BackUpFolder."/FMdata.fm";
{
    //UNIT DATA BACKUP
    $f = fopen($filename, 'w'); 
    $query = $con->query("SELECT * FROM equipment ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
        $NumRecords = $NumRecords + 1;
        while($row = $query->fetch_assoc())
            { 
                $sql = "INSERT INTO equipment(unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation,filter_type,filters_last_changed,assigned_to,image) VALUES('" . $row['unit_name'] . "','" . $row['location'] . "','" . $row['area_served'] . "','" . $row['filter_size'] . "','" . $row['filters_due'] . "','" . $row['belts'] . "','" . $row['notes'] . "','" . $row['filter_rotation'] . "','".$row['filter_type']."','".$row['filters_last_changed']."','".$row['assigned_to']."','".$row['image']."');";
                $sql = str_replace("\n", "", $sql);
                $sql = $sql . "\n"; //PUT NEW LINE AT END OF STRING
                fwrite($f, $sql);
            } 
    }
    
    //FILTERS DATA BACK UP
    $query = $con->query("SELECT * FROM filters ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
        $NumRecords = $NumRecords + 1;
        while($row = $query->fetch_assoc())
            { 
                $sql = "INSERT INTO filters (filter_size, filter_type, filter_count, par, notes, date_updated, pn, storage) VALUES('".$row['filter_size']."','". $row['filter_type']."','". $row['filter_count']."','". $row['par']."','". $row['notes']."', '". $row['date_updated']."', '". $row['pn']."', '". $row['storage']."');";
                $sql = str_replace("\n", "", $sql);
                $sql = $sql . "\n"; //PUT NEW LINE AT END OF STRING
                fwrite($f, $sql); 
            } 

    }
        //FILTER TYPE DATA BACK UP
    $query = $con->query("SELECT * FROM filter_types ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
        $NumRecords = $NumRecords + 1;
        while($row = $query->fetch_assoc())
            { 
                $sql = "INSERT INTO filter_types(type, trackable) VALUES('".$row['type']."','".$row['trackable']."');"; 
                $sql = str_replace("\n", "", $sql);
                $sql = $sql . "\n"; //PUT NEW LINE AT END OF STRING
                fwrite($f, $sql);
            } 
    }

        //FILTER ORDERS DATA BACK UP
    $query = $con->query("SELECT * FROM filter_orders ORDER BY order_date ASC"); 
    if($query->num_rows > 0)
    { 
        $NumRecords = $NumRecords + 1;
        while($row = $query->fetch_assoc())
            { 
                $sql = "INSERT INTO filter_orders(order_date, need_by_date, shoparea, requested_by, cer, sole_vender, suggested_vender, quote_number, order_array) VALUES('".$row['order_date']."','".$row['need_by_date']."','".$row['shoparea']."','".$row['requested_by']."','".$row['cer']."','".$row['sole_vender']."','".$row['suggested_vender']."','".$row['quote_number']."','".$row['order_array']."');"; 
                $sql = str_replace("\n", "", $sql);
                $sql = $sql . "\n"; //PUT NEW LINE AT END OF STRING
                fwrite($f, $sql);
            } 
    }
        //FILTER STORAGE BACKUP
    $query = $con->query("SELECT * FROM storage ORDER BY _id ASC"); 
    if($query->num_rows > 0)
    { 
        $NumRecords = $NumRecords + 1;
        while($row = $query->fetch_assoc())
        { 
            $sql = "INSERT INTO storage(location)VALUES('". $row['location']."');";
            $sql = str_replace("\n", "", $sql);//REMOVES THESE FROM NOTES - CAUSES ISSUES 
            $sql = $sql . "\n"; //PUT NEW LINE AT END OF STRING
            fwrite($f, $sql); 
        } 
    }
            //UPDATE MISC TABLE ON LAST DATABASE BACK UP
            //SEE IF ANY RECORDS EXISTS IN MISC TABLE
            $sql = "SELECT * FROM misc LIMIT 1";
                $Today=date('Y-m-d');
            while($row = $query->fetch_assoc())
                { 
                    $sql = "INSERT INTO misc(last_backup, company_name, company_image, backup_folder)VALUES('". $Today."', '". $row['company_name']."', '". $row['company_image']."' '". $row['backup_folder']."');";
                    $sql = str_replace("\n", "", $sql);//REMOVES THESE FROM NOTES - CAUSES ISSUES 
                    $sql = $sql . "\n"; //PUT NEW LINE AT END OF STRING
                    fwrite($f, $sql); 
                } 
//USERS DATA BACK UP
  

$username = "4094059_demo";
$password = "relays82";
$database = "4094059_demo";

$NewCon = mysqli_connect($_SESSION["server_name"],$username,$password,$database);
$query = $NewCon->query("SELECT * FROM users ORDER BY _id ASC"); 
if($query->num_rows > 0)
{ 
    $NumRecords = $NumRecords + 1;
    while($row = $query->fetch_assoc())
        {	
            $sql = "INSERT INTO users(user_name, password, field2, field3, Email, font_family, font_size, theme, admin, database_name, database_password, site_name) VALUES('" .$row['user_name']."', '". $row['password']."', '". $row['field2']."', '". $row['field3']."', '". $row['Email']."', '". $row['font_family']."', '". $row['font_size']."', '". $row['theme']."', '". $row['admin']."', '". $row['database_name']."', '". $row['database_password']."', '". $row['site_name']."');"; 
            $sql = str_replace("\n", "", $sql);
            $sql = $sql . "\n"; //PUT NEW LINE AT END OF STRING
            fwrite($f, $sql);
        } 
}
             if ($NumRecords > 0) 
                {
                    echo "<div style='text-align:center;background-color:black;color:white;font-size:1.5em;'>Export complete. DATA exported to file name: ( FMdata.fm ). (Optional: You can download back up file.)<br><BUTTON class='btn btn-white'><a href='".$url."'>CLICK HERE TO DOWNLOAD THE 'DATA BACK FILE'</A></BUTTON>";
                } 
                if(mysqli_error($con)){
                    echo "<div style='background-color:black;color:red;'>Error creating back up file " . mysqli_error($con) ."</div>";
                }
    }

?>