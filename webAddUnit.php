<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }
$TDstyle="";
$UserName="";
if (isset($_COOKIE["username"])) {
    $UserName = $_COOKIE["username"];
   // echo "COOKIE username=". $UserName."<BR>";
}

if (isset($_POST["username"])) {
    $UserName = $_POST["username"];
    //ECHO "_get username=". $_GET["username"];
    }

if (isset($_GET["username"])) {
    $UserName = $_GET["username"];
    //ECHO "_get username=". $_GET["username"];
    }

    $Theme="";
    if (isset($_SESSION["theme"])) {
    $Theme = $_SESSION["theme"];
    //echo "<font color='black'>cookie theme=".$Theme;
}
$FilterType = "";
if (isset($_POST["filter_type"])){$FilterType = $_POST["filter_type"];}
     if (isset($_POST["unit_name"])) {
         $UnitName = $_POST["unit_name"];
         $Location = $_POST["location"];
         $AreaServed = $_POST["area_served"];
         $FilterSize = $_POST["filter_size"];
         $FiltersDue = $_POST["filters_due"];
         $Rotation = $_POST["filter_rotation"];
         $Belts = $_POST["belts"];
         $Notes = $_POST["notes"];
         }
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//or, if you DO want afile to cache, use:
header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
?> 
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<meta charset="utf-8"
    name="viewport" content="width=device-width, initial-scale=1">
   
    <title>Filter Manager - Add Filter</title>
	<style>

    select {
		background-color:white;
        width: 150px;
        margin: 10px;
		font-size:30px;
    }
    select:focus {
        min-width: 150px;
        width: auto;
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
        * {
            box-sizing: border-box;
        }
        input[type="text"]
        {
        font-size:24px;
        }

        #myInput {
            background-image: url('/css/searchicon.png'); /* Add a search icon to input */
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
            width: 70%; /* Full-width */
            border: 1px solid #ddd; /* Add a grey border */
            font-size: 18px; /* Increase font-size */
        }

        #myTable th, #myTable td {
            text-align: left; /* Left-align text */
            padding: 12px; /* Add padding */
            background-color:<?php echo $TDstyle ?>;
        }

        #myTable tr {
            /* Add a bottom border to all table rows */
            border-bottom: 1px solid #ddd;
        }

        #myTable tr.header {
            /* Add a grey background color to the table header and on hover */
            background-color: #f1f1f1;
            position: fixed;
            top: 0px; display:none;
        }
    </style>

</head>
<script>
function setfilters(){
document.getElementById("filter_size").style.backgroundColor="green";
document.getElementById("filter_size").style.width="100px";
console.log("setfilters now");
var amount1 = document.getElementById("amount1").value;
var amount2 = document.getElementById("amount2").value;
var e1 = document.getElementById("slctSize1");
var e2 = document.getElementById("slctSize2");
var fsize1 = e1.options[e1.selectedIndex].text;
var fsize2 = e2.options[e2.selectedIndex].text;

if(document.getElementById("btnShowSize2").className!='d-none'){
document.getElementById("filter_size").value = "("+ amount1 + ")"+ fsize1;
}
else
{
document.getElementById("filter_size").value = "("+ amount1 + ")"+ fsize1 + " ("+ amount2 + ")"+ fsize2;
}
}
</script>
<?php

if(strcmp($Theme,"Dark-tdPlain")==0)
   {
      ?><body style='background-color: black;'>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <?php
      $TDstyle="Dark-tdPlain";
   }
if(strcmp($Theme,"Light-tdPlain")==0)
{
   ?>
   <body style='background-color: white;'>
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <?php
    $TDstyle="Light-tdPlain";
}



include 'phpfunctions.php';
include 'snackbar.css';
include "javafunctions.php";
include "functions.php";

//CREATE ARRAY OF ALL FILTER TYPES
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
$arFilterTypes = [];
foreach ($data["filter_types"] as $obj) {
    $arFilterTypes[] = $obj["type"];
}

//print_r($arFilterTypes);

//CREATE ARRAY OF ALL FILTER SIZES
$arFilters = array();
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
  foreach ($data['filters'] as &$object) {
        foreach ($object as $key => $value) {
            if($key == "filter_size"){
                array_push($arFilters, $value);
            }
        }
  }
//print_r($arFilters);
//echo var_dump($_POST);
//foreach ($_POST as $param_name => $param_val) {
  //  echo "Param: $param_name; Value: $param_val<br />\n";
//}
$visibility ="none";
$UnitNameFound="none";
$row_cnt=0;
$ServerResponse="none";
$FilterSizeFound = "";
$UnitName = "";
   $AreaServed = "";
   $Location = "";
   $FilterSize = "";
   $FiltersDue = date("Y-m-d");
   $FiltersLastChanged = "";
   $Rotation = "";
   $Belts = "";
   $Notes = "";
    //echo "COOKIE username=". $_COOKIE["username"]."<BR>";
