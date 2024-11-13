<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$Theme="";
$UserName="";
$overdue="";
$fs="";
include('../dbMirage_connect.php');
$ByDate="no";
//print_r($_COOKIE);
 if(isset($_POST['bydate'])){$ByDate = ($_POST['bydate']);}
 if(isset($_GET['bydate'])){$ByDate = ($_GET['bydate']);}
if (isset($_COOKIE["cookie_username"])) {
    $UserName = $_COOKIE["cookie_username"];
    }
    //echo "username=".$UserName;
    if (isset($_GET["username"])) {
    $UserName = $_GET["username"];
    //ECHO "_get username=". $_GET["username"];
    }
    if (isset($_POST["username"])) {
    $UserName = $_POST["username"];
    //ECHO "POST username=". $_POST["username"];
    }
if (isset($_COOKIE["ckoverdue"])) {
    //$overdue = $_COOKIE["ckoverdue"];
    //echo "cookie overdue=".$overdue;
    }


//----------------------FONT SIZE-------------
if(isset($_COOKIE["FontSize"]))
   {  
      $FontSize=$_COOKIE["FontSize"];
   }
if(!isset($_COOKIE["FontSize"]) && !isset($_POST["fontsize"]))
   {  
      $FontSize="10";
   }
if(isset($_POST["fontsize"]))
   {
      $FontSize=$_POST["fontsize"];
      setcookie("FontSize", $FontSize, time() + (86400 * 30), "/"); // 86400 = 1 day
   }
 //---------------------BY AREA---------------------
 
$Field2="location";
 if(isset($_COOKIE["field2"]))
   {  
      $Field2=$_COOKIE["field2"];
   }
if(!isset($_COOKIE["field2"]) && !isset($_POST["field2"]))
   {  
      $Field2="location";
   }
if (isset($_POST["field2"])) 
   {
      $Field2 = $_POST["field2"];
      setcookie("field2", $Field2, time() + (86400 * 30), "/"); // 86400 = 1 day
   }

$Field3="area_served";
 if(isset($_COOKIE["field3"]))
   {  
      $Field3=$_COOKIE["field3"];
   }
if(!isset($_COOKIE["field3"]) && !isset($_POST["field3"]))
   {  
      $Field3="location";
   }
if (isset($_POST["field3"])) 
   {
      $Field3 = $_POST["field3"];
      setcookie("field3", $Field3, time() + (86400 * 30), "/"); // 86400 = 1 day
   }
$Theme="none";

if (isset($_COOKIE["theme"])) {
    $Theme = $_COOKIE["theme"];
    //echo "<font color='black'>cookie theme=".$Theme;
}
if (isset($_POST["theme"])) 
{
   if(strcmp($_POST["theme"], "select_theme")!=0)
      {
         $Theme = $_POST["theme"];
         if(strcmp($Theme,"select_theme")==0){$Theme="location";}
         
         setcookie("theme", $Theme, time() + (86400 * 30 * 30), "/");
         //echo "<font color='black'>post theme=".$Theme;
      }
}
if (isset($_GET["theme"])) 
{
   if(strcmp($_GET["theme"], "select_theme")!=0)
      {
         $Theme = $_GET["theme"];
         if(strcmp($Theme,"select_theme")==0){$Theme="location";}
         
         setcookie("theme", $Theme, time() + (86400 * 30 * 30), "/");
         //echo "<font color='black'>post theme=".$Theme;
      }
}

if(strcmp($Theme,"none")==0)
{
   $TDstyle = "Light-tdPlain";
   $Theme="Light-tdPlain";
}
//echo "theme=".$Theme."<br>";
//if(strcmp($TDstyle,$FontSize)="30";
if (isset($_COOKIE["fontsize"])) {
    $FontSize = $_COOKIE["fontsize"];
}

