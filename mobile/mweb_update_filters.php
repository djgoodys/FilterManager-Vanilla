<?php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//print_r($_POST);

header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)


include '../dbMirage_connect.php';
include 'NavBar.html';
include '../BootStrapToast.css';
include '../javafunctions.php';
include '../CustomConfirmBox.css';
include '../fm.css';
echo "mwebupdatefilters fontsize=". $FontSize;
$buttonHeight=$FontSize*2;
$SearchWords = "";
$FilterSize="";
//create array of filters for search box 
 $sql = "SELECT filter_size FROM filters;";
   $arFilters = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   array_push($arFilters, $row["filter_size"]);
                }
        }

//create array of filter types for select box
 $sql = "SELECT type FROM filter_types;";
   $arFilterTypes = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   array_push($arFilterTypes, $row["type"]);
                }
        }
?>
<script>
<?php
$js_array = json_encode($arFilters);
echo "var filtersArray = ". $js_array . ";\n";
?>
</script>

</body>
<script>
function enterKeyPressed(event) {
      
      if (event.keyCode == 13) {
         //console.log(event.keyCode);
         resetElements('search');setLastQuery('search');
         return true;
      } else {
         return false;
      }
   }
</script>
<html>
<head>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
   * {
  box-sizing: border-box;
}
table tr {
border: 3px outset white;
}
.column {
  float: left;
  width: 33.33%;
  text-align:right;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
 
}

tr:hover {background-color: coral;}

#myTable {
    border-collapse: collapse; /* Collapse borders */
    width: 100%; /* Full-width */
    border: 1px solid #ddd; /* Add a grey border */
    font-size: 32px; /* Increase font-size */
    }
   
    #myTable th {
      background-color:green;
      
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
    
    #myTable tr{
    /* Add a grey background color to the table header and on hover */
    background-color: green;
    }

    #myTable tr:hover{
    /* Add a grey background color to the table header and on hover */
    background-color: #007acc;
    }

button{
	background-color: white;
	color:green;
	height:<?php echo $buttonHeight ?>px;
	font-size:<?php echo $FontSize ?>px; 
}
input[type=text] { 
	font-size:<?php echo $FontSize ?>px;
	font-weight:bold;
}

body {
background-color: green; 
color:white;
}

/* Portrait orientation */
@media screen and (orientation: portrait) {

}

/* Landscape orientation */
@media screen and (orientation: landscape) {

  
}
</style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<style>
.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9;
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important;
  color: #ffffff;
}
</style>
<script>
   let portrait = window.matchMedia("(orientation: portrait)");

portrait.addEventListener("change", function(e) {
    if(e.matches) {
        //portrait
        const allDivs = document.getElementsByTagName("div");
        const num = allDivs.length;
        console.log(`There are ${num} divs in this document`);
        for(let i=0; i < num; i++)
        {
      console.log(allDivs[i].innerHTML);
      
        }
    } else {
        //landscape
        //if(allDivs[i].className == "col-6 col-sm-3"){console.log(allDivs[i].className;)}
    }
})
</script>
<script>
   function showClearSearch(){
      //alert("starting showClearSearch function"+document.getElementById("clearsearch").classList);
      
      
      //if ( document.getElementById("clearsearch").classList.contains('d-block bg-primary invisible') ){
         //document.getElementById("clearsearch").classList.remove('d-block bg-primary invisible');
        // document.getElementById("clearsearch").classList.add('d-block bg-primary visible');
        //document.getElementById("clearsearch").className += " d-block btn-danger visible";
      document.getElementById("clearsearch").className = "d-block btn-danger visible";
      
      //}
      //alert("ending showClearSearch function"+document.getElementById("clearsearch").classList);
   }

        function clearsearch(){
	document.getElementById("myInput").value="";
   document.getElementById("clearsearch").className = "d-block btn-danger invisible";
	//document.getElementById('clearsearch').style.display='none';
   document.getElementById('frmSearch').submit();
}
   </script>

   <div style="width:100%;text-align:center;background-color:white;color:green;font-size:<?php echo $FontSize ?>px;font-weight:bold;"><?php echo $FontSize ?>
FILTER INVENTORY CONTROL</div>
</div> 

   <table style="width:100%;" class="myTable">
<tr style="height:100px;"><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = '../order.php';">Create order sheet</button></td><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = 'mweb_add_filter.php';">Add New Filter...</button></td><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = '../order.php?task=pastorders';">Order History...</button></td></tr></table>

