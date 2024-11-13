 <?php
 if(session_id() == ''){
    session_start();
 }
 ?>
 <head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>
.blink_me {
  animation: blinker 2s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
<script>
    // Ensure the user's web browser can run
    // JavaScript before creating the custom
    // alert box
    if (document.getElementById) {
        // Swap the native alert for the custom
        // alert
        window.alert = function (alert_message) {
            custom_alert(alert_message);
        }
    }
</script>
<script>
    function custom_alert(alert_message) {

        /* You can utilize the web page address
         * for the alert message by doing the following:

         const ALERT_TITLE = "The page at " + document.location.href + " says: ";
         */
        const ALERT_TITLE = "Filter Manager Alert Message";
        const ALERT_BUTTON_TEXT = "OK";

        // Check if there is an HTML element with
        // an ID of "alert_container".If true, abort
        // the creation of the custom alert.
        let is_alert_container_exist = document.getElementById("alert_container");
        if (is_alert_container_exist) {
            return;
        }

        // Create a div to serve as the alert
        // container. Afterward, attach it to the body
        // element.
        let get_body_element = document.querySelector("body");
        let div_for_alert_container = document.createElement("div");
        let alert_container = get_body_element.appendChild(div_for_alert_container);

        // Add an HTML ID and a class name for the
        // alert container
        alert_container.id = "alert_container";
        alert_container.className = "alert_container"

        // Create the div for the alert_box and attach
        // it to the alert container.
        let div_for_alert_box = document.createElement("div")
        let alert_box = alert_container.appendChild(div_for_alert_box);
        alert_box.className = "alert_box";

        // Set the position of the alert box using
        // scrollTop, scrollWidth, and offsetWidth
        alert_box.style.top = document.documentElement.scrollTop + "px";
        alert_box.style.left = (document.documentElement.scrollWidth - alert_box.offsetWidth) / 2 + "px";

        // Create h1 to hold the alert title
        let alert_header_tag = document.createElement("h1");
        let alert_title_text = document.createTextNode(ALERT_TITLE)
        let alert_title= alert_box.appendChild(alert_header_tag);
        alert_title.appendChild(alert_title_text);

        // Create a paragraph element to hold the
        // alert message
        let alert_paragraph_tag = document.createElement("p");
        let alert_message_container = alert_box.appendChild(alert_paragraph_tag);
        alert_message_container.textContent = alert_message;

        // Create the OK button
        let ok_button_tag = document.createElement("button");
        let ok_button_text = document.createTextNode(ALERT_BUTTON_TEXT)
        let ok_button = alert_box.appendChild(ok_button_tag);
        ok_button.className = "close_btn";
        ok_button.appendChild(ok_button_text);

        // Add an event listener that'll close the
        // custom alert
        ok_button.addEventListener("click", function () {
            remove_custom_alert();
        }, false);
    }
</script>
<script>
    function remove_custom_alert() {
        let HTML_body = document.querySelector("body");
        let alert_container = document.getElementById("alert_container");
        HTML_body.removeChild(alert_container);
    }
</script>
<script>
function myConfirmBox(message, frmID) {
    let element = document.createElement("div");
    const list = element.classList;
    list.add("box-background:red;");
    element.innerHTML = "<div class='box' style='width:500px;height:300px;top:5px;background-color:gold;'>This action will replace all existing data with the information in your back up file. Do you wish to proceed?<button id='trueButton' class='btn green'>Yes</button><button id='falseButton' class='btn red'>No</button></div>";
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
            document.write ("<div style='margin:auto;background-color:red;color:white;font-weight:bold;font-size:2em;padding:100px;text-align:center;'>Import action was canceled</div>");
        });
    })
}
</script>

</head>
<body style="background-color:green;" onload="doOnloadStuff();">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
function doOnloadStuff() {
if(document.getElementById("ask_to_update").value="ask_to_update"){
myConfirmBox("This action will replace all data in filter manager with the data from the back up data file. This action is irreversable. Do you wish to continue?", "frmUpdateDataTables");
}
}
</script>

<?php
$con2 = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
$con = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
$BackupFolder="";

$BackupFolder="sites/".$_SESSION['backup_folder']."/FMdata.fm";

