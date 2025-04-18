<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if(session_id() == '' || !isset($_SESSION) || session_status() == PHP_SESSION_NONE) {
    //echo "no session";
      session_start();
    }
    //echo "session id=".session_id();

include 'CustomConfirmBox.css';
include 'phpfunctions.php';
include 'javafunctions.php';


//echo "all GET vars:".var_dump($_GET)."<br>";
//echo "all POST vars:".var_dump($_POST)."<br>";
$Action = "";
$Message = "";
$numberOfTasks=0;
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
if(isset($_SESSION["user_name"])){
   $UserName=$_SESSION["user_name"];
 
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

   <link rel="stylesheet" href="fm_css.php">

   
 <style>
    .vertical-center {
  display: flex;
  flex-direction: column;
  text-align: center; /* Optional for horizontal centering */
}
  .glow {
  font-size: 80px;
  color: #46F953;
  text-align: center;
  animation: glow 1s ease-in-out infinite alternate;
}

@-webkit-keyframes glow {
  from {
    text-shadow: 0 0 10px #fff, 0 0 20px #46F953, 0 0 30px #46F953, 0 0 40px #46F953, 0 0 50px #46F953, 0 0 60px #46F953, 0 0 70px #46F953;
  }
  
  to {
    text-shadow: 0 0 20px #fff, 0 0 30px #ff4da6, 0 0 40px #ff4da6, 0 0 50px #ff4da6, 0 0 60px #ff4da6, 0 0 70px #ff4da6, 0 0 80px #ff4da6;
  }
}
    td{
        width: 20%; 
        height: auto;
        text-align:center;
        
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
  </style>


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    

   <script>
function myConfirmBox(message, frmID) {
    let element = document.createElement("div");
    const list = element.classList;
    list.add("box-background:red;");
    element.innerHTML = "<div class='box' style='width:500px;height:300px;top:5px;background-color:gold;'>"+message+"<button id='trueButton' class='btn green'>Yes</button><button id='falseButton' class='btn red'>No</button></div>";
    document.body.appendChild(element);
    return new Promise(function (resolve, reject) {
        document.getElementById("trueButton").addEventListener("click", function () {
            resolve(true);
            document.getElementById(frmID).submit();
            document.body.removeChild(element);
            
        });
        document.getElementById("falseButton").addEventListener("click", function () {
            resolve(false);
            document.body.removeChild(element);
        });
    })
}
</script>
<script>
function setFilterTypes(hiddenInputNumber){
    //alert('setFilterType, hiddenInputNumber='+hiddenInputNumber);
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
    for(index = 0; index < inputs.length; ++index) {
            if(inputs[index].value == "select"){
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
        //console.log("submiting ");
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
    document.getElementById("btnPrint").style= "display:block;margin-left:450px;font-size : 20px; width: 200px; height:70px;margin-top:0px;";
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



<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

<body onload="getUserName();countFilters();" style="background-color:white;<?php if($_SESSION["background_color"] == "#000000"){echo "color:white;";} ?>">
<?php

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

   $FilterArray=arrayFromDataJson("filters","filter_size");
        
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
if(isset($_GET["unit_nameforinfo"])) 
{
    if(isset($_GET["unitid"])){$ID=$_GET["unitid"];}
    $UnitNameForInfo = $_GET["unit_nameforinfo"];
    $sql = "SELECT unit_name, location, area_served, filter_size, filter_type, filters_due, filter_rotation, belts, notes, filters_last_changed FROM equipment WHERE unit_name ='" . $_GET["unit_nameforinfo"] . "';";
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);

foreach ($data["equipment"] as $object) {
    foreach ($object as $key => $value) {
        if($object["_id"] == $ID){
            $txtServerMsg = "Unit name: " . $object["unit_name"] . "\nLocation: " . $object["location"]. "\nAreaServed: " . $object["area_served"]. "\nFilters: " . $object["filter_size"]. "\nFilter type: " . $object["filter_type"]."\nFilters due: " . $object["filters_due"]. "\nRotation: " . $object["filter_rotation"]. "\nFilters last changed: " . $object["filters_last_changed"]."\nNotes: " . $object["notes"];

            $display = "visible";
            break;
        }
    }
}
}
//COMPLETE ALL TASKS---------------------------------------------------------
if(strcmp($Action,"complete_all_tasks")==0) 
    {
        //print_r($_POST);
        $FiltersLastChanged = date("Y-m-d")." [".$UserName."]";
        $unitCount = count($_POST["unit_id"]); 

        //echo "Number of unit IDs submitted: $unitCount<br>";
        //foreach ($_POST["unit_id"] as $UnitID)
        for($i=0; $i < $unitCount; $i++)  
            {
                $UnitID = $_POST["unit_id"][$i];
                $Rotation = $_POST["filter_rotation"][$i];
                $FilterType = $_POST["filter_type"][$i];
                $FiltersUsed = $_POST["filters_used"][$i];
                $effectiveDate=getNextDueDate($Rotation);
                $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
                $data = json_decode($jsonString, true);
                if(strcmp($FiltersUsed, "no_filters_used")==0)
                    {
                        //DONT UPDATE FILTER TYPE
                        foreach ($data["equipment"] as &$object) 
                        {
                            if ($object["_id"] == $UnitID) 
                            {
                                $object['assigned_to'] = '';
                                $object["filters_last_changed"] = $FiltersLastChanged;
                                $object["filters_due"] =   $effectiveDate;
                            }
                        }
                    }
                    else
                    {
                        foreach ($data["equipment"] as &$object) 
                        {
                            if ($object["_id"] == $UnitID) {
                                $object['assigned_to'] = '';
                                $object["filters_last_changed"] = $FiltersLastChanged;
                                $object["filter_type"] = $FilterType;
                                $object["filters_due"] =   $effectiveDate;
                            }
                        }
                    }
                $jsonString = json_encode($data, JSON_PRETTY_PRINT);
                file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);

                if(strcmp($FilterType, "no_filters_used") < 0)
                    {
                        //echo "arFiltersUsed[num]=".$arFiltersUsed[$i]."<br>";
                        ExtractFilters($FiltersUsed, $FilterType);
                    }
            }
                    ?>
                    <script defer>document.body.style.backgroundColor = "black";
                    var elements = document.querySelectorAll("*");
                    for (const element of elements) {
                    element.style.backgroundColor = "black";
                    }</script>
                    <div class="glow" style="width: 100%; height: 40px; color: #46F953; font-size:80px;font-weight: bold;background-color:black;text-align:center;"><?php echo $UserName ?></div>
                    <div style="background-color: black; margin-top:50px;width: 100%; height: 800px;margin-left:350px;margin-right:auto;background-image: url('images/no_tasks.jpg');background-size:50% 50%;background-repeat: no-repeat;"></div>
                    <?php
            }
    

//COMPLETE SINGLE TASK----------------------------------------------------
if(strcmp($Action,"completetask")==0 && isset($_POST['filter_rotation'])&&isset($_POST['unit_id'])) 
{
    $Rotation=$_POST['filter_rotation'];
    $effectiveDate=getNextDueDate($Rotation);
    if(isset($_POST['unit_id'])){$UnitID = $_POST['unit_id'];}
    $FiltersLastChanged = date("Y-m-d")." [".$UserName."]";
    if(isset($_POST["unit_id"])){$UnitId= $_POST["unit_id"];}
    if(isset($_POST['filter_rotation'])){$Rotation = $_POST['filter_rotation'];}
    $FiltersUsed=$_POST['filters_used'];
    $FilterType=$_POST['filter_type'];
    $RecID=$_POST["unit_id"];
    if(strcmp($FilterType, "no_filters_used")!=0)
        {
            ExtractFilters($FiltersUsed, $FilterType);
        }
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data["equipment"] as &$object) 
        {
            foreach ($object as $key => $value){
            if ($object["_id"] == $RecID) 
                {
                    $object['filters_last_changed'] = $FiltersLastChanged;
                    $object['filters_due'] = $effectiveDate;
                    if(strcmp($FilterType, "no_filters_used")==0)
                        {
                            //DONT UPDATE FILTER TYPE
                        }
                        else
                        {
                            $object['assigned_to'] = "";
                            $object['filters_type'] = $FilterType;
                        }
                    $jsonString = json_encode($data, JSON_PRETTY_PRINT);
                    file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
                }
            }
        }      
}



if(strcmp($Action,"canceltask")==0)
{

if(isset($_POST["unit_id"]) || isset($_GET["unit_id"]))
   {
        if(isset($_POST["unit_id"])){$UnitID = $_POST['unit_id'];}
        if(isset($_GET["unit_id"])){$UnitID = $_GET['unit_id'];}
   
        $today=date('Y-m-d');
        $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
        $data = json_decode($jsonString, true);
        $find="password";
        foreach ($data["equipment"] as &$object) {
            if ($object['assigned_to'] == $UserName && $object['_id'] == $UnitID) {
                $object['assigned_to'] = '';
            }
        }
        $jsonString = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
       
     }  
}
//GET ALL FILTER TYPES INTO AN ARRAY
$AllFilterTypes = arrayFromDataJson("filter_types", "type");
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

//GET ALL TASKS--------------------------------------------------------------------------
if($Action == "getalltasks" || strcmp($Action,"completetask")==0 || $Action == "" || strcmp($Action,"canceltask")==0) {
    $arAllData = array();
    $arDataRow = array();
    $object_cnt = 0;
    $UnitID = "00";
    if(isset($_GET["unitid"])){
      $UnitID= $_GET["unitid"];
    }
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    foreach ($data["equipment"] as $object) 
    {
        foreach($object as $key => $value)
            {
                //echo "obj=".$object["assigned_to"]."<br>";
                if($object["assigned_to"] === $UserName)
                    {
                        $object_cnt = $object_cnt + 1;
                    }
            }
    }
    if($object_cnt > 0){
  
?>
<div style="text-align: center;width:100%;font-weight:bold;"><?php echo $UserName ?>'s Tasks</div>
<Table class="tableTasks"  id='tblShowAllTasks'><tr><td>Cancel task</td>
<td   width='800'>Unit name</td>
<td  width='800' >Unit location</td>
<td  >Filter size</td>
<td  >Filter type</td>
<td  id="tdComplete">Complete task</td>
</tr>
<?php
    $n=0;
    $arFilterSizes = array();
    $arFiltersNeeded=array();
    $mystring="";
    $x=0;
    
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data["equipment"] as $object) {
        foreach($object as $key => $value)
            {
            //CREATE ARRAY OF FILTER SIZES
            if($key == "assigned_to" && $value == $UserName)
            {
                $numberOfTasks = $numberOfTasks + 1;
                $f=$object["filter_size"];
                $numOfSets= substr_count($f,'(');
           
                if($numOfSets == 1)
                    {
                        $pos = strpos($f, ")");
                        $filterSize = substr($f, $pos+1);  
                        $Amount = substr($f,1,$pos-1);
                        $type=$object["filter_type"];
                        $arFiltersNeeded = array("type"=>$type, "size"=>$filterSize, "amount"=>$Amount);
                        array_push($arFilterSizes, $arFiltersNeeded);
                    }
                if($numOfSets == 2)
                    {
                        $xFilter = explode(" ",$f);
                        //echo "set1=" . $xFilter[0]. "<br>";
                        //echo "set2=" . $xFilter[1]. "<br>";
                        //for First filter set
                        $pos = strpos($xFilter[0], ")");
                        $filterSize = substr($xFilter[0], $pos+1);  
                        $type=$object["filter_type"];
                        $Amount = substr($xFilter[0],1,$pos-1);
                        $arFiltersNeeded = array("type"=>$type, "size"=>$filterSize, "amount"=>$Amount);
                        array_push($arFilterSizes, $arFiltersNeeded);
                        //for Second filter set
                        $pos = strrpos($xFilter[1], ")");
                        $filterSize = substr($xFilter[1], $pos+1);  
                        $Amount = substr($xFilter[1],1,$pos-1);
                        $arFiltersNeeded = array("type"=>$type, "size"=>$filterSize, "amount"=>$Amount);
                        array_push($arFilterSizes, $arFiltersNeeded);
                        //echo "set2 size=".$filterSizeSent." qt2=".$Amount."<br>";
                        
                    }
                    //print_r($arFilterSizes);
        ?>

            <tr><td><div>
                     <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="<?php echo "frmCancel".$object["_id"] ?>">
                    <input type="hidden" name="username" value="<?php echo $UserName ?>">
                    <input type="hidden" name="action" value="canceltask">
                    <input type="hidden" name="unit_id" value="<?php echo $object["_id"] ?>">
                    <input type="hidden" name="unit_name" value="<?php echo $object["unit_name"] ?>">
                    
                    <img src="images/cancel.png" title="Cancel the task for <?php echo $object["unit_name"] ?>" style="width: 20%;height: auto;margin-top:8px;" onclick="myConfirmBox('Do you wish to cancel this task for <?php echo $object["unit_name"] ?>?', 'frmCancel<?php echo $object["_id"] ?>')"></td>
                     </form>
                     </div></td><td>
                    <a href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?username=<?php echo $UserName ?>&unit_nameforinfo=<?php echo $object["unit_name"] ?>&unitid=<?php echo $object["_id"] ?>"><?php echo $object["unit_name"] ?></a>
		<?php
              
                     if(strcmp($object["unit_name"], $UnitNameForInfo) == 0)
						{
							 echo "<br><div id='divUnitInfo'><textarea style='background-color:green;color:white;font-size:20px;border-radius: 15px;' rows='9' cols='35' id='txtinfo'". $object["_id"] ." ".$display .">"
							. $txtServerMsg . "</textarea>";
                            ?><br><button type='button' class='btn btn-primary' style='width:170px;' onclick="document.getElementById('divUnitInfo').style.display='none';">Close</button><a href='webEditUnit.php?_id=<?php echo $object["_id"] ?>&username=<?php echo $UserName ?>"><button type='button' class='btn btn-warning' style='width:180px;'>EDIT UNIT</button></a></div>
                            <?php
						}
         ?>
                     </td>
                     
                     <td><?php echo $object["location"] ?></td>
                     <td><?php echo $object["filter_size"] ?></td>
                    
                    <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="POST" name="frmName<?php echo $object["_id"] ?>" id="frmCompleteTask<?php echo $object["_id"] ?>">
                    <td class="tableTasks" style='text-align:left;' ><div style="width: 100%; height: 100%;">
                    <div class="selectWrapper">
                    <select id="slctFilterType<?php echo $object["_id"] ?>" title="Select the filter type that was installed into unit <?php echo $object["unit_name"] ?>" name="filter_type" class="ftype" onchange="setFilterTypes('<?php echo $object["_id"] ?>');">
                    <?php
                    if(isset($object["filter_type"])){echo "<option value='".$object["filter_type"]. "' SELECTED>".$object["filter_type"]."</option>";}else{ echo "<option value='select' selected>select</option>";}
                    ?>
                    <option value="no_filters_used">no filter used</option>
                    <?php
                    foreach ($AllFilterTypes as $value) {
                        echo "<option value='".$value."'>".$value."</option>";
                    }
                    array_push($arAllData, array("unit_id[]"=>$object["_id"], "filter_rotation[]"=>$object["filter_rotation"],"filters_used[]"=>$object["filter_size"]));
            ?>
                    </select></div></td>
					<td  ><img id="img<?php echo $object["_id"] ?>" src="images/dwcheckyes.png" title="Complete task for <?php echo $object["unit_name"] ?>" onclick="CompleteFilters(<?php echo $object["_id"] ?>);" style="text-align:center;width:30px;height:30px;"></td>
                    <input type="hidden" name="action" value="completetask">
                    <input type="hidden" name="username" value="<?php echo $UserName ?>">
                    <input type="hidden" name="unit_id" value="<?php echo $object["_id"] ?>">
                    <input type="hidden" name="filters_used" value="<?php echo $object["filter_size"] ?>">
                    <input type="hidden" name="filter_rotation" value="<?php echo $object["filter_rotation"] ?>">
                    <input type="hidden" name="filters_due" value="<?php echo $object["filters_due"] ?>">
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
  
        }}
            //FOR SUBMIT ALL TASKS BUTTON 
            echo "<form id='frmCompleteAll' action='". $_SERVER["SCRIPT_NAME"] ."' method='post'>";
            echo "<input type='hidden' name='username' value='".$UserName."'>";
            echo "<input type='hidden' name='action' value='complete_all_tasks'>";
            //echo "<input type='hidden' name='filter_size' value='(1) 20x20x2'>";
            $thisTaskID="";
            $keys = array_keys($arAllData);
            for($i = 0; $i < count($arAllData); $i++) 
            {
                foreach($arAllData[$keys[$i]] as $key => $value) 
                {
                    //echo "key=".$key." value=".$value."<br>";
                    if(strcmp($key,'unit_id[]')==0){$thisTaskID=$value;}
                    echo "<input type='hidden' name='".$key."' value='".$value."'>";
                }
                echo "<input type='hidden' id='hdnFtype".$thisTaskID."' name='filter_type[]' value='select'>";
            }
                     echo "</form></div><tr><td></td><td></td><td></td><td></td><td style='text-align:center;'>";
                    ?> <button class="btn btn-secondary" style="width: fit-content;float:left;" onclick="myConfirmBox('Complete all tasks?', 'frmCompleteAll');" id='btnCompleteAllTasks' title="Button disabled until filters for all tasks are selected above" disabled>Complete all tasks</button></td> <?php
         }
         
        else
        {
            ?>
            <script defer>document.body.style.backgroundColor = "black";
                    var elements = document.querySelectorAll("*");
                    for (const element of elements) {
                    element.style.backgroundColor = "black";
                    }</script>
            <div class="glow" style="width: 100%; height: 40px; color: #46F953; font-size:80px;font-weight: bold;background-color:black;text-align:center;"><?php echo $UserName ?></div>
            <div style="<?php if($Action == "complete_all_tasks"){echo "background-color:black;";} ?> margin-top:50px;width: 100%; height: 800px;margin-left:350px;margin-right:auto;background-image: url('images/no_tasks.jpg');background-size:50% 50%;background-repeat: no-repeat;"></div>
            <?php
        }
    }
    ?>
    
    <table style="width:300px;margin-left:auto;margin-right:auto;">
    <tr><th style="font-weight:bold;"><div id="divFiltersNeeded" style="margin-left:auto;margin-right:auto;width:fit-content;text-align:center;"></div></th></tr>
    <tr><td>                    
    <div style="display:flex;flex-direction:row;">
<div id='divAmount'  style="display:none;width:50px;border: 8px solid orange;color:black;background-color:orange;"></div>
<div id='divSize' class="vertical-center" style="display:none;width: 50%;color:black;"></div>
<div id='divType' class="vertical-center" style="display:none;width: 50%;color:black;display:none;"></div>
</div> 
</td>
</tr></table>
    <div id='divAllfilters' style='display:none;color:white;background-color:green;'>
    <?php
    $x = 0;
        $ThisSize="";
        $color="yellow";
        $amount=0;
        foreach ($arFilterSizes as $key => $value) {
            foreach ($value as $sub_key => $sub_val) {
                {
                    if($x == 0){$color="yellow";}
                    if($x == 1){$color="aqua";}
                    if($x == 2){$color="purple";}
                    //type"=>$type, "size"=>$filterSize, "amount"=>$Amount
                    if($sub_key == "size")
                        { 
                            $ThisSize = $sub_val;
                            echo "<input type='text' style='background-color:".$color.";' data-amount='".$value["amount"]."' data-ftype='".$value["type"]."' data-id='".$x."' data-size='".$value["size"]."' title='amount:".$value["amount"]." size:".$value["size"] ." x:".$x. "' name='fsize' id='fsize".$x."' value='$sub_val'>";
                        }
                    if($sub_key == "amount")
                        {
                            $amount=$sub_val;
                            echo "<input type='text' style='background-color:".$color.";' data-amount='".$value["amount"]."' data-ftype='".$value["type"]."' data-id='".$x."' data-size='".$value["size"]. "' title='amount:".$value["amount"]." size:".$value["size"] . "x:".$x. "' name='famount' id='famount".$x."' value='$sub_val'><br>";
                            $x=$x+1;
                          
                        }
                        if($sub_key == "type")
                        { 
                            $ftype = $sub_val;
                            echo "<input type='text' style='background-color:".$color.";' data-amount='".$value["amount"]."' data-ftype='".$value["type"]."' data-size='".$value["size"]. "' data-id='".$x."' title='amount:".$value["amount"]. " size:".$value["size"] ." x:".$x."' id='ftype".$x."' value='$sub_val' name='ftype'>";
                        }
                }
            }
        }
?>
    </div>
        <div id='divFiltersCounted' style="margin-left:auto;margin-right:auto;width:fit-content;text-align:center;display:flex;flex-direction:column;"></div>
        <div style="text-align: center;">
        <?php
        if($Message != "All tasks are completed" && $numberOfTasks > 0){
            echo "<button id='btnPrint' type='button' class='btn btn-success' style='margin-left:auto;margin-right:auto;width:300px;vertical-align:top;font-size : 20px; width: 200px; height:70px;' onClick='printme();'>PRINT</button>";
            }
            

        ?>
        </div>
    <?php

//UPDATE FILTERS----------------------------------------------------------------------------------------------
if(strcmp($Action,"updatefilters")==0){
	if(isset($_GET["filter_size"])){$FilterSize=$_GET["filter_size"];}
    if(isset($_GET["filter_type"])){$FilterType=$_GET["filter_type"];}
	ExtractFilters($FilterSize, $FilterType);
}

?>