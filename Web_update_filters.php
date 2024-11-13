<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }

 //print_r($_POST);
   include 'CustomConfirmBox.css';

  include 'fm.css';
   include 'BootStrapToast.css';
   include 'functions.php';
   include 'phpfunctions.php';
   include 'javafunctions.php';
   include 'snackbar.css';


    $LastUpdated = "by Last Update";
    $SortBy = "";
   if (isset($_COOKIE['filters_lastquery'])) {
      $LastFilterQuery = $_COOKIE['filters_lastquery'];
      $filterArray = json_decode($_COOKIE['filters_lastquery'], true);
      $cFilterType = $filterArray['type'];
      $cFilterSize = $filterArray['size'];
      $SortBy = $filterArray['by'];
      $SearchWords = $filterArray['searchwords'];
      if(isset($filterArray['lastupdated'])){$LastUpdated = $filterArray['lastupdated'];}
   //echo "cfilter type=".$cFilterType."<br>  cfilter size=".$cFilterSize. "<br>searchwords=".$SearchWords."<br>Last Updated =".$LastUpdated;
   }

if($SortBy == ""){$SortBy = "SELECT_ALL";}

$arFilters = array();
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);

foreach ($data["filters"] as $filterObject) 
{
    $arFilters[] = $filterObject["filter_size"];
}
//echo "Filter sizes: " . implode(", ", $arFilters);
//print_r($arFilters);
$Selected = "";
$SizeToShow="all";


//if(isset($_COOKIE["SearchWords"])){$SearchWords = $_COOKIE["SearchWords"];}
//BELOW NOT USED SINCE CHANGING TO USING LAST QUERY METHOD TO SEARCH.
   if(isset($_POST["search_words"]))
   {  
      //$SearchWords=$_POST["search_words"];
   }
  // print_r($_POST);
$FilterType ="";
 //GET LAST QUERY IN CASE PAGE WAS TEMPERARALY CHANGED
   $LastFilterQuery ="";
    //lAST QUERY IS SAVED WITH JAVASCRIPT WHEN ELEMENTS ARE CLICK USING FUNCTION setLastQuery()
   if(isset($_COOKIE["filters_lastquery"])){
      $LastFilterQuery=$_COOKIE["filters_lastquery"];
      }
      else
      {
      $LastFilterQuery = "SELECT_ALL";
      }
//echo "Last query = ".$LastFilterQuery;
if(isset($_POST["showsize"])){$SizeToShow=$_POST["showsize"];}
if(isset($_POST["FilterType"])){$FilterType = $_POST["FilterType"];}
//if(isset($_POST["byLastUpdated"])){$LastUpdated = $_POST["byLastUpdated"];}
//if(isset($_GET["byLastUpdated"])){$LastUpdated = $_GET["byLastUpdated"];}
//if(isset($_COOKIE["cookie_showbydate"])){$LastUpdated = $_COOKIE["cookie_showbydate"];}

