<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)

//include 'dbMirage_connect.php';
include "phpfunctions.php";
include "javafunctions.php";
include "snackbar.css";
include "functions.php";
$FontSize = "20px";
$RecID = "";
$UnitName = "";
$Location = "";
$AreaServed = "";
$FilterSize = "";
$FilterType  = "";  
$FiltersDue = "";
$Rotation = "";
$Belts = "";
$Notes = "";
$Action = "";
$UserName = "";
$Theme="";
$arFilterTypes = array();
//CREATE array of all filter types
$arFilterTypes =  arrayFromDataJson("filter_types", "type");
//create array of filters
$arFilters = arrayFromDataJson("filters","filter_size");
//print_r($_COOKIE);
if (isset($_SESSION["theme"])) {
    $Theme = $_SESSION["theme"];
    //echo "<font color='black'>cookie theme=".$Theme;
}
if (isset($_COOKIE["cookie_username"])) {
    $UserName = $_COOKIE["cookie_username"];
    //echo "<font color='black'>cookie theme=".$Theme;
}
$TDstyle = "";//GetUserSetting($username, "theme");
?>
<!DOCTYPE html>
<html>
<head>
<script src="jquery-3.6.3.min.js"></script>
<link rel="stylesheet" href="fm.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<meta charset="utf-8"
    name="viewport" content="width=device-width, initial-scale=1">
    <title>Filter manager. edit unit</title>
 <style>

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
text-align:center;
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
text-align:center;
padding: 2px;
white-space:nowrap;
}
        * {
            box-sizing: border-box;
        }

		input[type=text]{
			border: 3px solid #ddd;
			background-color:blue;
			color:white;
			border-color:'blue';
			height:30px;
			font-size:14px;
		}
		
textarea {
    width: 200px;
    padding: 18px 22px;
    border: none;
    border-radius: 5px;
    color: white;
    background-color: blue;  
}
        #myInput {
           
            background-position: 10px 12px; /* Position the search icon */
            background-repeat: no-repeat; /* Do not repeat the icon image */
            width: 100%; /* Full-width */
            font-size: 16px; /* Increase font-size */
            padding: 12px 20px 12px 40px; /* Add some padding */
            border: 5px solid #ddd; /* Add a grey border */
            margin-bottom: 12px; /* Add some space below the input */
        }

        #myTable {
            border-collapse: collapse; 
            width: 100%; /* Full-width */
            border: 1px solid grey; /* Add a grey border */
            font-size: 18px; /* Increase font-size */
        }

        #myTable th, td {
            text-align: left; /* Left-align text */
            padding: 12px; /* Add padding */
			font-weight:bold;
            background-color:<?php echo $TDstyle ?>;
            border: 1px solid grey;
        }

        #myTable tr {
            /* Add a bottom border to all table rows */
            border-bottom: 1px solid #ddd;
             border: 1px solid grey;
        }

        #myTable tr.header {
            /* Add a grey background color to the table header and on hover */
            background-color: #f1f1f1;
            position: fixed;
            top: 0px; display:none;
        }

        
    </style>
<script>
function confirm_delete()
{
    if (confirm('Are you sure you want to permentaly delete this unit?')) 
        {
          document.getElementById('frmDelete').submit();
        } else 
        {
          alert('delete canceled');
        }
}
</script>

