<?php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

  //or, if you DO want a file to cache, use:
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
if(isset($_COOKIE["FontSize"]))
   {  
      $FontSize=$_COOKIE["FontSize"];
   }
   else
   {
	   $FontSize=20;
	   $textBoxWidth=120;
   }
   $buttonHeight=$FontSize*2;
   $textBoxWidth=$FontSize/12;  
   
include '../dbMirage_connect.php';
include 'NavBar.html';
include '../BootStrapToast.css';
include '../javafunctions.php';
$SearchWords = "";
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
?>
<script>
<?php
$js_array = json_encode($arFilters);
echo "var filtersArray = ". $js_array . ";\n";
?>
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
.myselect { 
	font-size:<?php echo $FontSize ?>px;
	color:green; 
	background-color: white; 
	height:<?php echo $buttonHeight ?>px;
	width:300px;
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
   myFunction();
}
   </script>

   <div style="width:100%;text-align:center;background-color:white;color:green;font-size:<?php echo $FontSize ?>px;font-weight:bold;">
FILTER INVENTORY CONTROL</div>
</div> 

   <table style="table-layout: auto";>
<th></th>
<tr style="height:100px;"><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = '../order.php';">Create order sheet</button></td><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = 'mweb_add_filter.php';">Add New Filter...</button></td><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = '../order.php?task=pastorders';">Order History...</button></td><tr>
<td style="font-family:<?php echo $_SESSION["font_family"] ?> ?>;">
<div class="input-group rounded" style="margin-top:0px;vertical-align:top;">
<span class="input-group-text border-0 flex-nowrap." id="search-addon" style="margin-left:60px;vertical-align:top;width:220px;">
<div class="btn-danger d-none" id="clearsearch" style="font-size: 30px;font-weight:bold;text-align:top;height:40px;width: 50px;color:black;" onclick="clearsearch()">X</div>
<form  id="frmSearch" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" autocomplete="off" method="post" style="margin: auto;vertical-align:top;"><input type="hidden" name="showsize" id="hdnShowSize" value="search">
<div class="autocomplete" style="width:auto;height:auto;background-color:black;color:black;">
<input type="search" onkeypress="enterKeyPressed(event);" style="margin: auto;vertical-align:top;" name="search_words" value="<?php echo $SearchWords ?>" id="myInput" class="form-control rounded d-inline-flex" placeholder="Search" aria-label="Search" aria-describedby="search-addon" oninput="showClearSearch();">
     </div></form><img id="img_search" src="../images/search.png" style="width:50px;height:auto;
        text-align:middle;margin-left:5px;border-radius:45%" onclick="document.getElementById('frmSearch').submit();">
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
<table id="myTable" border="3;3" style="table-layout: auto;">
<?php
$Action = "";
if($Action=="changesize")
{
	$fwidthwanted=$_POST["showsize"];
}
//echo var_dump($_POST);
$FilterSize="";
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
if($Action = "update"){
if(isset($_POST["id"])){$id=$_POST["id"];}
if(isset($_POST["fcount"])){$fcount=$_POST["fcount"];}
if(isset($_POST["fsize"])){$fsize=$_POST["fsize"];}
if(isset($_POST["ftype"])){$ftype=$_POST["ftype"];}
//echo "size=".$fsize." qty=".$fcount;
if($fsize !=""){
$today=date("Y-m-d");
echo "<br>Todays date=".$today."<br>id=".$id;
$query = "UPDATE filters SET filter_size='".$fsize."', filter_count='" . $fcount . "', filter_type='".$ftype."', date_updated='". $today . "', par='".$Par."', notes='".$Notes."' WHERE _id='" . $id . "';";
        if (mysqli_query($con, $query)) {
            echo "filter inventory update complete<br>";
        } else {
            echo "Error updating filter inventory: " . mysqli_error($con);
        }
       
}

}
if(isset($_GET["action"]))
{
   $Action = isset($_GET["action"]);
}

