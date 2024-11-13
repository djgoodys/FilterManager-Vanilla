<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
    //echo "no session";
      session_start();
    }
    //echo "session id=".session_id();

include 'dbMirage_connect.php';
include 'CustomConfirmBox.css';
include 'phpfunctions.php';
include 'javafunctions.php';
include 'fm.css';

//echo "all GET vars:".var_dump($_GET)."<br>";
//echo "all POST vars:".var_dump($_POST)."<br>";
$Action = "";
if(isset($_POST["username"]))
         {
            $UserName=$_POST["username"];
            //echo "POST username=".$UserName. " LENGTH=" .strlen($UserName);
         }
if(isset($_GET["username"]))
         {
            $UserName=$_GET["username"];
            //echo "GET username=".$UserName;
         }

if(isset($_COOKIE["cookie_username"]))
         {
            $UserName=$_COOKIE["cookie_username"];
         //echo "cookie username=".$UserName;
         }

?>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=yes" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="width=device-width,height=device-height, user-scalable=no" />
   <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
   <meta http-equiv="pragma" content="no-cache" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
   <script>
function setFilterTypes(hiddenInputNumber){
    //console.log('setFilterType, hiddenInputNumber='+hiddenInputNumber);
    e=document.getElementById('slctFilterType'+hiddenInputNumber);
    document.getElementById('hdnFtype'+hiddenInputNumber).value = e.options[e.selectedIndex].text;
    areFilterTypesSet();
}
</script>
<script>
function getUserName(){
    username = getCookie("cookie_username");
    if (username == 'undefined' || username == null || username.length == '') 
         {
            alert("Your session has expired. Please log back in");
            window.location.href = "welcome1.php";
            //top.frames['iframe1'].location.href = "web_login3.php?action=logout";
         }
}

</script>
<script>
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
</script>
<script>
function areFilterTypesSet(){
    inputs = document.getElementsByClassName('ftype');
    document.getElementById("btnCompleteAllTasks").disabled = false;
    var Ready = true;
    for(i = 0; i < inputs.length; ++i) {
            if(inputs[i].value == "select"){
                Ready = false;
            }
            if(Ready == true)
            {
                document.getElementById("btnCompleteAllTasks").className = "btn btn-primary";
                document.getElementById("btnCompleteAllTasks").disabled = false;
            }
            else
            {
                document.getElementById("btnCompleteAllTasks").className = "btn btn-secondary";
                document.getElementById("btnCompleteAllTasks").disabled = true;
            }
    }
}
</script>

<script>
function ConfirmCompleteAll(){
    if (confirm("Are you sure you want to complete all tasks?") == true) {
            document.getElementById('frmCompleteAll').submit();
    }else{
        alert("Complete all tasks is canceled.");
    }
}
function showinfo($divID) {
//alert($divID);
        var my_disply = document.getElementById($divID).style.display;
         document.getElementById($divID).style.display = "block";

        if(my_disply == "none")
              document.getElementById($divID).style.display = "normal";
        else
              document.getElementById($divID).style.display = "none";
     }
</script>
<script>
function CompleteFilters($SelectNumber){
myForm = document.getElementById("frmCompleteTask"+$SelectNumber);
myselect = document.getElementById("slctFilterType"+$SelectNumber);
mybutton = document.getElementById("img"+$SelectNumber);

if(myselect.options[myselect.selectedIndex].text != "select")
    {
        console.log("submiting ");
      myForm.submit();
	  //setTimeout(history.go(0),3000);
    }
if(myselect.options[myselect.selectedIndex].text === "select")
    {
        document.getElementById("alert").style.display="block";//="alert alert-dark text-end";
        //alert("Select filter type installed first.")
        //mybutton.disabled = true;
    }
}
</script>

