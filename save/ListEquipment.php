<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }
$UserName="";
$overdue="";
$fs="";

//print_r($_COOKIE);
//print_r($_GET);
//print_r($_POST);
include 'dbMirage_connect.php';
include 'functions.php';
include 'fm.css';
include 'javafunctions.php';
include 'checkboxListEquipment.css';
include 'checkboxFormTasks.css';
//CREATE ARRAY OF ALL USERS 
$sql = "SELECT user_name FROM users;";
$Users = array();
 if ($result = $UsersCon->query($sql)) 
     {
          while ($row = $result->fetch_assoc()) 
             {
                array_push($Users, $row["user_name"]);
             }
     }

$con2 = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
$con = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
$ByDate="";
 if(isset($_POST['unitid'])){$UnitId = ($_POST['unitid']);}
 if(isset($_POST['due_date'])){$DueDate = ($_POST['due_date']);}
 if(isset($_POST['bydate'])){$ByDate = ($_POST['bydate']);}
 if(isset($_COOKIE["cookie_lastquery"])) {$LastQuery=$_COOKIE["cookie_lastquery"];}
if (isset($_COOKIE["cookie_username"])) {
    $UserName = $_COOKIE["cookie_username"];
    }
    
    if (isset($_GET["username"])) {
    $UserName = $_GET["username"];
    }
    if (isset($_POST["username"])) {
    $UserName = $_POST["username"];
    }

    

 function AddToUsersProfile($Field, $Value, $con)
{
$sql = "UPDATE users SET ".$Field."='" . $Value . "' WHERE user_name='" . $_SESSION['user_name'] . "';";
               //echo $sql;
            if ($con->query($sql) === TRUE) 
               {
                  //echo "Filter inventory was updated successfully<br>";
               } 
               else 
               {
                  echo "Error updating users preference: " . $con->error;
               }
}

if(strlen($_SESSION["field2"]) <= 0){
$_SESSION["field2"]="Location";
//echo "session field2 was empty";
}

   if (isset($_GET["field2"])) 
   {
      $_SESSION["field2"] = $_GET["field2"];
      AddToUsersProfile("Field2", $_SESSION["field2"], $con);
   }

if(strlen($_SESSION["field3"]) <= 0){$_SESSION["field3"]="Area Served";}
   if (isset($_GET["field3"])) 
   {
      $_SESSION["field3"] = $_GET["field3"];
      AddToUsersProfile("Field3", $_SESSION["field3"], $con);
   }
//echo "Field2=".$_SESSION["field2"]. " Filed3=". $_SESSION["field3"];
   ?>

<?php
   
$Action="";
if(isset($_POST["action"])){
$Action=$_POST["action"];
}
if(isset($_GET["action"])){
   $Action=$_GET["action"];
   }
$ShowOverDue="";
$overdue="";
if(isset($_POST['ckoverdue']))
   {
      if($_POST['ckoverdue'] == "on")
         {
            $ShowOverDue="checked";
            $overdue = "on";
         }
   }
$unitid="";
   if(strcmp($Action,"editunit") == 0)
         {
               if(isset($_POST["field"])){$FieldName = $_POST["field"];}
               if(isset($_POST["unitid"])){$ID = $_POST["unitid"];}
               if(isset($_GET["field"])){$FieldName = $_GET["field"];}
               if(isset($_GET["unitid"])){$ID = $_GET["unitid"];}
               if(isset($_GET["notes"])){$Notes = $_GET["notes"];}
               if(isset($_POST["filters_due"])){$FiltersDue = $_POST["filters_due"];}
               switch ($FieldName) {
               case "filters_due":
                   $sql = "UPDATE equipment SET filters_due = '" . $FiltersDue . "' WHERE _id=" . $ID . ";";
                  break;
               case "notes":
                  $sql = "UPDATE equipment SET notes = '" . $Notes . "' WHERE _id=" . $ID . ";";
                  break;
          
               default:

               }
             
               //echo $sql;
            if ($con->query($sql) === TRUE) 
               {
                  //echo "Filter inventory was updated successfully<br>";
               } 
               else 
               {
                  echo "Error updating filter due date: " . $con->error;
               }
         }
   if(strcmp($overdue,"on") == 0)
         {
            $query="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment WHERE datediff(CURDATE(),filters_due) > 0;";
            //echo $query;
            //$query="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image  FROM equipment WHERE filters_due <= $duedate ;";
         }
            else 
         {
            $query="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image  FROM equipment";
         }
        
   function UpdateInventory($filterSize, $filterType, $ChangedQty)
{
global $con;
   if ($con->connect_error) 
      {
         die("Connection failed: " . $con->connect_error);
       } 

   $sql = "UPDATE filters SET filter_count='" . $ChangedQty . "' WHERE filter_size='" . $filterSize . "' AND filter_type='" . $filterType . "';";

   if ($con->query($sql) === TRUE) 
      {
         //echo "Filter inventory was updated successfully<br>";
      } 
      else 
      {
         echo "Error updating filter inventory: " . $con->error;
      }
}