<script>
function setfilters(){

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
</head>
<?php
if(strcmp($Theme,"Dark-tdPlain")==0)
   {
      ?>
      <body style='background-color: black;'>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <?php
      $TDstyle="Dark-tdPlain";
   }
if(strcmp($Theme,"Light-tdPlain")==0)
{
   ?>
   <body style="background-color: white;" onload="if(document.getElementById('divEditSuccess').innerHTML == 'success'){window.location.href='ListEquipment.php';};console.log('true')">
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <?php
    $TDstyle="Light-tdPlain";
}
//error_reporting(0);


//or, if you DO want a file to cache, use:

//echo var_dump($_GET);

if(ISSET($_POST["unit_name"])) {
    //echo "from post=" . $_POST["_id"] . " name: " . $_POST["unit_name"];
}


$UnitID="";
if(ISSET($_POST["_id"])){
$UnitID = $_POST["_id"];
}
if(isset($_POST["Delete_image"]))
   {
      $file = $_POST["image_name"];
      if (!unlink($file)) 
         {  
            echo ("$file cannot be deleted due to an error");  
         }  
      else 
         {  
         $query = "UPDATE equipment SET image='' WHERE _id='" . $UnitID."';";
            if (mysqli_query($con, $query)) 
               {
                   
               }
            //header("Location: webEditUnit.php?_id=".$UnitID); 
            //exit;  
         }  
   }



if(isset($_POST["action"])){$Action=$_POST["action"];}
if (isset($_GET['_id'])){$RecID = $_GET['_id'];}
if (isset($_GET["_id"])){$UnitID = $_GET["_id"];}
if (isset($_GET['unit_name'])){$UnitName = $_GET['unit_name'];}
if (isset($_GET['location'])){$Location = $_GET['location'];}
if (isset($_GET['area_served'])){$AreaServed = $_GET['area_served'];}
if (isset($_GET['filters'])){$FilterSize = $_GET['filter_size'];}
if (isset($_GET['filter_type'])){$FilterType  = $_GET['filter_type'];}  
if (isset($_GET['filters_due'])){$FiltersDue = $_GET['filters_due'];}
if (isset($_GET['rotation'])){$Rotation = $_GET['rotation'];}
if (isset($_GET['belts'])){$Belts = $_GET['belts'];}
if (isset($_GET['notes'])){$Notes = $_GET['notes'];}
if (isset($_GET['filters_last_changed'])){$FiltersLastChanged = $_GET['filters_last_changed'];
           }
    // echo "Unit name=" . $_POST['unit_name'];//&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){

//if(isset($_POST['_id'])&&isset($_POST['unit_name'])&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){
        if (isset($_POST['_id'])){$RecID = $_POST['_id'];}
        if (isset($_POST['unit_name'])){$UnitName = $_POST['unit_name'];}
        if (isset($_POST['location'])){$Location = $_POST['location'];}
        if (isset($_POST['area_served'])){$AreaServed = $_POST['area_served'];}
        if (isset($_POST['filter_size'])){$FilterSize = $_POST['filter_size'];}
        if(!strpos($FilterSize,")")>0){$FilterSize = "(1)".$FilterSize;}
        if (isset($_POST['filter_type'])){$FilterType  = $_POST['filter_type'];}
        if (isset($_POST['filters_due'])){$FiltersDue = $_POST['filters_due'];}
        if (isset($_POST['filter_rotation'])){$Rotation = $_POST['filter_rotation'];}
        if (isset($_POST['belts'])){$Belts = $_POST['belts'];}
        if (isset($_POST['notes'])){$Notes = $_POST['notes'];}

     if(strcmp($Action,"delete_unit")==0)
     {
        $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
        $data = json_decode($jsonString, true);
        $foundIndex = null;
        foreach ($data["equipment"] as $index => $array) {
            if ($array["_id"] === $UnitID) {
                $unitToRemove = $array["unit_name"];
                $foundIndex = $index;
                break; // Exit the loop once found
            }
        }

        if ($foundIndex !== null) {
            unset($data["equipment"][$foundIndex]);
            $data["equipment"] = array_values($data["equipment"]);
            if (file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', json_encode($data, JSON_PRETTY_PRINT))) {
                echo "<div style='background-color:green;color:white;width:100%;height:100px;text-align:center;font-size:2em;'>".$unitToRemove."  was removed successfully!</div><div style='text-align:center;'><img src='images/DeleteUnit.jpg' style='width:50%;height:auto;'></div>";
            } else {
                echo "<div style='background-color:green;color:white;width:100%;height:100px;text-align:center;font-size:2em;'>Error saving modified data to JSON file.</div>";
            }
        } else {
            echo "<div style='background-color:green;color:white;width:100%;height:100px;text-align:center;font-size:2em;'>Unit with '_id' $UnitID not found in the 'equipment' object.</div>";
        }
     }

if(strcmp($Action,"updatenow")==0)
     {
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data["equipment"] as &$object) 
    //echo $object['_id'] ."===". $RecID."<br>";
        {
            if ($object['_id'] == $RecID) 
                {
                    // Change the value of the specified key
                    $object['unit_name'] = $UnitName;
                    $object['area_served'] = $AreaServed;
                    $object['filter_size'] = $FilterSize ;
                    $object['filters_due'] = $FiltersDue ;
                    $object['location'] = $Location ;
                    $object['filter_rotation'] = $Rotation;
                    $object['belts'] = $Belts ;
                    $object['notes'] =  $Notes;
                    $object['filter_type'] =  $FilterType;
                }
        }
    $jsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
    }

    echo "<table class='myTable'";
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
foreach ($data["equipment"] as $row) {


        if($row["_id"] == $UnitID)
{

?>
    <tr><td class="<?php echo $TDstyle ?>"><h3>Editing unit:<span class="badge badge-success"><?php echo $row["unit_name"] ?></span></h3><input type="hidden" name="action" value="delete_unit"></form></td><td class="<?php echo $TDstyle ?>"></td><td style="text-align:center;"><form method="POST" action="webEditUnit.php" id="frmDelete"><input type="button" class="btn btn-danger" value="DELETE UNIT FROM DATABASE" onclick="confirm_delete();"><input type="hidden" name="unit_name" value="<?php echo $row["unit_name"] ?>"><input type="hidden" name="_id" value="<?php echo $UnitID ?>"><input type="hidden" name="action" value="delete_unit"></form></tr>

    <form action="webEditUnit.php" method="post" id="frmSubmit">
        <?php
        echo "<tr><input type='hidden' id='". $row["_id"]. "' name='_id' value='". $row["_id"]. "'>";
        echo "<td class=".$TDstyle .">Unit name</td><td class=". $TDstyle ."><input type='text' class='form-select text-white bg-primary' id='unit_name' name='unit_name' value='". $row["unit_name"]. "'></td></tr>";
        echo "<tr><td class=". $TDstyle .">Location</td><td class=". $TDstyle ."><div class='view'><input type='text' class='form-select text-white bg-primary' id='". $row["location"] . "' name='location' value='". $row["location"]. "'></div></td></tr>";
        echo "<tr><td class=". $TDstyle .">Area served</td><td class=". $TDstyle ."><input type='text' class='form-select text-white bg-primary' id='". $row["area_served"] . "' name='area_served' value='". $row["area_served"]. "'></td></tr>";       
        
        $numberOfFilterSets=0;
        $arFsizes=array();
        $FilterSize1="";
        $FilterCount1="";
        $FilterSize2="";
        $FilterCount2="";
        $arFsizes =  ExtractFilterSize($row["filter_size"]);
        //print_r($arFsizes);

        foreach($arFsizes as $x => $x_value) {
            foreach($x_value as $key => $value) {
                if(strcmp($key,"size")==0){$numberOfFilterSets = $numberOfFilterSets + 1;}
                if(strcmp($key,"size")==0 && $numberOfFilterSets == 1){$FilterSize1=$value;}
                if(strcmp($key,"count")==0 && $numberOfFilterSets == 1){$FilterCount1=$value;}
                if(strcmp($key,"size")==0 && $numberOfFilterSets == 2){$FilterSize2=$value;}
                if(strcmp($key,"count")==0 && $numberOfFilterSets == 2){$FilterCount2=$value;}
            }
          }
        ?>
        <tr>
            <td class="<?php echo $TDstyle ?>">Filter size # 1:</td><td class="<?php echo $TDstyle ?>">
            <div class='container m-4'>Amount (<input type='text' class='form-select text-white bg-primary text-center' onkeyup='setfilters();' maxlength='3' style='width:60px; overflow: hidden; max-width: 6ch;' id='amount1' name='filter_amount1' value='<?php echo $FilterCount1 ?>'>)&nbsp;&nbsp;Size&nbsp;<select name='filtersize1' class='form-select text-white bg-primary' aria-label='Default select example' onchange='setfilters();'  id='slctSize1' title='input filter qty first'><?php
        sort($arFilters, SORT_NUMERIC);
        foreach($arFilters as $key => $value)
             {
                echo "<option value='".$value."'";
                
                if(trim($FilterSize1) == trim($value))
                {
                    echo " selected>".$value."</option>";
                } 
                else 
                {
                    echo ">".$value."</option>";
                }
             }
             if($FilterSize2 != ""){$xClass="container m-4";}else{$xClass="d-none";}
             if($FilterSize2 != ""){$yClass="d-none";}else{$yClass="btn btn-primary";}
             ?></select><input type='text' id='filter_size' name='filter_size' value='<?php echo $row["filter_size"] ?>' class='form-select text-white bg-primary text-center d-none'></div></td></tr><?php
            ?><tr><td class="<?php echo $TDstyle ?>">Filter size #2:(optional)</td><td class="<?php echo $TDstyle ?>"><button id="btnShowSize2" class='<?php echo $yClass ?>' type='button' onclick="document.getElementById('filtersize2').className='container m-4';this.className='d-none';">Add second filter size</button><div class='<?php echo $xClass ?>' id='filtersize2'>Amount (<input type='text' class='form-select text-white bg-primary text-center' onkeyup='setfilters();' maxlength='3' style='width:60px; overflow: hidden; max-width: 6ch;' id='amount2' name='filter_amount2' value='<?php echo $FilterCount2 ?>'>)&nbsp;&nbsp;Size&nbsp;<select name='filtersize2' class='form-select text-white bg-primary' aria-label='Default select example' onchange='setfilters();'  id='slctSize2'><option value='select size'>select size</option><?php
        foreach($arFilters as $key => $value)
             {
                echo "<option value='".$value."'";
                if(trim($FilterSize2) == trim($value))
                {
                    echo " selected>".$value."</option>";
                } 
                else 
                {
                    echo ">".$value."</option>";
                }
             }
             
        ?>
        </select>&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' onchange="document.getElementById('filtersize2').className='d-none';document.getElementById('btnShowSize2').className='d-block';document.getElementById('amount2').value='';this.checked=false;setfilters();">&nbsp;&nbsp;<font color='red'><font size='2px'>Remove 2nd filter<div></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter Type</td><td class="<?php echo $TDstyle ?>"><Select name="filter_type" class='form-select text-white bg-primary' aria-label='Default select example'><option="select type" id='slctFtype'>
<?php
        foreach($arFilterTypes as $key => $value)
             {
                echo "<option value='".$value."' "; 
                if(strcmp($row["filter_type"] ,$value)==0){echo "SELECTED";} 
                echo ">".$value."</option>";
             }
?>
        </Select></td></tr>
        
        <tr><td class="<?php echo $TDstyle ?>">Filter due date</td><td class="<?php echo $TDstyle ?>"><input type="date" id="Test_DatetimeLocal" data-date="" data-date-format="YYYY-DD-MMMM" id="filters_due" name="filters_due" value="<?php echo $row["filters_due"] ?>"></td></tr>
        <?php
        echo "<tr><td class=". $TDstyle .">Filter Rotation</td><td class=". $TDstyle ."><input type='text' class='bg-primary' id='filter_rotation' name='filter_rotation' value='". $row["filter_rotation"]. "' onkeyup='setfilters();'></td></tr>";
        //echo "Filter last changed<input type='text' id='" . $row["filters_last_changed"] . "' name='filters_last_changed' value='". $row["filters_last_changed"]. "'></td></tr>";
        echo "<tr><td class=". $TDstyle .">Belts</td><td class=". $TDstyle ."><input type='text' class='bg-primary' id='". $row["belts"] . "' name='belts' value='". $row["belts"]. "'></td></tr>";
        echo "<tr><td class=". $TDstyle .">Notes</td><td class=". $TDstyle ."><textarea rows ='5' class='bg-primary' id='". $row["notes"] . "' name='notes'>". $row["notes"]. "</textarea></td></tr>";
        echo "<input type='hidden' id='' name='action' value='updatenow'></form>";
        ?><tr><td class="<?php echo $TDstyle ?>"></td><td class="<?php echo $TDstyle ?>" style="display:flex;flex-direction:row;"><button class='btn btn-success border-primary' style='width:120px;height:50px;' onclick="checkForm('frmSubmit');">Update</button><?php
        echo "<form action='filtertype.php' method='get'>
        <input type='submit' value='FILTERS DONE' class='btn btn-primary border-primary' style='width:120px;height:50px;'>
        <input type='hidden' name='page' value='ListEquipment.php'>
        <input type='hidden' name='filtertype' value='".$row["filter_type"] ."'> 
        <input type='hidden' name='username' value='". $UserName."'>
        <input type='hidden' name='filters_due' value='". $FiltersDue."'>
        <input type='hidden' name='filter_rotation' value='".$row["filter_rotation"]."'>
        <input type='hidden' name='unit_id' value='".$row["_id"] ."'>
        <input type='hidden' name='unit_name' value='".$row["unit_name"] ."'>
        <input type='hidden' name='filter_size' value='".$row["filter_size"]."'>
        <input type='hidden' name='filters_used' value='".$row["filter_size"]."'>
        </form>";
        echo "<form action='ListEquipment.php'><input type='submit' class='btn-warn' style='width:120px;height:50px;' value='Cancel'></form></td></tr>";
        echo "</td></tr>";
        }
    }
    


