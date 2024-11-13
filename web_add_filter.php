<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }
  
    include 'phpfunctions.php';
    include 'fm.css';
    include 'BootStrapToast.css';
    include 'functions.php';
    include 'javafunctions.php';
    include 'snackbar.css';
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Create new filter</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <div class="container">
    <header class="d-flex justify-content-left py-3">
      <ul class="nav nav-pills">
        <li class="nav-item"><a href="ManageFilterTypes.php?Action=addfiltertype" class="nav-link"><button type="button" class="btn btn-success">Manage filter types</button></a></li>
        <li class="nav-item"><a href="web_update_filters.php" class="nav-link"><button type="button" class="btn btn-success">Back to filters</button></a></li>
      </ul>
    </header>

  </div>
<?php
$Action="";
$FilterSize="";
$FilterType="";
$Storage = "";
if(isset($_GET["filter_size"])){$FilterSize = $_GET["filter_size"];}
if(isset($_GET["pn"])){$PN = $_GET["pn"];}
if(isset($_GET["filter_type"])){$FilterType = $_GET["filter_type"];}
if(isset($_GET["action"])){$Action = $_GET["action"];}
if(isset($_POST["filter_size"])){$FilterSize = $_POST["filter_size"];}
if(isset($_POST["filter_type"])){$FilterType = $_POST["filter_type"];}
if(isset($_POST["filter_count"])){$FilterCount= $_POST["filter_count"];}
if(isset($_POST["par"])){$Par = $_POST["par"];}
if(isset($_POST["storage"])){$Storage= $_POST["storage"];}
if(isset($_POST["pn"])){$PN= $_POST["pn"];}
if(isset($_POST["notes"])){$Notes= $_POST["notes"];}
if(isset($_POST["action"])){$Action = $_POST["action"];}
//print_r($_POST);
//if(isset($Storage)){echo "<br>".implode(",", $Storage)."<br>";}
if($Action == "addfilter")
  {
    //SEE IF FILTER already exists
 $myQuery=   searchInJSONFile("filters", "filter_size", $FilterSize);

if ($myQuery === false) 
    {
        $Today = date("Y-m-d");
        $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"] . '/data.json');
        $data = json_decode($jsonString, true);
        $filtersArray = &$data["filters"]; 
        $id = getUniqueID("filters");
        $newFilter = [
        "_id" => $id,
        "filter_size" => $FilterSize ,
        "filter_type" => $FilterType,
        "filter_count" => $FilterCount,
        "par" => $Par,
        "pn" => $PN,
        "storage" => $Storage,
        "notes" => $Notes,
        "date_updated" => $Today
        ];
        $filtersArray[] = $newFilter;
        $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('sites/'.$_SESSION["backup_folder"] . '/data.json', $updatedJson);
        echo "New filter: ".$FilterSize." ".$FilterType. " was added successfully!";
    }
    else
    {
        echo "<div style='color:red;background-color:white;'>".$FilterSize." ".$FilterType. " already exists in database</div>";
    }
}





?>
<script>
function ckIfFilterExists(filterSize, filterType) 
{
    let filter = "["+filterSize + filterType+"]";
    let result = document.getElementById("txtData-json").value.indexOf(filter);
    console.log(result);
    if(result === -1){
        document.getElementById("divFilterExists").style.display = "none";
        document.getElementById("btnsubmit").disabled = false;
    }else{
        document.getElementById("divFilterExists").style.display = "inline-block";
        document.getElementById("btnsubmit").disabled = true;
    }
}
</script>
<script>
function validateForm() {
var formReady = true;
var errorcode = "";


  var x = document.forms["frmAddNewFilter"]["filtersize"].value;
  

  if (x == "") {
    formReady = false;
    errorcode = "Filter size can not be blank."
  }

  var x = document.forms["frmAddNewFilter"]["par"].value;
  if (x == "") {
    formReady = false;
    errorcode = "Par can not be blank;"
  }
  
var x = document.forms["frmAddNewFilter"]["filter_count"].value;
  if (x == "") {
    formReady = false;
    errorcode = "Amount in stock can not be blank";
  }

var e = document.getElementById("slctFilterType");
var value = e.value;
var text = e.options[e.selectedIndex].text;
  if (text == "Choose filter type") {
    formReady = false;
    errorcode = "Please select a filter type."
  }
var x = document.forms["frmAddNewFilter"]["fsize1"].value;
var y = document.forms["frmAddNewFilter"]["filtersize"].value;
var z = document.forms["frmAddNewFilter"]["fsize2"].value;

if (x == "" || y == "" || z == "") {
    formReady = false;
    errorcode = "Please fill out all filter demensions (Width, Height, Depth)."
  }

if(formReady == false){
   var snackbar = document.getElementById("snackbar");
    snackbar.innerHTML = errorcode;
    snackbar.className="bg-danger text-white w-50";
     snackbar.className = "show";
   setTimeout(function(){ snackbar.className = snackbar.className.replace("show", ""); }, 3000);
}
else{
    document.getElementById("frmAddNewFilter").submit();
}
}