function ExtractFilters($FiltersUsed, $FilterType) 
{
   $sql = "SELECT filter_size, filter_count, filter_type FROM filters;";
   $filters = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   //echo $row["filter_size"] ."= ".$row["filter_count"]."<br>"; 
                   $filters[$row["filter_size"]] = $row["filter_count"];  
                   //$filters[$index] = array('weight'=>$weight, 'height'=>$height, 'rgb'=>$rgb);
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
          $filterSizeSent = substr($f, $pos+1);  
          $filtersQtyUsed = substr($f,1,$pos-1);
          $x=0;

          foreach($filters as $key => $value)
             {
                if ($key == $filterSizeSent)
                   {
                      $x=$x+1;
                      $QtyUpdated = ((int)$value - ($filtersQtyUsed));
                      //if(strcmp($UpdateInventory, "yes")==0){UpdateInventory($filterSizeSent, $QtyUpdated);
                      UpdateInventory($filterSizeSent, $FilterType, $QtyUpdated);
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
                   UpdateInventory($filterSizeSent, $fiterType, $QtyUpdated);
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
                     UpdateInventory($filterSizeSent, $FilterType, $QtyUpdated);
                  }
             }
       }
}


   function getNextDueDate($FRotation)
{   
        $NextDueMonth = "";
        $Today= date("Y-m-d");
        $year = substr($Today, 0, 4);  // returns false
        $Month = substr($Today, 5, 2);  // returns false
        //echo "month".$month."<br>";
        $day = substr($Today, 8, 2);  // returns false
        //echo "day=".$day."<br>";
        $month = intval($Month);
        $rotation=intval($FRotation);
       $TotalMonths= $month + $rotation;
  
        if(intval($day) > 28 ){ $day="28";}
        if($TotalMonths > 12 && intval($FRotation) != 24){
            $duemonth = ($rotation + $month) - 12;
            $yr = 0;
            $yr = intval($year) + 1;
            $year = (string)$yr;
            $filtersDue = date('Y-m-d', strtotime("+ ".$rotation." months", strtotime($Today)));
            return $filtersDue;
         }
            

        if($NextDueMonth == 12){
             //echo "b= 12"."<br>";
             $month = $NextDueMonth;
            //$year = substr($Today, 0, 4); 
            //$year = intval($year) + 1;
            }
        
        if($NextDueMonth < 12){
            //echo "b < 12<br>";
            $month  = $rotation + intval($month);
            $d=intval($day);
            $year = substr($Today, 0, 4); 
        }
        if(intval($FRotation) == 24){
            //echo "<br>year2=". $year;
            $year = intval($year) + 2;
            //echo "year = ".$year;
             $month = substr($Today, 5, 2);  // returns false
        }
        $filtersDue=$year."-".$month."-".$day;
        //echo "New Filter Due Date = ". $filtersDue . "<br>";
        return $filtersDue;
}
//echo "action=".$Action;
$AssignTo="";
$id="";
if(strcmp($Action, "reassignto")==0)
{
   if(isset($_POST['AssignTo'])){$AssignTo=$_POST['AssignTo'];} 
   if(isset($_POST['id'])){$id=$_POST['id'];}   
   //echo "assigned to=".$AssignTo. " id=".$id;
   $query = "UPDATE equipment SET assigned_to='" . $AssignTo . "' WHERE _id='" . $id . "';";
   if (mysqli_query($con, $query)) 
       {
       } else {
           echo "Error re-assigning task: " . mysqli_error($con);
       }
   addtask();
}

if(strcmp($Action, "unitdone")==0)
{
        if(isset($_POST['filter_rotation'])){$Rotation = $_POST['filter_rotation'];}
        if(isset($_GET['filter_rotation'])){$Rotation = $_GET['filter_rotation'];}
        $effectiveDate = getNextDueDate($Rotation);
        //echo "effectiveDate=".$effectiveDate;
        if(isset($_POST['unit_id'])){$UnitID = $_POST['unit_id'];}
        if(isset($_GET['unit_id'])){$UnitID = $_GET['unit_id'];}
        $NoFilterUsed="";
        if(isset($_POST['no_filters_used']))
            {
                $NoFilterUsed = "true";
                if(isset($_POST['no_filters_used'])){$FilterType = $_POST['no_filters_used'];}
            }else {
	            if(isset($_POST['filter_type'])){$FilterType = $_POST['filter_type'];}
            }
            if(isset($_POST['filter_type'])){$FilterType = $_POST['filter_type'];}
            if(isset($_GET['filter_type'])){$FilterType = $_GET['filter_type'];}
            if(isset($_GET['no_filters_used']))
            {
                $NoFilterUsed = "true";
                if(isset($_GET['no_filters_used'])){$FilterType = $_GET['no_filters_used'];}
            }else {
	            if(isset($_GET['filter_type'])){$FilterType = $_GET['filter_type'];}
            }
        $FiltersLastChanged = date("Y-m-d")." [".$UserName."]";
        if(isset($_POST['filters_due'])){$FiltersDue = $_POST['filters_due'];}
        if(isset($_GET['filters_due'])){$FiltersDue = $_GET['filters_due'];}
        $ActionDone = "changed filters";
        if(isset($_POST['filter_rotation'])){$Rotation=$_POST['filter_rotation'];}
        if(isset($_GET['filter_rotation'])){$Rotation=$_GET['filter_rotation'];}
        if(isset($_POST['filters_used'])){$FiltersUsed=$_POST['filters_used'];} 
        if(isset($_GET['filters_used'])){$FiltersUsed=$_GET['filters_used'];}   
        if(isset($_POST["unit_id"])){$UnitId= $_POST["unit_id"];}
        if(isset($_GET["unit_id"])){$UnitId= $_GET["unit_id"];}
        $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filter_type='". $FilterType."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";
        if (mysqli_query($con, $sql)) 
            {
                if(strcmp($NoFilterUsed, "yes")!=0){ExtractFilters($FiltersUsed, $FilterType);}
             }else {
                    echo "Error updating equipment record: " . mysqli_error($con);
            }
}

//CREATE AN ARRAY OF FILTER TYPES
$sql = "SELECT type FROM filter_types;";
   $filtertypes = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                  array_push($filtertypes,$row["type"]); 
                }
        }

?>
<!DOCTYPE html>
<html class="myscroll">
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <meta charset="utf-8"/>
    <meta name="viewport" content ="width=device-width,initial-scale=1,user-scalable=yes" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="viewport" content="width=device-width,height=device-height, user-scalable=no" />
   <meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
   <meta http-equiv="pragma" content="no-cache" />
     
    <title>Unit List</title>