if (isset($_POST['_id'])) 
   {
      $UnitID = $_POST['_id'];
      $UnitID = (int)$UnitID;
      //echo "Unit id=". $UnitID;
   }
if(isset($_POST["submit_file"]))
   {

   //echo "Your file=".basename($_FILES["fileToUpload"]["name"]);
$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$file_ext = strstr($target_file, '.');
//echo "ext=".$file_ext."<br>";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit_file"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        //echo "File is an image - " . $check["mime"] . ".<br>";
        $uploadOk = 1;
    } else {
        echo "File is not an image.<br>";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.<br>";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 10000000) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else 
   {
    
            $path = $_FILES['image']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $newfilename = $target_dir.$UnitID.$file_ext;
           // $oldfile=$target_file;
            //echo "temp name=".$_FILES["fileToUpload"]["tmp_name"];
                //echo "The file ". basename( $_FILES["fileToUpload"]["name"]). "<br> was successfully uploaded.";
                //resizeImage($oldFile, $newfilename);
                $newfilename="images/".$UnitID.$file_ext;
                //echo "<br>".$newfilename;
                $imgData = resizeImage($_FILES["fileToUpload"]["tmp_name"], $newfilename, 300, 300);
               
               imagepng($imgData, $newfilename);
               //unlink($filename);
            $query = "UPDATE equipment SET image='" . $newfilename. "' WHERE _id='" . $UnitID."';";
            if (mysqli_query($con, $query)) 
               {
                  echo "<img src='" .$newfilename."' height='120px' width='120px'><br>";
                 
                } else 
                {
                    echo "Error updating img url: " . mysqli_error($con);
                }
      
   }
}

function resizeImage($file, $newFileName, $w, $h, $crop=false) {

    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
    
    //Get file extension
    $exploding = explode(".",$file);
    $ext = end($exploding);
    
    switch($ext){
        case "png":
            $src = imagecreatefrompng($file);
        break;
        case "jpeg":
        case "jpg":
            $src = imagecreatefromjpeg($file);
        break;
        case "gif":
            $src = imagecreatefromgif($file);
        break;
        default:
            $src = imagecreatefromjpeg($file);
        break;
    }
    
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $dst;
}

 ?>


</body>
</html>