<script>
function enableSubmit($SelectNumber){
myForm = document.getElementById("frmCompleteTask"+$SelectNumber);
myselect = document.getElementById("slctFilterType"+$SelectNumber);
mybutton = document.getElementById("img"+$SelectNumber);

if(myselect.options[myselect.selectedIndex].text != "Select filter type used")
    {
        mybutton.enabled = true;
    }
if(myselect.options[myselect.selectedIndex].text === "Select filter type used")
    {
       // alert("Select filter type used and try again.")
       
        //mybutton.disabled = true;
    }

}
</script>
<script>
  function printme(){
    var tbl = document.getElementById("tblShowAllTasks");
    tbl.style.border = "1px solid #000";
    

    document.getElementById("btnPrint").style.display = "none";
    //document.getElementById("btnTaskHistory").style.display = "none";
    document.getElementById("btnPrint").style.display = "none";
    for (var i = 0; i < tbl.rows.length; i++) {
                    for (var j = 0; j < tbl.rows[i].cells.length; j++) {
                        tbl.rows[i].cells[j].style.display = "";
                        tbl.rows[i].cells[j].style.border = "1px solid #000";
                        if (j == 0 || j == 5 || j == 4)
                            tbl.rows[i].cells[j].style.display = "none";
                    }
                }
                //tbl.style.marginLeft="30px";
                //tbl.style.marginRight="50px";
document.getElementById('divFiltersCounted').style.marginTop = '10px';
    window.print();                
    document.getElementById("btnPrint").style= "display:block;margin:auto;vertical-align:top;font-size : 20px; width: 200px; height:70px;";
    //document.getElementById("btnTaskHistory").style.display = "block";
    for (var i = 0; i < tbl.rows.length; i++) 
        {
            for (var j = 0; j < tbl.rows[i].cells.length; j++)
            {          
                tbl.rows[i].cells[j].style.display = "";
                tbl.rows[i].cells[j].style.border = "none";
            }
        }
        tbl.style.marginTop="10px";
        tbl.style.border="none";
        document.getElementById("divFiltersCounted").style.marginTop = "50px";
}
            
</script>
<?php include 'fm.css'; ?>
<style>
    #tblUnitInfo {
        td {
            text-align:left;
            border: 1px solid white;
        }
    }
    td{
        width: 20%; 
        height: auto;
        text-align:center;
        
    }
    img{
        
    }
    .alert_container {
    position: absolute;
    top: 0px;
    left: 0px;
    width: 100%;
    height: 100%;
    background-color: #0000004d;
}

.alert_box {
    position: relative;
    width: 300px;
    min-height: 100px;
    margin-top: 50px;
    border: 2px solid red;
    background-color: #E53935;
    float:right;
    display:none;
}

.alert_box h1 {
    font-size: 1em;
    margin: 0;
    background-color: red;
    color: white;
    border-bottom: 1px solid #000;
    padding: 2px 0 2px 5px;
}

.alert_box p {
    font-size: 1em;
    height: 50px;
    margin-left: 55px;
    padding-left: 5px;
}
.tableTasks {
BackGroundColor:<?php echo $BackGroundColor ?>;
}
</style>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

<?php
//print_r($_POST);


//echo "theme=".$TDstyle;
echo  "<body  onload='getUserName();countFilters();'>";

if(isset($_POST["username"]))
         {
            $UserName=$_POST["username"];
            //echo "POST username=".$UserName. " LENGTH=" .strlen($UserName);
         }
if(isset($_GET["username"]))
         {
            $UserName=$_GET["username"];
            //echo "GET username=".$UserName;
         }
//echo "username=".$UserName."<br>";


function getNextDueDate($FRotation){   
        $Today= date("Y-m-d");
        $year = substr($Today, 0, 4);  // returns false
        //echo "year".$year."<br>";
        $month = substr($Today, 5, 2);  // returns false
        //echo "month".$month."<br>";
        $day = substr($Today, 8, 2);  // returns false
        //echo "day=".$day."<br>";
        $m = intval($month);
        $Rotation = $FRotation;
        $r=intval($Rotation);
       $b= $m += $r;
       //echo "rot=".$FRotation."<br>";
       //echo "b=".$b."<br>";
        if(intval($day) > 28 ){ $day="28";}

        
        if($b>12 && intval($FRotation) != 24){
        //echo "$b>12"."<br>";
            $month = ($r += intval($month)) - 12;
            //echo "new month=".$month."<br>";
            $yr = 0;
            $yr += intval($year) + 1;
            $year = (string)$yr;
            
            }
            
        if($b==12){
             //echo "b= 12"."<br>";
             $month = $b;
            //$year = substr($Today, 0, 4); 
            //$year = intval($year) + 1;
            }
        
        if($b<12){
            //echo "b < 12<br>";
            $month= $r += intval($month);
            $d=intval($day);
            $year = substr($Today, 0, 4); 
        }
        if(intval($FRotation) == 24){
            //echo " 2 years";
            $year += intval($year) + 2;
             $month = substr($Today, 5, 2);  // returns false
        }
        $filtersDue=$year."-".$month."-".$day;
        //echo "New Filter Due Date = ". $filtersDue . "<br>";
        return $filtersDue;
  }
  $sql="select filter_size from filters;";
   if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error . ']');
        $txtServerMsg = "There was an error running query line 197";
    }
   $FilterArray=array();
        while ($row = $result->fetch_assoc()) 
        { 
            $fsize = $row["filter_size"];
            //echo "f=".$fsize." pos=";
            array_push($FilterArray,$fsize);
        }
        
