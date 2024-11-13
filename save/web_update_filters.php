<?php
if(session_id() == ''){
      session_start();
   }
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//Print_r($_COOKIE);
  //or, if you DO want a file to cache, use:
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)
if(isset($_COOKIE["FontSize"]))
   {  
      $FontSize=$_COOKIE["FontSize"];
      //echo "font size:".$FontSize;
   }
   else
   {
	   $FontSize="20px";
	   $textBoxWidth="120";
   }
  
   if(isset($_POST["search_words"]))
   {  
      $SearchWords=$_POST["search_words"];
   }
  
   $buttonHeight="*2";
   $textBoxWidth="12";  
   $con2 = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
   $con = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
include 'CustomConfirmBox.css';
//CREATE AN ARRAY OF FILTER TYPES
$sql = "SELECT type FROM filter_types;";
   $filtertypes = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                  array_push($filtertypes,$row["type"]); 
                }
        }
?>
<html>
<head>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
tr:hover {background-color: coral;}

td.input input {
    width: 100%;
    box-sizing: border-box;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
}

#myTable {
    border-collapse: collapse; /* Collapse borders */
    width: 100%; /* Full-width */
    border: 1px solid #ddd; /* Add a grey border */
    font-size: 32px; /* Increase font-size */
    }
   
    #myTable th {
      background-color:green;
      color:black;
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
</style>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
   function showClearSearch(){
        document.getElementById("clearsearch").className += " d-block btn-danger visible";
      document.getElementById("clearsearch").className = "d-block btn-danger visible";
   }

        function clearsearch(){
	document.getElementById("myInput").value="";
   document.getElementById("clearsearch").className = "d-block btn-danger invisible";
	//document.getElementById('clearsearch').style.display='none';
   myFunction();
}
   </script>
   <div style="width:100%;text-align:center;background-color:white;color:green;font-size:16px;font-weight:bold;">
FILTER INVENTORY CONTROL</div>
   <table style="width:100%;">
<tr><td style=""><button class="btn btn-primary" onclick="window.location.href = 'order.php';">Create order sheet...</button></td>
<td style=""><button class="btn btn-primary" onclick="window.location.href = 'order.php?task=pastorders';">Order History...</button></td>
<td style=""><button class="btn btn-primary" onclick="window.location.href = 'web_add_filter.php';">Add New Filter...</button></td>
<td style=""><ul class="nav nav-pills">
        <li class="nav-item"><a href="ManageFilterTypes.php?Action=addfiltertype" class="nav-link"><button type="button"  style="margin-left:0px;padding-left:0px;" class="btn btn-primary">Manage filter types...</button></a></li>
      </ul>
</td>
<td style="">
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="POST">
<select id='shwSize' name='showsize' style="margin-top:20px;margin-bottom:0px;height:36px;" onchange='this.form.submit();' class="selectpicker"> 
<option value="none">Select filter width to show</option>
<option value="all">All filter widths</option>
<option value="x1">1 inch only</option>
<option value='x2'>2 inch only</option>
<option value='x12'>12 inch only</option>
</select>
<input type="hidden" value="showallfilters" name="action"> 
</form></td>
<td style="">
<div class="input-group rounded" style="height:36px;">
<div class="d-block btn-danger invisible" style="font-size: 30px;font-weight:bold;text-align:center;height:45px;width: 50px;color:black;" id="clearsearch" onclick="clearsearch()">X</div>
<form action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">

<input type="hidden" name="showsize" value="search">
<span class="input-group-text border-0" id="search-addon" style="height:40;">
<input type="search" style="height:35;vertical-align:bottom;" name="search_words" id="myInput" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" oninput="showClearSearch();"/>
     <input type="image" style="padding-left:2px;" alt="Submit" name="search" src="search.png" height="30px" width="30px" text-align="middle"></form>
    <i class="fas fa-search"></i>
  </span>
</div>
 </td>
<?php
$fwidthwanted="";
   
?>
</td></tr></table>

