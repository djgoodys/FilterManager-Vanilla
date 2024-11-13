<?php
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
if(session_id() == "")
{
session_start();
//echo "starting session<br>";
}
else
{
echo "no session<br>";
}
include 'dbDemo_connect.php';
include 'CustomConfirmBox.css';
//echo "all GET vars:".var_dump($_GET)."<br>";
//echo "all POST vars:".var_dump($_POST)."<br>";
$Action = "";
$Theme="";
if (isset($_COOKIE["theme"])) {
    $Theme = $_COOKIE["theme"];
    if($Theme == "Dark-tdPlain"){
      $FontColor = "white";
    }
    else
    {
    $FontColor = "black";
    }
}

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
         if(isset($_COOKIE["user"])){
   //$UserName=$_COOKIE["user"];
   //echo "cookie ******user*****=".$UserName;
}
if(isset($_COOKIE["username"]))
         {
            $UserName=$_COOKIE["username"];
         //echo "cookie username=".$UserName;
         }
//echo "UserName=".$UserName;
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
    //alert('setFilterType, hiddenInputNumber='+hiddenInputNumber);
    e=document.getElementById('slctFilterType'+hiddenInputNumber);
    document.getElementById('hdnFtype'+hiddenInputNumber).value = e.options[e.selectedIndex].text;
    areFilterTypesSet();
}
</script>
<script>
function areFilterTypesSet(){
    inputs = document.getElementsByClassName('ftype');
    document.getElementById("btnCompleteAllTasks").disabled = false;
    for(index = 0; index < inputs.length; ++index) {
            if(inputs[index].value == "select"){
                //alert("You must set all filter types first")
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
        alert("Select filter type installed first.")
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
        alert("Select filter type used and try again.")
        //mybutton.disabled = true;
    }

}
</script>
<style>
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
    border: 1px solid #666;
    background-color: #fff;
}

.alert_box h1 {
    font-size: 1em;
    margin: 0;
    background-color: #1560bd;
    color: #fff;
    border-bottom: 1px solid #000;
    padding: 2px 0 2px 5px;
}

.alert_box p {
    font-size: 1em;
    height: 50px;
    margin-left: 55px;
    padding-left: 5px;
}

.close_btn {
    width: 70px;
    font-size: 0.7em;
    display: block;
    margin: 5px auto;
    padding: 7px;
    border: 0;
    color: #fff;
    background-color: #1560bd;
    border-radius: 3px;
    cursor: pointer;
}
#option.myoption {
    background-color: #cc0000; 
    font-weight: bold; 
    font-size: 12px; 
    color: white;
}