</script>
<script>
function setfilters(elementID)
{
   var isNum =  checkDataType(elementID);
    $f1=document.getElementById("fsize1").value.trim();
    $f2=document.getElementById("fsize2").value.trim();
    $f3=document.getElementById("fsize3").value.trim();
     $filters=document.getElementById("filtersize");

if($f1.value > $f2.value)
    {
      $hold = $f1;
      $f1=$f2;
      $f2=$hold;
    }

if (isNum == true)
    {
      if($f1 != "" && $f2 == "" && $f3 == "")
        {
          $filters.value=$f1;
        }
      if($f1 != "" && $f2 != "" && $f3 == "")
        {
          $filters.value=$f1 + "x" + $f2;
        }
      if($f1 != "" && $f2 != "" && $f3 != "")
        {
          $filters.value=$f1 + "x" + $f2 +"x" + $f3;
        }
    }
  if($f1 == "" && $f2 == "" && $f3 == "")
    {
      $filters.value="";
    }
}
</script>
<?php
//$FilterSize comes in from GET from WebTasks.php to create a filter that is not made yet;
if(strlen($FilterSize) <= 0){$FilterSize= " x x ";}
if(strlen($FilterSize) > 0){$fsizes = explode("x", $FilterSize);}
// $fsizes[0]."<br>"; 
//echo $fsizes[1]."<br>"; 
//echo $fsizes[2]."<br>"; 
   

?>
    <table border="3;3" class="table table-striped">
<tr><td></td><td><b>Add New Filter Size</b></td><tr>
            <td><form method="POST" name="frmAddFilter" action="web_add_filter.php" id="frmAddNewFilter"></td>
            <tr><td class="<?php echo $TDstyle ?>">Create Filter size</td>
            <td class="<?php echo $TDstyle ?>">width&nbsp;&nbsp;<input type='text' onkeyup='setfilters(this.id);' maxlength='4' style='font-weight: bold; overflow: hidden; max-width: 6ch;text-align:center;' id='fsize1' name='fsize1' value='<?php echo $fsizes[0] ?>'>&nbsp;height&nbsp;&nbsp;<input type='text' onkeyup='setfilters(this.id);' onfocus="$(this).select();" maxlength='4' style='font-weight: bold; overflow: hidden; max-width: 6ch;text-align:center;' id='fsize2' name='fsize2' value='<?php echo $fsizes[1] ?>'>&nbsp;depth&nbsp;&nbsp;<input type='text' onkeyup='setfilters(this.id);' maxlength='4' style='font-weight: bold; overflow: hidden; max-width: 6ch;text-align:center;' id='fsize3' name='fsize3' value='<?php echo $fsizes[2] ?>'>
            <br><div style="font-size:.5em;color:red;">Input smaller dimension first i.e. 20X24X1 not 24X20X1</div></td></tr>
         
        <tr><td class="<?php echo $TDstyle ?>">This Filter Size</td><td class="<?php echo $TDstyle ?>"><input type='text'id="filtersize" class="form-control" style='font-weight: bold; width: 300px;' id='filter_size' name='filter_size' value='<?php if($FilterSize != " x x "){echo $FilterSize;} ?>'  readonly></td></tr>
</td></tr>
<tr><td>Filter Type</td><td>
        <div id="divFilterExists" style="width:175px;display:none; background-color: whitesmoke;color:#cc1439;">Filter already exists</div><br>
        <select class="form-select" aria-label="Default select example" name="filter_type" id="slctFilterType" onchange="ckIfFilterExists(document.getElementById('filtersize').value,this.options[this.selectedIndex].text);">
             <option selected>Choose filter type</option>
             <?php
            $arFilterTypes = arrayFromDataJson("filter_types", "type");
            print_r($arFilterTypes);
            foreach($arFilterTypes as $row)
                        {
                            echo "<option value='". $row ."'"; 
                            if($row == $FilterType){echo " selected>";}else{echo ">";}
                            echo $row ."</option>";                               
                        }
                
            ?>
            </select>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-success" href="ManageFilterTypes.php"><button type="button" class="btn btn-success .text-white">Manage filter types</butten></a>
      </ul>
    </div>
  </div>

</td></tr>
<tr><td>Total in stock</td><td><input type="text" class="form-control" style='font-weight: bold; width: 300px;' cols="2" name="filter_count"></td></tr>
<tr><td>Part Number <div style="color:red;font-size:.5em;">(optional)</div></td><td><input type="text" class="form-control" style='font-weight: bold; width: 300px;' name="pn"></td></tr>
<tr><td>Par</td><td> <input type="text" class="form-control" style='font-weight: bold; width: 300px;' name="par"></td>
<tr><td>Storage Locations</td><td><select class="form-select" aria-label="Default select example" name="storage[]" id="slctStorage" multiple>
             <?php
            $arLocations = arrayFromDataJson("storage", "location");
            foreach($arLocations as $row)
                        {
                            echo "<option value='". $row ."'>".$row."</option>";                               
                        }
                
            ?>
            </select>&nbsp;&nbsp;<a href="ManageStorage.php"><button class="btn btn-primary>Manage storage locations</button></a></td>
</tr>
<tr><td>Notes</td><td><textarea class="form-control" rows="4" cols="20" id="notes" style='font-weight: bold; width: 300px;' maxlength ="50" name="notes"></textarea></td></tr>
<tr><td></td><td><input type="hidden" name="action" value="addfilter"></form><button class="btn-success" id="btnsubmit" onclick="validateForm();">Submit</button></td></tr>
    </table>
<div id="snackbar">Some text some message..</div>
<?php
echo "<textarea  style='width:0px;hieght:0px;' id='txtData-json'>";
$jsonData = file_get_contents('sites/'.$_SESSION["backup_folder"] . '/data.json');
$data = json_decode($jsonData, true);
$TableName="filters";
$data = json_decode($jsonData, true);

if (isset($data[$TableName])) {
   $targetData = $data[$TableName];
   foreach ($targetData as $item) {
       foreach ($item as $key => $value) {
           echo "[".$item["filter_size"].$item["filter_type"]."]";
       }
   }
} else {
   echo "Array $TableName not found in the JSON file.\n";
}
  ?>
</body>
</html>