<?php
$Action = "";
$SizeToShow="all";
if(isset($_POST["showsize"])){$SizeToShow=$_POST["showsize"];}
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
if(isset($_POST["fcount"])){$fcount=$_POST["fcount"];}
if(isset($_POST["fsize"])){$fsize=$_POST["fsize"];}
if(isset($_POST["ftype"])){$ftype=$_POST["ftype"];}
//echo "size=".$fsize." qty=".$fcount;
if($fsize !=""){
$today=date("Y-m-d");
//echo "<br>Todays date=".$today."<br>id=".$id;
$query = "UPDATE filters SET filter_size='".$fsize."', filter_count='" . $fcount . "', filter_type='".$ftype."', date_updated='". $today . "', par='".$Par."', notes='".$Notes."' WHERE _id='" . $id . "';";
        if (mysqli_query($con, $query)) {
            //echo "filter inventory update complete<br>";
        } else {
            echo "Error updating filter inventory: " . mysqli_error($con);
        }
       
}

}


//DELETE FILTER------------------------------------------------------------------------
if(strcmp($Action,"delete_filter")==0)
{
    $id="";
    if(isset($_GET["id"])){$id=$_GET["id"];}
    if(isset($_POST["id"])){$id=$_POST["id"];}
    $sql="DELETE FROM filters WHERE _id = '". $id."';";
    echo $sql ."<br>";
    $result = mysqli_query($con, $sql);

if ($result) {
    echo "Filter deleted from database successfully";
} else {
    echo "Error deleting record";
}
}

//SHOW ALL FILTERS-----------------------------------------------------------

   ?>
   <table  style="width:100%;">
   <tr id="header">
    <th style="background-color:black;color:white;text-align:center;width:200px;width:200px;">Edit</th>
    <th style="background-color:black;color:white;text-align:center;width:200px;">Qty</th>
    <th style="background-color:black;color:white;text-align:center;width:200px;"style="color:white;">Filter size</th>
    <th style="background-color:black;color:white;text-align:center;width:200px;">Filter Type</th>
    <th style="background-color:black;color:white;text-align:center;width:200px;">Par</th>
    <th style="background-color:black;color:white;text-align:center;width:200px;">Notes</th>
    <th style="background-color:black;color:white;text-align:center;width:200px;">Date updated</th>
    </tr>