.Dark-tdOverDue{
background-color:red;
color:white;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;
padding: 2px;
white-space:nowrap;
}
.Dark-tdPlain{
background-color:black;
color:white;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;black;
padding: 2px;
white-space:nowrap;
}
.Light-tdOverDue{
background-color:black;
vertical-align: middle;
color:red;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;
padding: 2px;
white-space:nowrap;
}
.tdUnitInfo{
   padding:0px; 
   height:10px; 
   background-color:blue; 
   color:white;"
}
.Light-Plain{
tr.hover{ background-color: #FFD700;}
}
.Light-tdPlain{
background-color:white;
color:black;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;black;
padding: 2px;
white-space:nowrap;
}

</style>

<style>
a:link {color:<?php echo $FontColor ?>;}
a:visited {color:<?php echo $FontColor ?>;}/* visited link */
a:hover {color:<?php echo $FontColor ?>;}   /* mouse over link */
a:active {color:<?php echo $FontColor ?>;}
</style>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

<?php
//print_r($_POST);

if(strcmp($Theme,"Dark-tdPlain")==0)
{
   echo  "<body style='background-color: black;'>";
   $TDstyle="Dark-tdPlain";
}
if(strcmp($Theme,"Light-tdPlain")==0)
{
   echo  "<body style='background-color: white;'>";
    $TDstyle="Light-tdPlain";
}
if(strcmp($Theme, "")==0)
{
//echo "no theme detected<br>";
   $TDstyle = "Light-tdPlain";
}
//echo "theme=".$TDstyle;


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
$UnitNameForInfo = "";
if(isset($_GET["unit_nameforinfo"])) {
    $UnitNameForInfo = $_GET["unit_nameforinfo"];
    $sql = "SELECT unit_name, location, area_served, filter_size, filter_type, filters_due, filter_rotation, belts, notes, filters_last_changed FROM equipment WHERE unit_name ='" . $_GET["unit_nameforinfo"] . "';";

    if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error . ']');
        $txtServerMsg = "unit not found, error running query";
    }
    $row_cnt = $result->num_rows;
    $txtServerMsg="number of rows=" . $row_cnt;

    if ($row_cnt > 0) {
        $display = "visible";
        while ($row = $result->fetch_assoc()) {
            $txtServerMsg = "Unit name: " . $row["unit_name"] . "\nLocation: " . $row["location"]. "\nAreaServed: " . $row["area_served"]. "\nFilters: " . $row["filter_size"]. "\nFilter type: " . $row["filter_type"]."\nFilters due: " . $row["filters_due"]. "\nRotation: " . $row["filter_rotation"]. "\nFilters last changed: " . $row["filters_last_changed"]."\nNotes: " . $row["notes"];
        }
    }
}

//COMPLETE ALL TASKS---------------------------------------------------------
if(strcmp($Action,"complete_all_tasks")==0) {

//print_r($_POST);
$arFiltersDue = array();
$arFiltersUsed = array();
$arTaskID = array();
$arRotation = array();
$arUnitID = array();
$arFilterType = array();
$num = 0;
foreach ($_POST["filter_rotation"] as $Rotation){
    array_push($arRotation, $Rotation);
    //$num = $num + 1;
}
foreach ($_POST["unit_id"] as $unitID) {
    array_push($arUnitID,$unitID);
}
foreach ($_POST["filter_type"] as $FilterType) {
    array_push($arFilterType,$FilterType);
}
foreach ($_POST["task_id"] as $TaskID) {
    array_push($arTaskID,$TaskID);
}
foreach ($_POST["filters_used"] as $FiltersUsed) {
    array_push($arFiltersUsed,$FiltersUsed);
}
//foreach ($_POST["filters_due"] as $FiltersDue) {
 //   array_push($arFiltersDue,$FiltersDue);
//}
$FiltersLastChanged = date("Y-m-d");
foreach ($_POST["task_id"] as $TaskID) {
    $effectiveDate=getNextDueDate($arRotation[$num]);
    //echo "UnitID=".$unitID[$num]." TaskID".$arTaskID[$num]." Filters used:".$arFiltersUsed[$num]."  Rotation:".$arRotation[$num]." next due date:".$effectiveDate."<br>";
  $FilterTypeUsed;
    $query = "UPDATE tasks SET task_date_completed='" . $FiltersLastChanged . "', action='changed filters' WHERE task_id='" . $arTaskID[$num] . "';";
        if (mysqli_query($con, $query)) {
            echo "All Tasks completed<br>".$UserName. " you have no more tasks.";
            ?>
            <tr><td class="<?php echo $TDstyle ?>"><form action='ListEquipment.php' method='post'> 
        <input type="submit" value="UNIT LIST" onclick="goHome()" 
        style="font-size : 20px; width: 200px; height:70px;color:gold; background-color:green;" id='btnGoToUnitlist' name='btnGoUnitList'>
            </td><input type='hidden' name='action' value='listUnits'>
            <input type="hidden" name="username" value="<?php echo $UserName ?>">
            </form></td>
            <td class="<?php echo $TDstyle ?>"><form action='webTask_history.php' method='post'><input type='hidden' id='cookie' name='cookie' value='reset'>
            <input type="hidden" name="username" value="<?php echo $UserName ?>"><input type='submit' style="background-color:green;color:gold;font-size : 20px; width: 200px; height:70px;" value='TASK HISTORY'></form>
            </td><td></td></tr>
            <?php
        } else {
            echo "Error updating task record: " . mysqli_error($con);
        }
        if(isset($_POST["unit_id"])){$UnitId= $_POST["unit_id"];}
        if(strcmp($arFilterType[$num], "no_filters_used")==0){
            //DONT UPDATE FILTER TYPE
            $sql="UPDATE equipment SET filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $arUnitID[$num] ."'";
        }else{
        $sql="UPDATE equipment SET filter_type='".$arFilterType[$num]."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $arUnitID[$num] ."'";
        }
        if (mysqli_query($con, $sql)) {
            //echo "Next filter due date updated successfully to ". $effectiveDate."<br>";
            //echo "extrating filters...........<br>";
            if(strcmp($arFilterType[$num], "no_filters_used") < 0){
         ExtractFilters($arFiltersUsed[$num], $con);}
         $num = $num + 1;
}
}
//echo "Unit ID:".$unitID." Rotation:".$rotation." Next due date:".$effectiveDate;

}