$txtServerMsg = "";
$display = "hidden";
$Action = "start";
if(isset($_POST["action"])){
    $Action = $_POST["action"];
}
if(isset($_GET["action"])){
    $Action = $_GET["action"];
}
if(strcmp($Action, "start")==0)
{
   $Action="getalltasks";
}
$UnitIdForInfo = "";
if(isset($_GET["UnitIdForInfo"])) 
{
    $UnitIdForInfo = $_GET["UnitIdForInfo"];
    $sql = "SELECT unit_name, location, area_served, filter_size, filter_type, filters_due, filter_rotation, belts, notes, filters_last_changed FROM equipment WHERE _id ='" . $UnitIdForInfo . "';";

    if (!$result = $con->query($sql)) 
        {
            die ('There was an error running query[' . $con->error . ']');
            $txtServerMsg = "unit not found, error running query";
        }
        else    
        {
            $row_cnt = $result->num_rows;
            if ($row_cnt > 0) 
                {
                    $display = "visible";
                    while ($row = $result->fetch_assoc()) 
                        {
                            $txtServerMsg = "<table id=tblUnitInfo style='background-color:black;color:white'><tr><td>Unit name</td><td>" . $row["unit_name"] . "</td></tr><tr><td>Location</td><td>" . $row["location"]. "</td></tr><tr><td>Area Served</td><td>" . $row["area_served"]. "</td></tr><tr><td>Filters</td><td>" . $row["filter_size"]. "</td></tr><tr><td>Filter type</td><td>" . $row["filter_type"]."</td></tr><tr><td>Filters due</td><td>" . $row["filters_due"]. "</td></tr><tr><td>Rotation</td><td>" . $row["filter_rotation"]. "</td></tr><tr><td>Filters last changed</td><td>" . $row["filters_last_changed"]."</td></tr><tr><td>Notes</td><td>" . $row["notes"]."</td></tr>";
                        }
                }
        }
}

//COMPLETE ALL TASKS---------------------------------------------------------
if(strcmp($Action,"complete_all_tasks")==0) 
    {
        //r($_POST);
        $arFiltersDue = array();
        $arFiltersUsed = array();
        $arRotation = array();
        $arUnitID = array();
        $arFilterType = array();
        $num = 0;
        foreach ($_POST["filter_rotation"] as $Rotation){
            array_push($arRotation, $Rotation);
        }
        foreach ($_POST["unit_id"] as $unitID) {
            array_push($arUnitID,$unitID);
        }
        foreach ($_POST["filter_type"] as $FilterType) {
        //echo "ftype=".$FilterType."<br>";
            array_push($arFilterType,$FilterType);
        }
              foreach ($_POST["filters_used"] as $FiltersUsed) {
            array_push($arFiltersUsed,$FiltersUsed);
        }
        $FiltersLastChanged = date("Y-m-d") ." [".$UserName."]";
 
        for($num=0; $num < sizeof($arUnitID);$num ++)
            {
                $effectiveDate=getNextDueDate($arRotation[$num]);
                //echo "UnitID=".$arUnitID[$num]."  Filters used:".$arFiltersUsed[$num]."  Rotation:".$arRotation[$num]." next due date:".$effectiveDate."<br>";
                if(strcmp($arFilterType[$num], "no_filters_used")==0)
                {
                    //DONT UPDATE FILTER TYPE
                    $sql="UPDATE equipment SET filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $arUnitID[$num] ."'";
                }
                else
                {
                    $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filter_type='".$arFilterType[$num]."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $arUnitID[$num] ."'";
                }
                if (mysqli_query($con, $sql)) 
                {
                    //echo "Next filter due date updated successfully to ". $effectiveDate."<br>";
                    //echo "extrating filters...........<br>";
                    if(strcmp($arFilterType[$num], "no_filters_used") < 0)
                    {
                        //echo "arFiltersUsed[num]=".$arFiltersUsed[$num]."<br>";
                        ExtractFilters($arFiltersUsed[$num], $arFilterType[$num]);
                    }
                }
                //echo "Unit ID:".$arUnitID[$num]." Rotation:".$arRotation[$num]." Next due date:".$effectiveDate." num=".$num."<br>";
            }
    }