</head>
<body onunload="saveScrollPosition();"  onload="doOnloadStuff();openInfo();setScrollPosition();">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
function doOnloadStuff()
{
//GET TASKS SAVED TO COOKIE
try{
var thisTask = [];
const allTasks = JSON.parse(getJavaCookie("tasklist"));
for (var i = 0; i < allTasks.tasks.length; i++) { 
   addTaskToForm(allTasks.tasks[i].id, allTasks.tasks[i].unitname, true) 
}
}catch{
   console.log("error occured on loading Tasks");
}
//SEE IF A UNIT INFO WINDOW WAS OPEN
if(getJavaCookie("cookie_infoid") != ""){
   showinfo(getJavaCookie("cookie_infoid"))
}
}
</script>     
<script>
function editUnit(unit_id, field){
switch (field) {
case "filters_due":
document.getElementById("modaleditUnit").style.display="block";
document.getElementById("txtUnitId").value=unit_id;
document.getElementById("txtNotes").style.display="none";
document.getElementById("modalDate").style.display="block";
document.getElementById("txtFieldToUpdate").value="filters_due";
document.getElementById("modal_header").innerHTML="Choose new filter due date";
break;
case "notes":
document.getElementById("editNotes"+unit_id).style.display="block";
document.getElementById("editNotesSubmit"+unit_id).style.display="block";
document.getElementById("divNotes"+unit_id).style.display="none";
//document.getElementById("txtFieldToUpdate").value="notes";
//document.getElementById("txtNotes").style.display="block";
//document.getElementById("btnModalSubmit").style.display="block";
//document.getElementById("modal_header").innerHTML="Edit notes";
break;
}
}
</script>
<!-- The Modal -->
<div id="modaleditUnit" class="modal">
  <!-- Modal content -->
  <div class="modal-content" style="text-align:center;">
    <div style="color:green;font-weight:bold;" id="modal_header">Pick filter due date</div>
<form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmModal">

<input type="hidden" name="unitid" value="notes" id="txtUnitId"><input type="hidden" name="action" value="editunit">

<input type="text" id="txtFieldToUpdate" name="field" value="" style="display:none;margin-left:auto;margin-right:auto;">

<input type="date" style="display:none;margin-left:auto;margin-right:auto;" name="filters_due" id="modalDate" onchange="this.form.submit();">

<textarea id="txtNotes" name="notes" style="overflow:auto;width:8vw;margin-left:auto;margin-right:auto;text-align:center;width:20vw;border-radius:5%;display:none;"></textarea>
<div style="display:flex;flex-direction:row;width:200px;height:20vh;margin-left:auto;margin-right:auto;">
<button class="btn button btn-success" style="width:8vw;height:10vh;text-align:center;display:none;" onclick="this.form.submit();" style="display:none;" id="btnModalSubmit">Submit</button></form></p>
    <button type="button" class="btn button btn-danger" style="width:8vw;height:10vh;text-align:center;" onclick="document.getElementById('modaleditUnit').style.display='none';">Cancel</button></div>
  </div>

</div>
<script>
document.addEventListener('keydown', (e) => {
    if (e.key.toLowerCase() === 'q' && e.ctrlKey) {
        X=document.getElementById("session_vars");
         if(X.style.display == "none"){
        X.style.display="block";
         }
         else
         {
         X.style.display="none";
         }
    }
});
</script>
<style type="text/css">
   .wrapper{
  background: #fff;
  border-radius: 5px;
  padding: 25px 25px 30px;
  box-shadow: 8px 8px 10px rgba(0,0,0,0.06);
   overflow-x: auto;
   max-height: 100px;
   background-color:black;
   max-width:15vw;
}
.wrapper h2{
  color: #4671EA;
  font-size: 28px;
  text-align: center;
}

.wrapper textarea{
  width: 100%;
  resize: none;
  height: 59px;
  outline: none;
  padding: 15px;
  font-size: 16px;
  margin-top: 10px;
  border-radius: 5px;
  max-height: 100px;
  caret-color: #4671EA;
  border: 1px solid #bfbfbf;
   overflow-y: auto;
overflow-x:auto;
//max-width:20vw;
}
textarea::placeholder{
  color: #b3b3b3;
}
textarea:is(:focus, :valid){
  padding: 14px;
  border: 2px solid #4671EA;
}
textarea::-webkit-scrollbar{
  width: 0px;
}
::-webkit-scrollbar {
  width: 12px;
  height: auto;
}
::-webkit-scrollbar-button {
  width: 0px;
  height: 0px;
}
::-webkit-scrollbar-thumb {
  background: #e1e1e1;
  border: 0px none #99ff66;
  border-radius: 50px;
}
::-webkit-scrollbar-thumb:hover {
  background: #339933;
}
::-webkit-scrollbar-thumb:active {
  background: #9933ff;
}
::-webkit-scrollbar-track {
  background: #666666;
  border: 0px none #ffffff;
  border-radius: 50px;
}
::-webkit-scrollbar-track:hover {
  background: #666666;
}
::-webkit-scrollbar-track:active {
  background: #333333;
}
::-webkit-scrollbar-corner {
  background: transparent;
}<script>
</style>

<script>
   function openInfo(){
    try{
      x=getCookie('cookie_infoid');

      document.getElementById(x).style.display = "block";
    } catch (error) {
       console.log("error with function openInfo");
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
function getusernameCANCELED(){   
document.cookie = "lastpage=ListEquipment.php";
username = getCookie("cookie_username");

if (username == 'undefined' || username == null || username.length == '') 
         {
            alert("Your session has expired. Please log back in.");
           window.location.href = "welcome1.php";
         }
         else
         {
            document.getElementById("username").value = username;
         }
      }
   
</script>

<script>
document.addEventListener("keydown", KeyCheck);  //or however you are calling your method
function KeyCheck(event)
{
   var KeyID = event.keyCode;
   switch(KeyID)
   {
      case 8:
      myFunction();
      break; 
      case 13:
      myFunction();
      break;
      default:
      break;
   }
}
</script>
<script>
function submittasks(UserName) {
   {
      document.getElementById("username").value = UserName;
      document.getElementById("frmsubmittasks").submit();
      document.cookie = "tasklist=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";
   }
}
</script>
<script>
   function FiltersDone(Url, ItemID){
      myfiltertype = document.getElementById('slctFilterTypes'+ItemID).value;
      username = getCookie("cookie_username");
      window.location = Url+'&filter_type='+myfiltertype+'&username='+username;
      }
</script>
<script>
   function changeHyperLink($hyperlinkID, $item_id){
      var selectbox = document.getElementById($item_id);
      var value = selectbox.value;
      var $ftype = value;
      $mylink = document.getElementById($hyperlinkID).getAttribute("href");
      $mylink = $mylink + '&filter_type=' + $ftype;
      document.getElementById($hyperlinkID).setAttribute("href", $mylink);
      document.getElementById($hyperlinkID).style.display="block";
   }

   </script>
<script>
   function showFilterTypes($item_id){
      if(document.getElementById('ckShowFilterTypes'+$item_id).checked)
      {
      myselect = document.getElementById('slctFilterTypes'+$item_id)
      document.getElementById('ckShowFilterTypes'+$item_id).style.display = "none";
      myselect.className = "dropdown";
       }
      else
      {
      myselect = document.getElementById('slctFilterTypes'+$item_id)
      document.getElementById('ckShowFilterTypes'+$item_id).style.display = "block";
      myselect.className= "d-none";
      }
   }

   </script>
<script>
function showinfo($divID) {

         var my_disply = document.getElementById($divID).style.display;
         setJavaCookie("cookie_infoid", $divID, 1);
        if(my_disply == "none"){
               saveScrollPosition();
              document.getElementById($divID).style.display = "block";
              setTimeout(setScrollPosition, 100);
            }else{
        setJavaCookie("cookie_infoid", "void", 1);
     }
   }
</script>

<script>
function closeinfo($divID) {

        document.getElementById("tblUnitInfo"+$divID).style.display = 'none';
document.getElementById("checkCloseInfo"+$divID).checked = false;
        setJavaCookie("cookie_infoid", "void", 1);
     }
</script>
<?php

//CREATING ARRAY OF ALL UNIT NAMES FOR SEARCH BOXES
include 'dbMirage_connect.php';
   $sql = "SELECT unit_name FROM equipment;";
   $arUnits = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   array_push($arUnits, $row["unit_name"]);
                }
        }