if(isset($_POST["fontsize"])){
$FontSize=$_POST["fontsize"];
setcookie("fontsize", $FontSize, time() + (86400 * 30 * 30), "/");
}
if(isset($_GET["fontsize"])){
    $FontSize=$_GET["fontsize"];
    setcookie("fontsize", $FontSize, time() + (86400 * 30 * 30), "/");
    }

if(strcmp($FontSize, "")==0)
{
   $FontSize = "14px";
}

$Action="";
if(isset($_POST["action"])){
$Action=$_POST["action"];
}
$ShowOverDue="";
 $overdue="";

 if (isset($_COOKIE['ckoverdue']))
{
  // $overdue=htmlspecialchars($_COOKIE["ckoverdue"]);
   //echo "COOKIE ckoverdue=".$overdue;
}

else
{
   //echo "Post ckoverdue=".$overdue="true"."<br>";
   //$overdue="false";
}
if(isset($_POST['ckoverdue']))
   {
      if($_POST['ckoverdue'] == "on")
         {
            $ShowOverDue="checked";
         }
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
        //echo "rotation=".$FRotation;
        $Today= date("Y-m-d");
        $year = substr($Today, 0, 4);  // returns false
        $month = substr($Today, 5, 2);  // returns false
        //echo "month".$month."<br>";
        $day = substr($Today, 8, 2);  // returns false
        //echo "day=".$day."<br>";
        $m = intval($month);
        $Rotation = $FRotation;
        $r=intval($Rotation);
       $b= $m + $r;
       //echo "rot=".$FRotation."<br>";
       //echo "b=".$b."<br>";
        if(intval($day) > 28 ){ $day="28";}

        if($b>12 && intval($FRotation) != 24){
        //echo "$b>12"."<br>";
            $month = ($r + intval($month)) - 12;
            //echo "new month=".$month."<br>";
            $yr = 0;
            $yr = intval($year) + 1;
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
            $month  = $r + intval($month);
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

if(strcmp($Action, "unitdone")==0)
{
        if(isset($_POST['filter_rotation'])){$Rotation = $_POST['filter_rotation'];}
        $effectiveDate = getNextDueDate($Rotation);
        if (isset($_POST['task_id'])) {$RecID = $_POST['task_id'];}
        if(isset($_POST['unit_id'])){$UnitID = $_POST['unit_id'];}
        $NoFilterUsed="";
        if(isset($_POST['no_filters_used']))
            {
                $NoFilterUsed = "true";
                $FilterType = $_POST['no_filters_used'];
            }else {
	            $FilterType = $_POST['filter_type'];
            }
        $FiltersLastChanged = date("Y-m-d");
        if(isset($_POST['filters_due'])){$FiltersDue = $_POST['filters_due'];}
        $ActionDone = "changed filters";
        $Rotation=$_POST['filter_rotation'];
        $FiltersUsed=$_POST['filters_used'];   
        $query = "UPDATE tasks SET task_date_completed='" . $FiltersLastChanged . "', action='" .$ActionDone. "' WHERE task_id='" . $RecID . "';";
        if (mysqli_query($con, $query)) 
            {
            //echo "Task complete<br>";
            } else {
                echo "Error updating task record: " . mysqli_error($con);
            }
        if(isset($_POST["unit_id"])){$UnitId= $_POST["unit_id"];}
        $sql="UPDATE equipment SET filters_due='".$effectiveDate."', filter_type='". $FilterType."', filters_last_changed='" . $FiltersLastChanged. "', assigned_to = '' WHERE _id = '". $UnitID ."'";
        if (mysqli_query($con, $sql)) 
            {
                if(strcmp($NoFilterUsed, "yes")!=0){ExtractFilters($FiltersUsed, $FilterType);}
             }else {
                    echo "Error updating equipment record: " . mysqli_error($con);
            }
}
?>
<!DOCTYPE html>
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

    <title>Filters List</title>

<style>
/* Popup container */
.popup {
  position: relative;
  display: inline-block;
  cursor: pointer;
}

/* The actual popup (appears on top) */
.popup .popuptext {
  visibility: hidden;
  width: 160px;
  background-color: #555;
  color: #fff;
  text-align: left;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -80px;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class when clicking on the popup container (hide and show the popup) */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;}
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}

.myoption {
    background-color: #cc0000; 
    font-weight: bold; 
    font-size: <?php echo $FontSize ?>px; 
    color: white;
    height:40px;
    width:1px;
    background-color:#4CAF50;
}
.myselect {
	font-size:<?php echo $FontSize ?>px;
	color:black; 
	background-color: #07ff10; 
	height:50px;
	width:300px;
	font-weight:bold;
}
 <?php
if(strcmp($Theme,"Dark-tdPlain")==0)
{
   echo  "body {background-color: black;}";
   $TDstyle="Dark-tdPlain";
   $Theme="Dark-tdPlain";
   $bgColor="black";
}
if(strcmp($Theme,"Light-tdPlain")==0)
{
   echo  "body {background-color: white;}";
    $TDstyle="Light-tdPlain";
    $Theme="Light-tdPlain";
	$bgColor="white";
}
?>
textarea  
{  
   font-family:"Times New Roman", Times, serif;  
   font-size: 12px;   
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
background-color:white;
vertical-align: middle;
color:red;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;
padding: 2px;
white-space:nowrap;
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
.Light-tdPlain-outofstock{
background-color:orange;
color:black;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;black;
padding: 2px;
white-space:nowrap;
}
.Light-tdPlain-outofstock-overdue{
background-color:orange;
color:red;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;black;
padding: 2px;
white-space:nowrap;
}

	
/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark {

  height: 25px;
  width: 25px;
  background-color: #eee;
   display: inline-block;
  vertical-align: top;
  padding: 5px,5px,5px,5px;
  position: relative;
}    
  
<?php
//echo "<font color='black'>theme=".$Theme;
   if(strcmp($Theme,"Dark-tdPlain")==0)
   {
      echo "a:link {color:white;}"; 
      echo "a:visited {color:white;}";/* visited link */
      echo "a:hover {color:#0eff05;}";   /* mouse over link */
      echo "a:active {color:#45fc03}"; 
      $TDstyle="Dark-tdPlain";
   }
  if(strcmp($Theme,"Light-tdPlain")==0)
   {
      echo "a:link {color:black;}"; 
      echo "a:visited {color:black;}";/* visited link */
      echo "a:hover {color:#0eff05;}";   /* mouse over link */
      echo "a:active {color:#fe0000}";
      $TDstyle="Light-tdPlain";
   }

   if(strcmp($_POST["ckoverdue"], "checked")==0)
      {
         if(strcmp($Theme,"Dark-tdPlain")==0)
         {
            echo "a:link {color:red;}"; 
            echo "a:visited {color:red;}";/* visited link */
            echo "a:hover {color:#0eff05;}";   /* mouse over link */
            echo "a:active {color:#45fc03;}"; 
         }
        if(strcmp($Theme,"Light-tdPlain")==0)
         {
            echo "a:link {color:white;}"; 
            echo "a:visited {color:white;}";/* visited link */
            echo "a:hover {color:#0eff05;}";   /* mouse over link */
            echo "a:active {color:#fe0000}";
         }
      }
?>
    
</style>
    <style>

      .mytextarea {
  background-color:black;
  color:white;
  font-size:18px;
  cols;9;
  rows;6 
  width:140px;
  height:100px;
  }
   .unitInfo {
    display:none;
    background-color:gold;
    }
    .myFont{
    font-size:<?php echo $FontSize ?>px;
    )
}
 optgroup { font-size:18px; }
 
    input[type="text"] {
  background-color:black;
  color:white;
  font-size:18px;
  padding-right: 5px;
  padding-left: 5px;
  height:60px;
  width:100px;
  }
   .tdUnitInfo{
   padding:0px; 
   height:10px; 
   background-color:blue; 
   color:white;"
   
   }
   .tdUnitInfo2{
   padding:0px; 
   height:10px; 
   background-color:black; 
   color:white;"
   
   }
   
 .super-centered {
    position:absolute; 
    width:40px;
    height:40px;
    text-align:center; 
    vertical-align:middle;
    z-index: 9999;
}
   .myButton{
   background-color: #4CAF50; /* Green */
   border: none;
   color: white;
   font-size:40px;
   padding: 1px 1px;
   text-align: left;
   text-decoration: none;
   display: inline-block;
   width:300px; 
   height:120px; 
   border-radius: 30px;
  }
    * {
    box-sizing: border-box;
    }
    #submittasks {
    width: 20em;  height: 2em;
    }
   
    #myInput {
    
    background-position: 10px 12px; /* Position the search icon */
    background-repeat: no-repeat; /* Do not repeat the icon image */
    width: 100%; /* Full-width */
    font-size: 16px; /* Increase font-size */
    padding: 12px 20px 12px 40px; /* Add some padding */
    border: 1px solid #ddd; /* Add a grey border */
    margin-bottom: 12px; /* Add some space below the input */
    }
    
    #myTable {
    border-collapse: collapse; /* Collapse borders */
    width: 100%; /* Full-width */
    border: 1px solid #ddd; /* Add a grey border */
    font-size: 32px; /* Increase font-size */
    }
   
    #myTable th {
      background-color:black;
      color:white;
      align:center;
	  font-size: <?php echo $FontSize ?>px;
    }
    #myTable td {
    text-align: left; /* Left-align text */
    vertical-align: middle;
    padding: 2px; /* Add padding */
    font-size: <?php echo $FontSize ?>px;
    }
    
    #myTable tr{  
    border-bottom: 1px solid #ddd;
    font-size: <?php echo $FontSize ?>px;    
    }
    
   
    #myTable tr, tr:hover{
    /* Add a grey background color to the table header and on hover */
    background-color: #FFD700;
    }
    </style>
    <STYLE>A {text-decoration: none;} </STYLE>
