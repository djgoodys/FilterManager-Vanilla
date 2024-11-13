 <?php
include 'dbMirage_connect.php';
?>
<html>
<head>
<meta charset="utf-8"
     name="viewport" content="width=device-width, initial-scale=1">

    <title>Task History</title>
    <link rel="stylesheet" type="text/css" href="style_sheet.css.css">
</head><body>
<table border=�1 | 0�><tr><td>
            Search task history by: <form>
                <input type ="radio" name="rdo_searchcol" id="rdo_unitname" value="Unit Name" checked>UnitName
                <input type ="radio" name="rdo_searchcol" id="rdo_filtersize" value="Filter Size">Filter Size
                <input type ="radio" name="rdo_searchcol" id="rdo_datecompleted" value="Date completed">Date Completed</form>
        </td></tr></table><br>
<input type="text" id="myInput" onkeyup="myFunction();" placeholder="Search for words">
<form action="<?php echo $ServerAddress ?>/ListEquipment.php" method="post"><input type="submit" value="Back to unit list" id="btnGoToUnitlist" name="btnGoUnitList"><input type="hidden" name="action" value="listUnits"></form>
<?php
$UserName="";
if(isset($_POST["username"]))
         {
            $UserName=$_POST["username"];
         }
$sql =  "SELECT task_id,unit_name,filters_needed,unit_id,task_date_completed,filters_due FROM tasks WHERE employee_name = '". $UserName ."' AND task_date_completed IS NOT NULL;";
 echo "<Table style='myTable' border='1' id='myTable'><tr><th>Unit name</th><th>Filters</th><th>Date completed</th></tr>";

    if (!$result = $con->query($sql)) {
        die ('There was an error running query[' . $con->error . ']');
    }
    while ($row = $result->fetch_assoc()) {
        ?>
        <tr>
            <td><a href="web_control_panel.php?_id=<?php echo $row["task_id"] ?>&unit_name=<?php echo $row["unit_name"] ?>&action=getunitinfo"><?php echo $row["unit_name"] ?></a></td>
            <td><?php echo $row["filters_needed"] ?></td>
            <td><?php echo $row["task_date_completed"] ?></td>
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
        table = document.getElementById("myTable");
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