<?php
if(session_id() == ''){
      session_start();
   }
   ?>
<!DOCTYPE html>
<html>
<?php 
$Fontsize = "";
if(isset($_GET["fontsize"])){$Fontsize=$_GET["fontsize"];}
if(isset($_POST["fontsize"])){$Fontsize=$_POST["fontsize"];}
if($Fontsize == ""){$Fontsize="1";}
?>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <meta charset="utf-8"
     name="viewport" content="width=device-width, initial-scale=1">
    <title>Filters List</title>
    <style>
   
    #myTable {
    
    
    width: 100%; /* Full-width */
    font-size: <?php echo $Fontsize ?> px; /* Increase font-size */
    }
    
    #myTable th, #myTable td {
    text-align: left; /* Left-align text */
    padding: 0px; /* Add padding */
    border-collapse: collapse;
    }
    
    #myTable tr {
    /* Add a bottom border to all table rows */
   
    border-collapse: collapse;
    }
    
    #myTable tr.header, #myTable tr:hover {
    /* Add a grey background color to the table header and on hover */
    background-color: #f1f1f1;
    }
    body:not(#btnPrint) {
    font-size: <?php echo $Fontsize ?>em;
    }
 
     #btnPrint {
      border: none;
      color: white;
      padding: 5px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      border-radius: 10%; 
        height:40px;
      }
    #printPage
    {
    font-size: <?php echo $Fontsize ?> px;
    margin: 0px;
    padding: 0px;
    width: 670px; /* width: 7in; */
    height: 900px; /* or height: 9.5in; */
    clear: both;
    background-color: white;
    page-break-after: always;
    
    }
 table {
   border-collapse: collapse;
      border: 1px solid black;
 }
    </style>
    <STYLE>A {text-decoration: none;} </STYLE>
     <link rel="stylesheet" href="print.css">
</head>
<body style="printpage" onclick="showprint();">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script type="text/javascript">
function Printpage(){
   document.getElementById("btnPrint").style.display = "none";
   document.getElementById("font").style.display = "none";
   window.print();
}

function showprint(){
document.getElementById("btnPrint").style.display = "block";
document.getElementById("font").style.display = "block";
}
</script>
<?php

error_reporting(E_ALL);
$LastQuery = "NORMAL";
if(isset($_COOKIE["cookie_lastquery"])) 
{
    $LastQuery=$_COOKIE["cookie_lastquery"];
}
$Field2 =  "Area Served";  
if(isset($_SESSION["field2"]))
{
    if($_SESSION["field2"] != ""){$Field2 = $_SESSION["field2"];}      
}

$Field3 =  "Location";
if(isset($_SESSION["field3"]))
{
    if($_SESSION["field3"] != ""){$Field3 = $_SESSION["field3"];}
} 
 //echo "field2=".$Field2. " field3=".$Field3;

 if(isset($_COOKIE["cookie_lastquery"])) {
    $query = $_COOKIE["cookie_lastquery"];
    //echo "lastquery=". $_COOKIE["cookie_lastquery"];
}else{
$query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation,filters_last_changed, assigned_to FROM equipment WHERE DATE (filters_due) < DATE(CURRENT_DATE)";
}
?>
<table style="margin:auto;">
<th><td>
<div class="dropdown bg-success rounded display-6" id="font">
  <button class="btn btn-success dropdown-toggle display-6" type="button"  id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Font Size
  </button>
  <div class="dropdown-menu display-6" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item display-6"  href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?fontsize=.50">.50 em</a>
    <a class="dropdown-item display-6"  href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?fontsize=1">1 em</a>
    <a class="dropdown-item display-6" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?fontsize=1.5">1.5 em</a>
    <a class="dropdown-item display-6" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?fontsize=2">2 em</a>
  </div>
</div>
</td><td>
<button id="btnPrint" onclick="Printpage();" class="bg-success">Print...</button></form>
</th></td><td style="width:25%;text-align:right;"><?php echo "Filter status as of " . date("Y/m/d"); ?></td><td style="font-weight: bold;color:red;width:25%;">&nbsp;&nbsp;&nbsp;BOLD font = over due</td></tr></table>
<table class="printpage" id="myTable">
    <tr>
        <th>UNIT NAME</th>
        <th><?php echo $Field2 ?></th>
         <th><?php echo $Field3 ?></th>
         <th>DUE DATE</th>
        
    </tr>
    <?php
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);