</head>
<body style="<?php echo $TDstyle ?>"  onload="ckIfLoggedIn();">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <?php include('../functions.php'); ?>

<script>
function clearsearch(){
	document.getElementById("myInput").value="";
	document.getElementById('clearsearch').style.display='none';
   myFunction();
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
function submittasks() {
if(document.getElementById("username").value == "")
   {
      alert("no username");
   }
else
   {
      document.getElementById("frmsubmittasks").submit();
   }
}
</script>

<script>
function showinfo($divID, $UnitName, $UnitID) {
         var my_disply = document.getElementById($divID).style.display;
         document.cookie = "unitname=" + $UnitName;
         document.cookie = "unitid=" + $UnitID;
        if(my_disply == "none")
              document.getElementById($divID).style.display = "block";
        else
              document.getElementById($divID).style.display = "none";
     }
</script>
<script>
function closeinfo($divID) {
        var my_disply = document.getElementById($divID).style.display;
        document.getElementById($divID).style.display = "none";
     }
</script>
<?php
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

//echo "<font color='green'>theme=". $Theme;
if (strcmp($Action,"addalltasks")==0 || isset($_POST["ckBox"])) 
   { 
      $data = array();
      foreach ($unitid as $unit)
         { 
		 //echo "unit=".$unit."<br>";
            $query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation,filters_last_changed, assigned_to FROM equipment WHERE _ID=".$unit.";";
            $result = $con->query($query);
            $row = $result -> fetch_assoc();
            $newdata =  array (
              'unit_id' => $row["_id"],
              'unit_name' => $row["unit_name"],
              'location' => $row["location"],
              'filter_size' => $row["filter_size"], 
              'rotation' => $row["filter_rotation"], 
              'filters_due' => $row["filters_due"]
                );
            $md_array["unitinfo"][] = $newdata;
        }
         addtasks($md_array, $con, $UserName);
   }


   function addtasks(&$arrayname, $con, $Uname)
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
                addtask($con,$Uname, $UnitID,$Location,$UnitName,$FilterSize, $Rotation, $FiltersDue);
                //echo "<br>unitid=".$UnitID. " location=".$Location." unit name=".$UnitName." fsize=".$FilterSize." rotation=". $Rotation. " filtersdue=". $FiltersDue;
                }
                
                
            }
            
    }
    