?>
<script>
function myConfirmBox(message, frmID) {
    let element = document.createElement("div");
    var list = element.classList;
    list.add("box-background:red;");
    element.innerHTML = "<div class='box' style='width:500px;height:300px;top:5px;background-color:gold;'>"+ message + "<button id='trueButton' class='btn green'>Yes</button><button id='falseButton' class='btn red'>No</button></div>";
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
   function whatmachine()
{

        var userAgent = navigator.userAgent.toLowerCase();
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
        console.log("ismobile="+isMobile);
    if(isMobile == true)
      {
          document.cookie =("cookie_machine=mobile");
          return "mobile";
      }
      else
      {
          if(document.getElementById("loggedin").value == "true")
            {
                parent.document.getElementsByTagName( 'frameset' )[ 0 ].rows='150,*';
            }
            else
            {
                parent.document.getElementsByTagName( 'frameset' )[ 0 ].rows='80,*';
            }
      document.cookie =("cookie_machine=desktop");
      document.getElementById("menu_gif").style.display="none";
      document.getElementById("menu_gif2").style.display="none";
      console.log("whatmachine:false");
      return "desktop";
      }    
}
  </script>
<script>
function resetElements(element){
   console.log("resetElements for:"+element);
var selectedSize = document.getElementById("bySize").value;
var selectedType = document.getElementById("byType").value;
var search_words = "";
var last_updated = "by Last Updated";
   switch(element){
      case "bySize":
      console.log("startging resetElements bySize");
         document.getElementById("myInput").value = "";
         document.getElementById("clearsearch").className = "d-none";
         document.getElementById("btnLastUpdated").innerHTML = "by Last Update";
         document.getElementById('calShowByLastUpdated').value = "by Last Update";
         var filterData = {
         type: selectedType,
         size: selectedSize,
         by: element,
         searchwords: search_words,
         lastupdated: ""
         };     
         break;
      case "byType":
      console.log("starting by type");
         document.getElementById("myInput").value = "";
         document.getElementById("clearsearch").className = "d-none";
         document.getElementById("lblForCalender").innerHTML = "by Last Update";
         var selectedSize = document.getElementById("bySize").value;
         var selectedType = document.getElementById("byType").value;
         var search_words = "";
         var last_updated = "by Last Updated";
         var filterData = {
         type: selectedType,
         size: selectedSize,
         by: element,
         searchwords: search_words,
         lastupdated: last_updated
         };
         break; 
      case "searchwords":
      console.log("starting resetElements by search");
         document.getElementById("clearsearch").className = "d-none";
         var options = document.getElementById("bySize").options;
         options[0].selected = true;
         var options = document.getElementById("byType").options;
         options[0].selected = true;
         document.getElementById("lblForCalender").innerHTML = "by Last Update";
         document.getElementById('calShowByLastUpdated').value  = "by Last Update";
         break;

      case "lastupdated":
      console.log("starting resetElements by lastupdated");
         document.getElementById("clearsearch").className = "d-none";
         var options = document.getElementById("bySize").options;
         options[0].selected = true;
         var options = document.getElementById("byType").options;
         options[0].selected = true;
         document.getElementById("myInput").value = "";
      
         break;
   }
   var jsonString = JSON.stringify(filterData);
   //setJavaCookie("filters_lastquery", jsonString,1); 
}
</script>
<script>
function setLastQuery(element)
{
   resetElements(element);
   var selectedSize = document.getElementById("bySize").value;
   var selectedType = document.getElementById("byType").value;
   if(element == "bySize" || element == "byType")
      {
         if(selectedSize != "SELECT_ALL" && selectedType != "SELECT_ALL")
         {
            element = "both";
         }
         if(selectedType != "SELECT_ALL" && selectedSize == "SELECT_ALL")
         {
            element = "byType";
         }
         if(selectedType == "SELECT_ALL" && selectedSize != "SELECT_ALL")
         {
            element = "bySize";
         }
            if(selectedType == "SELECT_ALL" && selectedSize == "SELECT_ALL")
         {
            element = "SELECT_ALL";
         }
      }

   var search_words = document.getElementById("myInput").value;
   var last_updated = document.getElementById('calShowByLastUpdated').value;

   var filterData = {
      type: selectedType,
      size: selectedSize,
      by: element,
      searchwords: search_words,
      lastupdated: last_updated
   }
   
   var jsonString = JSON.stringify(filterData);
   setJavaCookie("filters_lastquery", jsonString,1);
   //document.write(getJavaCookie("filters_lastquery"));
   location.reload();
}
</script>
<script>
<?php
$js_array = json_encode($arFilters);
echo "var filtersArray = ". $js_array . ";\n";
?>
</script>



<?php

//print_r($_SESSION, TRUE);
//CREATE AN ARRAY OF FILTER TYPES
   $arFilterTypes = array();
   $arFilters = array();
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data["filter_types"] as $filterObject) {
        $arFilterTypes[] = $filterObject["type"];
    }

   //CREATE ARRAY OF STORAGE LOCATIONS
   $sql = "SELECT location FROM storage;";
   $arStorage = array();
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    foreach ($data["storage"] as $storageObject) {
        $arStorage[] = $storageObject["location"];
    }
?>
<html>
<head>

<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" href="fm.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

<style>
.btn-primary {
box-shadow: 4px 4px black;
}
select {
box-shadow: 4px 4px black;
}
#myInput {
box-shadow: 4px 4px black;
}
#img_search {
box-shadow: 4px 4px black;
}
.storage{
width:14vw;
}

* { box-sizing: border-box; }
body {
  font: 16px Arial;
   background-color:<?php echo $BackGroundColor ?>
   color:$FontColor;
}
table td{
  font: <$php echo #_SESSION["font_size"]. " ". #_SESSION["font_family"];
   background-color:<?php echo $_SESSION["background-color"] ?>
   color:<?php echo $_SESSION["font-color"] ?>;
}
.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}