$equipment = [];
//-------------over due only---------------
if($LastQuery == "OVERDUE"){
//datediff(CURDATE(),filters_due) > 0
$today = date("Y-m-d");

foreach ($data["equipment"] as $obj) {
            $filterDueDateObject = new DateTime($obj["filters_due"]);
            $todayObject = new DateTime($today);
            if ($filterDueDateObject < $todayObject) {
                $equipment[] = $obj;
            }
}
}
//-----Normal (show all)---------------------------
if($LastQuery == "NORMAL"){
foreach($data["equipment"] as $obj){
$equipment[]=$obj;
}
}

//---------------SORT BY Ascending-------------------
if($LastQuery == "ASC"){
usort($data['equipment'], function($a, $b) {
    return strtotime($a['filters_due']) - strtotime($b['filters_due']);
});
 $equipment = $data["equipment"]; 
}
//--------------SORT BY DESCENDING-----------
if($LastQuery == "DESC"){
    //echo "starting by DESC";
    usort($data['equipment'], function($a, $b) {
    return strtotime($b['filters_due']) - strtotime($a['filters_due']);
});
$equipment = $data["equipment"];
}
//--------------SORT BY DUE TODAY ONLY-------------------------------
if($LastQuery == "today"){
    $today = date('Y-m-d');
foreach($data["equipment"] as $obj){
    if(strtotime($obj["filters_due"]) == strtotime($today)){
        $equipment[]=$obj;
    }
}
}
//------------------------------------------------

    foreach($equipment as $row){
         $today = date('Y-m-d');
        $someDate = new DateTime($row["filters_due"]);
        $daysoverdue= s_datediff( "d",$today,$someDate,true);
       // $daysoverdue=  $diff = date_diff("d",$today, $someDate);
        if($daysoverdue <= 1) {
        $fontcolor="red";
        $font_weight="bold";
        }else{
        $font_weight="normal";    
        $fontcolor="black";
        }
            ?>
            <tr>
                <td style="color:<?php echo $fontcolor ?>;font-weight:<?php $font_weight ?>;"><?php echo $row["unit_name"] ?></td>
                <?php           
                switch ($Field2) {
                case "Location":
                echo "<td style='color:". $fontcolor.";font-weight:".$font_weight. ";'>". $row["location"] ."</td>";
                break;

                case "Area Served":
                ?>
                <td style="color:<?php echo $fontcolor ?>;font-weight:<?php echo $font_weight ?>;"><?php echo $row["area_served"] ?></td>
                <?php
                break;

                case "Last Changed":
                ?>
                <td style="color:<?php echo $fontcolor ?>;font-weight:<?php echo $font_weight ?>;"><?php echo  $row["filters_last_changed"] ?></td>
                <?php
                break;
                
                default:
                echo "<td style='color:". $fontcolor .";font-weight:".$font_weight .";>". $row["area_served"] ." bbbbb</td>";
                break;
                }

                 switch ($Field3) {
                case "Area Served":
                ?>
                <td style="color:<?php echo $fontcolor ?>;font-weight:<?php echo $font_weight ?>;"><?php echo $row["area_served"] ?></td>
                <?php
                break;

                case "Location":
                ?>
                <td style="color:<?php echo $fontcolor ?>;font-weight:<?php echo $font_weight ?>;"><?php echo $row["location"] ?> </td>
                <?php
                break;

                case "Filter Size":
                ?><td style="color:<?php echo  $fontcolor ?>;font-weight:<?php echo $font_weight ?>;"><?php echo  $row["filter_size"] ?> </td>
                <?php
                break;

                case "Notes":
                ?><td style="color:<?php echo  $fontcolor ?>;font-weight:<?php echo $font_weight ?>;"><?php echo  $row["notes"] ." </td>";
                break;
                 }
                 ?>
                <td style="color:<?php echo  $fontcolor ?>;font-weight:<?php echo $font_weight ?>;"><?php echo $row["filters_due"] ?></td>
            </tr>

            <?php

        }
    

    ?>
    

    
    </tr>

<?php

echo "</table>";


function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
       
       switch( $str_interval){
           case "y": 
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h": 
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i": 
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s": 
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }
       if($diff->invert){
         return -1 * $total;
       }else{
       return $total;
   }
}
?>

</body>
</html>