//COMPLETE SINGLE TASK----------------------------------------------------
if(strcmp($Action,"completetask")==0 && isset($_POST['filter_rotation'])&&isset($_POST['unit_id'])) {

$Rotation=$_POST['filter_rotation'];
$effectiveDate=getNextDueDate($Rotation);
      // echo "duedate=".$effectiveDate;
    if(isset($_POST['unit_id'])){$UnitID = $_POST['unit_id'];}
        $FiltersLastChanged = date("Y-m-d")." [".$UserName."]";
        if(isset($_POST['filter_rotation'])){$Rotation = $_POST['filter_rotation'];}
        $ActionDone = "changed filters";
        if(isset($_POST['filter_rotation'])){$Rotation=$_POST['filter_rotation'];}
        if(isset($_POST['filters_used'])){$FiltersUsed=$_POST['filters_used'];}
        if(isset($_POST["filter_type"])){$FilterType=$_POST['filter_type'];}
        if(isset($_POST["unit_id"])){$UnitId= $_POST["unit_id"];}
        if(strcmp($FilterType, "no_filters_used")==0){
            //DONT UPDATE FILTER TYPE
        $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";
        }else{
        $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filter_type='".$FilterType."', filters_last_changed='" . 
        $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";

        }
        if (mysqli_query($con, $sql)) {
            //echo "Next filter due date updated successfully to ". $effectiveDate."<br>";
            if(strcmp($FilterType, "no_filters_used")==0){
                //DONT UPDATE INVENTORY
            }else{
            //echo "filters used:".$FiltersUsed. " type=".$FilterType;
         ExtractFilters($FiltersUsed, $FilterType);
            }

        } else {
            echo "Error updating equipment record: " . mysqli_error($con);
        }
}

//COMPLETE TASK -GET METHOD---------------------------------------------------------------
if(strcmp($Action,"completetask_get")==0 && isset($_GET['filter_rotation'])) 
{
   $Rotation=$_GET['filter_rotation'];
   $effectiveDate=getNextDueDate($Rotation);

    if(isset($_GET['unit_id'])){$UnitID = $_GET['unit_id'];}
    $FiltersLastChanged = date("Y-m-d")." [".$UserName."]";
    if(isset($_GET['filter_rotation'])){$Rotation = $_GET['filter_rotation'];}
    if(isset($_GET['filter_type'])){$FilterType = $_GET['filter_type'];}
    $Rotation=$_GET['filter_rotation'];
    $FiltersUsed=$_GET['filters_used'];
    if(isset($_GET["unit_id"])){$UnitId= $_GET["unit_id"];}
    $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";
    if (mysqli_query($con, $sql)) 
         {
            echo "Next filter due date updated successfully to ". $effectiveDate."<br>";
            //echo "extrating filters...........<br>";
         ExtractFilters($FiltersUsed, $FilterType);
             
        } 
        else 
        {
            echo "Error updating record: " . mysqli_error($con);
        }
    redirect("ListEquipment.php");
}

//GET ALL FILTER TYPES INTO AN ARRAY
$AllFilterTypes = array();
$sql="SELECT type FROM filter_types;";
if (!$result = $con->query($sql)) {
    die ('There was an error running query[' . $con->error . ']');
}
while ($row = $result->fetch_assoc()) {
array_push($AllFilterTypes, $row["type"]);
}
//print_r($AllFilterTypes);