if ($Action == "addtask") {
    if(isset($_POST['_id'])){$UnitID = ($_POST['_id']);}
    if(isset($_POST['unit_name'])){$UnitName = ($_POST['unit_name']);}
    if(isset($_POST['filters'])){$FilterSize = ($_POST['filters']);}
    if(isset($_POST['filter_rotation'])){$FilterRotation = ($_POST['filter_rotation']);}
    if(isset($_POST['filters_due'])){$FiltersDue = ($_POST['filters_due']);}
    AddTask($UserName, $UnitID, $UnitName, $FilterSize, $FilterRotation, $FiltersDue);
}

include('NavBar.html');
?>
    <table style="myTable"><tr><th></th>
     <th><button class="btn btn-danger my-2 my-sm-0" style="display:none;" id="clearsearch" onclick="clearsearch();">X</button><input class="mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="myInput" onchange="document.getElementById('clearsearch').style.display='inline-block';" style='width: 150px;'><button class="btn btn-outline-success my-2 my-sm-0" onclick="myFunction();">Search</button></th>
		 <th width="100px" height="30px">
         <div style="background-color:orange;font-size:<?php echo $FontSize ?>;color:black;width:100px;text-align:center;">OOS</div>    
       <div style="background-color:red;font-size:<?php echo $FontSize ?>;color:white;width:100px;text-align:center;">Over Due</div></th>
       <td>
       </td>
       <td style="font-color:gold; color:green;"><?php if(strcmp($ByDate,"newest")==0) {echo "<fontsize='20px'>Newest to oldest:";}
       if(strcmp($ByDate,"today")==0) {
        $currentDate = new DateTime();
        $TodaysDate = $currentDate->format('Y-m-d');
       echo "<fontsize='20px'>Due today: ".$TodaysDate;}
       if(strcmp($ByDate,"oldest")==0) {echo "<fontsize='20px'>Oldest to newest";} ?></td>
       <td><input type="textbox" class="input-group rounded" value="<?php echo $UserName ?>" disabled style="text-align:left;background-color:black;color:aqua;font-weight:bold;font-size:1em;margin: auto;width: 50px;padding: 10px;" id='divUserName'></td>
       </tr>
      </font></table>