if(isset($_GET["_id"])) {
    //echo "from GET id=" . $_GET["_id"] ;
}
if(isset($_POST["addunitnow"])) 
{
      $UnitAlreadyExists=false;
     if(isset($_GET["unit_name"])){$UnitName= $_GET["unit_name"];}
     if(isset($_POST["unit_name"])){$UnitName= $_POST["unit_name"];}
    //DOES UNIT NAME ALREADY EXIST?
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data["equipment"] as $object) 
        {
            if (strpos(strtolower($object["unit_name"]), $UnitName) !== false) 
            {
                $UnitAlreadyExists=true;
                // echo " number of rows=".$row_cnt."unit name=".$row["unit_name"];
                $UnitName = $object["unit_name"];
                $Location = $object["location"];
                $AreaServed = $object["area_served"];
                $FilterSize = $object["filter_size"];
                if(!strpos($FilterSize,")")>0)
                    {
                            $FilterSize="(1)".$FilterSize;
                    }
                $FiltersDue = $object["filters_due"];
                $Rotation = $object["filter_rotation"];
                $Belts = $object["belts"];
                $Notes = $object["notes"];
                $unitIdFound = $object["_id"];
                $UnitNameFound = $object["unit_name"];
                $LocationFound = $object["location"];
                $AreaServedFound = $object["area_served"];
                $FilterSizeFound = $object["filter_size"];
                $FiltersDueFound = $object["filters_due"];
                $RotationFound = $object["filter_rotation"];
                $BeltsFound = $object["belts"];
                $NotesFound = $object["notes"];
                $ServerResponse=$UnitNameFound." already exists:<br>Unit name: " .$UnitNameFound. "<br>Location: ".$LocationFound . "<br>Area served: " . $AreaServedFound."<br>$FilterSizeFound<br><br><a href='webEditUnit.php?_id=".$unitIdFound . "&unit_name=".$UnitNameFound ."'><button class='btn btn-warn'>EDIT UNIT</button></a> or change unit name below and re-submit";
            } 
        }
    }

if(isset($_GET["unit_name"])) {
//$UnitToFind = $_GET["name"];
    $UnitName = "unit_name";
    $AreaServed = "area_served";
    $Location = "location";
    $FilterSize = "filter_size";
    $FilterType="";
    $FiltersDue = "filters_due";
    $FiltersLastChanged = "filters_last_changed";
    $Rotation = "rotation";
    $Belts = "belts";
    $Notes = "notes";
    }

?>
<table>
<tr><td class="<?php echo $TDstyle ?>"><div style="border:2px solid red;font-size:20px;width:300px;height:auto;overflow:auto;margin-left:40%;color:white;background-color :red;" id="divServerResponse"
<?php 
if($ServerResponse !="none") 
{echo " style='display:initial;'>".$ServerResponse ."</div></td></tr>";}
else
{echo " style='display:none;'></div></td></tr>";}
?>
<tr><td class="<?php echo $TDstyle ?>"><h1><span class="badge badge-pill badge-success">ADDING NEW UNIT</span></h1></td></tr></table>
<table style="myTable" id="myTable" border="1">
<!--<tr><td class="<?php echo $TDstyle ?>">
<form action="webEditUnit.php" method="post" enctype="multipart/form-data">
    Select image to upload if you wish.<br> (Max file size 500 kb.)<br>
    <input type='file' style='height:50px;width:auto;' name="fileToUpload" style='height:30px;width130px;' value='Upload file now'><br>
    <input type="hidden" name="_id" value="<?php echo $row["_id"] ?>">
    <input type="submit" style='height:30px;width130px;' onclick='document.getElementById("imgUpload").style.display='visible''; value="Upload Image" name="submit_file" id='btnUploadFile'></div>
</form></td></tr>-->
    <form action="webAddUnit.php" method="post" id="frmsubmit">
        <tr><td class="<?php echo $TDstyle ?>"><font size="3">Unit name</td><td class="<?php echo $TDstyle ?>"><input type="text" id="unit_name" name="unit_name" value="<?php echo $UnitName ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Location</td><td class="<?php echo $TDstyle ?>"><input type="text" id="location"" name="location" value="<?php echo $Location ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Area served</td><td class="<?php echo $TDstyle ?>"><input type="text" id="area_served" name="area_served" value="<?php echo $AreaServed ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter size #1:</td><td class="<?php echo $TDstyle ?>">Amount (<input type='text' onkeyup="setfilters(this.id);" maxlength='3' style='width:60px; overflow: hidden; max-width: 4ch;' id='amount1' name='filter_amount' value='<?php if(isset($_POST["filter_amount"])){echo $_POST["filter_amount"];} ?>')>&nbsp;Filter #1 size:
        <select name='filtersize1' class='form-select text-white bg-primary w-50' aria-label='Default select example' onchange='setfilters(this.id);'  id='slctSize1'>
        <option value="select size">Select size</option>
        <?php if(isset($_POST["filtersize1"])){echo "<option value='".$_POST["filtersize1"]."' SELECTED>".$_POST["filtersize1"]."</option>";}
   
        //$arFsizes =  ExtractFilterSize($row["filter_size"]);
        sort($arFilters, SORT_NUMERIC);
        foreach($arFilters as $key => $value)
             {
                echo "<option value='".$value."'>".$value."</option>";
            } 
        ?>
        </select><div id="snackbar">Use numerical inputs only</div>
        <input type='text' id='filter_size' name='filter_size' value='' class='form-select text-white bg-primary text-center d-none'></div></td></tr>
         <tr><td class="<?php echo $TDstyle ?>">Filter size #2:(optional)</td><td class="<?php echo $TDstyle ?>"><button id="btnShowSize2" type='button' class='btn btn-primary' onclick="document.getElementById('filtersize2').className='container m-4';this.className='d-none';">Add second filter size</button><div class='d-none' id='filtersize2'>Amount (<input type='text' onkeyup="setfilters(this.id);" maxlength='3' style='width:30px; overflow: hidden; max-width: 4ch;' id='amount2' name='filter_amount2' value='<?php if(isset($_POST["filter_amount2"])){echo $_POST["filter_amount2"];} ?>'>)&nbsp;Filter #2 size:
         <select name='filtersize2' class='form-select text-white bg-primary w-50' aria-label='Default select example' onchange='setfilters(this.id);'  id='slctSize2'>
        <option value="select size">Select size</option>