//print_r($arUnits);
?>
<script>
<?php
$js_array = json_encode($arUnits);
echo "var unitsArray = ". $js_array . ";\n";
?>
</script>
<?php
//echo var_dump($_POST);
//error_reporting(E_ALL);

// Check connection
if(mysqli_connect_errno()){
	//echo "<font color='white'>Failed to connect to MySQL: " . mysqli_connect_error();
    }else{
	//echo "<font color='white'>Connection to database successfully";
}

$query = "SELECT filter_size  FROM filters WHERE filter_count <= 0;";
   $result = mysqli_query($con, $query) or die(mysqli_error());


//while($row = mysqli_fetch_array($result)){
	//echo $row['filter_size']."<br>";
//}
 
$AssignedTo = null;
//echo "session status=".session_status()."<BR>";

if (isset($_POST["action"])) {
    $Action = $_POST["action"];
}

if(isset($_POST["ckBox"])){
   $unitid = $_POST["ckBox"];
 foreach ($_POST["ckBox"] as $unitnam)
 {
	 //echo "name=".$unitnam;
 }
}else
{
   //echo "check box not found";
}

if (strcmp($Action,"edit_task")==0) 
   {
      $ReAssignedTo = "";
      $Assigned_To = "";
      $uid="";//assignedto="+assignedto+"&reassignto
      if (isset($_GET["reassignto"])) {$ReAssignedTo = $_GET["reassignto"];}
      if (isset($_GET["assignedto"])) {$Assigned_To = $_GET["assignedto"];}
      //echo "ReAssignedTo=".$ReAssignedTo. " Assigned_To=".$Assigned_To;
      if (isset($_GET["id"])) {$uid = $_GET["id"];}
            $sql="UPDATE equipment SET assigned_to ='".$ReAssignedTo."' WHERE _id = '".$uid."';";
            if ($con->query($sql) === TRUE) 
                    {
                        //echo "Equipment Reassigned<br>".$sql."<br>";
                    }
                    else
                    {
                        echo "Error editing equipment. Please submit a bug report with this line: " . $sql . "<br>" . $con->error;
                    }
            $sql="UPDATE equipment SET assigned_to ='".$ReAssignedTo."' WHERE  _id = '".$uid."';";
            if ($con->query($sql) === TRUE) 
                    {
                        //echo "Task Reassigned<br>".$sql;
                    }
                    else
                    {
                        echo "Error updating tasks. Please submit a bug report including this line: " . $sql . "<br>" . $con->error;
                        //die('Could not enter data: ' . mysqli_error($con2));
                    }
}

if (strcmp($Action,"addalltasks")==0) 
   {
      //$unitid = $_POST['ckBox'];
      $data = array();
      foreach ($unitid as $unit)
         { 
		       //echo "unit=".$unit."<br>";
            $query = "UPDATE equipment SET assigned_to='".$UserName."' WHERE _id='" . $unit . "';"; 
          if ($con->query($query) === TRUE) 
            {
            //echo "Task for unit id:".$unit." added successfully";
            } else {
            echo "Error updating record for : " . $unit. "error= ".$con->error;
            }

         }

   }
   function addtasks(&$arrayname, $con, $con2, $Uname)
    {
        foreach ($arrayname as $person)
            {
            foreach ($person as $key=>$value)
                {
                foreach ($value as $mkey=>$mvalue)
                {
                if($mkey=="unit_name"){$UnitName = $mvalue;}
                if($mkey=="unit_id"){$UnitID = $mvalue;}
                if($mkey=="location"){$Location = $mvalue;}
                if($mkey=="filter_size"){$FilterSize = $mvalue;}
                if($mkey=="filters_due"){$FiltersDue = $mvalue;}
                if($mkey=="rotation"){$Rotation = $mvalue;}
                }
                
                addtask($con, $con2, $Uname, $UnitID,$Location,$UnitName,$FilterSize, $Rotation, $FiltersDue);
                //echo "<br>unitid=".$UnitID. " location=".$Location." unit name=".$UnitName." fsize=".$FilterSize." rotation=". $Rotation. " filtersdue=". $FiltersDue;
                }
                
                
            }
            
    }
    
if ($Action == "addtask") {
    AddTask($con, $UnitID);
}

//echo "<a href='ListEquipment.php?action=reset'>reset cookie</a>";
if(strcmp($UserName, "")==0 || strcmp($UserName, "none")==0)
   {
      //header("Location: web_login.php"); 
      // exit;
   }

  
   if (isset($_GET['unit_name'])) {$UnitName=$_GET['unit_name'];}
   if(isset($_POST['_id'])){$UnitID = ($_POST['_id']);}
   if(isset($_POST['ckoverdue'])){$overdue = $_POST['ckoverdue'];}
 $ByDate="no";
   if(isset($_POST['bydate'])){
     switch ($_POST['bydate']) {
        case "oldest":
           $ByDate = "oldest to newest";
            break;
        case "newest":
           $ByDate = "newest to oldest";
            break;
        case "today":
           $ByDate = "due today";
            break;
        case "normal":
           $ByDate = "normal";
           break;
    }
  }