input[type=text] {
  background-color: #f1f1f1;
  width: 100%; 
}
input[type=submit] {
  background-color: DodgerBlue;
  color: #fff;
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
table, th, td {
    font-family: <?php echo $FontFamily ?>;
    font-size: <?php echo $FontSize ?>;
}
</style>

</head>
<body onload="if(document.getElementById('myInput').value != ''){document.getElementById('clearsearch').className='d-block bg-danger';}" style="background-color:<?php echo $BackGroundColor ?>;">
       <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>

function enterKeyPressed(event) {
      
      if (event.keyCode == 13) {
         return true;
      } else {
         return false;
      }
   }
</script>
<script>
   function showClearSearch(){
        document.getElementById("clearsearch").className += "d-block rounded bg-danger";
        document.getElementById('clearsearch').innerHTML = "X";
   }

   function clearsearch(){
	document.getElementById("myInput").value="";
   var selectedSize = "";
   var selectedType = "";
   var search_words = "";
   var filterData = {
      type: selectedType,
      size: selectedSize,
      by: "SELECT_ALL",
      searchwords: search_words,
      last_updated: ""
   };
   var jsonString = JSON.stringify(filterData);
   setJavaCookie("filters_lastquery", jsonString,1);
   setJavaCookie("SearchWords","",1);
   document.getElementById("clearsearch").className = "d-none";
	document.getElementById('clearsearch').innerHTML = "";
   document.getElementById('hdnShowSize').value = "all";
   document.getElementById('frmSearch').submit();
}
   </script>
<script>
function removeElements(id){
console.log("remove id=img "+id);
//mSelect=document.getElementById("img"+id);
//var mSelectChild = mSelect.children;
ImageToRemove = document.getElementById("img"+id);
//ImageToRemove=mSelectChild[0];
ImageToRemove.remove();
console.log("removing "+id);
}
 
function addElements(id){
//console.log("addingImage id="+id);
mSelectChild=document.getElementById(id);
//console.log("id="+mSelectChild.getAttribute("id"));
//create image
var img = document.createElement("img");
img.setAttribute("src","images/check.png");
img.setAttribute("width","auto");
img.setAttribute("height","45vh");
img.setAttribute("id","img"+id);
mSelectChild.appendChild(img);

//create p tag to hold storage
var p = document.createElement("P");
p.setAttribute("id","p"+id);
mSelectChild.appendChild(p);
}
</script>

<script>
function addToHref(ID, X, storage)
{
   strStorage=""; 
   console.log("begin id="+ID);
   ThisElement="";
   var row = document.getElementById("trEditRow"+ID); 
   var row_value = ""; 
   for (var j = 0; j < row.cells.length; j++) 
      {

         row_child =row.cells[j].children; 
         row_value += " | "; 
         thisElementID = row_child[0].getAttribute("id");
        thisElementType = row_child[0].getAttribute("type");
         console.log("rowItemtype="+thisElementType + " thisElementID="+thisElementID);
         if(thisElementID.search("mySelect") > -1) {ThisElement="storage";}else{ThisElement = "";}
         switch(ThisElement)
            {
               case "storage":
               //console.log("were storing"+document.getElementById(thisElementID).getAttribute("id"));
               mAllCheckBoxes=document.getElementById(thisElementID).children;
                //console.log(mAllCheckBoxes);
              
               count = document.getElementById(thisElementID).childElementCount;
               set = document.getElementById(thisElementID).children;
                //console.log("set size="+count);
               for (var i = 0; i < count; i++) 
               {
                  //var Child = mAllCheckBoxes[i];
                //console.log("ckbox="+set[i].children);
                ckboxs = set[i].children;
                 console.log("b="+ckboxs.length);
                for (var b = 0; b < ckboxs.length; b++)
                    {
                        if(ckboxs[b].getAttribute("id").search("chkbox") > -1){
                        if(ckboxs[b].checked){
                         number=ckboxs[b].getAttribute("id").substr(ckboxs[b].getAttribute("id").length - 4);
                        strStorage=strStorage+","+document.getElementById("lbl"+number).innerHTML;
                        }
                        }
                    }
                  
               }
               
               console.log("strStorage="+strStorage);
               break;
            }

      } 


   fcount = document.getElementById("fcount"+ID).value;
   notes = document.getElementById("notes"+ID).value;
   pn = document.getElementById("pn"+ID).value;
   par = document.getElementById("par"+ID).value;
   document.getElementById("a"+ID).href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?id="+ID+"&action=update&notes="+notes + "&storage="+strStorage + "&fcount="+fcount+"&par="+par+"&pn="+pn;

   console.log(document.getElementById("a"+ID).href);
}
</script>
   <div style="width:100%;text-align:center;font-size:30px;" class="bg-success text-white">
FILTER INVENTORY CONTROL</div>
   <table  style="width:100%;margin-left:50px;table-layout:fixed;">
<tr><td ><button id="btnGoToFilters" style="width:fit-content;" class="btn btn-primary" onclick="showSubButtons();">Filter orders</button>
    <div id="divSubButtons" style="display:none;flex-direction: column;">
        <button class="bg-warning text-black" id="slctOrder" style="height: 40px;box-shadow: 3px 3px black;width:fit-content;border-radius:5px;margin-top:15px;" onclick="window.location.href = 'order.php';">Create new order</button>
        <form action="order.php" method="post">
      <select class="form-select bg-warning text-black" id="slctOrder" style="height: 40px;border-radius:5px;margin-top:15px;" aria-label="Default select example" name="order_info" onchange="this.form.submit();">
      <option selected>Past Orders ......</option>
      <?php
      $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
      $data = json_decode($jsonString, true);
        foreach ($data['filter_orders'] as &$row) 
         {
            
               echo "<option value='".$row["_id"] ."*".$row["order_date"]."'>".$row["order_date"] ." [".$row["_id"]."]</option>";
            
   	}
      ?>
      </select><input type="hidden" name="action" value="get_order"></form>
    </div>

    <script>
        const mainButton = document.getElementById('btnGoToFilters');
        const subButtons = document.querySelector('.sub-buttons');

        mainButton.addEventListener('click', () => {
         document.getElementById("divSubButtons").style.display="flex";
            buts=document.getElementsByClassName("sub-buttons");
            for(i=0;i < buts.length ;i++){
            buts[i].style.display="block";
            }
        });
    </script>
</td>
<td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><button style="width:fit-content;;" class="btn btn-primary" onclick="window.location.href = 'web_add_filter.php';">Add New Filter...</button></td>
<td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><ul class="nav nav-pills">
        <li class="nav-item"><a href="ManageFilterTypes.php?Action=addfiltertype" class="nav-link"><button type="button"  style="margin-left:0px;padding-left:12px;width:fit-content;;" class="btn btn-primary">Manage filter types...</button></a></li>
      </ul>
</td>
<td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><ul class="nav nav-pills">
        <li class="nav-item"><a href="ManageStorage.php?Action=addlocation" class="nav-link"><button type="button"  style="margin-left:0px;padding-left:12px;width:fit-content;;" class="btn btn-primary">Manage filter storage...</button></a></li>
      </ul>
</td>
<?php
$fwidthwanted="";
   
?>
</td></tr>
<tr><td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
<div class="input-group rounded" style="margin-top:0px;vertical-align:top;">
<span class="input-group-text border-0 flex-nowrap." id="search-addon" style="margin-left:60px;vertical-align:top;width:220px;">
<div class="btn-danger d-none" id="clearsearch" style="font-size: 30px;font-weight:bold;text-align:top;height:40px;width: 50px;color:black;" onclick="clearsearch()">X</div>
<form  id="frmSearch" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" autocomplete="off" method="post" style="margin: auto;vertical-align:top;"><input type="hidden" name="txtShowSize" id="hdnShowSize" value="searchwords">
<div class="autocomplete" style="width:auto;height:auto;background-color:black;color:black;">
<input type="search" onkeypress="enterKeyPressed(event);" style="width:140px;margin: auto;vertical-align:top;" name="search_words" value="<?php if(isset($SearchWords)){echo $SearchWords; }?>" id="myInput" class="form-control rounded d-inline-flex" placeholder="Search" aria-label="Search" aria-describedby="search-addon" oninput="showClearSearch();">
<script>
        $("#myInput").keyup(function (event) {
            console.log("key pressed was "+ event.keyCode);
            if (event.keyCode === 13) {
                //$("#img_search").click();
               document.getElementById('img_search').click();
                //submitSearch();
            }
        });
   function submitSearch(){
      setLastQuery('searchwords');
      //resetElements("search");
      var searchwords = document.getElementById('myInput').value;
      setJavaCookie('SearchWords', searchwords, 1);
      //document.getElementById('frmSearch').submit();
   }
</script>
     </div></form><img id="img_search" src="images/search.png" style="max-width: 38px;
        max-height: auto;text-align:middle;margin-left:5px;border-radius:45%" onclick="submitSearch();">
    <i class="fas fa-search"></i>
  </span>
 </td>
<td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
<?php
$filtersize="";
if (isset($_COOKIE['filters_lastquery'])) {
    $filterArray = json_decode($_COOKIE['filters_lastquery'], true);
    $type = $filterArray['type'];
    $filtersize = $filterArray['size'];
    //echo "size is".$filtersize;
}
?>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
<?php
$jsonData = file_get_contents('sites/hard_rock/data.json');
$data = json_decode($jsonData, true);
$uniqueSizes = [];
foreach ($data["filters"] as $item) {
   $thisFilter = strtolower($item['filter_size']);
    $lastDimension = explode('x', $thisFilter)[2];
    if (!in_array($lastDimension, $uniqueSizes)) {
        $uniqueSizes[] = $lastDimension;
    }
}
sort($uniqueSizes, SORT_NUMERIC);
?>
<select id="bySize" name="showsize" style="margin-top:20px;margin-bottom:0px;height:36px;text-align:center;width:160px;padding-left:30px;" onchange="document.getElementById('filter_width').value = options[this.selectedIndex].value;setLastQuery('bySize');"><option value='SELECT_ALL'>Select width</option>
<option value="SELECT_ALL"
<?php
if($SizeToShow == "SELECT_ALL")
   {
      echo " SELECTED";
   } 
echo "'>All widths</option>";
foreach($uniqueSizes as $f){
   if($f != ""){
echo "<option value='".$f."'";
if($filtersize === $f){echo " SELECTED";}
echo ">".$f;
if(strlen($f) == 1){echo "&nbsp;&nbsp;";}
echo " inch only</option>";
   }
}
?>
</select>
<input type="hidden" id="filter_width" name="width" value="<?php if(isset($_POST["filter_width"])){echo $_POST["filter_width"];} ?>">
</form></td>
<td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
<select id='byType' name='FilterType' style="margin-top:20px;margin-bottom:0px;height:36px;width:fit-content;" onchange="setLastQuery('byType');"> 
<option value="SELECT_ALL">All filter types</option>
<?php
foreach ($arFilterTypes as $value) {
   if(strpos($_COOKIE["filters_lastquery"], $value)){$Selected = "SELECTED";}else{$Selected = "";}
   ?><option value="<?php echo $value ?>" <?php echo $Selected ?>><?php echo $value ?></option>
    <?php
   }
?>
</select>
<input type="hidden" value="showbytype" name="action"> 
</form></td>
<td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;" id="tdByDate">
<button id="btnLastUpdated" class="btn btn-primary" style="max-height:10vh;width:fit-content;" onclick="this.style.display='none';document.getElementById('tdByDate').style.border='2px solid black';document.getElementById('divBySpecificDate').style.display='flex';">
<?php 
if($LastUpdated == "")
{
   echo "by Last Update";
}
else
{
   echo $LastUpdated;
}
?>
</button>
<div id="divBySpecificDate" style="display:none;flex-direction:column;">
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST" id="frmSortByDate">
<label for="calShowByLastUpdated" id="lblForCalender" style="margin-left:35px;margin-right:auto;">By specific date:<?php echo $LastUpdated ?></label>
<input type="date" value="<?php echo $LastUpdated ?>" id='calShowByLastUpdated' name='byLastUpdated' onchange="setLastQuery('lastupdated');" style="margin-left:35px;">
</form>
<button type="button" class="btn btn-danger" onclick="deletecookie('cookie_showbydate');setLastQuery('showall');" id="btnShowAllUpdateDates" style="width:10vw;height:8vh;font-size:.75em;margin-left:45px;text-align: center !important;line-height: 0 !important;">Cancel</button>
</td><td style="margin-top:20px;font-size:.25em;display:flex;flex-direction:row;"><div style="box-shadow: 4px 4px black;font-size:2em;padding:4px;color:white;border-radius:5%;width:fit-content;height:fit-content;background-color:#b51f0e">This color =<br> Qty low<br>(under par)</div>
</td>
</table>
<?php
$Action = "";

//echo var_dump($_POST);
$FilterSize="";
$FilterType="";
$FilterCount="";
$Par=0;
$RecID="";
if(isset($_POST["action"])){$Action=$_POST["action"];}
if(isset($_GET["action"])){$Action=$_GET["action"];}
//echo "Action=".$Action;
if(isset($_POST["fsize"]))
{
    $FilterSize=$_POST["fsize"];
}else{
   
    $fsize="";
}

if(isset($_POST["notes"])){
$Notes=$_POST["notes"];
}else{
$Notes="";
}


if(isset($_POST["ftype"])){$FilterType=$_POST["ftype"];}
if(isset($_POST["FilterType"])){$FilterType=$_POST["FilterType"];}

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
if(strcmp($Action, "update")==0){
if(isset($_POST["id"])){$id=$_POST["id"];}
if(isset($_POST["pn"])){$pn=$_POST["pn"];}
if(isset($_POST["fcount"])){$fcount=$_POST["fcount"];}
//if(isset($_POST["fsize"])){$fsize=$_POST["fsize"];}//NO EDITING OF FILTER SIZE.DELETE AND REMAKE FILTER


if(isset($_GET["id"])){$id=$_GET["id"];}
if(isset($_GET["pn"])){$pn=$_GET["pn"];}
if(isset($_GET["fcount"])){$fcount=$_GET["fcount"];}
if(isset($_GET["storage"])){$Storage = $_GET["storage"];
if(isset($_GET["par"])){$Par=$_GET["par"];}
if(isset($_GET["notes"])){$notes=$_GET["notes"];}
//echo "size=".$fsize." qty=".$fcount;
if($fcount < 0){$fcount = "0";}
$today=date("Y-m-d");
//echo "<br>Todays date=".$today."<br>id=".$id;
$Storage = "";
$x=0;

//UPDATE DATA FILE
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
foreach ($data['filters'] as &$object) 
{
            if ($object['_id'] === $id) 
		{
                $object["par"] = $Par;
                $object["filter_count"] = $fcount;
                $object["pn"] = $pn;
                $object["storage"] = $Storage;
                $object["par"] = $Par;
                $object["notes"] = $notes;
            	}
}
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
}
}

//DELETE FILTER------------------------------------------------------------------------
if(strcmp($Action,"delete_filter")==0)
{
    $id="";
    if(isset($_GET["id"])){$id=$_GET["id"];}
    if(isset($_POST["id"])){$id=$_POST["id"];}
 

   $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
   $data = json_decode($jsonString, true);
   $index = -1;
   foreach ($data['filters'] as $key => $object) {
      if ($object['_id'] === $id) {
         $index = $key;
         break;
      }
   }
   if ($index > -1) {
      unset($data['filters'][$index]);
   }
   $jsonString = json_encode($data, JSON_PRETTY_PRINT);
   file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
}
   ?>
<input type="text" id="txtholdsize" style="display:none;"><input type="text" id="txtholdtype" style="display:none;">
 <table style="width:100%;margin-left:0px;table-layout:fixed;">
   <tr id="header">
    <th>Edit</th>
   <th>P/N</th>
    <th>Qty</th>
    <th>Filter size</th>
    <th>Filter Type</th>
    <th>Par</th>
   <th>Storage</th>
    <th>Notes</th>
    <th>Last updated</th>
    </tr>

    <?php
   $file_path = 'sites/'.$_SESSION["backup_folder"].'/data.json'; 
   $json_data = file_get_contents($file_path);
   $data = json_decode($json_data, true);
switch ($SortBy) 
{
   case "searchwords":
   //echo "the searchwords are :".$SearchWords;
  // $SearchWords = $_COOKIE["SearchWords"];
   //echo "performeing search for ".$SearchWords."<br>searchwords=".$_COOKIE["SearchWords"];
   $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
   $data = json_decode($jsonString, true);
   $filtersArray = $data["filters"];

   $result = [];

   if($SearchWords != "")
      {
         foreach ($data["filters"] as $filterObject) 
            {
               if (strpos(strtolower($filterObject["_id"]), strtolower($SearchWords)) !== false ||
                  strpos(strtolower($filterObject["filter_size"]), strtolower($SearchWords)) !== false ||
                  strpos(strtolower($filterObject["filter_type"]), strtolower($SearchWords)) !== false ||
                  strpos(strtolower($filterObject["par"]), strtolower($SearchWords)) !== false ||
                  strpos(strtolower($filterObject["notes"]), strtolower($SearchWords)) !== false ||
                  strpos(strtolower($filterObject["pn"]), strtolower($SearchWords)) !== false ||
                  strpos(strtolower($filterObject["storage"]), strtolower($SearchWords)) !== false
               ) 
               {
                  $result[] = $filterObject;
               }
            }
      }
        break;
   case "SELECT_ALL" :
      $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
      $data = json_decode($jsonString, true);
      $result = $data["filters"];
      break;

   case "both" : //SIZE AND TYPE ARE BEING USED AS FILTERS
   //echo "STARTING USING BOTH TYPE AND SIZE<BR>";

      $matching_filters = [];
      error_reporting(0);
      foreach ($data['filters'] as $filter) 
         {
            $fsize = explode("x", strtolower($filter["filter_size"]));
            if($fsize[2] == $cFilterSize && $filter["filter_type"] == $cFilterType)
               {
                  $result[] = $filter;
               }
         }
      error_reporting(E_ERROR);
      break;

   case "bySize" :

      $matching_filters = [];
      error_reporting(0);
      //echo "cFilterType =". $cFilterType. " cFilterSize=". $cFilterSize;
      if($cFilterType == "SELECT_ALL" && $cFilterSize == "SELECT_ALL"){
      //echo "<br>starting BYSIZE both SELECT_ALL<BR>";
         foreach ($data['filters'] as $filter) 
         {
            $result[] = $filter;
         }
         
      }
      else
      {
         //echo "<br>starting BYSIZE<BR>";
         foreach ($data['filters'] as $filter) 
         {
            $fWidth = explode("x", strtolower(($filter["filter_size"])));
             //echo $fWidth[2] ."==". $cFilterSize."<br>";
            if($fWidth[2] == $cFilterSize)
               {
                 
                  $result[] = $filter;
               }
         }
      }
      error_reporting(E_ERROR);
      break;

   case "byType" :
   //echo "<br>staring by type</br>";
   error_reporting(0);
   if($cFilterType != "SELECT_ALL" && $cFilterSize == "SELECT_ALL"){
   foreach ($data['filters'] as $filter)  
      {
         //echo $cFilterType."==". $filter["filter_type"]."<br>";
      if ($cFilterType == $filter["filter_type"]) 
         {
            $result[] = $filter;
         }
      }
   
}
   error_reporting(E_ERROR);
   break;
   
   case "lastupdated" :
   //echo "starting by lastupdated =".$LastUpdated."<br>";
   foreach ($data['filters'] as $filter)  
      {
         //echo $LastUpdated ."==". $filter["date_updated"]."<br>";
      if ($LastUpdated == $filter["date_updated"]) 
         {
            $result[] = $filter;
         }
      }
   break;

}
      $filtersCount=0;
      if(isset($result))
         {
            $filtersCount = count($result);
         }
      
         if(isset($result)){$filtersCount = count($result);}
         //echo "count=". $filtersCount;
         if($filtersCount == 0)
            {
            echo "<div style='background-color:#cc0066;color:white;font-weight:bold; text-align: center;'>NO SEARCH RESULTS FOUND</div>";
            }
            else
            {
            $fontColor="";
         if($filtersCount == 0){echo "<div style='background-color:#cc0066;color:white;font-weight:bold; text-align: center;'>NO SEARCH RESULTS FOUND</div>";}
         $fontColor="";
         foreach ($result as $row)  
            {
               //ARE FILTERS BELOW PAR?
               $fcount = $row["filter_count"];
               if($fcount < 0){$fcount = 0;}
               $fpar = $row["par"];
               if($fcount < $fpar)
               {
                  $color="#b51f0e";
                  $fontColor="#b51f0e";
               }
               else
               {
                  $color="<?php echo $BackGroundColor ?>";
                  $fontColor="black";
			      }

               ?>
                    
                   <tr id="trFilterRow<?php echo $row["_id"] ?>" class="FilterRow" style="color:<?php echo $fontColor ?>;">
                        <td style="display:inline-block;font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><img src="images/edit.png" style="width:2em;height:auto;" title="edit this filter info" onclick="editme('<?php echo $row["_id"] ?>');"></td>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
                        <?php 
                        $Len="";
                        $Len = strlen($row["pn"]);
                        if($Len > 8) {
                           $myPn = substr($row["pn"], 0, ($Len / 2)) ."<br>".substr($row["pn"], ($Len / 2));
                           }
                           else 
                           {
                           $myPn=$row["pn"];
                           }
                        if($Len > 0){
                        echo "<div style='width:fit-content;border:2px solid ". $FontColor."';>".$myPn."<div>";}
                        ?>
                        </td></td>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><?php echo $fcount ?></td>
                        <?php 
                        $myID = $row["filter_size"] . $row["filter_type"];
                        $myID = str_replace(" ","",$myID);
                        ?>
                        <td id="<?php echo  $myID ?>"><?php echo $row["filter_size"] ?></td>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><?php echo $row["filter_type"] ?></td>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><?php echo $row["par"] ?></td>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><div onclick="if(document.getElementById('divStorage<?php echo $row["_id"] ?>').innerHTML.length > 29){document.getElementById('modaltext').innerHTML=document.getElementById('divStorage<?php echo $row["_id"] ?>').innerHTML;$('#myModal').modal('show');}"><div style="font-size:.5em;" id="divStorage<?php echo $row["_id"] ?>">
                        <?php 
                        $Array = explode(",", $row["storage"]);
                        foreach ($Array as $loc){
                        echo $loc."<br>";  
                        }
                        ?></div></td>
                        <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog" style="top:0px">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">close</button>
      </div>
      <div class="modal-body">
        <p id="modaltext" style="font-size:2em"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><div  onclick="document.getElementById('modaltext').innerHTML=document.getElementById('divNotes<?php echo $row["_id"] ?>').innerHTML;$('#myModal').modal('show');" id="divNotes<?php echo $row["_id"] ?>"><?php echo $row["notes"] ?></div></td>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><a href="#" onclick="document.getElementById('calShowByLastUpdated').value = '<?php echo $row["date_updated"] ?>';setLastQuery('lastupdated');document.getElementById('frmSortByDate').submit();"><?php echo $row["date_updated"] ?></a></td>
                     </tr>
                    <tr id="trEditRow<?php echo $row["_id"] ?>" class="DisplayNone">
                   <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" id="frmDelete<?php echo $row["_id"] ?>" method="post"><input type="hidden" name="action" value="delete_filter"><input type="hidden" name="id" value="<?php echo $row["_id"] ?>"></form>
                   
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><form id="frm<?php echo $row["_id"] ?>" method="get" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>">
                        <a id="a<?php echo $row["_id"] ?>" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?action=update&id=<?php echo $row["_id"] ?>"><img src="images/save.png" style="width: 50px; height auto;padding-top:25px;" title="save filter edit"></a>
                        <img style="width: 50px; height auto;padding-top:25px" src="images/delete.png" title="delete filter" onclick="myConfirmBox('Do you wish to delete this filter?', 'frmDelete<?php echo $row["_id"] ?>')"></td>
                        <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><input type="text" style="overflow:scroll;text-align:left;max-width:10em;border-radius:5px;" id="pn<?php echo $row["_id"] ?>" onmouseup="return false;" name="pn" onkeyup="addToHref(<?php echo $row["_id"] ?>, 'empty', 'empty');" value="<?php echo $row["pn"] ?>"> </td>
                       <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><input type="text" style="text-align:center;max-width:3em;border-radius:5px;" id="fcount<?php echo $row["_id"] ?>" onfocus="this.select();" onmouseup="return false;"  name="fcount" value="<?php echo $row["filter_count"] ?>" onkeyup="checkDataType('fcount<?php echo $row["_id"] ?>');addToHref(<?php echo $row["_id"] ?>, 'empty','empty');"></td>
                       <td ><div class="nonEditable" id="divFilterSize<?php echo $row["_id"] ?>"  onmouseleave="if(document.getElementById('txtholdsize').value.length > 0){this.innerHTML=document.getElementById('txtholdsize').value;document.getElementById('txtholdsize').value='';}"  onclick="document.getElementById('txtholdsize').value = this.innerHTML;this.innerHTML='not editable';"><?php echo $row["filter_size"] ?></div></td>
                       <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
                       <div class="nonEditable" id="ftype<?php echo $row["_id"] ?>" onmouseleave="if(document.getElementById('txtholdtype').value.length > 0){this.innerHTML=document.getElementById('txtholdtype').value;document.getElementById('txtholdtype').value.length ='';}" onclick="document.getElementById('txtholdtype').value =this.innerHTML;this.innerHTML='not editable';"><?php echo $row["filter_type"] ?></div>
                     </td>
                       <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><input type="text" id="par<?php echo $row["_id"] ?>" style="text-align:center;max-width:3em;margin-left:.5em;" class="fselect"  name="par" onfocus="this.select();" onmouseup="return false;"  value="<?php echo $row["par"] ?>" onkeyup="addToHref(<?php echo $row["_id"] ?>, 'empty','empty');checkDataType('par');"></td>
                     <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
                     <div style="display:flex;flex-direction:column;border:1px solid black;overflow:auto;height:100px;" id="mySelect<?php echo $row["_id"] ?>">
                     <?php
                     $row_id=$row["_id"];
                     $X=0;
                     $message='';
                     $LocMatch="";
                     $Array = explode(",",$row["storage"]);
                                 foreach ($arStorage as $location)
                                    {
                                        $Checked="";
                                       $Color="gray";
                                       $Display="none";
                                       foreach($Array as $value)
                                          {  
                                             $X=$X+1;
                                             if($value == $location)
                                                {
                                                   $Checked="checked";
                                                   $Color="green";
                                                   $Display="inline-block";
                                                   
                                                }
                                          }
                                    ?>
                                   <div style="height:100%;border:1px solid black;display: flex;justify-content: space-between;color:white;background-color:<?php echo $Color ?>;display:flex;flex-direction:row;" id="divCheckBox<?php echo $row_id. $X ?>"><label for="chkbox<?php echo $row_id.$X ?>" id="lbl<?php echo $row_id.$X ?>" style="height:100%;background-color:background-color:<?php echo $Color ?>"><?php echo $location ?></label>
                                    <input type="checkbox" id="chkbox<?php echo $row_id.$X ?>" style="color:white;background-color:<?php echo $Color ?>;" onclick="if(this.checked){this.style.backgroundColor='green';document.getElementById('divCheckBox<?php echo $row_id. $X ?>').style.backgroundColor='green';document.getElementById('lbl<?php echo $row_id. $X ?>').style.backgroundColor='green';}else{this.style.backgroundColor='gray';document.getElementById('lbl<?php echo $row_id. $X ?>').style.backgroundColor='gray';document.getElementById('divCheckBox<?php echo $row_id. $X ?>').style.backgroundColor='gray'};addToHref(<?php echo $row_id.", ". $X .",'".$location."'"; ?>);" value="<?php echo $location ?>" <?php echo $Checked ?>></div> 
                        <?php 
                                    }
                        ?>                 
                    </td>
                       <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;"><textarea id="notes<?php echo $row["_id"] ?>" name="notes" rows="3" cols="12" class="TextAreaFilters" oninput="addToHref(<?php echo $row["_id"] ?>);"><?php echo $row["notes"] ?></textarea></form></td>
                       <td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
                        <div class="nonEditable" style="width:fit-content;" id="LastUpdated<?php echo $row["_id"] ?>"><?php echo $row["date_updated"] ?></div>
                     </tr>
                    
               <?php
            }
            }
?>
</table>

<div class="toast" role="alert" id="toastAlert" aria-live="assertive" aria-atomic="true">
  <div class="toast-header">
    <img src="..." class="rounded mr-2" alt="..."><button type="button">hello</button>
    <strong class="mr-auto">Bootstrap</strong>
    <small>11 mins ago</small>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="toast-body">
    Use numeral inputs only.
  </div>
</div>
<?php

 function getFilterCount($fsize, &$result){
   foreach($result as $side=>$direc) 
      {
      //echo $fsize . "=" . $direc["filter_size"]."<br>";
         if(strpos($fsize, $direc["filter_size"]) != false)
            {
               if($direct["filter_count"] <= 0)
               {
               return "0";
               }
               else
               {
               return $direc["filter_count"];
               }
            }
      }
      }
?>

<script>
    function editme(filterID){        

        filter_to_edit = document.getElementById("trEditRow"+filterID);
if(filter_to_edit.classList.contains("FilterRow")){console.log("yes");}
         //console.log(filter_to_edit.classList);
         filter_to_edit.classList.toggle('EditRow');
         filter_to_edit.classList.remove('DisplayNone');
         filterRow = document.getElementById("trFilterRow"+filterID);
        filterRow.classList.toggle('DisplayNone');

    }
</script>
<script>
    function saveme(filterID){
      document.getElementById("frm"+filterID).submit();
        filter = document.getElementById("FilterRow"+filterID);
        filter_to_edit = document.getElementById("trEditRow"+filterID);
        filter.style.display="block";
        filter_to_edit.style.display="none";
        
    }
</script>
<script>
autocomplete(document.getElementById("myInput"), filtersArray);
</script>
</body>
<?php
  function RemoveSpecialChar($str) {
      $res = str_replace( array( '\'', '"',
      ',' , ';', '<', '>' ), ' ', $str);
      return $res;
      }
?>