function SearchArray($search_value, $array, $id_path)
{
  
        // Iterating over main array
        foreach ($array as $key1 => $val1) {
      
            $temp_path = $id_path;
              
            // Adding current key to search path
            array_push($temp_path, $key1);
      
            // Check if this value is an array
            // with atleast one element
            $found=false;
            if(is_array($val1) and count($val1)) {
      
                // Iterating over the nested array
                foreach ($val1 as $key2 => $val2) {
                    //echo "key=".$key2. " val=".$val2."<br>";
                    if($key2 == "amount" && $found == true) {return $val2;}
                    if($key2 == "size" && $val2 == $search_value) {
                        $found = true;
                        }
                        else
                        {
                            $found = false;
                        }
                        //echo "<div style='background-color:black;color:white;'>found:".$search_value."</div><br>";
                       if($found == true && $key2 == "amount") {
                        //echo "found:".$search_value."<br>";
                        $found=false;
                        }
                    
                }
            }

        }
          
        return null;
    
}
?><div style="width:100%;text-align:center;font-size:30px;" class="bg-success text-white">Tasks</div><?php
//CANCEL TASK
if(strcmp($Action,"canceltask")==0) 
 {
if(isset($_POST["unit_id"])){$UnitID=$_POST["unit_id"];}
    $sql="UPDATE equipment SET assigned_to = '' WHERE _id = '". $UnitID ."'";
       if (mysqli_query($con, $sql)) 
                {
                    //echo ";unit canceled ". 
                }
}
//GET ALL TASKS--------------------------------------------------------------------------
if($Action == "getalltasks" || strcmp($Action,"completetask")==0 || $Action == "" || strcmp($Action,"canceltask")==0) {
    $arAllData = array();
    $arDataRow = array();
     $sql = "SELECT * FROM equipment WHERE assigned_to = '".$UserName."';";
//echo $sql;
    if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error  . ']');
    }
    $row_cnt = $result->num_rows;
 
    $UnitID = "00";
    if(isset($_GET["unitid"])){
      $UnitID= $_GET["unitid"];
    }
    if($row_cnt > 0){
  
?>

<Table class="tableTasks"  id='tblShowAllTasks'><tr><td>Cancel task</td>
<td   width='800'>Unit name</td>
<td  width='800' >Unit location</td>
<td  >Filter size</td>
<td  >Filter type</td>
<td  id="tdComplete">Complete task</td>
</tr>
<?php
    if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error  . ']');
    }
    $n=0;
    $arFilterSizes = array();
    $arFiltersNeeded=array();
    $mystring="";
    $x=0;
    
     while ($row = $result->fetch_assoc()) 
        {
            //CREATE ARRAY OF FILTER SIZES
            $f=$row["filter_size"];
            $numOfSets= substr_count($f,'(');
            if($numOfSets == 1)
                {
                    $pos = strpos($f, ")");
                    $filterSize = substr($f, $pos+1);  
                    $Amount = substr($f,1,$pos-1);
                    $arFiltersNeeded = array("size"=>$filterSize, "amount"=>$Amount);
                    array_push($arFilterSizes, $arFiltersNeeded);
                }
            if($numOfSets == 2)
                {
                    $lastpos = strrpos($f, ")", 0);
                    $pos = strpos($f, "(");
                    $filt1 = substr($f, $pos-strlen($f),$lastpos-2 );
                    //echo "set1=" . $filt1. "<br>";
                    $filt2 = substr(strrchr($f, "("), 0);
                    //echo "set2=" . $filt2. "<br>";
                    //for First filter set
                    $pos = strpos($filt1, ")");
                    $filterSize = substr($filt1, $pos+1);  
                    $Amount = substr($filt1,1,$pos-1);
                    $arFiltersNeeded = array("size"=>$filterSize, "amount"=>$Amount);
                    array_push($arFilterSizes, $arFiltersNeeded);
                    //for Second filter set
                    $pos = strrpos($filt2, ")");
                    $filterSize = substr($filt2, $pos+1);  
                    $Amount = substr($filt2,1,$pos-1);
                    $arFiltersNeeded = array("size"=>$filterSize, "amount"=>$Amount);
                    array_push($arFilterSizes, $arFiltersNeeded);
                    //echo "set2 size=".$filterSizeSent." qt2=".$Amount."<br>";
                    
                }
               
        ?>

            <tr><td><div style="">
                     <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="<?php echo "frmCancel".$row["_id"] ?>">
                    <input type="hidden" name="username" value="<?php echo $UserName ?>">
                    <input type="hidden" name="action" value="canceltask">
                    <input type="hidden" name="unit_id" value="<?php echo $row["_id"] ?>">
                    <input type="hidden" name="unit_name" value="<?php echo $row["unit_name"] ?>">
                    
                    <img src="images/cancel.png" title="Cancel the task for <?php echo $row["unit_name"] ?>" style="width: 20%;height: auto;margin-top:8px;" onclick="myConfirmBox('Do you wish to cancel this task?', 'frmCancel<?php echo $row["_id"] ?>')"></td>
                     </form>
                     </div></td><td>
                    <a href="?UnitIdForInfo=<?php echo $row["_id"] ?>"><?php echo $row["unit_name"] ?></a>
		<?php
              
                     if(strcmp($row["_id"], $UnitIdForInfo) == 0)
						{
							 echo $txtServerMsg;
                            ?>
                            <tr><td><button type='button' class='btn btn-primary' style='width:170px;' onclick="document.getElementById('tblUnitInfo').style.display='none';">Close</button></td><td><a href='webEditUnit.php?_id=<?php echo $row["_id"] ?>&username=<?php echo $UserName ?>"><button type='button' class='btn btn-warning' style='width:180px;'>EDIT UNIT</button></a></td></tr></table>
                            <?php
						}
         ?>
                     </td>
                     
                     <td><?php echo $row["location"] ?></td>
                     <td><?php echo $row["filter_size"] ?></td>
                    
                    <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="POST" name="frmName<?php echo $row["_id"] ?>" id="frmCompleteTask<?php echo $row["_id"] ?>">
                    <td class="tableTasks" style='text-align:left;' ><div style="width: 100%; height: 100%;">

                    <div class="selectWrapper">
                    <select id="slctFilterType<?php echo $row["_id"] ?>" title="Select the filter type that was installed into unit <?php echo $row["unit_name"] ?>" name="filter_type" class="ftype" onchange="setFilterTypes('<?php echo $row["_id"] ?>');">
                    <option value="select" selected>select</option>
                    <option value="no_filters_used">no filter used</option>
                    <?php
                    foreach ($AllFilterTypes as $value) {
                        echo "<option value='".$value."'>".$value."</option>";
                    }
            ?>
                    </select></div></td>
                    <?php
                    array_push($arAllData, array("unit_id[]"=>$row["_id"], "filter_rotation[]"=>$row["filter_rotation"],"filters_used[]"=>$row["filter_size"]));
                    ?>
					<td><img id="img<?php echo $row["_id"] ?>" src="images/dwcheckyes.png" title="Complete task for <?php echo $row["unit_name"] ?>" onclick="CompleteFilters(<?php echo $row["_id"] ?>);" style="text-align:center;width:30px;height:30px;"></td>
                    <input type="hidden" name="action" value="completetask">
                    <input type="hidden" name="username" value="<?php echo $UserName ?>">
                    
                    <input type="hidden" name="unit_id" value="<?php echo $row["_id"] ?>">
                    <input type="hidden" name="filters_used" value="<?php echo $row["filter_size"] ?>">
                    <input type="hidden" name="filter_rotation" value="<?php echo $row["filter_rotation"] ?>">
                    <input type="hidden" name="filters_due" value="<?php echo $row["filters_due"] ?>">
                </form>
                <div class="alert_box" role="alert" id="alert">
  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </svg>
  <div class="alert-box">
    <h1>Select the filter type used and try again</h1><button type="button" style="margin-left: auto;
  margin-right: auto;" class="btn btn-warning d-flex align-items-center" onclick="document.getElementById('alert').style.display='none';">OK</button>
  </div>
</div>
                </td></tr>
                <?php
                $n=$n+1;
        }
  
        $ThisUnitID="";
        //FOR SUBMIT ALL TASKS BUTTON 
        echo "<form id='frmCompleteAll' action='". $_SERVER["SCRIPT_NAME"] ."' method='post'>";
        echo "<input type='hidden' name='username' value='".$UserName."'>";
        echo "<input type='hidden' name='action' value='complete_all_tasks'>";
        $keys = array_keys($arAllData);
            for($i = 0; $i < count($arAllData); $i++) {
                foreach($arAllData[$keys[$i]] as $key => $value) {
                    //echo "key=".$key." value=".$value."<br>";
                    if(strcmp($key,'unit_id[]')==0){$ThisUnitID=$value;}
                    echo "<input type='hidden' name='".$key."' value='".$value."'>";
                }
                echo "<input type='hidden' id='hdnFtype".$ThisUnitID."' name='filter_type[]' value='select'>";
            }
                     echo "</form></div><tr><td></td><td></td><td></td><td></td><td style='text-align:center;'>";
                    ?> <button class="btn btn-secondary" style="width: fit-content;" onclick="ConfirmBox('Complete all tasks?', 'frmCompleteAll');" id='btnCompleteAllTasks' title="This button is disabled until filters for all tasks are selected above" disabled>Complete All Tasks</button></td> <?php
         }
        else
        {
            echo "<tr><td class='tableTasks'>
            <td class='tableTasks' align='center'><div style='width: 100%; height: 100%; color: red; font-size:40px;font-weight: bold;background-color:#3FBF3F;text-align:center;'>".$UserName. " you have no tasks
            </div></td></tr>";
        }
    }
    ?>
    </table>
    <table style="width:500px;margin-left:17vw;margin-right:auto;">
    <tr><th></th><th id="divFiltersNeeded" style="font-weight:bold;text-align: center;"></th></tr>
    <tr><div id='divFiltersCounted'>