?>
<div id="divTasks" style="overflow-x: auto;overflow-y: hidden;white-space: nowrap;background-color:green;display:inline-block;">
 <form action="ListEquipment.php" method="post" id="frmsubmittasks" title="Add units to tasks list" style="background-color:green;color:white;height:100px;margin-left:20px;display:none;flex-direction:row;"><button class="btn btn-primary rounded-9 btn-sm" style="width: fit-content;height:60px;white-space: normal;border-radius:10px;margin-top:20px;" onclick="submittasks('<?php echo $UserName ?>');">Submit Tasks</button><button type="button" class="btn btn-danger rounded-9 btn-sm" style="width: fit-content;height:60px;white-space: normal;border-radius:10px;margin-top:20px;" onclick="if(confirm('Delete all tasks?') == true){cancelAllTasks();}">Cancel All</button>
 <input type="hidden" name="action" value="addalltasks">
   <input type="hidden" name="username" id="username" value="<?php echo $UserName ?>"></div><select id="slctTasks" style="display:none;"></select>
   </form>
   <div id="table-wrapper" style="">
<table class="myTable" id="myTable" style="width:100%;" style="height:100%;overflow-y: auto;">
<thead>
    <tr style="top:0px;position:sticky;z-index:4;" id="tableheader">
        <th style="background-color:#07ff10;color:black;text-align:center;width:10%;">Add<br>Task</th>
        <th style="background-color:#07ff10;color:black;text-align:center;width:20%;">Filters<br>Done</td>
        <th style="background-color:#07ff10;color:black;text-align:center;width:10%;" id="thunitname"><div style="margin-right:auto;margin-left:0;text-align: left;">Unit Name</div></th>
      
		<form action="ListEquipment.php" method="post">
        <th style="background-color:#07ff10;color:black;width:25%">
        <div class="dropdown show">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#07ff10;color:black;font-weight:bold;"><?php echo $_SESSION["field2"] ?></a>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" >
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field2=Area Served">Area Served</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field2=Location">Location</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field2=Last Changed">Last Changed</a>
      </div>
      </div>
		</th>
        <th id="thfield3" style="background-color:#07ff10;color:black;width:25%">
         <div class="dropdown show" >
      <a class="btn btn-secondary dropdown-toggle font-weight-bold" style="background-color:#07ff10;color:black;" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo $_SESSION["field3"] ?>
      </a>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" >
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field3=Area Served">Area Served</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field3=Location">Location</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field3=Filter Size">Filter Size</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field3=Notes">Notes</a>
         </div>
         </div></th>
        <th style="background-color:#07ff10;color:black;width:10%;"><font color="black">Due Date<!--<?php if(strcmp($ByDate, "no") != 0){ echo "<br><div style='font-size:10px;background-color:black;color:aqua;'>".$ByDate."</div>";} ?>--></th>
        
    </tr></thead>
  
    <?php
    
   //GET LAST QUERY IN CASE PAGE WAS TEMPERARALY CHANGED
   $LastQuery ="";
    //lAST QUERY IS SAVED WITH JAVASCRIPT WHEN ELEMENTS ARE CLICK USING FUNCTION setLastQuery()
   if(isset($_COOKIE["cookie_lastquery"])){
      $LastQuery=$_COOKIE["cookie_lastquery"];
      //echo "last qry=".$_COOKIE["cookie_lastquery"];
      }

   if(strlen($LastQuery) == 0)
            {
               $query="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment;";
            }
            else
            {
               $query = $LastQuery;
            }
            if ($stmt = $con->prepare($query)) {
        $stmt->execute();
        //Bind the fetched data to $unitId and $UnitName
        $stmt->bind_result($unitId, $UnitName, $Location, $AreaServed, $FilterSize, $FiltersDue, $FilterType, $Belts, $Notes, $FilterRotation, $FiltersLastChanged, $AssignedTo, $Image );
          $X=0;
          $EquipmentList="";
        while ($stmt->fetch()) {
        $EquipmentList= $EquipmentList . "{\"units\":[  {\"_id\":\"".$unitId."\", \"unit_name\":\"".$UnitName."\",\"location\":\"".$Location."\"}]}";
         $X=$X+1;
        
         $today = date('Y-m-d');
        $someDate = new DateTime($FiltersDue);
        $daysoverdue= s_datediff("d",$today,$someDate,true);
            ?>
            <tr>
			<?php 
					if(getFilterCount($FilterSize, $result)=="outofstock")
					{
						$outofstock="outofstock";
					}
					else
					{
						$outofstock="";
					}
					
			$myCss = GetCss($Theme,$daysoverdue,$outofstock); 
         $myCss = $myCss ."font-family:".$_SESSION["font_family"];
			?>
                    <td style='<?php echo $myCss ?>;width:fit-conent;'>
                        <?php 
                        if($AssignedTo == "")
                           {
                                 ?>
                                 <label class="container"><input type="checkbox" class="checkmarkListEquipment" id="cktask<?php echo $unitId ?>" onchange="addTaskToForm('<?php echo $unitId ?>','<?php echo $UnitName ?>', false);" value="<?php echo $unitId ?>">
                                 <span class="checkmark"></span>
                                 </label>
                                 <?php 
                           }
                           else
                           { 
                              ?>
                              <div style="text-align:center;width:100%;"  onclick="showSelectUsers(<?php echo $unitId ?>);"><?php echo $AssignedTo ?></div><div id='divSelectUser<?php echo $unitId ?>' style='display:none;margin-left:auto;margin-right:auto;'>
                              <div class="dropdown show">
                              <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#07ff10;color:black;font-weight:bold;">Re-assin too
                              </a>      
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" >
                              <a class='dropdown-item' href='#' onclick="document.getElementById('divSelectUser<?php echo $unitId ?>').style.display='none';">Cancel</a>
                              <?php
                              foreach ($Users as $value) 
                                 {
                                    echo "<a class='dropdown-item' href='".$_SERVER['SCRIPT_NAME']."?action=edit_task&assignedto=".$UserName."&reassignto=".$value."&id=".$unitId."'>".$value."</a>";
                                 }
                              echo "</div></div></td>";
                           }
                           ?>
                        <td style='<?php echo $myCss ?>;text-align:center;'>
                        <label class="switch">
                        <input type="checkbox" id="ckShowFilterTypes<?php echo $unitId ?>" onChange="showFilterTypes('<?php echo $unitId ?>');">
                        <span class="slider round"></span>
                        </label></checkbox>
                        <?php $myURL = "ListEquipment.php?action=unitdone&filter_rotation=".$FilterRotation."&unit_id=".$unitId."&unit_name=".$UnitName."&filter_size=".$FilterSize."&filters_used=".$FilterSize."&filters_due=".$FiltersDue; ?>
                        <br>
                        <div class="dropdown d-none" id="slctFilterTypes<?php echo $unitId ?>">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           select filter used
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                        foreach ($filtertypes as $value) {
                           echo "<a class='dropdown-item' href='".$myURL."&filter_type=".$value."'>".$value."</a>";
                        }
                        ?>
                        <a class='dropdown-item' href='<?php echo $myURL ?>&filter_type=no_filters_used'>No filters used</a>
                        </div>
                        </div>
                   <A style="display:none;" id="ahref<?php echo $unitId ?>" href="ListEquipment.php?action=unitdone&username=<?php echo $UserName ?>&filter_rotation=<?php echo $FilterRotation ?>&unit_id=<?php echo $unitId ?>&unit_name=<?php echo $UnitName ?>&filter_size=<?php echo $FilterSize ?>&filters_used=<?php echo $FilterSize ?>&filters_due=<?php echo $FiltersDue ?>" style="font-weight: bold;font-size: 50px;color:white;background-color:gold;height:170px;width:100%;"><div style="font-weight: bold;font-size: 50px;background-color:white;height:50px;">SUBMIT</div></A>
                     </td>
					<td style="<?php echo $myCss ?>" id='info'>
                    <a href="#" onclick="showinfo('tblUnitInfo<?php echo $unitId ?>');"> <div style="<?php echo $myCss ?>;text-align:left;width:10vw;margin-right:auto;margin-left:auto;" id='mydiv<?php echo $unitId ?>'>
					<?php echo $UnitName ?></div></a>
 
                 <table class="tableUnitInfo" style="display:none;" id="tblUnitInfo<?php echo $unitId ?>">
                  <tr id = "trinfo"><td><div class="bg-info" style="box-shadow: 4px 4px black;border-radius:10px;height:25px;text-align:center;font-weight:bold;"  name="checkbox" value="value" id="checkCloseInfo<?php echo $unitId ?>" title="Close information window for <?php echo $UnitName ?>" onclick="closeinfo('<?php echo $unitId ?>');">CLOSE</div>


                     </td><td></td>
                  </tr>
                 <tr><td class="tdUnitInfo" style="text-align:left;">Unit name</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $UnitName ?></td></tr>
                  <tr><td class="tdUnitInfo" style="text-align:left;">Assigned too</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $AssignedTo ?></td></tr>
                   <tr><td class="tdUnitInfo" style="text-align:left;">Location</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $Location ?></td></tr>
                   <tr><td class="tdUnitInfo" style="text-align:left;">Area served</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo  $AreaServed ?></td></tr>
                  <tr><td class="tdUnitInfo" style="text-align:left;">Filter size</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $FilterSize ?></td></tr>
                  <tr><td class="tdUnitInfo" style="text-align:left;">Filter type</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $FilterType ?></td></tr>
                  <tr><td class="tdUnitInfo" style="text-align:left;width:100px;"> Filters due</td><td class="tdUnitInfo2" style="cursor:grab;text-align:left;" onclick="editUnit('<?php echo $unitId ?>', 'filters_due');document.getElementById('modalDate').value='<?php echo $FiltersDue ?>';"><?php echo $FiltersDue ?></td></tr>
                   <tr><td class="tdUnitInfo" style="text-align:left;">Filters last changed</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $FiltersLastChanged ?></td></tr>
                   <tr><td class="tdUnitInfo" style="text-align:left;">Rotation</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $FilterRotation ?></td></tr>
                  <tr><td class="tdUnitInfo" style="text-align:left;">Belts</td><td class="tdUnitInfo2" style="text-align:left;"> <?php echo $Belts ?></td></tr>
                   <tr><td class="tdUnitInfo" style="text-align:left;">Notes</td><td class="tdUnitInfo2" style="text-align:left;" onclick="editUnit('<?php echo $unitId ?>', 'notes');document.getElementById('txtNotes').value=document.getElementById('divNotes<?php echo $unitId ?>').innerHTML;"><div id="divNotes<?php echo $unitId ?>" class="wrapper" style="cursor:grab;"><?php echo $Notes ?></div><textarea style="display:none;color:white;" id="editNotes<?php echo $unitId ?>" class="wrapper" onkeyup="document.getElementById('aEditNotes<?php echo $unitId ?>').href='<?php echo $_SERVER["SCRIPT_NAME"] ?>?unitid=<?php echo $unitId ?>&notes='+document.getElementById('editNotes<?php echo $unitId ?>').value+'&field=notes&action=editunit';"><?php echo $Notes ?></textarea><a href="" id="aEditNotes<?php echo $unitId ?>"><button style="display:none;" type="button" id="editNotesSubmit<?php echo $unitId ?>" class="btn btn-Success">Save</button></a></td></tr>
                   <tr><td class="tdUnitInfo" style="text-align:left;">
                   <a href="webEditUnit.php?_id=<?php echo $unitId ?>"><div class="btn btn-warning" title="Edit <?php echo $UnitName ?> properties" style="box-shadow: 4px 4px black;">EDIT UNIT</div></a>
                                     
                   </td><td class="tdUnitInfo" style="text-align:left;">
                   <?php 
                   if ($Image != null)
                     {
                        echo "<a href='" . $Image ."' target='_blank'><img src='".$Image."' alt='". $UnitName ."' style='width:100px;height:100px;'></a>";
                     }
                     ?>
                     </td></tr>
                </table>
                </td>
                
                <?php
                if(strcmp($_SESSION["field2"], "Location")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'><div style='margin-left:auto;margin-right:auto;text-align:left;width:20vw;'>". $Location."</div></td>";
                  }
                if(strcmp($_SESSION["field2"], "Area Served")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'>". $AreaServed ."</td>";
                  }
                if(strcmp($_SESSION["field2"], "Last Changed")==0)
                  {
                     echo "<td style='".$myCss."'>". $FiltersLastChanged ."</td>";
                  }
                if(strcmp($_SESSION["field3"], "Location")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'>". $Location."</td>";
                  }
                if(strcmp($_SESSION["field3"], "Area Served")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'>". $AreaServed ."</td>";
                  }
                  if(strcmp($_SESSION["field3"], "Last Changed")==0)
                  {
                     echo "<td style='".$myCss."'>". $FiltersLastChanged ."</td>";
                  }
                  if(strcmp($_SESSION["field3"], "Notes")==0)
                  {
                     if(strlen($Notes) >= 12)
                        {
                           ?><td style='<?php echo $myCss ?>;cursor:grab;'><div style='border:2px solid white;overflow:auto;max-width:120px;max-height:50px;' onclick="editUnit('<?php echo $unitId ?>', 'notes');document.getElementById('txtNotes').value='<?php echo $Notes ?>'"><?php echo $Notes ?></div></td><?php
                        }
                        else
                        {
                           ?><td style='<?php echo $myCss ?>;cursor:grab;' onclick="editUnit('<?php echo $unitId ?>', 'notes');document.getElementById('txtNotes').value='<?php echo $Notes ?>';"><?php echo $Notes ?></td><?php
                        }
                  }
                  if(strcmp($_SESSION["field3"], "Filter Size")==0)
                  {
                        
                      $outofstock = getFilterCount($FilterSize,$result);
						   //$outofstock=getFilterCount($fs, $result);
                     $myCss=getCss($Theme,$daysoverdue,$outofstock);
                     $myCss ."font-family:".$_SESSION["font_family"];
                     $AlCss=GetAlinkCss($Theme,$daysoverdue,$outofstock);
                     $AlCss = $AlCss . ";font-family:".$_SESSION["font_family"];
						  // echo "myCss=".$myCss." theme=".$Theme." days=".$daysoverdue." size=".$FilterSize." oos=".$outofstock." Alink font color:".$AlCss."<br>";
                     //REMOVE AMOUNT FROM FILTERSIZE FOR BOOKMARK HYPERLINK TO FILTERS PAGE
                     $NumberOfFilters = substr_count($FilterSize,"(");
                     $B=0;
                     echo "<td style='".$myCss."'><div style='text-align:center;margin-left:auto;margin-right:auto;'>";
                     if($NumberOfFilters == 1)
                        {
                        $myfilter = $FilterSize;
                        $x = strpos($myfilter, ")",0);
                        $myfilter = substr($myfilter, $x + 1, strlen($myfilter)- $x);
                        $myfilter_id = $myfilter . $FilterType;
                        $myfilter_id = str_replace(" ","",$myfilter_id);
                        echo "<a href='web_update_filters.php?searchfilterid=".$myfilter ."' style='".$AlCss."';>". $FilterSize . "</a>";
                        }
                     else
                        {
                        $arfilters = explode(" ", $FilterSize);
                        foreach ($arfilters as $value) 
                        {
                           $B=$B+1;
                           if($B <= 2)
                           {
                           $myfilter = $value;
                           $x = strpos($myfilter, ")",0);
                           $myfilteronly = substr($myfilter, $x + 1, strlen($myfilter)- $x);
                           $numoffilters=substr($myfilter, 0, $x + 1);
                           echo "<a href='web_update_filters.php#".$myfilteronly .$FilterType."' style='".$AlCss."';>". $numoffilters.$myfilteronly. "<br>". "</a>";
                           }
                        }
                        echo "</div></td>";
                    }                    
                  }

                ?>
                <td style="<?php echo $myCss ?>"><div style="text-align:center;cursor: grab;" onclick="editUnit('<?php echo $unitId ?>');"><?php echo $FiltersDue ?></div></td>

                </tr>

            <?php

        }
    }
    
    //$stmt->close();
    
     ?>
  
    
     
