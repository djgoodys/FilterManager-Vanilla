<?php
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
    if (isset($_COOKIE["theme"])) {
    $Theme = $_COOKIE["theme"];
    //echo "<font color='black'>cookie theme=".$Theme;
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
    <script>
function setfilters()
{
$f1=document.getElementById("fsize1");
$f2=document.getElementById("fsize2");
$f3=document.getElementById("fsize3");
$amount=document.getElementById("amount");
$filters=document.getElementById("filter_size");
if($f1.value > $f2.value){
   $hold = $f1;
   $f1=$f2;
   $f2=$hold;
}
$filters.value="("+$amount.value+")"+$f1.value + "x" + $f2.value +"x" + $f3.value;
}

function setfilters2()
{
$f1=document.getElementById("fsize1");
$f2=document.getElementById("fsize2");
$f3=document.getElementById("fsize3");
$f4=document.getElementById("fsize4");
$f5=document.getElementById("fsize5");
$f6=document.getElementById("fsize6");
if($f1.value > $f2.value){
   $hold = $f1;
   $f1=$f2;
   $f2=$hold;
}
if($f4.value > $f5.value){
   $hold = $f4;
   $f4=$f5;
   $f5=$hold;
}
$amount=document.getElementById("amount");
$filterset1="("+ $amount.value +")" + $f1.value + "x" + $f2.value + "x" + $f3.value;
$amount2=document.getElementById("amount2");
$filters=document.getElementById("filter_size");
$filters.value=$filterset1 + " (" + $amount2.value + ")" + $f4.value + "x" + $f5.value +"x" + $f6.value;
}
</script>

</head>
<?php
$Theme="";
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


include 'dbDemo_connect.php';
//set headers to NOT cache a page

//echo var_dump($_POST);
//foreach ($_POST as $param_name => $param_val) {
  //  echo "Param: $param_name; Value: $param_val<br />\n";
//}
$visibility ="none";
$UnitNameFound="none";
$row_cnt=0;
$ServerResponse="none";
$UnitName = "";
   $AreaServed = "";
   $Location = "";
   $FilterSize = "";
   $FiltersDue = "";
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
    $query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation,filters_last_changed FROM equipment WHERE unit_name='".$UnitName ."';";
         if($result = $con->query($query))
               {
                    $row_cnt = $result->num_rows;
                    if($row_cnt > 0)
                         {
                              $UnitAlreadyExists=true;
                              while ($row = $result->fetch_assoc()) 
                                   {
                                         // echo " number of rows=".$row_cnt."unit name=".$row["unit_name"];
                                          $UnitName = $_POST["unit_name"];
                                        $Location = $_POST["location"];
                                          $AreaServed = $_POST["area_served"];
                                          $FilterSize = $_POST["filter_size"];
                                          if(!strpos($FilterSize,")")>0)
                                               {
                                                     $FilterSize="(1)".$FilterSize;
                                               }
                                                     $FiltersDue = $_POST["filters_due"];
                                                     $Rotation = $_POST["filter_rotation"];
                                                     $Belts = $_POST["belts"];
                                                      $Notes = $_POST["notes"];
                                                     $unitIdFound = $row["_id"];
                                                     $UnitNameFound = $row["unit_name"];
                                                     $LocationFound = $row["location"];
                                                     $AreaServedFound = $row["area_served"];
                                                     $FilterSizeFound = $row["filter_size"];
                                                     $FiltersDueFound = $row["filters_due"];
                                                     $RotationFound = $row["filter_rotation"];
                                                     $BeltsFound = $row["belts"];
                                                     $NotesFound = $row["notes"];
                                                     $ServerResponse="This unit was found:\nUnit name: " .$UnitNameFound. "\nLocation: ".$LocationFound . "\nArea served: " . $AreaServedFound;
                                      } 
                                }
                 }
         if($UnitNameFound != "none"){
              $visibility = "visible";
              echo "<font color='red'>" , $UnitNameFound . " already exists</font><br><a href='web_control_panel.php?_id=".$unitIdFound . "&unit_name=".$UnitNameFound ."'>EDIT UNIT</a> or change unit name below.";
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
<tr><td class="<?php echo $TDstyle ?>"><textarea overflow-y: scroll rows="6" id="txtServerResponse"
<?php 
if($ServerResponse !="none") 
{echo " style='display:initial;'>".$ServerResponse ."</textarea></td></tr>";}
else
{echo " style='display:none;'></textarea></td></tr>";}
?>
<tr><td style="background-color:black; color:white;">****ADDING NEW UNIT****</td></tr></table>
<table style="myTable" id="myTable" border="1">
<tr><td class="<?php echo $TDstyle ?>">
<form action="webEditUnit.php" method="post" enctype="multipart/form-data">
    Select image to upload if you wish.<br> (Max file size 500 kb.)<br>
    <input type='file' style='height:50px;width:220px;' name="fileToUpload" style='height:30px;width130px;' value='Upload file now'><br>
    <input type="hidden" name="_id" value="<?php echo $row["_id"] ?>">
    <input type="submit" style='height:30px;width130px;' onclick='document.getElementById("imgUpload").style.display='visible''; value="Upload Image" name="submit_file" id='btnUploadFile'></div>
</form></td></tr>
    <form action="webAddUnit.php" method="post">
        <tr><td class="<?php echo $TDstyle ?>"><font size="3">Unit name</td><td class="<?php echo $TDstyle ?>"><input type="text" id="unit_name" name="unit_name" value="<?php echo $UnitName ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Location</td><td class="<?php echo $TDstyle ?>"><input type="text" id="location"" name="location" value="<?php echo $Location ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Area served</td><td class="<?php echo $TDstyle ?>"><input type="text" id="area_served" name="area_served" value="<?php echo $AreaServed ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter size #1:</td><td class="<?php echo $TDstyle ?>">Amount (<input type='text' onkeyup='setfilters();' maxlength='3' style='width:30px; overflow: hidden; max-width: 4ch;' id='amount' name='filter_amount' value=''>)&nbsp;Filter #1 size<input type='text' onkeyup='setfilters();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize1' name='fsize1' value=''>x<input type='text' onkeyup='setfilters();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize2' name='filter_size2' value=''>x<input type='text' onkeyup='setfilters();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize3' name='fsize3' value=''></td></tr>
         <tr><td class="<?php echo $TDstyle ?>">Filter size #2:</td><td class="<?php echo $TDstyle ?>">Amount (<input type='text' onkeyup='setfilters2();' maxlength='3' style='width:30px; overflow: hidden; max-width: 4ch;' id='amount2' name='filter_amount2' value=''>)&nbsp;Filter #2 size<input type='text' onkeyup='setfilters2();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize4' name='fsize4' value=''>x<input type='text' onkeyup='setfilters2();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize5' name='filter_size5' value=''>x<input type='text' onkeyup='setfilters2();' maxlength='2' style='width:30px; overflow: hidden; max-width: 3ch;' id='fsize6' name='fsize6' value=''></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter sizes</td><td class="<?php echo $TDstyle ?>"><input type='text' id='filter_size' name='filter_size' value='' style='font-weight: bold; width: 300px; background-color: black; color: white;' readonly></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter Type</td><td class="<?php echo $TDstyle ?>">
        <Select name="filter_type">
		<option value="Cut Media">Cut Media</option>
        <option value="Extended 12 inch">Extended 12 inch</option> 
        <option value="Extended 2 inch">Extended  2 inch</option>
        <option value="Paper" selected >Paper</option>
		
        </Select></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter due date</td><td class="<?php echo $TDstyle ?>"><input type="text" placeholder="i.e. 2019-06-04" id="filters_due" name="filters_due" value="<?php echo $FiltersDue ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Filter Rotation</td><td class="<?php echo $TDstyle ?>"><input type="text" id="filter_rotation" name="filter_rotation" value="<?php echo $Rotation ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Belts</td><td class="<?php echo $TDstyle ?>"><input type="text" id="belts" name="belts" value="<?php echo $Belts ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>">Notes</td><td class="<?php echo $TDstyle ?>"><input type="text" id="notes" name="notes" value="<?php echo $Notes ?>"></td></tr>
        <tr><td class="<?php echo $TDstyle ?>"><input type="submit" style="color:gold;font-size : 20px; width: 200px; height:70px;background-color:green;" value="Add unit"></td><td class="<?php echo $TDstyle ?>"></td>
        <input type="hidden" name="username" value="<?php echo $UserName ?>">
                <input type="hidden" id="addunitnow" name="addunitnow" value="true">
    </form>
    <tr><td class="<?php echo $TDstyle ?>"><form action='listequipment.php' method='post'><input type='submit'style="font-size : 20px; width: 200px; height:70px;background-color:green;color:gold;" value='Back to unit list' id='btnGoToUnitlist' name='btnGoUnitList'>
                <input type="hidden" name="action" value="listUnits">
            </form></td><td class="<?php echo $TDstyle ?>"></td></tr>
</table>
    <?php
 if(isset($_POST["addunitnow"]) && $UnitAlreadyExists==false){
// echo var_dump($_POST);
//eecho var_dump($_GET);
     /*
     if (isset($_POST["unit_name"])) {
         $UnitName = $_POST["unit_name"];
         $Location = $_POST["location"];
         $AreaServed = $_POST["area_served"];
         $FilterSize = $_POST["filter_size"];
         $FilterType = $_POST["filter_type"];
         $FiltersDue = $_POST["filters_due"];
         $Rotation = $_POST["filter_rotation"];
         $Belts = $_POST["belts"];
         $Notes = $_POST["notes"];
         }
     }*/
     //echo "Unit name=" . $_POST['unit_name'];//&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){

//if(isset($_POST['_id'])&&isset($_POST['unit_name'])&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){'" . $FiltersDue .

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
         //$query = "UPDATE equipment SET unit_name=?,area_served=?,filter_size=?,filters_due=?,location=?,filter_rotation=?,belts=?,notes=? WHERE _id='".$UnitName."'";
         $query = "INSERT INTO equipment(unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation,filter_type,filters_last_changed) VALUES('" . $UnitName . "','" . $Location . "','" . $AreaServed . "','" . $FilterSize . "','" . $FiltersDue . "','" . $Belts . "','" . $Notes . "','" . $Rotation . "','".$FilterType."','0000-00-00');";
         if (mysqli_query($con, $query)) {
             //header("Location: http://www.filtermanager.net//web_control_panel.php?_id=". $RecID . "&unit_name=" . $UnitName);
             // exit();
             echo "<div style='background-color:black;color:white;'>" . $UnitName . " was added successfully!</div>";
         } else {
             echo "<div style='background-color:black;color:white;'>Error adding record: " . mysqli_error($con) ."</div>";
         } 
     }

        ?>

</body>
</html>