<?php 
if(isset($_POST["filtersize2"])){echo "<option value='".$_POST["filtersize2"]."' SELECTED>".$_POST["filtersize2"]."</option>";}
        
        //$arFsizes =  ExtractFilterSize($row["filter_size"]);
        foreach($arFilters as $key => $value)
             {
                echo "<option value='".$value."'>".$value."</option>";
            } 
        ?>
        </select></div>
        </td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter Type</td><td class="<?php echo $TDstyle ?>">
        <Select name="filter_type" class='form-select text-white bg-primary' aria-label='Default select example' style='width:fit-content;' id='slctFtype'>
        <option>Select Type</option>
<?php
        foreach($arFilterTypes as $value)
             {
                echo "<option value='".$value."' "; 
                if(strcmp($FilterType ,$value)==0){echo "SELECTED";} 
                echo ">".$value."</option>";
             }
?>
        </Select></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter due date</td><td class="<?php echo $TDstyle ?>"><input type="date" id="Test_DatetimeLocal" data-date="" data-date-format="YYYY-DD-MMMM" id="filters_due" name="filters_due" value="<?php echo $FiltersDue ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter Rotation</td><td class="<?php echo $TDstyle ?>"><input type="text" id="filter_rotation" name="filter_rotation" value="<?php echo $Rotation ?>" onkeyup="setfilters(this.id);"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Belts</td><td class="<?php echo $TDstyle ?>"><input type="text" id="belts" name="belts" value="<?php echo $Belts ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Notes</td><td class="<?php echo $TDstyle ?>"><input type="text" id="notes" name="notes" value="<?php echo $Notes ?>"></td></tr>
        <input type="hidden" name="username" value="<?php echo $UserName ?>">
        <input type="hidden" id="addunitnow" name="addunitnow" value="true">
        </form>
        <tr><td class="<?php echo $TDstyle ?>"></td><td class="<?php echo $TDstyle ?>"><button class='btn btn-success border-primary' style='width:120px;' onclick='checkForm("frmsubmit");'>Add new unit</button></td></tr>
</table>
    <?php
 if(isset($_POST["addunitnow"]) && $UnitAlreadyExists==false){

         $UnitName = $_POST["unit_name"];
         $Location = $_POST["location"];
         $AreaServed = $_POST["area_served"];
         $FilterSize = $_POST["filter_size"];
         $FilterType = $_POST["filter_type"];
         $FiltersDue = $_POST["filters_due"];
         $Rotation = $_POST["filter_rotation"];
         $Belts = $_POST["belts"];
         $Notes = $_POST["notes"];

         //Query to update equipment 
         $jsonString = file_get_contents('sites/' . $_SESSION["backup_folder"] . '/data.json');
        $data = json_decode($jsonString, true);
        $newId = getUniqueID("equipment");
        $newEquipment = [
            "_id" => $newId,
            "unit_name" => $UnitName,
            "location" => $Location,
            "area_served" => $AreaServed,
            "filter_size" => $FilterSize,
            "filters_due" => $FiltersDue,
            "belts" => $Belts,
            "notes" => $Notes,
            "filter_rotation" => $Rotation,
            "filter_type" => $FilterType,
            "filters_last_changed" => "never",
            "assigned_to" => "",
            "image" => ""
        ];
        $data["equipment"][] = $newEquipment;
        $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
        if (file_put_contents('sites/' . $_SESSION["backup_folder"] . '/data.json', $updatedJson)) {
            echo "<div style='text-align:center;width:100%;background-color:black;color:white;position: absolute;top: 0;right: 0;'>".$UnitName." was added successfully!</div>";
        } else {
            echo "<div style='text-align:center;width:100%;background-color:black;color:red;position: absolute;top: 0;right: 0;'>Failed to save new equipment.</div>";
        }

     }

        ?>

</body>
</html>