</table>
</div>
<div style="display:none;" id="equipmentlist" style="back-color:white;color:black;"><font color="white"><?php echo $EquipmentList ?></div>
<?php 
echo  $X . " units in returned by query."; 


function getFilterCount($fsize, &$result)
{
//echo "from function fsize=".$fsize."<br>";
   foreach($result as $side=>$direc)
      {
      //echo $fsize . "=" . $direc["filter_size"]."<br>";
         if(strpos($fsize, $direc["filter_size"]) > 0)
            {
              return "outofstock";
            }
      }
}
function AddTask2($UserName, $unitid,$UnitName,$filters, $filterrotation, $filtersdue)
{
    $link = mysqli_connect("MYSQL5013.site4now.net","a3f5da_lobby","relays82","db_a743b0_cannery");
     echo "AddTask2<br>";
    // Check connection
    if($link === false)
    {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
 
    // Prepare an insert statement
    $sql = "INSERT INTO tasks2 (_id, employee_name, unit_name, task_date_created, filters_needed, unit_id, filter_rotation, filters_due) VALUES (?,?,?,?,?,?,?,?)";
    $date_created=date("Y-m-d");
    if($stmt = mysqli_prepare($link, $sql))
       {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssssss",$_id, $employee_name, $unitid, $task_date_created, $filters, $UnitName, $filterrotation, $filtersdue);
    
            // Set parameters
            $_id='';
            $employee_name=$UserName;
            $unit_name=$unitid;
            $task_date_created=$date_created;
            $filters_needed=$filters;
            $unit_id = $unitid;
            $filter_rotation=$filterrotation;
            $filters_due=$filters;
    
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
                {
                    //echo "Affected rows: " . mysqli_affected_rows($link)."<br>";
                    echo "Records inserted successfully.";
                } 
                else
                {
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
                }
        } 
        else
        {
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
        }
    // Close statement
    mysqli_stmt_close($stmt);
    // Close connection
    mysqli_close($link);
}

function getCss($theme,$daysoverdue,$outofstock)
{
	//echo "theme=".$theme." days=".$daysoverdue." oos=".$outofstock."<br>";
	if(strcmp($theme,"Light-tdPlain")==0)
	{
		if ($daysoverdue > 0)//FILTERS ARE NOT OVERDUE
				{
					  if(strcmp($outofstock, "outofstock")==0)//FILTERS OUT OF STOCK
					  {
							   $nCss = "font-family:".$_SESSION["font_family"].";background-color:orange;color:black;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
					  else
					  {			//FILTERS NOT OUT OF STOCK
								$nCss="background-color:white;color:black;text-align: left;font-weight:bold;";	
								//echo "nCss=".$nCss."<br>";
					  }
			   }
				if ($daysoverdue <= 0)//FILTERS ARE OVERDUE
				{
					     if(strcmp($outofstock, "outofstock")==0)
					  {
							   $nCss="font-family:".$_SESSION["font_family"].";background-color:orange;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $nCss="font-family:".$_SESSION["font_family"].";background-color:red;color:white;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	else
	{
	//DARK Theme
		if ($daysoverdue > 0)//FILTERS ARE NOT OVERDUE
				{
					  if(strcmp($outofstock, "outofstock")==0)//FILTERS OUT OF STOCK
					  {
							   $nCss = "font-family:".$_SESSION["font_family"].";background-color:orange;color:white;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
					  else
					  {			//FILTERS NOT OUT OF STOCK
								$nCss="font-family:".$_SESSION["font_family"].";background-color:black;color:white;text-align: left;font-weight:bold;";	
								//echo "nCss=".$nCss."<br>";
					  }
			   }
				if ($daysoverdue <= 0)//FILTERS ARE OVERDUE
				{
					     if(strcmp($outofstock, "outofstock")==0)
					  {
							   $nCss="font-family:".$_SESSION["font_family"].";background-color:orange;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $nCss="font-family:".$_SESSION["font_family"].";background-color:black;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	return $nCss;
}

function getAlinkCss($theme,$daysoverdue,$outofstock)
{
	$AlinkCss="";
	if(strcmp($theme,"Light-tdPlain")==0)
	{
		if ($daysoverdue > 0)//FILTERS ARE NOT OVERDUE
				{
					  if(strcmp($outofstock, "outofstock")==0)//FILTERS OUT OF STOCK
					  {
							   $AlinkCss = "color:black;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
					  else
					  {			//FILTERS NOT OUT OF STOCK
								$AlinkCss="color:black;text-align: left;font-weight:bold;";	
								//echo "nCss=".$nCss."<br>";
					  }
			   }
				if ($daysoverdue <= 0)//FILTERS ARE OVERDUE
				{
					     if(strcmp($outofstock, "outofstock")==0)
					  {
							   $AlinkCss="color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $AlinkCss="color:white;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	return $AlinkCss;
}


function AddTask($con, $user, $unitid)
    {
        //echo var_dump($_POST);
	    //echo "AddTask<br>";
        if (isset($_POST['action'])=="addtask") 
            {
                if (isset($_COOKIE["user"])) 
                    {
                      $UserName = $_COOKIE["user"];
                      //echo "COOKIE username=". $UserName."<BR>";
                    }        
   
                $sql="UPDATE equipment  SET assigned_to ='".$user."' WHERE _id='". $unitid ."';";
                 //mysqli_select_db($con, 'db_a743b0_cannery');
                if ($retval=mysqli_query($con2, $sql))
                    {
                        //echo "<br>" .$UnitName . " was added to your tasks<br>";
                    }
                
            }

}

function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false)
{

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
       if($diff->invert)
       {
            return -1 * $total;
       }else
       {
            return $total;
       }
}


?>
<script>
   function reAssignTask(id,assignedto){
      reassignto=document.getElementById("slctAssignTo").value;
      window.location = "<?php echo $_SERVER['SCRIPT_NAME'] ?>?action=edit_task&assignedto="+assignedto+"&reassignto="+reassignto+"&id="+id;
   }
</script>
<script>
   function showSelectUsers(id){
      document.getElementById("divSelectUser"+id).style.display="block";
   }
</script>
<script>
function uncheck(id){ 
   ischecked=document.getElementById(id).checked;
   document.getElementById('cktask'+id).checked = ischecked;
   document.getElementById(id).remove();
   document.getElementById("lbl"+id).remove();
  //CLEAR GREEN FROM IF NO CHECK BOXES LEFT
   var inputs = document.forms["frmsubmittasks"].getElementsByTagName("input");
   var found=false;
      for(var i = 0; i < inputs.length; i++) {
         //console.log("type="+inputs[i].type.toLowerCase());
         if(inputs[i].type.toLowerCase() == 'checkbox') {
            found=true;
            document.getElementById("frmsubmittasks").style.display="flex";
   document.getElementById("tableheader").style.position="static";
document.getElementById("divTasks").style.top="0px";
   document.getElementById("divTasks").style.position="sticky";
document.getElementById("divTasks").style.zIndex=4;
document.getElementById("divTasks").style.display="inline-block";
         }
      }
      if(found==false){
document.getElementById("frmsubmittasks").style.display="none";
   document.getElementById("tableheader").style.position="sticky";
document.getElementById("divTasks").style.display="none";
   document.getElementById("divTasks").style.position="static";
}
saveTasksToCookie();
}
</script>

<script>
    function myFunction() {    
	     
        var input, filter, table, tr, td, i, txtValue;
        var $n="1";
        input = document.getElementById("myInput");
        $lastsearch = document.getElementById("myInput").value
        document.cookie = "search=" + $lastsearch;
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
     
        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[$n];
        if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
        
        tr[i].style.display = "";
		tr[i].height= "300px";
        td.style.height = "20px";
        } else {
		 td.style.height = "20px";
		 	if(tr[i].id != "trinfo"){
		  tr[i].style.display = "none";
		  }
        }
	
        }
		}
    }
</script>
   

</body>
</html>