<table style="myTable" id="myTable" border="1">
<tr><td class="<?php echo $TDstyle ?>"></td><td class="<?php echo $TDstyle ?>"></td>
    <tr class="myTable">
        <th style="background-color:#07ff10;text-align:center;"><font color="black">
        <input type="button" style="background-color:#1b0aff;height:50px;width:50px;font-size:10px;font-weight:bold;color:gold;" value ="Submit" onclick="submittasks();"></td></th> 
        <th style="background-color:#07ff10;font-size:<?php echo $FontSize ?>;color:black;" id="thunitname">Unit Name
		</th>       
        <th style="background-color:#07ff10"><font color="black">Due Date</font></th>
    </tr>
   <form action="mListEquipment.php" method="post" id="frmsubmittasks">
   <input type="hidden" name="action" value="addalltasks">
   <input type="hidden" name="username" id="username" value="<?php echo $UserName ?>">
    <?php
    $ByDate="no";
    if (isset($_GET['unit_name'])) {$UnitName=$_GET['unit_name'];}
    if(isset($_POST['_id'])){$UnitID = ($_POST['_id']);}
    if(isset($_POST['bydate'])){$ByDate = ($_POST['bydate']);}
    if(isset($_GET['bydate'])){$ByDate = ($_GET['bydate']);}
    if(isset($_GET['ckoverdue'])){$overdue = $_GET['ckoverdue'];}
    if(isset($_POST['ckoverdue'])){$overdue = $_POST['ckoverdue'];}
    // $query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment;";
    //echo "overdue=".$overdue. strcmp($overdue,"on");
  
    if(strcmp($overdue,"on") == 0)
         {
            $query="SELECT * FROM equipment WHERE datediff(CURDATE(),filters_due) > 0;";
            //echo $query;
            //$query="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image  FROM equipment WHERE filters_due <= $duedate ;";
         }
            else 
         {
         $query="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image  FROM equipment";
         }
         if(strcmp($ByDate,"today") == 0){
        
         $query="SELECT * FROM equipment WHERE filters_due =".$TodaysDate;}
         if(strcmp($ByDate,"oldest") == 0){$query="SELECT * FROM equipment ORDER BY filters_due ASC";}
         if(strcmp($ByDate,"newest") == 0){$query="SELECT * FROM equipment ORDER BY filters_due DESC";}
   //echo "<br>Query=".$query;
    if ($stmt = $con->prepare($query)) {
        $stmt->execute();
        //$AssignedTo=null;
        //Bind the fetched data to $unitId and $UnitName
        $stmt->bind_result($unitId, $UnitName, $location, $AreaServed, $FilterSize, $FiltersDue, $FilterType, $belts, $notes, $FilterRotation, $FiltersLastChanged, $AssignedTo, $Image );
?>
    
    <?php
          $X=0;
          //$TDstyle="";
          $EquipmentList="";
        while ($stmt->fetch()) {
        $EquipmentList= $EquipmentList . "{\"units\":[  {\"_id\":\"".$unitId."\", \"unit_name\":\"".$UnitName."\",\"location\":\"".$location."\"}]}";
         //echo $unitId ."vbcrlf";
         $X=$X+1;
        
         $today = date('Y-m-d');
        $someDate = new DateTime($FiltersDue);
        $daysoverdue= s_datediff("d",$today,$someDate,true);
       // $daysoverdue=  $diff = date_diff("d",$today, $someDate);
       
	   

        //echo "<br>TDstyle=".$TDstyle."fontcolor=".$FontColor;
            ?>
            <tr class="myTable">
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
			?>
                    <td style='<?php echo $myCss ?>;text-align:center;"'>
                    
                        <input type="checkbox" name="ckBox[]" id="cktask<?php echo $unitId ?>" class="checkmark" value="<?php echo $unitId ?>"></td>
                        <!--<input type="hidden" name="username" value="<?php echo $UserName ?>">
                        <input type="hidden" name="_id" value="<?php echo $unitId ?>">
                        <input type="hidden" name="unit_name" value="<?php echo $UnitName ?>">
                        <input type="hidden" name="filters" value="<?php echo $FilterSize ?>">
                        <input type="hidden" name="filters_due" value="<?php echo $FiltersDue ?>">
                        <input type="hidden" name="filter_rotation" value="<?php echo $FilterRotation ?>">-->

			
					<td style="<?php echo $myCss ?>" id='info' onclick="showinfo('tblUnitinfo<?php echo $unitId ?>', '<?php echo $UnitName ?>','<?php echo $unitId ?>');">
                    <div style="<?php echo $myCss ?>" id='mydiv<?php echo $unitId ?>'>
					<?php
					if($AssignedTo != "")
                    { 
                        echo $UnitName;//"(". $AssignedTo .")" . $UnitName;
                    }
                    else
                    {
                         echo $UnitName;
                    } 
                 ?>
                </a>
                
                 <table style="display:none;" id="tblUnitinfo<?php echo $unitId ?>">
                  <tr id="trinfo"><td class="tdUnitInfo">Unit name</td><td class="tdUnitInfo2"><?php echo $UnitName ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Assigned too</td><td class="tdUnitInfo2"><?php echo $AssignedTo ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Location</td><td class="tdUnitInfo2"><?php echo $location ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Area served</td><td class="tdUnitInfo2"><?php echo  $AreaServed ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Filter size</td><td class="tdUnitInfo2"><?php echo $FilterSize ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Filter type</td><td class="tdUnitInfo2"><?php echo $FilterType ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Filters due</td><td class="tdUnitInfo2"><?php echo $FiltersDue ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Filters last changed</td><td class="tdUnitInfo2"><?php echo $FiltersLastChanged ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Rotation</td><td class="tdUnitInfo2"><?php echo $FilterRotation ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Belts</td><td class="tdUnitInfo2"> <?php echo $belts ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Notes</td><td class="tdUnitInfo2"><textarea class="mytextarea"><?php echo $notes ?></textarea></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">
                   <div style="background-color:green;color:red;width:100%;height:170px;font-size:30px;font-weight: bold;"><a href="webEditUnit.php?_id=<?php echo $unitId ?>&username=<?php echo $UserName ?>">EDIT UNIT</a></div></font>
                   <br><A href="filtertype.php?page=mListEquipment.php&filter_type=<?php echo $FilterType ?>&username=<?php echo $UserName ?>&filter_rotation=<?php echo $FilterRotation ?>&unit_id=<?php echo $unitId ?>&unit_name=<?php echo $UnitName ?>&filter_size=<?php echo $FilterSize ?>&filters_due=<?php echo $FiltersDue ?>" style="font-weight: bold;font-size: 25px;color:blue;background-color:gold;height:170px;width:100%;">FILTERS DONE</A>
                   
                   </td><td class="tdUnitInfo">
                   <?php 
                   if ($Image != null)
                     {
                        echo "<a href='" . $Image ."' target='_blank'><img src='".$Image."' alt='". $UnitName ."' style='width:100px;height:100px;'></a>";
                     }
                     ?>
                     </td></tr>
                </table>
                </td>
                
                <td style="<?php echo $myCss ?>"><?php echo $FiltersDue ?></font></td>

                </tr>

            <?php

        }
    }
    
    //$stmt->close();
    
     ?>
    </form>
    
     
