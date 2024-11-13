<?php
if(session_id() == ''){
      session_start();
   }
   ?>
<html>
<body style="background-color: #fadcac;">

    <head>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="bootsrap/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </head>

<?php
//echo "backup folder=".$_SESSION["backup_folder"];
 $delimiter = ",";
$filenameWithPath = 'sites/'.$_SESSION["backup_folder"].'/equipment_' . date('Y-m-d') . '.csv'; 
$arrTable = array('table','equipment');
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
 
 $f = fopen($filenameWithPath, 'c'); 
fputcsv($f, $arrTable, $delimiter); 
$fields = array('_id', 'unit_name','location','area_served','filter_size','filters_due','belts','notes','filter_rotation','filter_type','filters_last_changed', 'assigned_to', 'image'); 
fputcsv($f, $fields, $delimiter); 
foreach ($data["equipment"] as $row) 
{

        $lineData = array($row['_id'], $row['unit_name'], $row['location'], $row['area_served'], $row['filter_size'],$row['filters_due'],$row['belts'], $row['notes'], $row['filter_rotation'], $row['filter_type'], $row['filters_last_changed'], $row['assigned_to'], $row['image']); 
            //print_r($lineData);
            fputcsv($f, $lineData, $delimiter); 

    }
    //USERS DATA BACK UP
     
    $arrTable = array("table","users");
    fputcsv($f, $arrTable, $delimiter);
    $fields = array('_id', 'user_name','password','userscol','Email', 'font_family', 'font_size', 'theme', 'admin'); 
    $jsonString = file_get_contents('table_users.json');
    $data = json_decode($jsonString, true);
    fputcsv($f, $fields, $delimiter); 
    foreach ($data as $row) 
        {
            if($row["backup_folder"] == $_SESSION["backup_folder"])
            {
                $lineData = array($row['_id'], $row['user_name'], $row['password'], $row['Email'], $row['font_family'], $row['font_size'], $row['theme'], $row['admin'], $row['backup_folder']); 
                fputcsv($f, $lineData, $delimiter); 
            }
        } 
    
    //FILTERS DATA BACK UP
    $arrTable = array("table","filters");
    fputcsv($f, $arrTable, $delimiter);
     
    $fields = array('_id', 'filter_size','filter_type','filter_count','par','notes','date_updated', 'pn', 'storage'); 
    fputcsv($f, $fields, $delimiter); 
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data["filters"] as $row) 
    {
    if(is_array($row['storage']))
        {
            $Storage = implode(",",$row['storage']);
        }
        else
        {
            $Storage = $row['storage'];
        }
            $lineData = array($row['_id'], $row['filter_size'], $row['filter_type'], $row['filter_count'], $row['par'], $row['notes'], $row['date_updated'], $row['pn'], $Storage);
            fputcsv($f, $lineData, $delimiter); 
        } 

    //FILTER TYPE DATA BACK UP
        $arrTable = array("table","filtertype");
        fputcsv($f, $arrTable, $delimiter);
         
        $fields = array('_id', 'type','trackable'); 
        fputcsv($f, $fields, $delimiter); 
        $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
        $data = json_decode($jsonString, true);
        foreach ($data["filter_types"] as $row) 
        {
            $lineData = array($row['_id'], $row['type'], $row['trackable']); 
            fputcsv($f, $lineData, $delimiter); 
        } 

   //FILTER ORDERS DATA BACK UP
        $arrTable = array("Table","Filter Orders");
        fputcsv($f, $arrTable, $delimiter);
         
        $fields = array('_id', 'order_date','need_by_date, shoparea, requested_by, cer, sole_vender, suggested_vender, quote_number, order_array'); 
        fputcsv($f, $fields, $delimiter); 
         $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
        $data = json_decode($jsonString, true);
        foreach ($data["filter_orders"] as $row) 
        {
            $lineData = array($row['_id'], $row['order_date'], $row['need_by_date'], $row['shoparea'], $row['requested_by'], $row['cer'], $row['sole_vender'], $row['suggested_vender'], $row['quote_number'], $row['order_array']); 
            fputcsv($f, $lineData, $delimiter); 
        } 
    
        //STORAGE DATA BACK UP
            $arrTable = array("table","storage");
            fputcsv($f, $arrTable, $delimiter);
            $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
            $data = json_decode($jsonString, true);
             
            $fields = array('_id', 'location'); 
            fputcsv($f, $fields, $delimiter); 
            foreach ($data["storage"] as $row) 
            { 
                $lineData = array($row['_id'], $row['location']); 
                fputcsv($f, $lineData, $delimiter); 
            } 
            //MISC DATA BACK UP
            $arrTable = array("table","misc");
            fputcsv($f, $arrTable, $delimiter);
            $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
            $data = json_decode($jsonString, true); 
             
            $fields = array('_id', 'last_backup', 'company_name', 'company_image'); 
            fputcsv($f, $fields, $delimiter); 
            foreach ($data["misc"] as $row) 
                {  
                    $lineData = array($row['_id'], $row['last_backup'], $row['company_name'], $row['company_image']); 
                    fputcsv($f, $lineData, $delimiter); 
                } 

            fclose($f);
            $url = $filenameWithPath;
            $filename = basename($filenameWithPath);
            echo "<div style='text-align:center;background-color:white;color:green;'>Export complete. DATA exported to file name: <strong>". $filename ."</strong></div>";
                 echo "<a href='".$url."'><BUTTON style='margin-left:500px;margin-right:auto;' class='btn btn-primary'>CLICK HERE TO DOWNLOAD  ".$filename."</BUTTON></a>";

            ?>
            
        <img src='images/saveascvs.jpg' style='margin-left:400px;margin-top:100px;width: 50%;height: 50%;'>