<table style="width:100%;" class="myTable"><tr><td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
<form  id="frmSearch" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" autocomplete="off" method="post" style="margin: auto;vertical-align:top;">
<div class="input-group rounded" style="margin-top:0px;vertical-align:top;width:100%;height:100%;display:inline-flex;flex-direction:vertical;">

<span class="input-group-text border-0 flex-nowrap." id="search-addon" style="margin-left:60px;vertical-align:top;height:100px;width:60%;">
<div class="btn-danger d-none" id="clearsearch" style="border-radius:5px;margin-right:10px;font-size: 30px;font-weight:bold;text-align:top;height:40px;width: 50px;color:black;" onclick="clearsearch()">X</div>

<div class="autocomplete" style="height:auto;width:70%;background-color:transparent;color:black;display:inline;">
<input type="search" onkeypress="enterKeyPressed(event);" style="margin: auto;vertical-align:middle;" name="search_words" value="<?php echo $SearchWords ?>" id="myInput" class="form-control rounded d-inline-flex" placeholder="Search" aria-label="Search" aria-describedby="search-addon" oninput="showClearSearch();"><img id="img_search" src="../images/search.png" style="width:80px;height:80px;border-radius:45%;display:inline;margin-left:15px;" onclick="document.getElementById('frmSearch').submit();">
     <input type="hidden" name="showsize" id="hdnShowSize" value="search"></form></div>
    <i class="fas fa-search"></i>
  </span>
 </td>
<td style="vertical-align:center;"><form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="POST">
<div style="text-align:right;vertical-align:center;height:60px;">
<select id='shwSize' name='showsize' onchange='this.form.submit();' class="selectpicker" style="vertical-align:center;height:70px;"
}"> 
<option value="none">Select filter width to show</option>
<option value="all">All filter widths</option>
<option value="x1">1 inch only</option>
<option value='x2'>2 inch only</option>
<option value='x12'>12 inch only(not yet available)</option>
</select></div>
<input type="hidden" value="changesize" name="action"> 
</form></td></tr><tr>
<td style="display:inline-block;">

 </td>
<?php
$fwidthwanted="";
if($fwidthwanted=="x12"){echo "12 inch not in database yet.<br>";}

   
?>
</td></tr></table>
<table id="myTable"class="myTable" style="width:100%;">
<?php
$Action = "";
if($Action=="changesize")
{
	$fwidthwanted=$_POST["showsize"];
}
//echo var_dump($_POST);

$FilterType="";
$FilterCount="";
$Par=0;
$RecID="";
if(isset($_POST["action"])){$Action=$_POST["action"];}
if(isset($_GET["action"])){$Action=$_GET["action"];}
if(isset($_POST["fsize"])){$FilterSize=$_POST["fsize"];
}else{
$fsize="";
}

if(isset($_POST["notes"])){
$Notes=$_POST["notes"];
}else{
$Notes="";
}

if(isset($_POST["ftype"])){$FilterType=$_POST["ftype"];}
if(isset($_POST["par"])){
$Par=$_POST["par"];
}
else
{
$Par=0;
}
if(isset($_POST["fcount"])){
$FilterCount=$_POST["fcount"];
}
else
{
   $fcount="";
}
if(isset($_POST["rec_id"])){$RecID=$_POST["rec_id"];}

//UPDATE RECORD----------------------------------------------------------------
if($Action == "update"){
if(isset($_POST["id"])){$id=$_POST["id"];}
if(isset($_POST["fcount"])){$fcount=$_POST["fcount"];}
if(isset($_POST["ftype"])){$ftype=$_POST["ftype"];}
//echo "size=".$fsize." qty=".$fcount;

$today=date("Y-m-d");
//echo "<br>Todays date=".$today."<br>id=".$id;
$query = "UPDATE filters SET filter_count='" . $fcount . "', filter_type='".$ftype."', date_updated='". $today . "', par='".$Par."', notes='".$Notes."' WHERE _id='" . $id . "';";
        if (mysqli_query($con, $query)) {
            echo "filter edit complete<br>";
        } else {
            echo "Error updating filter inventory: " . mysqli_error($con);
        }
}
if(isset($_GET["action"]))
{
   $Action = isset($_GET["action"]);
}
echo "fontsize = ". $FontSize;
//DELETE FILTER------------------------------------------------------------------------
if($Action=="delete_filter")
{
    if(isset($_POST["id"])){$id=$_POST["id"];}
   if(isset($_POST["filter_size"])){$filterSize=$_POST["filter_size"];}
    $sql="DELETE FROM filters WHERE _id=". $id.";";
    //echo $sql ."<br>";
    $result = mysqli_query($con, $sql);

if ($result) {
    echo "Filter ".$filterSize ." was successfully deleted from database";
} else {
    echo "Error deleting record. Please file a bug report.";
}
}