//DELETE FILTER------------------------------------------------------------------------
if($Action=="delete_filter")
{
    if(isset($_GET["id"])){$id=$_GET["id"];}
    $sql="DELETE FROM filters WHERE _id=". $id.";";
    echo $sql ."<br>";
    $result = mysqli_query($con, $sql);

if ($result) {
    echo "Filter deleted from database successfully";
} else {
    echo "Error deleting record";
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
            </div>
               <table id="EditRow<?php echo $id ?>" style="display:none;">
               <tr>
               <td><h1>Filter size:</h1><td>
               <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>">
            <input type="hidden"  name="id" value="<?php echo $id ?>">
               <div style="width:250px;height:80px;font-size:50px;text-align:left;font-weight:bold;"><?php echo $row["filter_size"] ?></div>
               </td>
               <tr><td><h1>Count:</h1></td>
                     <td><input type="text" style="background-color:<?php echo $color ?>;width:250px;height:80px;font-size:50px;font-weight:bold;" onkeyup="checkDataType('txtcount<?php echo $row["_id"] ?>');" id="txtcount<?php echo $row["_id"] ?>" name="fcount" value="<?php echo $row["filter_count"] ?>">
                        </td></tr><tr>
                     <td><h1>Filter type:</h1></td>
                     <td><input type="text"  style="background-color:<?php echo $color ?>;width:250px;height:80px;font-size:50px;font-weight:bold;" name="ftype" value="<?php echo $row["filter_type"] ?>">                          
                        </td></tr>
                      <tr><td><h1>Par:</h1></td>
                      <td><input type="text" id="par<?php echo $row["_id"] ?>" onkeyup="checkDataType('par<?php echo $row["_id"] ?>');"  style="background-color:<?php echo $color ?>;width:250px;height:80px;font-size:50px;font-weight:bold;" name="par" value="<?php echo $row["par"] ?>">
                     </td>
                     </tr>
                    <tr><td><h1>Notes:</td>
                    <td><textarea style="width:250px;height:80px;font-size:30px;font-weight:bold;" name="notes" rows="3" cols="12"><?php echo $row["notes"] ?></textarea>
                     </td>
                     </tr>
                        <td><td><input type="hidden" name="action" value="UPDATE">
                        <input type=image src="../images/save.png" style="width:50px;height:auto;" title="save edits" alt="submit">
                        <A Href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?action=delete_filter&id=<?php echo $id ?>">
                     <img src="../images/delete.png" style="width:50px;height:auto;" title="Delete this filter from database"></A>
                        </td></tr><tr>
                        <td><h1>Last updated:</td>
                    <td><?php echo $row["date_updated"] ?></h1>
                  </td></tr>
                  </table>
                  <!--/*---------------------------->
               <table id="FilterRow<?php echo $id ?>">
               <tr>
               <td><h1>Filter size:</h1><td>
               <h1 style="width:250px;height:80px;font-size:50px;text-align:left;font-weight:bold;"><?php echo $row["filter_size"] ?></h1>
               </td>
               <tr><td><h1>Count:</h1></td>
                     <td><div style="width:250px;height:80px;font-size:50px;font-weight:bold;"><?php echo $row["filter_count"] ?></div>
                        </td></tr><tr>
                     <td><h1>Filter type:</h1></td>
                     <td><div style="width:250px;height:80px;font-size:50px;font-weight:bold;"><?php echo $row["filter_type"] ?></div>                     
                        </td></tr>
                      <tr><td><h1>Par:</h1></td>
                      <td><div style="width:250px;height:80px;font-size:50px;font-weight:bold;"><?php echo $row["par"] ?></div>
                     </td>
                     </tr>
                    <tr><td><h1>Notes:</td>
                    <td><div style="overflow:auto;width:250px;height:80px;font-size:30px;font-weight:bold;" name="notes" rows="3" cols="12"><?php echo $row["notes"] ?></div>
                     </td>
                     </tr>
                     <tr>
                     <td><img src="../images/edit.png" style="width:50px;height:auto;" onclick="document.getElementById('EditRow<?php echo $row["_id"] ?>').style.display='table';document.getElementById('FilterRow<?php echo $row["_id"] ?>').style.display='none';" ><h1>Last updated:</td>
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
</body>