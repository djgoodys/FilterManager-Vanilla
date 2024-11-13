<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }
   if(!isset($_SESSION["backup_folder"]))
   {
      header('Location: '."start.php");
   }
   //echo $_COOKIE["cookie_lastquery"];
$UserName="";
$overdue="";
$fs="";

//print_r($_COOKIE);
//print_r($_GET);
//print_r($_POST);

include 'functions.php';
include 'phpfunctions.php';
include 'fm.css';
include 'javafunctions.php';
include 'checkboxListEquipment.css';
include 'checkboxFormTasks.css';

$ByDate="";
 if(isset($_POST['unitid'])){$row["_id"] = ($_POST['unitid']);}
 if(isset($_POST['due_date'])){$DueDate = ($_POST['due_date']);}
 if(isset($_POST['bydate'])){$ByDate = ($_POST['bydate']);}
 if(isset($_COOKIE["cookie_lastquery"])) {$LastQuery=$_COOKIE["cookie_lastquery"];}
 //echo "lastquery=".$LastQuery."<br>";
if (isset($_COOKIE["cookie_username"])) {
    $UserName = $_COOKIE["cookie_username"];
    }
    
    if (isset($_GET["username"])) {
    $UserName = $_GET["username"];
    }
    if (isset($_POST["username"])) {
    $UserName = $_POST["username"];
    }

    //CREATE ARRAY OF ALL USERS 
    $jsonString = file_get_contents('table_users.json');
   $data = json_decode($jsonString, true);
   $arUsers = [];
   if(is_array($data)){
   foreach ($data as $obj) {
      if($obj["backup_folder"] == $_SESSION["backup_folder"]){
      $arUsers[] = $obj["user_name"];
   }
   }
   }

 function ChangeUserSettings($Field, $Value)
{
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
foreach ($data as &$object) {
   //echo $object["user_name"];
    if ($object['user_name'] === $_SESSION["user_name"]) {
        $object[$Field] = $Value;
    }
}
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('table_users.json', $jsonString);
}

if(strlen($_SESSION["field2"]) <= 0){
$_SESSION["field2"]="Location";
//echo "session field2 was empty";
}

   if (isset($_GET["field2"])) 
   {
      $_SESSION["field2"] = $_GET["field2"];
      ChangeUserSettings("Field2", $_SESSION["field2"]);
   }