//SHOW ALL TextAreaFilters
$SizeToShow="all";
$SearchWords = "";
   if(isset($_POST["search_words"]))
   {  
      $SearchWords=$_POST["search_words"];
   }

if(isset($_POST["showsize"])){$SizeToShow=$_POST["showsize"];}

    switch ($SizeToShow) {
      case "x1":
         $sql = "select * from filters WHERE substring(filter_size, CHAR_LENGTH(filter_size), 1)='1';";
        break;
      case "x2":
         $sql = "select * from filters WHERE substring(filter_size, CHAR_LENGTH(filter_size), 1)='2';";
        break;
        case "x12":
         $sql = "select * from filters WHERE substring(filter_size, CHAR_LENGTH(filter_size)-1, 2)='12';";
        break;
      case "all":
         $sql = "select * from filters order by substring(filter_size, 1, 2)";
        break;
      //USED FOR SEARCHING FILTER QUERY
      case "search":
         $sql= "SELECT  * FROM filters WHERE filter_size LIKE '%". $SearchWords ."%' OR filter_type LIKE '%". $SearchWords ."%' OR filter_count LIKE '%". $SearchWords ."%' OR par LIKE '%". $SearchWords ."%' OR notes LIKE '%". $SearchWords ."%' OR date_updated LIKE '%". $SearchWords ."%'";
         break;
         default:
    }

    if ($result = $con->query($sql)) 
      {
         while ($row = $result->fetch_assoc()) 
         {
            $fwidth="";
            $id = $row["_id"];
            $filters=$row["filter_size"];
            $fcount = $row["filter_count"];
            $fpar = $row["par"];
            if($fcount < $fpar){
               $color="red";
               }else{
               $color="white";
			   }
			   if(isset($_POST["action"]))
					{
						$Action=$_POST["action"];		
					}
				if($Action=="changesize")
					{
						$fwidth=substr($filters, -2);
                  if(isset($_POST["showsize"])){
						$fwidthwanted=$_POST["showsize"];
                  }
					}
				
			   if($fwidth==$fwidthwanted || $fwidthwanted=="x12" || $fwidthwanted=="all")
			   {
           ?>
            <div class="toast" role="alert" id="toastAlert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
               <img src="..." class="rounded mr-2" alt="...">
               <strong class="mr-auto">Bootstrap</strong>
               <small>11 mins ago</small>
               <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="toast-body">
               Use numeral inputs only.
            </div>
            </div><form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" id="frmEdit<?php echo $id ?>">
               <input type="hidden" name="action" value="update">
               <table id="EditRow<?php echo $id ?>" style="display:none;" class="myTable">
               <tbody><tr>
               <td><div>Filter size:</div><td>
            <input type="hidden"  name="id" value="<?php echo $id ?>">
               <div style="width:250px;height:80px;font-size:<?php echo $FontSize ?>px;text-align:left;font-weight:bold;"><?php echo $row["filter_size"] ?></div>
               </td>
               <tr><td><div style="font-size:<?php echo $FontSize ?>px;">Count:</div></td>
                     <td><input type="text" style="background-color:white;width:250px;height:80px;font-size:<?php echo $FontSize ?>px;font-weight:bold;" onkeyup="checkDataType('txtcount<?php echo $row["_id"] ?>');" id="txtcount<?php echo $row["_id"] ?>" name="fcount" value="<?php echo $row["filter_count"] ?>">
                        </td></tr><tr>
                     <td><div style="font-size:<?php echo $FontSize ?>px">Filter type:</div></td>
                     <td>      
                     <select style="background-color:white;height:70px;font-size:<?php echo $FontSize ?>px;" id="slctFilterTypes<?php echo $row["_id"] ?>" name="ftype">
                        <option>SELECT TYPE</option>
                        <?php
                        foreach ($arFilterTypes as $value) {
                        $Selected = "";
                        if($value == $row["filter_type"]){$Selected = "SELECTED";}
                           echo "<option value='". $value."' ".$Selected.">". $value."</option>";
                        }
                        ?>
                        </select>     
                        </td></tr>
                      <tr><td><div style="font-size:<?php echo $FontSize ?>px">Par:</div></td>
                      <td><input type="text" id="par<?php echo $row["_id"] ?>" onkeyup="checkDataType('par<?php echo $row["_id"] ?>');"  style="background-color:white;width:250px;height:80px;font-size:40px;font-weight:bold;" name="par" value="<?php echo $row["par"] ?>">
                     </td>
                     </tr>
                    <tr><td><div style="font-size:<?php echo $FontSize ?>px">Notes:</div></td>
                    <td><div style="overflow:auto;max-width:250px;height:80px;font-size:<?php echo $FontSize ?>px" name="notes"><?php echo $row["notes"] ?></div>
                     </td>
                     </tr></tbody></table></form>
                     
                     <table  style="width:100%;display:none;" id="tblSaveDelete<?php echo $row["_id"] ?>"><tr>
                        <td>
                     <img src="../images/delete.png" style="width:150px;height:auto;" onclick="myConfirmBox('Do you wish to delete this filter?', 'frmDelete<?php echo $row["_id"] ?>')"  title="Delete this filter from database"></td><td>
                        <img src="../images/save.png" style="width:150px;height:auto;" title="save edits" onclick="document.getElementById('frmEdit<?php echo $id ?>').submit();">
                        </td></tr><tr>
                        <td><div>Last updated:</td>
                    <td><h3><?php echo $row["date_updated"] ?></h3>
                  </td></tr>
                  </table>
                  <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmDelete<?php echo $row["_id"] ?>">
                  <input type="hidden" name="action" value="delete_filter"><input type="hidden" name="id" value="<?php echo $id ?>"><input type="hidden" name="filter_size" value="<?php echo $row["filter_size"] ?>">
                  </form>
                  <!--/*---------------------------->
               <table id="FilterRow<?php echo $id ?>" class="myTable">
               <tr>
               <td><div>Filter size:</div><td>
               <div style="color:<?php echo $color ?>;width:250px;height:80px;font-size:<?php echo $FontSize ?>px;text-align:left;font-weight:bold;"><?php echo $row["filter_size"] ?></div>
               </td>
               <tr><td><div style="font-size:<?php echo $FontSize ?>px">Count:</div></td>
                     <td><div style="color:<?php echo $color ?>;width:250px;height:80px;font-size:<?php echo $FontSize ?>px;font-weight:bold;"><?php echo $row["filter_count"] ?></div>
                        </td></tr><tr>
                     <td><div>Filter type:</div></td>
                     <td><div style="color:<?php echo $color ?>;width:250px;height:80px;font-size:<?php echo $FontSize ?>px;font-weight:bold;"><?php echo $row["filter_type"] ?></div>                     
                        </td></tr>
                      <tr><td><div style="font-size:<?php echo $FontSize ?>px">Par:</div></td>
                      <td><div style="color:<?php echo $color ?>;width:250px;height:80px;font-size:<?php echo $FontSize ?>px;font-weight:bold;"><?php echo $row["par"] ?></div>
                     </td>
                     </tr>
                    <tr><td><div>Notes:</td>
                    <td><div style="color:<?php echo $color ?>;overflow:auto;width:250px;height:80px;<?php echo $FontSize ?>px;" name="notes" rows="3" cols="12"><?php echo $row["notes"] ?></div>
                     </td>
                     </tr>
                     <tr>
                     <td><img src="../images/edit.png" style="width:150px;height:auto;" onclick="document.getElementById('EditRow<?php echo $row["_id"] ?>').style.display='table';document.getElementById('tblSaveDelete<?php echo $row["_id"] ?>').style.display='table';document.getElementById('FilterRow<?php echo $row["_id"] ?>').style.display='none';" ><div>Last updated:</td>
                    <td><h3><?php echo $row["date_updated"] ?></h3>
                  </td></tr> 
<tr style="height:40px;width:100%;background-color:black;"><td style="height:40px;width:100%;background-"></td><td style="height:40px;width:100%;background-color:black;"><td></tr>
</table>
           <?php
			   }
        }
        echo "</table>";
     }


 $con->close();
 function getFilterCount($fsize, &$result){
   foreach($result as $side=>$direc) 
      {
      //echo $fsize . "=" . $direc["filter_size"]."<br>";
         if(strpos($fsize, $direc["filter_size"]) != false)
            {
               return $direc["filter_count"];
            }
      }
      }
?>
<script>
autocomplete(document.getElementById("myInput"), filtersArray);
</script>