<td id='divAmount'></td><td id='divSize' style="width: 100%;height:fill-content;overflow: auto;color:black;"></td>
<div id='divFiltersCounted'>
</tr><tr><td></td><td><?php if($row_cnt > 0){echo "<button id='btnPrint' type='button' class='btn btn-success' style='vertical-align:top;font-size : 20px; width: 200px; height:70px;' onClick='printme();'>PRINT</button>";} ?></td></tr></table>
    <div id='divAllfilters' style='display:none;color:black;background-color:green;'>
    <?php
    $x = 0;
        $ThisSize="";
        foreach ($arFilterSizes as $key => $value) {
            foreach ($value as $sub_key => $sub_val) {
                {
                    if($sub_key == "size")
                        { 
                            echo "<input type='text' title='void' name='fsize' id='fsize".$x."' value='$sub_val'>";
                            $ThisSize = $sub_val;
                        }
                    if($sub_key == "amount")
                        {
                            echo "<input type='text' title='$ThisSize' name='famount' id='famount".$x."' value='$sub_val'> <br>";
                            $x=$x+1;
                            echo "<br>";
                        }
                }
            }
        }
?>

    <?php

//UPDATE FILTERS----------------------------------------------------------------------------------------------
if(strcmp($Action,"updatefilters")==0){
	if(isset($_GET["filter_size"])){$FilterSize=$_GET["filter_size"];}
    if(isset($_GET["filter_type"])){$FilterType=$_GET["filter_type"];}
	echo "<style='color:white;'>fsize=".$FilterSize;
	ExtractFilters($FilterSize, $FilterType);
}
function CkIfOddNumber($number){
    if($number % 2 == 0){
       return "even"; 
    }
    else{
        return "odd";
    }
}
function IfTwoSets($f)
{
   $numOfSets= substr_count($f,'(');
   $pos = strpos($f, ")");
   $filterSizeSent = substr($f, $pos+1);  
   $filtersQtyUsed = substr($f,1,$pos-1);
   $filtarray=array("","");

if($numOfSets == 1)
   {
      $pos = strpos($f, ")");
      $filterSizeSent = substr($f, $pos+1);  
      $filtersQtyUsed = substr($f,1,$pos-1);
   }
   
if($numOfSets == 2)
   {
      $lastpos = strrpos($f, ")", 0);
      $pos = strpos($f, "(");
      $filt1 = substr($f, $pos-strlen($f),$lastpos-2 );
      $filt2 = substr(strrchr($f, "("), 0);
      $pos = strpos($filt1, ")");
      $filt1 = substr($filt1, $pos + 1, strlen($filt1) - $pos);
      $filt2 = substr($filt2, $pos + 1, strlen($filt2) - $pos);
     array_push($filtarray,$filt1,$filt2);
   }
 return $filtarray;

}



?>