echo "backfolder=".$BackupFolder;
if(file_exists($BackupFolder))
{
$Action = "ask_to_update";
$UserName = "";
$FilterSize = "";
$FilterType = "";
if(isset($_POST["action"])){$Action = $_POST["action"];}
if(isset($_POST["myfile"])){$File = $_POST["myfile"];}
if(isset($_GET["action"])){$Action = $_GET["action"];}
?>
<form id="frmUpdateDataTables" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmUpdateData">
    <input type="hidden" value="UpdateData" name="action">
</form>

<?php
if($Action == "ask_to_update"){
?>
<div id="divDanger" class="blink_me" style="width:100%;background-color:red;font-size:2em;color:white;margin-right:auto;margin-left:auto;text-align:center">***WARNING***</div><div style="width:100%;background-color:red;font-size:2em;color:white;margin-right:auto;margin-left:auto;text-align:center" >This action will replace all data in Filter Manager with the back up file data. <BR>This action is not reversable!</div><div id="divMessage" style="text-align:center;background-color:green;font-size:2em;color:white;font-weight:bold;">Do you wish to continue?<br><button class="btn btn-success" onclick="window.location.href='<?php echo $_SERVER["SCRIPT_NAME"] ?>?action=UpdateData';">YES</button><button class="btn btn-danger" onclick="document.getElementById('divDanger').className='none';document.getElementById('divMessage').innerText='ACTION CANCELED';">NO</button></div>
<?php
}

if($Action == "UpdateData"){
    $query="TRUNCATE table equipment;";
    $truncate = mysqli_query($con, $query);
    if ($truncate) {
        echo "<div style='background-color:black;color:red;text-align:center;font-size:1em;width:100%;'>ALL UNIT DATA HAS BEEN DELETED</div>";
    }

    $query="TRUNCATE table users;";
    $truncate = mysqli_query($con, $query);
    if ($truncate) {
        echo "<div style='background-color:black;color:red;text-align:center;font-size:1em;width:100%;'>ALL USERS DATA HAS BEEN DELETED</div>";
    }

    $query="TRUNCATE table filters;";
    $truncate = mysqli_query($con, $query);
    if ($truncate) {
        echo "<div style='background-color:black;color:red;text-align:center;font-size:1em;width:100%;'>ALL FILTER DATA HAS BEEN DELETED</div>";
    }

    $query="TRUNCATE table filter_orders;";
    $truncate = mysqli_query($con, $query);
    if ($truncate) {
        echo "<div style='background-color:black;color:red;text-align:center;font-size:1em;width:100%;'>ALL FILTER ORDER DATA HAS BEEN DELETED</div>";
    }

    $query="TRUNCATE table filter_types;";
    $truncate = mysqli_query($con, $query);
    if ($truncate) {
        echo "<div style='background-color:black;color:red;text-align:center;font-size:1em;width:100%;'>ALL FILTER TYPES DATA HAS BEEN DELETED</div>";
    }

    $query="TRUNCATE table storage;";
    $truncate = mysqli_query($con, $query);
    if ($truncate) {
        echo "<div style='background-color:black;color:red;text-align:center;font-size:1em;width:100%;'>ALL FILTER STORAGE DATA HAS BEEN DELETED</div>";
    }

    $query="TRUNCATE table misc;";
    $truncate = mysqli_query($con, $query);
    if ($truncate) {
        echo "<div style='background-color:black;color:red;text-align:center;font-size:1em;width:100%;'>ALL MISC DATA HAS BEEN DELETED</div>";
    }
    echo "<div style='background-color:black;color:yellow;text-align:center;font-size:1em;width:100%;'>IMPORTING SAVED DATA...</div>";
    $Count = 0;
    $TableName = "";
    $HoldTableName = "";

$fp = fopen("FMdata.fm", "r") or die("Can't create file");
if ($fp) {
    while (($query = fgets($fp, 4096)) !== false) {
        $query = str_replace("\n", " ", $query);
        $Count = $Count + 1;
        $last = strpos($query, '(', 1);
        $first = strpos($query, 'O', 1);
        $Len = $last - $first;
        $TableName = substr($query,$first + 1,$Len-1);
        if($HoldTableName == ""){$HoldTableName = $TableName;}
        if($HoldTableName != $TableName && $HoldTableName != ""){
            echo "<div style='text-align:center;background-color:black;color:green;font-size:1em;width:100%;'>".$Count. " ".$HoldTableName." records were installed</div>";
            $HoldTableName = $TableName;
            $Count = 0;
        }
        //echo "1pos = ".strpos($query, 'O', 1)." sec=".$last."<br>";
        if (mysqli_query($con, $query)) {
           echo "<div style='background-color:black;color:white;'>" . $query . " was added successfully!</div>";
        } else {
            echo "<div style='text-align:cener;background-color:black;color:white;'>Error adding record: " . mysqli_error($con) ."</div>";
        } 
       }
       $Count = $Count + 1;
       echo "<div style='text-align:center;background-color:black;color:green;font-size:1em;width:100%;'>".$Count. " ".$TableName." records were installed</div><div style='background-color:black;color:white;text-align:center;'>RESTORE FROM IMPORT COMPLETE</div>";
    }
    if (!feof($fp)) {
        echo "Error: unexpected fgets() fail\n";
    }

}
}
else{
    echo "<div style='text-align:center;background-color:red;color:white;font-size:2em;width:100%;'>Back up file( FMdata.fm ) was not found. The back up file was never created or it was moved. For more help report bug;";
}
?>