</table>
    <?php
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
      $sql = "select * from filters order by substring(filter_size, 1, 2)";
    }

    if ($result = $con->query($sql)) 
      {
         if(mysqli_num_rows($result) == 0){echo "NO SEARCH RESULTS FOUND";}
         while ($row = $result->fetch_assoc()) 
            {

               ?>
                    <table id="filter<?php echo $row["_id"] ?>" style="display:block;width:100%;">
                   <tr>
                        <div style="font-size:<?php echo $FontSize ?>;color:black;font-weight:bold;"><td style="text-align:center;width:200px;width:200px;"><img src="edit.png" title="edit this filter info" onclick="editme('<?php echo $row["_id"] ?>');"></div></td>
                       <div style="font-size:<?php echo $FontSize ?>;color:black;font-weight:bold;"><td style="text-align:center;width:200px;vertical-align:center;"><b><?php echo $row["filter_count"] ?></div></td>
                       <div style="font-size:<?php echo $FontSize ?>;color:black;font-weight:bold;"><td style="text-align:center;width:200px;"><b><?php echo $row["filter_size"] ?></div></td>
                       <div style="font-size:<?php echo $FontSize ?>;color:black;font-weight:bold;"><td style="text-align:center;width:200px;"><b><?php echo $row["filter_type"] ?></div></td>
                       <div style="font-size:<?php echo $FontSize ?>;color:black;font-weight:bold;"><td style="text-align:center;width:200px;"><b><?php echo $row["par"] ?></div></td>
                       <div style="font-size:<?php echo $FontSize ?>;color:black;font-weight:bold;"><td style="text-align:center;width:200px;"><b><div><?php echo $row["notes"] ?></div></td>
                       <div style="font-size:<?php echo $FontSize ?>;color:black;font-weight:bold;"><td style="text-align:center;width:200px;"><b><?php echo $row["date_updated"] ?></div></td>
                     </tr>
                    </table>
                    <table id="edit_filter<?php echo $row["_id"] ?>" style="display:none;width:100%;">
                   <tr><form id="frm<?php echo $row["_id"] ?>" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>" method="post">
                   <input type="hidden" name="action" value="update">
                   <input type="hidden" name="id" value="<?php echo $row["_id"] ?>">
                        <td style="text-align:center;width:200px;"><img src="save.png" title="save filter edit" onclick="saveme('<?php echo $row["_id"] ?>');"></form>
                     <form id="frmDelete<?php echo $row["_id"] ?>" method="post" action="<?php echo $_SERVER['SCRIPT_NAME'] ?>"><img src="cancel.jpg" onclick="ConfirmBox('Delete Filter?', 'frmDelete<?php echo $row["_id"] ?>');"></td>
                       <td style="text-align:center;width:200px;vertical-align:center;"><input type="text" style="width:40px;"  name="fcount" value="<?php echo $row["filter_count"] ?>"></td>
                       <input type="hidden" name="action" value="delete_filter"><input type ="hidden" name="id" value="<?php echo $row["_id"] ?>">
                       <td style="text-align:center;width:200px;"><input type="text" style="width: 100px;"  name="fsize" value="<?php echo $row["filter_size"] ?>"></td>
                       <td style="text-align:center;width:200px;">
                       <select class="fselect" id="slctFilterTypes<?php echo $row["_id"] ?>" name="filter_type">
                        <option>SELECT TYPE</option>
                        <?php
                        foreach ($filtertypes as $value) {
                           echo "<option value='". $value."'>". $value."</option>";
                        }
                        ?>
                        </select>
                     </td>
                       <td style="text-align:center;width:200px;"><input type="text" style="width: 40px;"  name="par" value="<?php echo $row["par"] ?>"></td>
                       <td style="text-align:center;width:200px;"><textarea name="notes"rows="3" cols="12"><?php echo $row["notes"] ?></textarea></td>
                       <td style="text-align:center;width:200px;"><div><b><?php echo $row["date_updated"] ?></b></div></td>
            </form></tr>
                    </table>
               <?php
            }
     }


echo "</table></div>";
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
    function editme(filterID){
        filter = document.getElementById("filter"+filterID);
        filter_to_edit = document.getElementById("edit_filter"+filterID);
        filter.style.display="none";
        filter_to_edit.style.display="block";
    }
</script>
<script>
    function saveme(filterID){
      document.getElementById("frm"+filterID).submit();
        filter = document.getElementById("filter"+filterID);
        filter_to_edit = document.getElementById("edit_filter"+filterID);
        filter.style.display="block";
        filter_to_edit.style.display="none";
        
    }
</script>
<script>  
      function myFunction() {    
         //alert("starting myFunction");
        var input, filter, table, tr, td, i, txtValue,trid, string, index;
        var $n="0";
        input = document.getElementById("myInput");
        $lastsearch = document.getElementById("myInput").value;
        document.cookie = "search=" + $lastsearch;
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) 
        if(tr[i].id != "header"){
         {
            td = tr[i].getElementsByTagName("td")[$n];
            if (td) {
                        var ele = tr[i].getElementsByTagName('input');  
                        for (x = 0; x < ele.length; x++)
                            {
                                 if (ele[x].type == 'text')
                                     {
                                       string = ele[x].value;
                                       index = string.indexOf(input.value);
                                          if(index > -1)
                                                {
                                                   trid=tr[i].id; 
                                                }
                                    }
                           }
                           
                     }
                     if(tr[i].id === trid){tr[i].style.display="";tr[i].style.width="100%";}else{tr[i].style.display="none";}
                     if(input.value === ""){tr[i].style.display="";}
         }
      }
         //for (i = 0; i < tr.length; i++) 
         //{
            
            
         //}
        
		}

        </script>
</body>