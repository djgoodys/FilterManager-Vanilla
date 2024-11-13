<?php
  
include 'dbMirage_connect.php';
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>Create new filter</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script>
function setfilters()
{
$f1=document.getElementById("fsize1");
$f2=document.getElementById("fsize2");
$f3=document.getElementById("fsize3");
$filters=document.getElementById("filter_size");
if($f1.value > $f2.value){
   $hold = $f1;
   $f1=$f2;
   $f2=$hold;
}
$filters.value=$f1.value + "x" + $f2.value +"x" + $f3.value;
}
</script>
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
if(isset($_POST["filter_size"])){$Filter_size = $_POST["filter_size"];}
if(isset($_POST["filter_type"])){$Filter_type = $_POST["filter_type"];}
if(isset($_POST["filter_count"])){$Filtercount= $_POST["filter_count"];}
if(isset($_POST["par"])){$Par = $_POST["par"];}
if(isset($_POST["notes"])){$Notes= $_POST["notes"];}
if(isset($_POST["action"])){$Action = $_POST["action"];}

if($Action == "addfilter"){
$query = "INSERT INTO filters (filter_size, filter_type, filter_count, par, notes) VALUES ('".$Filter_size."','".$Filter_type."',". $Filtercount.",". $Par.",'".$Notes."');";
        if (mysqli_query($con, $query)) {
            echo "filter set (".$Filter_size."-".$Filter_type.") was created successfully<br>";
        } else {
            echo "Error creating new filter set: " . mysqli_error($con);
        }
}




?>
    <table border="3;3" class="table table-striped">
<tr><td><h1>Add New Filter Size</h1></td><tr>
            <td><form method="POST" name="frmAddFilter" action="web_add_filter.php"></td>
            <tr><td class="<?php echo $TDstyle ?>"><h2>Create Filter size:</h2></td></tr><tr><td class="<?php echo $TDstyle ?>">width&nbsp;&nbsp;<input type='text' onkeyup='setfilters();' maxlength='2' style='font-size:2em;font-weight: bold; overflow: hidden; max-width: 3ch;' id='fsize1' name='fsize1' value=''>&nbsp;height&nbsp;&nbsp;<input type='text' onkeyup='setfilters();' maxlength='2' style='font-weight: bold;font-size:2em; overflow: hidden; max-width: 3ch;' id='fsize2' name='filter_size2' value=''>&nbsp;depth&nbsp;&nbsp;<input type='text' onkeyup='setfilters();' maxlength='2' style='font-weight: bold;font-size:2em; overflow: hidden; max-width: 3ch;' id='fsize3' name='fsize3' value=''>
            <font size="2" color="red"><br><em>Input smaller dimension first i.e. 20X24X1 not 24X20X1</em></td></tr>
         
        <tr><td class="<?php echo $TDstyle ?>">This Filter Size</td></tr><tr><td class="<?php echo $TDstyle ?>"><input type='text' class="form-control" style='font-weight: bold; width: 300px;' id='filter_size' name='filter_size' value=''  readonly></td></tr>
</td></tr>
<tr><td><h2>Filter Type</h2></td></tr><tr><td>
        <select class="form-select" aria-label="Default select example" name="filter_type">
             <option selected>Choose filter type</option>
             <?php
            $sql = "SELECT type FROM filter_types;";
            if ($result = $con->query($sql)) 
                {
                    while ($row = $result->fetch_assoc()) 
                        {
                            echo "<option value='". $row['type'] ."'>". $row['type'] ."</option>";                               
                        }
                }
            ?>
            </select>&nbsp;&nbsp;&nbsp;&nbsp;<a class="btn-success" href="ManageFilterTypes.php"><button type="button" class="btn btn-success .text-white">Manage filter types</butten></a>
      </ul>
    </div>
  </div>

</td></tr>
<tr><td><h2>Total Quantity in stock</h2></td></tr><tr><td><input type="text" class="form-control" style='font-weight: bold; width: 300px;' cols="2" name="filter_count"></td></tr>
<tr><td><h2>Par </h2><font size="2" color="red"><em>(total to keep in stock when ordering)</em></td></tr><tr><td> <input type="text" class="form-control" style='font-weight: bold; width: 300px;' name="par"></td></tr>
<tr><td><h2>Notes<h2></td></tr><tr><td><textarea class="form-control" rows="4" cols="20" style='font-weight: bold; width: 300px;' maxlength ="50" name="notes"></textarea></td></tr>
<tr><td></td></tr><tr><td><input type="submit" value="Add Filter" class="btn-success" style="height:70px;width:100px;><input type="hidden" name="action" value="addfilter"></form></td></tr>
    </table>


</body>
</html>