<?php
ob_start();
if (isset($_SESSION['_id'])){echo "yep";}
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE || session_status() === 1) {
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
$filename = 'sites/'.$_SESSION["backup_folder"] .'/data.json';
$backup_filename = 'sites/'.$_SESSION["backup_folder"] .'/data_backup.json';
if (file_exists($backup_filename)) {
    //echo "file exists last modified date and time: ". date("F d Y H:i:s.", filemtime($backup_filename));
}
if (file_exists($filename)) 
    {
        //$ret = copy($filename, $backup_filename) ;
    if (copy($filename, $backup_filename)) 
        {
        echo "<div style='background-color:black;color:aqua;text-align:center;'>Data was backuped successfully!<br>You can revert to this saved data anytime.<br>
        <a href='".$backup_filename."' download='data_backup.json'><button>CLICK HERE TO DOWNLOAD BACKUP FILE</buton></a></div>";
        } 
        else 
        {
        echo "<div style='background-color:black;color:red;text-align:center;>Error copying data.json file.</div>";
        }
    } 
    else 
    {
    echo "Original file 'data.json' not found.";
    }
    ob_end_flush() ;
    ?>