//COMPLETE SINGLE TASK----------------------------------------------------
if(strcmp($Action,"completetask")==0 && isset($_POST['filter_rotation'])&&isset($_POST['filters_due'])&&isset($_POST['task_id'])) {

$Rotation=$_POST['filter_rotation'];
$effectiveDate=getNextDueDate($Rotation);
      // echo "duedate=".$effectiveDate;
    if (isset($_POST['task_id'])) {$RecID = $_POST['task_id'];}
    if(isset($_POST['unit_id'])){$UnitID = $_POST['unit_id'];}
        $FiltersLastChanged = date("Y-m-d");
        if(isset($_POST['filters_due'])){$FiltersDue = $_POST['filters_due'];}
        if(isset($_POST['filter_rotation'])){$Rotation = $_POST['filter_rotation'];}
        $ActionDone = "changed filters";
        $Rotation=$_POST['filter_rotation'];
        $FiltersUsed=$_POST['filters_used'];
        $FilterType=$_POST['filter_type'];
    
        $query = "UPDATE tasks SET task_date_completed='" . $FiltersLastChanged . "', action='" .$ActionDone. "' WHERE task_id='" . $RecID . "';";
        if (mysqli_query($con, $query)) {
            echo "Task complete<br>";
        } else {
            echo "Error updating task record: " . mysqli_error($con);
        }
        if(isset($_POST["unit_id"])){$UnitId= $_POST["unit_id"];}
        if(strcmp($FilterType, "no_filters_used")==0){
            //DONT UPDATE FILTER TYPE
        $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";
        }else{
        $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filter_type='".$FilterType."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";
        }
        if (mysqli_query($con, $sql)) {
            echo "Next filter due date updated successfully to ". $effectiveDate."<br>";
            if(strcmp($FilterType, "no_filters_used")==0){
                //DONT UPDATE INVENTORY
            }else{
         ExtractFilters($FiltersUsed, $con);
            }
         ?>
         <!--<a href="WebTasks.php?action=getalltasks&username=<?php echo $UserName ?>"><BUTTON style="height:100px;width:200px;color:green;font-size:20px;"><<< Back to Tasks</BUTTON></a>-->
         <?php
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
    $FiltersLastChanged = date("Y-m-d");
    if(isset($_GET['filters_due'])){$FiltersDue = $_GET['filters_due'];}
    if(isset($_GET['filter_rotation'])){$Rotation = $_GET['filter_rotation'];}
    $Rotation=$_GET['filter_rotation'];
    $FiltersUsed=$_GET['filters_used'];
    if(isset($_GET["unit_id"])){$UnitId= $_GET["unit_id"];}
    $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";
    if (mysqli_query($con, $sql)) 
         {
            echo "Next filter due date updated successfully to ". $effectiveDate."<br>";
            //echo "extrating filters...........<br>";
         ExtractFilters($FiltersUsed, $con);
             
        } 
        else 
        {
            echo "Error updating record: " . mysqli_error($con);
        }
    redirect("ListEquipment.php");
}

if(strcmp($Action,"canceltask")==0)
{
if(isset($_POST["task_id"])|| isset($_GET["task_id"]))
   {
        if(isset($_POST["task_id"])){$RecID = $_POST['task_id'];}
        if(isset($_POST["unit_id"])){$UnitID = $_POST['unit_id'];}
        if(isset($_GET["task_id"])){$RecID = $_GET['task_id'];}
        if(isset($_GET["unit_id"])){$UnitID = $_GET['unit_id'];}
        $today=date('Y-m-d');
        $query = "UPDATE tasks SET task_date_completed='" . $today . "', action='canceled' WHERE task_id='" . $RecID . "';";
        if (mysqli_query($con, $query)=== TRUE) 
            {
               echo "canceled task successfully ";
            }
            else
            {
               echo $con->error;
            }
        $query = "UPDATE equipment SET assigned_to='' WHERE _id='" . $UnitID . "';";
        if (mysqli_query($con, $query)) 
            {
       
               ?>
               <!--<br><a href="WebTasks.php?action=getalltasks&username=<?php echo $UserName ?>"><BUTTON style="height:100px;width:200px;color:green;font-size:20px;"><<< Back to Tasks</BUTTON></a> -->
               <?php
            }
     }  
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


//GET ALL TASKS--------------------------------------------------------------------------
if($Action == "getalltasks" || strcmp($Action,"completetask")==0 || $Action == "" || strcmp($Action,"canceltask")==0) {
    $arAllData = array();
    $arDataRow = array();
     $sql = "SELECT task_id,employee_name,unit_name,unit_location,filters_needed,task_date_created,unit_id,filter_rotation,filters_due FROM tasks WHERE action='new' AND employee_name='".$UserName."';";
    if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error  . ']');
    }
    $row_cnt = $result->num_rows;
    if($row_cnt <= 0){
     // echo $UserName . " you have no tasks.";
    }
    $UnitID = "00";
    if(isset($_GET["unitid"])){
      $UnitID= $_GET["unitid"];
    }
    if($row_cnt > 0){
  
?>
<Table border='1' id='tblShowAllTasks' width='60%' align='left'><tr><td class="<?php echo $TDstyle ?>">Cancel task</td>
<td class="<?php echo $TDstyle ?>" width='800'>Unit name
</td>
<td class="<?php echo $TDstyle ?>" width='800'>Unit location
</td><td class="<?php echo $TDstyle ?>">Filter size</td>
<td class="<?php echo $TDstyle ?>">Filter type</td>
<td class="<?php echo $TDstyle ?>">Complete task</td>
</tr>
<?php
    if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error  . ']');
    }
    $n=0;
     while ($row = $result->fetch_assoc()) 
        {
        ?>

            <tr><td class="<?php echo $TDstyle ?>" align="center"><div style="width: 100%; height: 100%;">
                     <form action="WebTasks.php" method="post" id="<?php echo "frmCancel".$row["task_id"] ?>">
                    <input type="hidden" name="username" value="<?php echo $UserName ?>">
                    <input type="hidden" name="action" value="canceltask">
                    <input type="hidden" name="unit_id" value="<?php echo $row["unit_id"] ?>">
                    <input type="hidden" name="unit_name" value="<?php echo $row["unit_name"] ?>">
                    <input type="hidden" name="task_id" value="<?php echo $row["task_id"] ?>">
     
        <!--
        <div class="box-background">
        <div class="box">
            Do you want to download this file?
            <div>
                <button> class="btn green">Yes</button>
                <button> class="btn red">No</button>
            </div>
        </div>
        </div>
        -->
                    <img src="cancel.jpg" onclick="myConfirmBox('Do you wish to cancel this task?', 'frmCancel<?php echo $row["task_id"] ?>')"></td>
                     </form>
                     </div></td><td class="<?php echo $TDstyle ?>">
                    <a href="WebTasks.php?username=<?php echo $UserName ?>&unit_nameforinfo=<?php echo $row["unit_name"] ?>&unitid=<?php echo $row["unit_id"] ?>"><?php echo $row["unit_name"] ?></a>
		<?php
              
                     if(strcmp($row["unit_name"], $UnitNameForInfo) == 0)
						{
							 echo "<br><textarea style='background-color:green;color:white;font-size:20px;'  rows='9' cols='35' id='txtinfo'". $row["task_id"] ." ".$display .">"
							. $txtServerMsg . "</textarea>";
						}
         ?>
                     </td>
                     
                     <td class="<?php echo $TDstyle ?>"><?php echo $row["unit_location"] ?></td>
                     <td class="<?php echo $TDstyle ?>"><?php echo $row["filters_needed"] ?></td>
                    
                    <form action="WebTasks.php" method="POST" name="frmName<?php echo $row["task_id"] ?>" id="frmCompleteTask<?php echo $row["task_id"] ?>">
                    <td class="<?php echo $TDstyle ?>" align="center"><div style="width: 100%; height: 100%;">
                    <select  id="slctFilterType<?php echo $row["task_id"] ?>" name="filter_type" class="ftype" onchange="setFilterTypes('<?php echo $row["task_id"] ?>');">
                    <option value="select" selected>select</option>
                    <option value="no_filters_used">no filters used</option>
                    <?php
                    foreach ($AllFilterTypes as $value) {
                        echo "<option value='".$value."'>".$value."</option>";
                    }
                    array_push($arAllData, array("task_id[]"=>$row["task_id"], "unit_id[]"=>$row["unit_id"], "filter_rotation[]"=>$row["filter_rotation"],"filters_used[]"=>$row["filters_needed"]));
            ?>
                    </select></td>
					<td class="<?php echo $TDstyle ?>" align="center"><img id="img<?php echo $row["task_id"] ?>" src="dwcheckyes.png" onclick="CompleteFilters(<?php echo $row["task_id"] ?>);" style="width:30px;height:30px;"></td>
                    <input type="hidden" name="action" value="completetask">
                    <input type="hidden" name="username" value="<?php echo $UserName ?>">
                    <input type="hidden" name="task_id" value="<?php echo $row["task_id"] ?>">
                    <input type="hidden" name="unit_id" value="<?php echo $row["unit_id"] ?>">
                    <input type="hidden" name="filter_rotation" value="<?php echo $row["filter_rotation"] ?>">
                    <input type="hidden" name="filters_due" value="<?php echo $row["filters_due"] ?>">
                    <input type="hidden" name="filters_used" value="<?php echo $row["filters_needed"] ?>">
                </form>
                </td></tr>
                <?php
                $n=$n+1;
        }
        //FOR SUBMIT ALL TASKS BUTTON 
        echo "<form id='frmCompleteAll' action='WebTasks.php' method='post'>";
        echo "<input type='hidden' name='username' value='".$UserName."'>";
        echo "<input type='hidden' name='action' value='complete_all_tasks'>";
        $thisTaskID="";
        $keys = array_keys($arAllData);
            for($i = 0; $i < count($arAllData); $i++) {
                foreach($arAllData[$keys[$i]] as $key => $value) {
                    //echo "key=".$key." value=".$value."<br>";
                    if(strcmp($key,'task_id[]')==0){$thisTaskID=$value;}
                    echo "<input type='hidden' name='".$key."' value='".$value."'>";
                }
                echo "<input type='hidden' id='hdnFtype".$thisTaskID."' name='filter_type[]' value='select'>";
            }
                     echo"</form></div><tr><td></td><td></td><td></td><td></td><td>";
                    ?> <button onclick="ConfirmBox('Complete all tasks?');" id='btnCompleteAllTasks' title="Get all filters selected above before clicking" disabled>Complete All Tasks</button></td> <?php
         }
        else
        {
            echo "<tr><td class='<?php echo $TDstyle ?>'>
            <td class='<?php echo $TDstyle ?>' align='center'><div style='width: 100%; height: 100%; color: red; font-size:40px;font-weight: bold;background-color:#3FBF3F;'>".$UserName. " you have no tasks</div>
            </td><td class='<?php echo $TDstyle ?>'></td>
            <td class='<?php echo $TDstyle ?>'></td></tr>";
        }
    }
    ?>
        <tr><td class="<?php echo $TDstyle ?>"><form action='ListEquipment.php' method='post'> 
        <input type="submit" value="UNIT LIST" onclick="goHome()" 
        style="font-size : 20px; width: 200px; height:70px;color:gold; background-color:green;" id='btnGoToUnitlist' name='btnGoUnitList'>
            </td><input type='hidden' name='action' value='listUnits'>
            <input type="hidden" name="username" value="<?php echo $UserName ?>">
            </form></td>
            <td class="<?php echo $TDstyle ?>"><form action='webTask_history.php' method='post'><input type='hidden' id='cookie' name='cookie' value='reset'>
            <input type="hidden" name="username" value="<?php echo $UserName ?>"><input type='submit' style="background-color:green;color:gold;font-size : 20px; width: 200px; height:70px;" value='TASK HISTORY'></form>
            </td><td><form action = "PrintTasks.php" method="POST"><input type='submit' style="background-color:green;color:gold;font-size : 20px; width: 200px; height:70px;" value='PRINT...'>
            <input type="hidden" name="action" value="getalltasks"><input type="hidden" name="username" value="<?php echo $UserName ?>"></form></td></tr>
    </table>
    
    <?php


