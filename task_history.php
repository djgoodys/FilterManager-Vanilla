 <?php
 header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
 header("Cache-Control: GET-check=0, pre-check=0", false);
 header("Pragma: no-cache");
 header("Cache-Control: no-cache");
include 'db/db_connect.php';
 $result = array();
 $unitArray = array();
 $response = array();

$user = "";

if(isset($_GET["user"])){$user = $_GET["user"];}
//echo "thisusername=" . $user;
    $query = "SELECT task_id, unit_name, filters_needed, unit_id, task_date_completed, filters_due, filter_rotation FROM tasks WHERE employee_name = '" . $user . "' AND task_date_completed IS NOT NULL;";

    if ($stmt = $con->prepare($query)) {
        $stmt->execute();
        //Bind the fetched data to $unitId and $unitName
        $stmt->bind_result($RecId, $UnitName, $FiltersNeeded, $UnitID,  $DateCompleted, $FiltersDue, $Rotation);
        $stmt->store_result();
        while ($stmt->fetch()) {
            $unitArray["_id"] = $RecId;
            $unitArray["unit_id"] = $UnitID;
            $unitArray["unit_name"] = $UnitName;
            $unitArray["filters_due"] = $FiltersDue;
            $unitArray["filters_needed"] = $FiltersNeeded;
            $unitArray["task_date_completed"] = $DateCompleted;
            $unitArray["filter_rotation"] = $Rotation;
            $result[] = $unitArray;
        }
        $stmt->close();
        $response["success"] = 1;
        $response["data"] = $result;


    } else {
        //Some error while fetching data
        $response["success"] = 0;
        $response["message"] = mysqli_error($con);


    }
    //Display JSON response
    echo json_encode($response);
//}else{echo "no employee name for query";}
 ?>