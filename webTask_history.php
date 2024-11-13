 <?php
echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE || session_status() != 0 ) {
   //echo "no session";
     session_start();
   }

function save_handler($errno, $errmsg) 
{ 
    throw new Exception('Error caught and thrown as exception: ' .
$errmsg); 
} 
include 'dbMirage_connect.php';
include "functions.php";
include "fm.css";
$UserName =$_SESSION["user_name"];
$SearchWords="";
$Action="";

if(isset($_GET["action"]))
{
    $Action = $_GET["action"];
    $UnitToFind = $_GET["_id"];
    $AssignedTo="";
    $AreaServed = "area_served";
    $Location = "location";
    $FilterSize = "filter_size";
    $FiltersDue = "filters_due";
    $FiltersLastChanged = "filters_last_changed";
    $Rotation = "rotation";
    $Belts = "belts";
    $notes = "notes";
}

if($Action == "getunitinfo")
{
    $query = "SELECT _id,unit_name,location,area_served,filter_size,filters_due,belts,notes,filter_rotation, filters_last_changed,filter_type, assigned_to FROM equipment WHERE _id='" . $UnitToFind . "';";
    if (!$result = $con->query($query)) 
        {
            die ('There was an error running query[' . $con->error . ']');
        }
        else
        {
            while ($row = $result->fetch_assoc()) 
                {
                    $UnitINFO = "Unit name: " . $row["unit_name"] . "<BR>"
                    . "Assigned to: " . $row["assigned_to"] . "<BR>"
                    . "Location: " . $row["location"] . "<br>"
                    . "Area served: " . $row["area_served"] . "<br>"
                    . "Filter size: " . $row["filter_size"] . "<br>"
                    . "Filter Type : " . $row["filter_type"] . "<br>"
                    . "Filters due date: " . $row["filters_due"] . "<br>"
                    . "Filters last changed: " . $row["filters_last_changed"] . "<br>"
                    . "Filter rotation: " . $row["filter_rotation"] . "<br>"
                    . "Belt size: " . $row["belts"] . "<br>"
                    . "Notes: ". $row["notes"];
                }
        }
}
if(isset($_POST["search_words"])){$SearchWords=$_POST["search_words"];}
?>
<html>
<head>
<meta charset="utf-8"
     name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 
    <title>Task History</title>
 
</head>
<style>
.divUnitInfo {
background-color:green;
border-radius:5px;
color:white;
margin-left:30%;
width:fit-content;
}
</style>
<body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

<script>
function clearsearch(){
document.getElementById("myInput").value="";
document.getElementById("frmSearch").submit();
}
</script>

<script>
   function enterKeyPressed(event) {
      
      if (event.keyCode == 13) {
         console.log(event.keyCode);
         document.getElementById("clearsearch").style.display="inline-block";
         return true;
      } else {
         return false;
      }
   }
</script>
<div style="width:100%;text-align:center;font-size:30px;" class="bg-success text-white">Task History</div>
<?php 
if($Action == "getunitinfo")
{
?>
<div id='divInfo' class='divUnitInfo'><?php echo$UnitINFO ?><br><button style="border-radius:5px;background-color:white;color:green;" onclick="document.getElementById('divInfo').style.display='none';">CLOSE</button></div>
<?php
}
?>
<div style="margin-left:40%;margin-right:auto;">
<form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmSearch" >
<span class="input-group-text border-0 flex-nowrap." id="search-addon" style="margin-left:60px;vertical-align:top;width:220px;">
<div class="btn-danger d-none" id="clearsearch" style="border-radius:5px;font-weight:bold;font-size:30px;text-align:top;height:40px;width: 50px;" onclick="clearsearch()">X</div>
<input type="search" onkeypress="document.getElementById('clearsearch').className='btn-danger d-block';" style="margin: auto;vertical-align:top;" name="search_words" id="myInput" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" oninput="showClearSearch();">
     </form><img id="img_search" src="images/search.png" height="30px" width="30px" text-align="middle" onclick="resetElements('search');setLastQuery('search');">
    <i class="fas fa-search"></i>
  </span></form></div>
<?php
if($SearchWords == ""){
$sql =  "SELECT task_id,unit_name,filters_needed,unit_id,task_date_completed,filters_due,action FROM tasks WHERE employee_name = '". $UserName ."' AND task_date_completed IS NOT NULL;";
}
if(strlen($SearchWords) > 0){
   $sql= "SELECT task_id,unit_name,filters_needed,unit_id,task_date_completed,filters_due,action FROM tasks WHERE employee_name = '". $UserName ."' AND unit_name LIKE '%".$SearchWords."%' OR filters_needed LIKE '%".$SearchWords."%' OR unit_id LIKE '%".$SearchWords."%' OR task_date_completed LIKE '%".$SearchWords."%' OR action LIKE '%".$SearchWords."%' OR filters_due LIKE '%".$SearchWords."%';";
//echo $sql."<br>";

}
 echo "<Table class='TaskHistoryTable' style='margin-left:25%;margin-right:auto;' id='TaskHistoryTable'><tr><th>Unit name</th><th>Filters</th><th>Action</th><th>Date completed</th></tr>";

    if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error . ']');
    }
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td style="padding:20px;"><a href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?_id=<?php echo $row["task_id"] ?>&unit_name=<?php echo $row["unit_name"] ?>&action=getunitinfo"><?php echo $row["unit_name"] ?></a></td>
            <td style="padding:20px;"><?php echo $row["filters_needed"] ?></td>
            <td style="padding:20px;"><?php echo $row["action"] ?></td>
            <td style="padding:20px;"><?php echo $row["task_date_completed"] ?></td>
                </tr>
        <?php
    }


    echo "<tr><td><form action='ListEquipment.php' method='post'><input type='submit' value='Back to unit list' id='btnGoToUnitlist' name='btnGoUnitList'>
                <input type='hidden' name='action' value='listUnits'>
            </form></td><td></td><td></td></tr></table>";
?>
<script>
    function myFunction() {
        // Declare variables
       
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("TaskHistoryTable");
        tr = table.getElementsByTagName("tr");

        ckboxDatecompleted = document.getElementById("rdo_datecompleted");
        ckboxUnitname = document.getElementById("rdo_unitname");
        ckboxFiltersize = document.getElementById("rdo_filtersize");
        if (ckboxUnitname.checked) {
            $n = "0";
        }
        if (ckboxFiltersize.checked) {
            $n = "1";
        }
        if (ckboxDatecompleted.checked) {
            $n = "3";
        }
        
       
        switch ($n) {
            case "0":
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[0];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
                break;
            case "1":
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[1];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }
                    }
                }
                break;
        
            case "3":
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[3];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }

                    }
                }
                break;
            case "4":
                // Loop through all table rows, and hide those who don't match the search query
                for (i = 0; i < tr.length; i++) {
                    td = tr[i].getElementsByTagName("td")[4];
                    if (td) {
                        txtValue = td.textContent || td.innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                        } else {
                            tr[i].style.display = "none";
                        }

                    }
                }
                break;
            default:
        }
    }

</script></body></html>