//UPDATE FILTERS----------------------------------------------------------------------------------------------
if(strcmp($Action,"updatefilters")==0){
	if(isset($_GET["filter_size"])){$FilterSize=$_GET["filter_size"];}
	echo "<style='color:white;'>fsize=".$FilterSize;
	ExtractFilters($FilterSize, $con);
}
function IfTwoSets($f)
{
  
   //echo "number of filter sets=".substr_count($f, '(')."<br>";
   $numOfSets= substr_count($f,'(');
   //echo "filter string=" . $f . "<br>";
   $pos = strpos($f, ")");
   //echo "found at=". $pos ."<br>";
   $filterSizeSent = substr($f, $pos+1);  
   $filtersQtyUsed = substr($f,1,$pos-1);
   $filtarray=array("","");

if($numOfSets == 1)
   {
      $pos = strpos($f, ")");
      //echo "found at=". $pos ."<br>";
      $filterSizeSent = substr($f, $pos+1);  
      $filtersQtyUsed = substr($f,1,$pos-1);
      //echo "1 set :".$filterSizeSent . "<br>";
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
      $filt1 = substr($filt1, $pos + 1, strlen($filt1) - $pos);
      $filt2 = substr($filt2, $pos + 1, strlen($filt2) - $pos);
      //echo "set1=" . $filt1. " set2=" . $filt2."<br>"
     array_push($filtarray,$filt1,$filt2);
   }
 return $filtarray;

}
function ExtractFilters($FiltersUsed, $con) 
{
   $sql = "SELECT filter_size, filter_count, filter_type FROM filters;";
   $filters = array();
    if ($result = $con->query($sql)) 
    {
         while ($row = $result->fetch_assoc()) 
            {
               //echo $row["filter_size"] ."= ".$row["filter_count"]."<br>"; 
               $filters[$row["filter_size"]] = $row["filter_count"];  
            }
    }
                              
   $f=$FiltersUsed;
   //echo "number of filter sets=".substr_count($f, '(')."<br>";
   $numOfSets= substr_count($f,'(');
   //echo "f sent=" . $f . "<br>";
   $pos = strpos($f, ")");
   //echo "found at=". $pos ."<br>";
   $filterSizeSent = substr($f, $pos+1);  
   $filtersQtyUsed = substr($f,1,$pos-1);
   //echo "size sent=".$filterSizeSent . " qtySent=". $filtersQtyUsed. "<br>";

if($numOfSets == 1)
   {
      $pos = strpos($f, ")");
      //echo "found at=". $pos ."<br>";
      $filterSizeSent = substr($f, $pos+1);  
      $filtersQtyUsed = substr($f,1,$pos-1);
      //echo "size sent=".$filterSizeSent . " qtySent=". $filtersQtyUsed. "<br>";
      $x=0;
      foreach($filters as $key => $value)
         {
            if ($key == $filterSizeSent)
               {
                  $x=$x+1;
                  //echo "number ". $x."<br>";
                  // echo "size to update= ". $key .  " qty to update=" . $value ."<br>" ;
                   $QtyUpdated = ((int)$value - ($filtersQtyUsed));
                  //echo "updated qty set1=" . $QtyUpdated."<br>";
                  UpdateInventory($filterSizeSent, $QtyUpdated, $con);
                  //echo $filtersizeSent.":". $QtyUpdated.":"; 
               }
         }
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
      $filterSizeSent = substr($filt1, $pos+1);  
      $filtersQtyUsed = substr($filt1,1,$pos-1);
      foreach($filters as $key => $value)
         {
            if ($key == $filterSizeSent)
               {
               //echo "qty for ".$key." from array=". $value." qty used=". $filtersQtyUsed."<br>";
                $QtyUpdated = ((int)$value - ($filtersQtyUsed));
               UpdateInventory($filterSizeSent, $QtyUpdated, $con);
                //echo "set1 updated ". $filterSizeSent.":". $QtyUpdated."<br>";                       
               }
            
         }
   
      //for Second filter set
      $pos = strrpos($filt2, ")");
      $filterSizeSent = substr($filt2, $pos+1);  
      $filtersQtyUsed = substr($filt2,1,$pos-1);
      //echo "set2 size=".$filterSizeSent." qt2=".$filtersQtyUsed."<br>";
      $filterSizeSent = mb_convert_encoding($filterSizeSent, "ASCII");
      $filterSizeSent = str_replace ("?", "x", $filterSizeSent);
      foreach($filters as $key => $value)
         {  
            
            //$filterSizeSent = html_entity_decode($filterSizeSent);
            
            //$filterSizeSent=strtoupper($filterSizeSent);
            //echo "filter from array=" .$key."(".strlen(trim($key)).") filter sent=".$filterSizeSent."(".strlen(trim($filterSizeSent)).")<br>";
            if ($key == trim($filterSizeSent))
              {
                 //echo "qty from array=".$value."<br>";
                  $QtyUpdated = ((int)$value - (int)$filtersQtyUsed);
                  //echo $value["qty"]."v2=".$value(0)."-". $filtersQtyUsed. "=".$QtyUpdated."<br>";
                  //echo "set 2 updated QtyUpdated set2=".$QtyUpdated."<br>";
                 UpdateInventory($filterSizeSent, $QtyUpdated, $con);
              }
         }
   }
}  


function UpdateInventory($filterSize, $ChangedQty)
{
    Global $con;
   if ($con->connect_error) 
      {
         die("Connection failed: " . $con->connect_error);
       } 

   $sql = "UPDATE filters SET filter_count='" . $ChangedQty . "' WHERE filter_size='" . $filterSize . "';";

   if ($con->query($sql) === TRUE) 
      {
         //echo "Filter inventory was updated successfully<br>";
      } 
      else 
      {
         echo "Error updating filter inventory: " . $cone->error;
      }
}

//$con->close();
?>