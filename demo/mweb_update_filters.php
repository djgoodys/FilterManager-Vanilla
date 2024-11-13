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
   
include 'dbMirage_connect.php';
?>
<html>
<head>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<style>
   * {
  box-sizing: border-box;
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
      //alert("starting showClearSearch function"+document.getElementById("clearsearch").classList);
      
      
      //if ( document.getElementById("clearsearch").classList.contains('d-block bg-primary invisible') ){
         //document.getElementById("clearsearch").classList.remove('d-block bg-primary invisible');
        // document.getElementById("clearsearch").classList.add('d-block bg-primary visible');
        document.getElementById("clearsearch").className += " d-block btn-danger visible";
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
<tr style="height:100px;"><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = 'order.php';">Create order sheet</button></td><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = 'mweb_add_filter.php';">Add New Filter...</button></td><td><button style="height:100px;font-size:40px;" class="btn btn-primary font-weight-bold btn-lg" onclick="window.location.href = 'order.php?task=pastorders';">Order History...</button></td><tr>
<td>
<div class="input-group rounded">
<div class="d-block btn-danger invisible" style="font-size: 30px;font-weight:bold;text-align:center;vertical-align:center;height:60px;color:black;" id="clearsearch" onclick="clearsearch()">X</div><input type="search" id="myInput" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" onchange="showClearSearch();"/>
  <span class="input-group-text border-0" id="search-addon">
     <img src="search.png" height="30px" width="30px" text-align="middle" onclick="myFunction();">
    <i class="fas fa-search"></i>
  </span>
</div>
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

//SHOW ALL FILTERS-----------------------------------------------------------
if(strcmp($Action, "showallfilters")==0)
{
    echo "showing all filters";
   //$sql = "SELECT filter_size, filter_count, filter_type, date_updated, par FROM Filters;";
   
   $sql = "select * from filters order by substring(filter_size, 1, 2)";
    if ($result = $con->query($sql)) 
      {
         while ($row = $result->fetch_assoc()) 
            {

               ?>
                   <tr id="tr<?php echo $row["id"] ?>">
                   <li><td style="text-align:center;"><div style="color:white;text-align:center;">Qty<?php echo $row["filter_count"] ?></div>
                    <br><div style="color:white;text-align:center;"style="color:white;">Filter size <?php echo $row["filter_size"] ?>/div></li>
                    <div style="color:white;text-align:center;">Filter Type</div>
                    <div style="color:white;text-align:center;">Par</div>
                    <div style="color:white;text-align:center;">Notes</div>
                    <div style="color:white;text-align:center;">Update database</div>
                    <div style="color:white;text-align:center;">Date updated</div>
                    <div style="color:white;text-align:center;">Delete filter</div>
                        
                       <td style="text-align:center;vertical-align:center;"><?php echo $row["filter_count"] ?></td>
                       </td>
                       <td style="text-align:center;"><?php echo $row["filter_type"] ?></td>
                       <td style="text-align:center;"><?php echo $row["par"] ?></td>
                       <td style="text-align:center;"><?php echo $row["date_updated"] ?></td>
                       <td style="text-align:center;"><textarea name="notes"  rows="3" cols="12"><?php echo $row["notes"] ?></textarea></td>
                   </tr>
               <?php
            }
     }
}
else
{
$sql = "select * from filters order by substring(filter_size, 1, 2)";
   //$sql = "SELECT filter_size, filter_count, filter_type, date_updated, par, notes FROM Filters;";
   //CREATE ARRAY FILTERS AND COUNT
    $result = mysqli_query($con, $sql) or die(mysqli_error($con));
   while($row = mysqli_fetch_array($result)){
	//echo $row['filter_size']. " - ". $row['filter_count'];
	//echo "<br />";
}
 

   $filters = array(["size"],["qty"]);
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
               <tr id="tr<?php echo $id ?>" style="height:610px;">
               <div class="container">
               <div class="row">
               <div class="col-6 col-sm-3">
                     <form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>">
                     <input type="hidden"  name="id" value="<?php echo $id ?>">
                    <h1>Filter size:</h1></div>
                    <div class="col-6 col-sm-3"><input type="text" style="background-color:<?php echo $color ?>;width:250px;height:80px;font-size:50px;text-align:left;font-weight:bold;color:black;" id="<?php echo $row["filter_size"] ?>" name="fsize" value="<?php echo $row["filter_size"] ?>">
                  </div>
                    <div class="w-100"></div>
                    <div class="col-6 col-sm-3"><h1>Count:</h1></div>
                     <div class="col-6 col-sm-3"><input type="text" style="background-color:<?php echo $color ?>;width:250px;height:80px;font-size:50px;font-weight:bold;color:black;" id="txtcount" name="fcount" value="<?php echo $row["filter_count"] ?>">
                        </div>
                        <div class="w-100"></div>
                     <div class="col-6 col-sm-3"><h1>Filter type:</h1></div>
                     <div class="col-6 col-sm-3"><input type="text"  style="background-color:<?php echo $color ?>;width:250px;height:80px;font-size:50px;font-weight:bold;color:black;" name="ftype" value="<?php echo $row["filter_type"] ?>">                          
                        </div>
                        <div class="w-100"></div>
                      <div class="col-6 col-sm-3"><h1>Par:</h1></div>
                      <div class="col-6 col-sm-3"><input type="text"  style="background-color:<?php echo $color ?>;width:250px;height:80px;font-size:50px;font-weight:bold;color:black;" name="par" value="<?php echo $row["par"] ?>">
                     </div>
                     <div class="w-100"></div>
                    <div class="col-6 col-sm-3"><h1>Notes:</div>
                    <div class="col-6 col-sm-3"><textarea style="width:250px;height:80px;font-size:30px;font-weight:bold;" name="notes" rows="3" cols="12"><?php echo $row["notes"] ?></textarea>
                     </div>
                     <div class="w-100"></div>
                        <div class="col-6 col-sm-3"><input type="submit" class="btn btn-info" style="font-size:40px;width:200px;height:90;font-weight:bold;color:#fbf109;" name="action" value="UPDATE">
                      </div>
                     <div class="col-6 col-sm-3"> <A Href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?action=delete_filter&id=<?php echo $id ?>">
                     <BUTTON class="btn btn-danger" style="font-size:40px;width:200px;height:90;font-weight:bold;color:#fbf109;">DELETE</BUTTON></A>
                        </div>
                        <div class="w-100"></div>
                    <div class="col-6 col-sm-3"><h1>Last updated:</div>
                    <div class="col-6 col-sm-3"><h1><?php echo $row["date_updated"] ?></h1>
                  </div>
                  <div class="w-100" style="height:20px;width:100%;background-color:black"></div>
               </form>
               <tr>
           <?php
			   }
        }
        echo "</table>";
     }
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
      function myFunction() {    
         alert("starting myFunction");
        var input, filter, table, tr, td, i, txtValue,trid, string, index;
        var $n="0";
        input = document.getElementById("myInput");
        $lastsearch = document.getElementById("myInput").value;
        document.cookie = "search=" + $lastsearch;
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
       
        for (i = 0; i < tr.length; i++) 
         {
            found = false;
            td = tr[i].getElementsByTagName("div");
            const div = document.getElementsByClassName("col-6");
            console.log("trid="+tr[i].id);
            if (div) {
              
                        var ele = div[i].getElementsByTagName('input');  
                       
                        for (x = 0; x < ele.length; x++)
                            {
                              
                                 if (ele[x].type == 'text')
                                     {
                                       
                                       string = ele[x].value;
                                       console.log("string="+string);
                                       index = string.indexOf(input.value);
                                          if(index > -1)
                                                {
                                                   //trid=tr[i].id; 
                                                   
                                                   found = true;
                                                }
                                                else{
                                                   
                                                   //tr[i].style.display="none";
                                                   
                                                }
                                    }
                           }
                           
                     }
                     if(found == true){document.getElementById(trid).style.display="none";console.log("found:" + index);}
                     
                    // if(tr[i].id === trid){tr[i].style.display="";tr[i].style.width="100%";}else{tr[i].style.display="none";}
                     //if(input.value === ""){tr[i].style.display="";}
         }
      
         //for (i = 0; i < tr.length; i++) 
         //{
            
            
         //}
        
		}

        </script>
</body>