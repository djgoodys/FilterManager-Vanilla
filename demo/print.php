<!DOCTYPE html>
<html>
<?php 
$Fontsize = "";
if(isset($_GET["fontsize"])){$Fontsize=$_GET["fontsize"];}
if(isset($_POST["fontsize"])){$Fontsize=$_POST["fontsize"];}
if($Fontsize == ""){$Fontsize="4";}
?>
<head>
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

     button {
      background-color: #4CAF50; /* Green */
      border: none;
      color: white;
      padding: 15px 32px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 16px;
      border-radius: 10%; 
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
include('dbMirage_connect.php');

error_reporting(E_ALL);
 
// Check connection
if(mysqli_connect_errno()){
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}


//echo "font=".var_dump($_GET);
$query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation,filters_last_changed, assigned_to FROM equipment WHERE DATE (filters_due) < DATE(CURRENT_DATE)";

?>
<table>
<th><td>
<form action="print.php" method="get">
<p id="font">Font size<SELECT onchange="submit();" name="fontsize">
<OPTION value="1" <?php if ($Fontsize == "1"){echo "SELECTED";} ?>>1</option>
<option value="2" <?php if ($Fontsize == "2"){echo "SELECTED";} ?>>2</option>
<option value="3" <?php if ($Fontsize == "3"){echo "SELECTED";} ?>>3</option>
<option value="4" <?php if ($Fontsize == "4"){echo "SELECTED";} ?>>4</option>
</SELECT></p></td><td>
<button id="btnPrint" onclick="Printpage();">Print...</button></form>
</th></td></tr></table>
<table class="printpage" id="myTable">
    <tr>
        <th>UNIT</th>
        <th>AREA</th>
        <th>SERVICES</th>
         <th>FILTER SIZE</th>
         <th>DUE DATE</th>
        
    </tr>
    <?php
    if ($stmt = $con->prepare($query)) {
        $stmt->execute();
        //Bind the fetched data to $unitId and $UnitName
        $stmt->bind_result($unitId, $UnitName, $Location, $AreaServed, $FilterSize, $FiltersDue, $belts, $notes, $FilterRotation, $FiltersLastChanged, $AssignedTo );
?>
    <form action='ListEquipment.php' method='post'>
    <input type='hidden' id='action' name="action" value ="addalltasks">
    <?php
        while ($stmt->fetch()) {
         $today = date('Y-m-d');
        $someDate = new DateTime($FiltersDue);
        $daysoverdue= s_datediff( "d",$today,$someDate,true);
       // $daysoverdue=  $diff = date_diff("d",$today, $someDate);
        if($daysoverdue <= 1) {
        $fontcolor="red";
        }else{
        $fontcolor="black";
        }
            ?>
            <tr>
                <td><font size="<?php echo $Fontsize ?>" color="<?php echo $fontcolor ?>"><?php echo $UnitName ?></font></td>
                <td><font size="<?php echo $Fontsize ?>" color="<?php echo $fontcolor ?>"><?php echo $Location ?></font></td>
                <td><font size="<?php echo $Fontsize ?>" color="<?php echo $fontcolor ?>"><?php echo $AreaServed ?> </font></td>
                <td><font size="<?php echo $Fontsize ?>" color="<?php echo $fontcolor ?>"><?php echo $FilterSize ?></font></td>
                <td><font size="<?php echo $Fontsize ?>" color="<?php echo $fontcolor ?>"><?php echo $FiltersDue ?></font></td>

            </tr>

            <?php

        }
    }
    $query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation,filters_last_changed, assigned_to FROM equipment WHERE DATE (filters_due) > DATE(CURRENT_DATE)";
    if ($stmt = $con->prepare($query)) {
    $stmt->execute();
    //Bind the fetched data to $unitId and $UnitName
    $stmt->bind_result($unitId, $UnitName, $Location, $AreaServed, $FilterSize, $FiltersDue, $belts, $notes, $FilterRotation, $FiltersLastChanged, $AssignedTo );
    
    while ($stmt->fetch()) {
    ?>
    <tr>
    <td><font size="<?php echo $Fontsize ?>" color="black"><?php echo $UnitName ?></font></td>
    <td><font size="<?php echo $Fontsize ?>" color="black"><?php echo $Location ?></font></td>
    <td><font size="<?php echo $Fontsize ?>" color="black"><?php echo $AreaServed ?> </font></td>
    <td><font size="<?php echo $Fontsize ?>" color="black"><?php echo $FilterSize ?></font></td>
    <td><font size="<?php echo $Fontsize ?>" color="black"><?php echo $FiltersDue ?></font></td>
    
    </tr>

<?php
}
echo "</table>";
}
function AddTask($user, $unitid,$UnitName,$filters, $filterrotation, $filtersdue)
{
    //echo var_dump($_POST);
    if (isset($_POST['action'])=="addtask") {
   
        // $DB_USER1= "a3f5da_lobby"; // db user
        //$DB_PASSWORD1= "relays82"; // db password (mention your db password here)
        //$DB_DATABASE1= "db_a3f5da_lobby"; // database name
        //$DB_SERVER1= "MYSQL5013.site4now.net"; // db server
        $con2 = mysqli_connect("MYSQL5013.site4now.net","a3f5da_lobby","relays82","db_a3f5da_lobby");
    
    
        
        $TodaysDate = date("Y-m-d");
        $sql = "INSERT INTO tasks(employee_name,unit_id,unit_name,filters_needed,task_date_created,filter_rotation,filters_due) VALUES ('" . $user . "','" . $unitid . "','" . $UnitName. "','" . $filters . "','" . $TodaysDate . "','" . $filterrotation . "','" . $filtersdue . "')";
        // error_reporting(-1);
        //mysqli_select_db($con2, 'db_a3f5da_lobby');
        
       $retval = mysqli_query($con2, $sql); 
        if (!$retval) {
            echo "could not enter data" . mysqli_error($con2);
            die('Could not enter data: ' . mysqli_error($con2));

        }
        if(isset($_POST['_id'])){$UnitID = ($_POST['_id']);}
        $sql="UPDATE equipment  SET assigned_to ='".$_COOKIE["user"]."' WHERE _id='". $UnitID ."';";
        //mysqli_select_db($con, 'db_a3f5da_lobby');
          if ($retval=mysqli_query($con2, $sql)){
         echo "<br>" .$UnitName . " was added to your tasks<br>";
         }
        mysqli_close($con2);
        
    
}
}

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