if(strlen($_SESSION["field3"]) <= 0){$_SESSION["field3"]="Area Served";}
   if (isset($_GET["field3"])) 
   {
      $_SESSION["field3"] = $_GET["field3"];
      ChangeUserSettings("Field3", $_SESSION["field3"]);
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
$row["_id"]="";
if(strcmp($Action,"editunit") == 0)
   {
         if(isset($_POST["field"])){$FieldName = $_POST["field"];}
         if(isset($_POST["unitid"])){$ID = $_POST["unitid"];}
         if(isset($_GET["field"])){$FieldName = $_GET["field"];}
         if(isset($_GET["unitid"])){$ID = $_GET["unitid"];}
         if(isset($_GET["notes"])){$Notes = $_GET["notes"];}
         if(isset($_POST["notes"])){$Notes = $_POST["notes"];}
         if(isset($_POST["filters_due"])){$FiltersDue = $_POST["filters_due"];}
         $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
        $data = json_decode($jsonString, true);
        foreach ($data['equipment'] as &$object) 
         {
            if ($object['_id'] == $ID) 
		      { 
               switch ($FieldName) 
               {
               case "filters_due":
                   $object["filters_due"] = $FiltersDue;
                  break;
               case "notes":
                  $object["notes"] = $Notes;
                  break;
          
               default:
               break;
               }
               $jsonString = json_encode($data, JSON_PRETTY_PRINT);
               file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
      }
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
        



  
$AssignTo="";
$id="";

if(strcmp($Action, "unitdone")==0)
{
        if(isset($_POST['filter_rotation'])){$Rotation = $_POST['filter_rotation'];}
        if(isset($_GET['filter_rotation'])){$Rotation = $_GET['filter_rotation'];}
        $effectiveDate = getNextDueDate($Rotation);
        if(isset($_POST['unit_id'])){$row["_id"] = $_POST['unit_id'];}
        if(isset($_GET['unit_id'])){$row["_id"] = $_GET['unit_id'];}
        $NoFilterUsed="";
        if(isset($_POST['no_filters_used']))
            {
                $NoFilterUsed = "true";
                if(isset($_POST['no_filters_used'])){$row["filter_type"] = $_POST['no_filters_used'];}
            }else {
	            if(isset($_POST['filter_type'])){$row["filter_type"] = $_POST['filter_type'];}
            }
            if(isset($_POST['filter_type'])){$row["filter_type"] = $_POST['filter_type'];}
            if(isset($_GET['filter_type'])){$row["filter_type"] = $_GET['filter_type'];}
            if(isset($_GET['no_filters_used']))
            {
                $NoFilterUsed = "true";
                if(isset($_GET['no_filters_used'])){$row["filter_type"] = $_GET['no_filters_used'];}
            }else {
	            if(isset($_GET['filter_type'])){$row["filter_type"] = $_GET['filter_type'];}
            }
        $row["filters_last_changed"]= date("Y-m-d")." [".$UserName."]";
        if(isset($_POST['filters_due'])){$row["filters_due"] = $_POST['filters_due'];}
        if(isset($_GET['filters_due'])){$row["filters_due"] = $_GET['filters_due'];}
        $ActionDone = "changed filters";
        if(isset($_POST['filter_rotation'])){$Rotation=$_POST['filter_rotation'];}
        if(isset($_GET['filter_rotation'])){$Rotation=$_GET['filter_rotation'];}
        if(isset($_POST['filters_used'])){$FiltersUsed=$_POST['filters_used'];} 
        if(isset($_GET['filters_used'])){$FiltersUsed=$_GET['filters_used'];}   
        if(isset($_POST["unit_id"])){$row["_id"]= $_POST["unit_id"];}
        if(isset($_GET["unit_id"])){$row["_id"]= $_GET["unit_id"];}
        $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
        $data = json_decode($jsonString, true);
        foreach ($data['equipment'] as &$object) 
{
            if ($object['_id'] == $row["_id"]) 
		         {
                $object["filters_due"] = $effectiveDate;
                $object["filter_type"] = $row["filter_type"];
                $FiltersLastChanged = date('Y-m-d') . " [".$_SESSION["user_name"]. "]";
                $object["filters_last_changed"] = $FiltersLastChanged;
                $object["assigned_to"] = "";
            	}
}
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
}

//CREATE AN ARRAY OF FILTER TYPES
   $arFilterTypes = arrayFromDataJson("filter_types", "type");

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
<body onunload="saveScrollPosition();"  onload="doOnloadStuff();OpenInfoWindow();setScrollPosition();">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
function doOnloadStuff()
{
//GET TASKS SAVED TO COOKIE

try {
  // Existing code
  var thisTask = [];
  console.log("tasklist from cookie=" + getJavaCookie("tasklist"));
  const allTasks = JSON.parse(getJavaCookie("tasklist"));
  for (var i = 0; i < allTasks.tasks.length; i++) {
    addTaskToForm(allTasks.tasks[i].id, allTasks.tasks[i].unitname, true);
  }
} catch (error) {
  // Handle errors here
  console.error("Error processing tasklist:", error);
  // You can also display an error message to the user, log the error to a server, etc.
}


}
</script>     
<script>
function editUnit(unit_id, field){
switch (field) {
case "filters_due":
document.getElementById("modaleditUnit").style.display="block";
document.getElementById("txtUnitId").value = unit_id;
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

<input type="hidden" name="unitid" value="" id="txtUnitId">
<input type="hidden" name="action" value="editunit">

<input type="text" id="txtFieldToUpdate" name="field" value="" style="display:none;margin-left:auto;margin-right:auto;">

<input type="date" style="display:none;margin-left:auto;margin-right:auto;" name="filters_due" id="modalDate" onchange="this.form.submit();"><button type="button" class="btn button btn-danger" style="width:100px;height:50px;margin-left:auto;margin-right:auto;text-align:center;margin-left:auto;margin-right:auto;" onclick="document.getElementById('modaleditUnit').style.display='none';">Cancel</button>

<textarea id="txtNotes" name="notes" style="overflow:auto;width:8vw;margin-left:auto;margin-right:auto;text-align:center;width:20vw;border-radius:5%;display:none;"></textarea>
<div style="display:flex;flex-direction:column;width:200px;height:20vh;">
<button class="btn button btn-success" style="width:100px;height:50px;text-align:center;display:none;" onclick="this.form.submit();" style="display:none;" id="btnModalSubmit">Submit</button></form></p>
    </div>
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

   .DidYouKnow {
      background-color:black;
      color:white;
      vertical-align:center;
      display: inline-block;
      width:100%;
      height:fit-content;
      text-align: left;
   }
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
}
</style>

<script>
   function OpenInfoWindow(){
    try{
      x=getCookie('cookie_infoid');
      console.log("OpenInfoWindow="+x);
      document.getElementById(x).style.display = "block";
    } catch (error) {
        console.log("error with function OpenInfoWindow");
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
function showinfo($divID, $UnitName, $UnitId) {
         var my_disply = document.getElementById($divID).style.display;
         setCookie2("cookie_infoid", $divID);
         document.cookie = "unitid=" + $UnitId;
        if(my_disply == "none"){
               saveScrollPosition();
              document.getElementById($divID).style.display = "block";
              setTimeout(setScrollPosition, 100);
            }else{
        setCookie2("cookie_infoid", "void");
     }
   }
</script>

<script>
function closeinfo($divID) {

        document.getElementById("tblUnitInfo"+$divID).style.display = 'none';
document.getElementById("checkCloseInfo"+$divID).checked = false;
        setCookie2("cookie_infoid", "void");
     }
</script>
<?php

//CREATING ARRAY OF ALL UNIT NAMES FOR SEARCH BOXES

   $arUnits = arrayFromDataJson("equipment", "unit_name");

//print_r($arUnits);
?>
<script>
<?php
$js_array = json_encode($arUnits);
echo "var unitsArray = ". $js_array . ";\n";
?>
</script>
<?php

$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);

// Extract the "filter_size" values into an array
$arFiltersOOS=[];
$row["filter_size"] = [];
foreach ($data["filters"] as $filterObject) {
   if($filterObject["filter_count"] <= 0){
    $arFiltersOOS[] = $filterObject["filter_size"];
}
}

$AssignedTo = null;
if (isset($_POST["action"])) {
    $Action = $_POST["action"];
}

if(isset($_POST["ckBox"])){
   $row["_id"] = $_POST["ckBox"];
 foreach ($_POST["ckBox"] as $unitnam)
 {
	 //echo "name=".$unitnam;
 }
}else
{
   //echo "check box not found";
}

if (strcmp($Action,"reassign_task")==0) 
   {
      $ReAssignedTo = "";
      $Assigned_To = "";
      $uid="";
      if (isset($_GET["id"])) {$uid = $_GET["id"];}
      if (isset($_GET["reassignto"])) {$ReAssignedTo = $_GET["reassignto"];}
      if (isset($_GET["assignedto"])) {$Assigned_To = $_GET["assignedto"];}
      //echo "ReAssignedTo=".$ReAssignedTo. " Assigned_To=".$Assigned_To;
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
        $data = json_decode($jsonString, true);
        foreach ($data['equipment'] as &$object) 
         {
            if ($object['_id'] === $uid) 
		      {
                $object["assigned_to"] = $ReAssignedTo;
            	}
         }
      $jsonString = json_encode($data, JSON_PRETTY_PRINT);
      file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);           
}

if (strcmp($Action,"addalltasks")==0) 
   {
      //$row["_id"] = $_POST['ckBox'];
      $data = array();
      foreach ($row["_id"] as $unit)
         { 
            $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
            $data = json_decode($jsonString, true);
            foreach ($data['equipment'] as &$object) {
               if ($object['_id'] == $unit) {
                  $object['assigned_to'] = $_SESSION["user_name"];
               }
            }
            $jsonString = json_encode($data, JSON_PRETTY_PRINT);
            file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
         }
   }
  
   if (isset($_GET['unit_name'])) {$UnitName=$_GET['unit_name'];}
   if(isset($_POST['_id'])){$row["_id"] = ($_POST['_id']);}
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

function getLineNumber() {
  $filename = "table_users.json";
  $data = json_decode(file_get_contents($filename), true);

  if (json_last_error() !== JSON_ERROR_NONE) {
    // Handle JSON decode error (optional)
    return null; // Or throw an exception
  }
   $X=0;
  foreach ($data as $user) {
    if ($user['user_name'] == $_SESSION["user_name"]) {
      if (isset($user['didYouKnow'])) {
        foreach ($user['didYouKnow'] as $key => $value) {
          if ($value != "dont_show") {
            return $X; // Return first non-"dont_show" value
          }
           $X=$X+1;
        }
      }
      break; // Exit loop after finding the user
    }
  }

  // User not found or no "didYouKnow" field
  return null;
}


  $LineNumber ="";
  $LineNumber = getLineNumber();
  $DidYouKnow  = getDidYouKnow($LineNumber);
  //echo "lineNumber=".$LineNumber."<br>did you know= ".$DidYouKnow."<br>";
  function getDidYouKnow($LineNumber) {
   $fileName = "didYouKnow.txt";
 
    $fileHandle = fopen($fileName, "r");
  
    if ($fileHandle === false) {
      // Handle file opening error (optional)
      return false;
    }
  
    // Loop to find the desired line
    $currentLine = 1; // Start with line 1
    while (!feof($fileHandle)) {
      $line = fgets($fileHandle);
  
      // Extract line number and content
      $parts = explode(" ", $line, 2); // Split at first space
      //echo $parts[0]. "=".$LineNumber."<br>";
      if (count($parts) >= 2 && trim($parts[0]) == $LineNumber.".") {
        //echo "<br>line number found<br>";
        $DidYouKnow = trim($parts[1]); // Remove leading/trailing whitespace
        fclose($fileHandle);
        return $DidYouKnow;
      }
  
      $currentLine++; // Increment line counter for the next iteration
    }
  
    // Line not found
    fclose($fileHandle);
    return false;
  }
  

?>
<div id="divTasks" style="overflow-x: auto;overflow-y: hidden;white-space: nowrap;background-color:green;display:inline-block;">
 <form action="ListEquipment.php" method="post" id="frmsubmittasks" title="Add units to tasks list" style="background-color:green;color:white;height:100px;margin-left:20px;display:none;flex-direction:row;"><button class="btn btn-primary rounded-9 btn-sm" style="width: fit-content;height:60px;white-space: normal;border-radius:10px;margin-top:20px;" onclick="submittasks('<?php echo $UserName ?>');">Submit Tasks</button><button type="button" class="btn btn-danger rounded-9 btn-sm" style="width: fit-content;height:60px;white-space: normal;border-radius:10px;margin-top:20px;" onclick="if(confirm('Delete all tasks?') == true){cancelAllTasks();}">Cancel All</button>
 <input type="hidden" name="action" value="addalltasks">
   <input type="hidden" name="username" id="username" value="<?php echo $UserName ?>"></div><select id="slctTasks" style="display:none;"></select>
   </form>
   <div id="divDidYouKnow" class="DidYouKnow" <?php if($LineNumber == ""){echo "style='display:none'";} ?>"><input type="text" id="txtDidYouKnowLineNumber" value="<?php echo $LineNumber ?>" style="display:none;"><img src="images/DidYouKnow.jpg" style="width: 50px;height:50px;"><?php echo $DidYouKnow ?>&nbsp;&nbsp;&nbsp;<button class="btn btn"success" onclick="document.getElementById('divDidYouKnow').style.display='none';">Close</button><label style="float:right;margin-right:100px;color:red;">&nbsp;&nbsp;&nbsp;Do not show again.&nbsp;&nbsp;&nbsp;<input type="checkbox" onclick="updateDidYouKnow();" style="margin-top:10px;width: 25px;height:25px;" id="ckDidyouknow"></label></div>
<table class="myTable" id="myTable" style="width:100%;">
<thead>
    <tr style="top:0px;position:sticky;z-index:4;" id="tableheader">
        <th style="background-color:#07ff10;color:black;text-align:center;">Add<br>Task</th>
        <th style="background-color:#07ff10;color:black;text-align:center;">Filters<br>Done</td>
        <th style="background-color:#07ff10;color:black;width:150px;" id="thunitname">Unit Name
		</th>
      
		<form action="ListEquipment.php" method="post">
        <th style="background-color:#07ff10;color:black;">
        <div class="dropdown show">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#07ff10;color:black;font-weight:bold;"><?php echo $_SESSION["field2"] ?></a>

      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" >
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field2=Area Served">Area Served</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field2=Location">Location</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?field2=Last Changed">Last Changed</a>
      </div>
      </div>
		</th>
        <th id="thfield3" style="background-color:#07ff10;color:black;">
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
        <th style="background-color:#07ff10;color:black;"><font color="black">Due Date<!--<?php if(strcmp($ByDate, "no") != 0){ echo "<br><div style='font-size:10px;background-color:black;color:aqua;'>".$ByDate."</div>";} ?>--></th>
        
    </tr></thead>
  
    <?php
    
   //GET LAST QUERY IN CASE PAGE WAS TEMPERARALY CHANGED
   $LastQuery ="";
    //lAST QUERY IS SAVED WITH JAVASCRIPT WHEN ELEMENTS ARE CLICK USING FUNCTION setLastQuery()
   if(isset($_COOKIE["cookie_lastquery"]))
      {
         $LastQuery=$_COOKIE["cookie_lastquery"];
         //echo "last qry=".$_COOKIE["cookie_lastquery"];
      }
      else
      {
         $LastQuery="SELECT";
      }
if(strcmp($Action,"clearsearch")==0){
      $query= "SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment;";
   }

if(strlen($LastQuery) >= 0)
{
  // $query="NORMAL";
}
else
{
   //$query = $LastQuery;
}

//--------------GET DATA BY SEARCH--------------

if(strpos($LastQuery, "LIKE"))
   {
      if(isset($_COOKIE["SearchWords"]))
      {
         $SearchWords = $_COOKIE["SearchWords"];
      }
      else
      {
         $SearchWords = "";
      }
$searchFields = [
    "_id",
    "unit_name",
    "location",
    "area_served",
    "filter_size",
    "filters_due",
    "belts",
    "notes",
    "filter_rotation",
    "filter_type",
    "filters_last_changed",
    "assigned_to",
    "image"
];
$equipment = [];
foreach ($data["equipment"] as $unit) {
    foreach ($searchFields as $field) {
      //echo "filed=".$field. " SearchWords=".$SearchWords;
      if($SearchWords != ""){
        if (strpos(strtolower($unit[$field]), strtolower($SearchWords)) !== false) {
            $equipment[] = $unit;
            break; 
        }
      }
    }
}
   }

$Id="";
$UnitName="";
$row["location"] ="";
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
switch ($LastQuery) 
{
  case "OVERDUE":
   $today = date("Y-m-d");
   $equipment = [];
   foreach ($data["equipment"] as $obj) 
   {
      $filterDueDateObject = new DateTime($obj["filters_due"]);
      $todayObject = new DateTime($today);
      if ($filterDueDateObject < $todayObject) 
      {
         $equipment[] = $obj;
      }
   }
   break;

    case "NORMAL":
   {
   //echo "the lastquery=".strpos($LastQuery,"NORMAL")."<br>";
    $equipment = $data['equipment'];
    //print_r($equipment);
   }
   break;

   case "SELECT":
   {
   //echo "the lastquery=".strpos($LastQuery,"NORMAL")."<br>";
    $equipment = $data['equipment'];
    //print_r($equipment);
   }
   break;

   case "ASC":
   {
      usort($data['equipment'], function($a, $b) 
         {
      return strtotime($a['filters_due']) - strtotime($b['filters_due']);
         });
      $equipment = $data["equipment"]; 
   }
   break;

   case "DESC":
   {
      usort($data['equipment'], function($a, $b) 
      {
         return strtotime($b['filters_due']) - strtotime($a['filters_due']);
      });
      $equipment = $data["equipment"];
      break;
   }

   case "today":
   {
      $today = date('Y-m-d');
      //echo "today=".$today;
      $equipment = [];
         foreach($data["equipment"] as $obj)
            {
               //echo $obj["filters_due"] ."==". $today."<br>";
            if($obj["filters_due"] == $today)
               {
                  $equipment[]=$obj;
                  if(count($equipment) <= 0){echo "nothing here";}
               }
            }
   }
   //print_r($equipment);
   break;
}

if(count($equipment) > 0)
{
    foreach($equipment as $row)
      {
         $X=0;
         $EquipmentList="";
         $AssignedTo = $row["assigned_to"];
         $today = date('Y-m-d');
         $someDate = new DateTime($row["filters_due"]);
         $daysoverdue= s_datediff("d",$today,$someDate,true);
         ?>
         <tr>
         <?php 
         if(is_array($arFiltersOOS) > 0){
         if(getFilterCount($row["filter_size"], $arFiltersOOS)=="outofstock")
         {
            $outofstock="outofstock";
         }
         else
         {
            $outofstock="";
         }
         }		
			$myCss = GetCss($Theme,$daysoverdue,$outofstock); 
         $myCss = $myCss ."font-family:".$_SESSION["font_family"];
			?>
         <td style='<?php echo $myCss ?>'>
            <?php 
            if($AssignedTo == "")
               {
                  ?>
                  <label class="container"><input type="checkbox" class="checkmarkListEquipment" id="cktask<?php echo $row["_id"] ?>" onchange="addTaskToForm('<?php echo $row["_id"] ?>','<?php echo $row["unit_name"] ?>', false);" value="<?php echo $row["_id"] ?>">
                  <span class="checkmark"></span>
                  </label>
                  <?php 
               }
               else
               { 
                  ?>
                  <div style="text-align:center;width:100%;"  onclick="showSelectUsers(<?php echo $row["_id"] ?>);"><?php echo $AssignedTo ?></div><div id='divSelectUser<?php echo $row["_id"] ?>' style='display:none;margin-left:auto;margin-right:auto;'>
                  <div class="dropdown show">
                  <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#07ff10;color:black;font-weight:bold;">Re-asign too
                  </a>      
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" >
                  <a class='dropdown-item' href='#' onclick="document.getElementById('divSelectUser<?php echo $row["_id"] ?>').style.display='none';">Cancel</a>
                  <?php
                  foreach ($arUsers as $value) 
                     {
                        echo "<a class='dropdown-item' href='".$_SERVER['SCRIPT_NAME']."?action=reassign_task&assignedto=".$UserName."&reassignto=".$value."&id=".$row["_id"]."'>".$value."</a>";
                     }
                  echo "</div></div></td>";
               }
               ?>
               <td style='<?php echo $myCss ?>;text-align:center;'>
               <label class="switch">
               <input type="checkbox" id="ckShowFilterTypes<?php echo $row["_id"] ?>" onChange="showFilterTypes('<?php echo $row["_id"] ?>');">
               <span class="slider round"></span>
               </label></checkbox>
               <?php $myURL = "ListEquipment.php?action=unitdone&filter_rotation=".$row["filter_rotation"]."&unit_id=".$row["_id"]."&unit_name=".$row["unit_name"]."&filter_size=".$row["filter_size"]."&filters_used=".$row["filter_size"]."&filters_due=".$row["filters_due"]; ?>
               <br>
               <div class="dropdown d-none" id="slctFilterTypes<?php echo $row["_id"] ?>">
               <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  select filter used
               </button>
               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
               <?php
               foreach ($arFilterTypes as $value) {
                  echo "<a class='dropdown-item' href='".$myURL."&filter_type=".$value."'>".$value."</a>";
            }
            ?>
            <a class='dropdown-item' href='<?php echo $myURL ?>&filter_type=no_filters_used'>No filters used</a>
            </div>
            </div>
            <A style="display:none;" id="ahref<?php echo $row["_id"] ?>" href="ListEquipment.php?action=unitdone&username=<?php echo $UserName ?>&filter_rotation=<?php echo $row["filter_rotation"] ?>&unit_id=<?php echo $row["_id"] ?>&unit_name=<?php echo $row["unit_name"] ?>&filter_size=<?php echo $row["filter_size"] ?>&filters_used=<?php echo $row["filter_size"] ?>&filters_due=<?php echo $row["filters_due"] ?>" style="font-weight: bold;font-size: 50px;color:white;background-color:gold;height:170px;width:100%;"><div style="font-weight: bold;font-size: 50px;background-color:white;height:50px;">SUBMIT</div></A>
            </td>
            <td style="<?php echo $myCss ?>" id='info'>
            <a href="#" onclick="showinfo('tblUnitInfo<?php echo $row["_id"] ?>', '<?php echo $row["unit_name"] ?>','<?php echo $row["_id"] ?>'); CanYouShare('tdShare<?php echo $row["_id"] ?>','<?php echo $row["unit_name"] ?>','<?php echo $row["_id"] ?>');"> <div style="<?php echo $myCss ?>;text-align:center;width:100%;" id='mydiv<?php echo $row["_id"] ?>'>
				<?php echo $row["unit_name"] ?></div></a>
            <table style="display:none;" id="tblUnitInfo<?php echo $row["_id"] ?>">
            <tr id = "trinfo"><td><div class="bg-info" style="box-shadow: 4px 4px black;border-radius:10px;height:25px;text-align:center;font-weight:bold;"  name="checkbox" value="value" id="checkCloseInfo<?php echo $row["_id"] ?>" title="Close information window for <?php echo $row["unit_name"] ?>" onclick="closeinfo('<?php echo $row["_id"] ?>');">CLOSE</div>
            </td><td></td>
            </tr>
            <tr><td class="tdUnitInfo" style="text-align:left;">Unit name</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $row["unit_name"] ?></td></tr>
            <tr><td class="tdUnitInfo" style="text-align:left;">Assigned too</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $AssignedTo ?></td></tr>
            <tr><td class="tdUnitInfo" style="text-align:left;">Location</td><td class="tdUnitInfo2" style="text-align:left;"><div id="divLocation" style="max-height: 70px; overflow-y: auto;"><?php echo $row["location"] ?></div></td></tr>
            <tr><td class="tdUnitInfo" style="text-align:left;">Area served</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo  $row["area_served"] ?></td></tr>
            <tr><td class="tdUnitInfo" style="text-align:left;">Filter size</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $row["filter_size"] ?></td></tr>
            <tr><td class="tdUnitInfo" style="text-align:left;">Filter type</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $row["filter_type"] ?></td></tr>
            <tr><td class="tdUnitInfo" style="text-align:left;width:100px;"> Filters due</td><td class="tdUnitInfo2" style="cursor:grab;text-align:left;" onclick="editUnit('<?php echo $row["_id"] ?>', 'filters_due');document.getElementById('modalDate').value='<?php echo $row["filters_due"] ?>';"><?php echo $row["filters_due"] ?></td></tr>
               <tr><td class="tdUnitInfo" style="text-align:left;">Filters last changed</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $row["filters_last_changed"]?></td></tr>
               <tr><td class="tdUnitInfo" style="text-align:left;">Rotation</td><td class="tdUnitInfo2" style="text-align:left;"><?php echo $row["filter_rotation"] ?></td></tr>
            <tr><td class="tdUnitInfo" style="text-align:left;">Belts</td><td class="tdUnitInfo2" style="text-align:left;"> <?php echo $row["belts"] ?></td></tr>
               <tr><td class="tdUnitInfo" style="text-align:left;">Notes</td><td class="tdUnitInfo2" style="text-align:left;" onclick="editUnit('<?php echo $row["_id"] ?>', 'notes');document.getElementById('txtNotes').value=document.getElementById('divNotes<?php echo $row["_id"] ?>').innerHTML;"><div id="divNotes<?php echo $row["_id"] ?>" class="wrapper" style="cursor:grab;"><?php echo $row["notes"] ?></div><textarea style="display:none;color:white;" id="editNotes<?php echo $row["_id"] ?>" class="wrapper" onkeyup="document.getElementById('aEditNotes<?php echo $row["_id"] ?>').href='<?php echo $_SERVER["SCRIPT_NAME"] ?>?unitid=<?php echo $row["_id"] ?>&notes='+document.getElementById('editNotes<?php echo $row["_id"] ?>').value+'&field=notes&action=editunit';"><?php echo $row["notes"] ?></textarea><a href="" id="aEditNotes<?php echo $row["_id"] ?>"><button style="display:none;" type="button" id="editNotesSubmit<?php echo $row["_id"] ?>" class="btn btn-Success">Save</button></a></td></tr>
               <tr><td class="tdUnitInfo" style="text-align:left;">
               <a href="webEditUnit.php?_id=<?php echo $row["_id"] ?>"><div class="btn btn-warning" title="Edit <?php echo $row["unit_name"] ?> properties" style="box-shadow: 4px 4px black;">EDIT UNIT</div></a>
                                 
               </td><td id="tdShare<?php echo $row["_id"] ?>" class="tdUnitInfo" style="text-align:left;">              
                   
                   <?php 
                   if ($row["image"] != null)
                     {
                        echo "<a href='" . $row["image"] ."' target='_blank'><img src='".$row["image"]."' alt='". $row["unit_name"] ."' style='width:100px;height:100px;'></a>";
                     }
                     ?>
                     </td></tr>
                </table>
                </td>
                
                <?php
                if(strcmp($_SESSION["field2"], "Location")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'>". $row["location"]."</td>";
                  }
                if(strcmp($_SESSION["field2"], "Area Served")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'>". $row["area_served"] ."</td>";
                  }
                if(strcmp($_SESSION["field2"], "Last Changed")==0)
                  {
                     echo "<td style='".$myCss."'>". $row["filters_last_changed"]."</td>";
                  }
                if(strcmp($_SESSION["field3"], "Location")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'>". $row["location"]."</td>";
                  }
                if(strcmp($_SESSION["field3"], "Area Served")==0)
                  {
                     echo "<td style='".$myCss.";width:25vw;white-space: initial;'>". $row["area_served"] ."</td>";
                  }
                  if(strcmp($_SESSION["field3"], "Last Changed")==0)
                  {
                     echo "<td style='".$myCss."'>". $row["filters_last_changed"]."</td>";
                  }
                  if(strcmp($_SESSION["field3"], "Notes")==0)
                  {
                     if(strlen($row["notes"]) >= 12)
                        {
                           ?><td style='<?php echo $myCss ?>;cursor:grab;'><div style='border:2px solid white;overflow:auto;max-width:120px;max-height:50px;' onclick="editUnit('<?php echo $row["_id"] ?>', 'notes');document.getElementById('txtNotes').value='<?php echo $row["notes"] ?>'"><?php echo $row["notes"] ?></div></td><?php
                        }
                        else
                        {
                           ?><td style='<?php echo $myCss ?>;cursor:grab;' onclick="editUnit('<?php echo $row["_id"] ?>', 'notes');document.getElementById('txtNotes').value='<?php echo $row["notes"] ?>';"><?php echo $row["notes"] ?></td><?php
                        }
                  }
                  if(strcmp($_SESSION["field3"], "Filter Size")==0)
                  {
                        
                      $outofstock = getFilterCount($row["filter_size"],$arFiltersOOS);
						   //$outofstock=getFilterCount($fs, $arFiltersOOS);
                     $myCss=getCss($Theme,$daysoverdue,$outofstock);
                     $myCss ."font-family:".$_SESSION["font_family"];
                     $AlCss=GetAlinkCss($Theme,$daysoverdue,$outofstock);
                     $AlCss = $AlCss . ";font-family:".$_SESSION["font_family"];
						  // echo "myCss=".$myCss." theme=".$Theme." days=".$daysoverdue." size=".$row["filter_size"]." oos=".$outofstock." Alink font color:".$AlCss."<br>";
                     //REMOVE AMOUNT FROM FILTERSIZE FOR BOOKMARK HYPERLINK TO FILTERS PAGE
                     $NumberOfFilters = substr_count($row["filter_size"],"(");
                     $B=0;
                     if($NumberOfFilters == 1)
                           {
                           $myfilter = $row["filter_size"];
                           $x = strpos($myfilter, ")",0);
                           $myfilter = substr($myfilter, $x + 1, strlen($myfilter)- $x);
                           $myfilter_id = $myfilter . $row["filter_type"];
                           $myfilter_id = str_replace(" ","",$myfilter_id);
                           ?>
                           <td style="<?php echo $myCss ?>"><a href="#" style="<?php echo $AlCss ?>" onclick="getFilter('<?php echo $myfilter ?>');"><?php echo $row["filter_size"] ?></a></td>
                           <?php
                           }
                        else
                           {
                              $arfilters = explode(" ", $row["filter_size"]);
                              echo "<td style='".$myCss."'>";
                              foreach ($arfilters as $value) 
                              {
                                 $B=$B+1;
                                 if($B <= 2)
                                 {
                                 $myfilter = $value;
                                 $x = strpos($myfilter, ")",0);
                                 $myfilteronly = substr($myfilter, $x + 1, strlen($myfilter)- $x);
                                 $numoffilters=substr($myfilter, 0, $x + 1);
                                 ?>
                                 <a href="#" onclick="getFilter('<?php echo $myfilteronly ?>');" style="<?php echo $AlCss ?>"> <?php echo $numoffilters.$myfilteronly ."</a><br>" ?>
                                 <?php
                                }
                              }
                     echo "</td>";
                    }                    
                  }

                ?>
                <td style="<?php echo $myCss ?>"><div style="cursor: grab;" onclick="editUnit('<?php echo $row["_id"] ?>', 'filters_due');document.getElementById('modalDate').value='<?php echo $row["filters_due"] ?>';"><?php echo $row["filters_due"] ?></div></td>

                </tr>

            <?php
 }
   }
   else
   {
      echo "<div style='width:100%;background-color:green;color:white;'>You currently have no units in your database. click <a href='webAddUnit.php'>HERE</a> to start in putting data for your air conditioning units and there filter change times.</div>";
   }
     ?>
  
    
     
</table>
<div style="display:none;" id="equipmentlist" style="back-color:white;color:black;"><font color="white"><?php echo $EquipmentList ?></div>
<?php 
echo  count($equipment) . " units in returned by query."; 


function getFilterCount($fsize, &$arFiltersOOS)
{
if(is_array($fsize)){
   foreach($arFiltersOOS as $size)
      {
      //echo $fsize . "=" . $size."<br>";
         if(strpos($fsize, $size) > 0)
            {
              return "outofstock";
            }
      }
}
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
function IsThere(element) {
   if(document.getElementById(element) === null || document.getElementById(element) === undefined)
   {
      return false;
   }
   else
   {
      return true
   }}

                   function CanYouShare(tdID, UnitName, UnitID)  {                    
                     if (IsThere("shareButton"+UnitID) === false) 
                        {
                           const shareButton = document.createElement("img");
                           shareButton.setAttribute("id", "shareButton"+UnitID);
                           shareButton.setAttribute("style", "margin-left:50px;box-shadow: 5px 5px 10px black;border-radius:50%;");
                           shareButton.setAttribute("src", "images/share.jpg");
                           shareButton.setAttribute("onclick", "share('"+ UnitName + "', '"+UnitID + "');");
                           elem = document.getElementById(tdID);
                           elem.appendChild(shareButton);
                           shareButton.style.display = "inline-block";
                           shareButton.style.width = "70px";
                           shareButton.style.height = "70px";
                        }
                   }
                   </script>
                   <script>
                   function share(unit_name, unit_id)
                   {
                     if (navigator.share) {
                     const shareData = {
                        title: "Info for " + unit_name,
                        text: "Info brought to you by FilterManager",
                        url: "https://filtermanager.net/share.php?id="+ unit_id+"&folder=<?php echo $_SESSION["backup_folder"] ?>",
                     };

                     navigator.share(shareData)
                        .then(() => console.log("Content shared successfully!"))
                        .catch((error) => console.error("Error sharing:", error));
                  } else {
                     console.error("Sharing API not supported in this browser.");
                  } 
                             
                   }
</script>
<script>
function getFilter(filtersize){
   console.log("starting getfilter");
var filterData = {
         type: '',
         size: '',
         by: "searchwords",
         searchwords: filtersize,
         lastupdated: ""
         };
    var jsonString = JSON.stringify(filterData);
   setJavaCookie("filters_lastquery", jsonString,1);  
   location.href="web_update_filters.php";       
}

</script>
<script>
   function reAssignTask(id,assignedto){
      reassignto=document.getElementById("slctAssignTo").value;
      window.location = "<?php echo $_SERVER['SCRIPT_NAME'] ?>?action=reassign_task&assignedto="+assignedto+"&reassignto="+reassignto+"&id="+id;
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
function setCookie2(cookiename, cookievalue){
   var now = new Date();
  var time = now.getTime();
  now.setFullYear(now.getFullYear() +10);//ten years
    //document.cookie = 'myCookie=to_be_deleted; expires=' + now.toUTCString() + ';';
  //document.cookie = "username=John Doe; expires=Thu, 18 Dec 2013 12:00:00 UTC; path=/";
  document.cookie = cookiename+"="+cookievalue+";expires="+now.toUTCString()+";path=/";
  //alert("done setCookie2 cookiename="+cookiename+" cookievalue="+getCookie(cookiename));
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
<script>
   function updateDidYouKnow() {
//  const username = "<?php echo isset($_SESSION['user_name']) ? $_SESSION['user_name'] : ''; ?>";
  const lineNumber = document.getElementById('txtDidYouKnowLineNumber').value;

  // Validate input (optional)
  if (!username || !lineNumber) {
    alert("Please enter a username and line number.");
    return;
  }

  // Prepare AJAX request
  const xhr = new XMLHttpRequest();
  const url = "didYouKnow.php?line_number=" + lineNumber;

  xhr.open("GET", url, true); // GET request, asynchronous

  // Handle response
  xhr.onload = function() {
    if (xhr.status === 200) { // Success
      const response = xhr.responseText;
      document.getElementById("divDidYouKnow").innerText =  response; // Or display response to user (optional)
    } else {
      document.getElementById("divDidYouKnow").innerText ="Error:", xhr.statusText; // Handle errors
    }
  };

  // Send the request
  xhr.send();
}
</script>

</body>
</html>