</table>
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
							   $nCss = "background-color:orange;color:black;text-align: left;font-weight:bold;";
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
							   $nCss="background-color:orange;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $nCss="background-color:red;color:white;text-align: left;font-weight:bold;";
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
							   $nCss = "background-color:orange;color:white;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
					  else
					  {			//FILTERS NOT OUT OF STOCK
								$nCss="background-color:black;color:white;text-align: left;font-weight:bold;";	
								//echo "nCss=".$nCss."<br>";
					  }
			   }
				if ($daysoverdue <= 0)//FILTERS ARE OVERDUE
				{
					     if(strcmp($outofstock, "outofstock")==0)
					  {
							   $nCss="background-color:orange;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $nCss="background-color:black;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	return $nCss;
}

function getAlinkCss($theme,$daysoverdue,$outofstock)
{
	//echo "theme=".$theme." days=".$daysoverdue." oos=".$outofstock."<br>";
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
							   $AlinkCss="color:black;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	return $AlinkCss;
}


function AddTask($con, $user, $unitid,$UnitLocation,$UnitName,$filters, $filterrotation, $filtersdue)
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
                    $servername = "localhost";
                    $username = "4094059_mirage";
                    $password = "relays82";
                    $database = "4094059_mirage";
               $con2 = new mysqli("$servername","$username","$password","$database");
               // Check connection
               if ($con2->connect_error) 
                    {
                        die("Connection failed: " . $con2->connect_error);
                    }
                $TodaysDate = date("Y-m-d");  
                                            
                $sql = "INSERT INTO tasks(employee_name,unit_id,unit_name,unit_location,filters_needed,task_date_created,filter_rotation,action) VALUES ('" . $user . "','" . $unitid . "','" . $UnitName . "','" . $UnitLocation. "','" . $filters . "','" . $TodaysDate . "','" . $filterrotation . "', 'new')";
                //$sql = "INSERT INTO tasks(employee_name, unit_name, task_date_created, filters_needed, unit_id, filter_rotation, filters_due) VALUES ('" . $UserName . "','" . $UnitName. "','". $TodaysDate . "','" . $filters . "','" . $unitid . "','" . $filterrotation . "','" . $filtersdue . "')";
                //echo $sql."<br>";
                //mysqli_select_db($con2, 'db_a743b0_cannery');
                if ($con->query($sql) === TRUE) 
                    {
                        echo "insert success tasks created<br>";
                    }
                    else
                    {
                        echo "Error: " . $sql . "<br>" . $con2->error;
                        //die('Could not enter data: ' . mysqli_error($con2));
                    }
                if(isset($_POST['_id'])){$UnitID = ($_POST['_id']);}
                $sql="UPDATE equipment  SET assigned_to ='".$user."' WHERE _id='". $unitid ."';";
                 //mysqli_select_db($con, 'db_a743b0_cannery');
                if ($retval=mysqli_query($con2, $sql))
                    {
                        echo "<br>" .$UnitName . " was added to your tasks<br>";
                    }
                mysqli_close($con2);
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

function ExtractFilterSize($f){
                              
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
	 // echo "filterSizeSent=".$filterSizeSent."<br>";
	  if(getfiltercount($filterSizeSent,$result) == "outofstock"){return "outofstock";}
   }

if($numOfSets == 2)
   {
      $lastpos = strpos($f, ")", 0);
      $pos = strpos($f, "(");
      $filt1 = substr($f, $pos-strlen($f),$lastpos-2 );
      echo "set1=" . $filt1. "<br>";
      $filt2 = substr(strrchr($f, "("), 0);
	  //if(getfiltercount($filt1, $result) == "outofstock"){return "outofstock";}
	  //if(getfiltercount($filt2, $result) == "outofstock"){return "outofstock";}
      echo "set2=" . $filt2. "<br>";
      //for First filter set
      $pos = strpos($filt1, ")");
      $filterSizeSent = substr($filt1, $pos+1);  
	  if(getfiltercount($filterSizeSent, $result) == "outofstock"){return "outofstock";}
      $filtersQtyUsed = substr($filt1,1,$pos-1);
   }
      //return $filterSizeSent;
}
?>
<script>
// When the user clicks on <div>, open the popup
function showPOPUP() {
alert("showPOPUP");
  var popup = document.getElementById("myPopup");
  popup.classList.toggle("show");
}


function setOverDue(){
   if(document.getElementById('ckoverdue').checked)
      {
         document.cookie = "ckoverdue=true";
         //alert("seting to false");
      }
      else
      {
         document.cookie = "ckoverdue=false";
         //alert("settting to true");
      }
      //$line=readCookie("ckoverdue");
      //alert("cookie="+$line);
   document.getElementById("cklate").submit();
}  
</script>
<script>
function readCookie(name) {
 var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  return parts.pop().split(";").shift();
}
</script>
<script>

function showsettings(){
   document.getElementById("selFontSize").style.width = "115px";
   document.getElementById("selTheme").style.width = "115px";
	tdSettings = document.getElementById("tdSettings");
	tdSettings.style.visibility="visible";
}
</script>
<script>
function fillOptions(){
		searchby=document.getElementById("selSearch");
		x = document.getElementById("selField2");
		var option = document.createElement("option");
		  option.text = x.options[x.selectedIndex].text;
		  searchby.add(option);
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