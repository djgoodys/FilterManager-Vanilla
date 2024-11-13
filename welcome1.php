<?php
ob_start();
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE || session_status() === 1) {
    session_start();
}
if(!isset($_SESSION["backup_folder"]))
   {
      session_start();
   }

if(isset($_SESSION["user_name"])) 
  {
      $UserName=$_SESSION["user_name"];   
      $file = "table_users.json";
      $jsonString = file_get_contents($file);
      $data = json_decode($jsonString, true);
      $found = false;
      foreach ($data as $user) {
      //echo $user["user_name"] ."=". $UserName;
      if ($user["user_name"] == $UserName) 
        {
      $UserNameNamePassed = "true"; 
      $_SESSION['user_name'] = $user["user_name"];
      $_SESSION['theme'] = $user["theme"];
      $_SESSION['font_family'] = $user["font_family"];
      $_SESSION['font_size'] = $user["font_size"];
      $_SESSION['admin'] = $user["admin"];
      $_SESSION['field2'] = $user["field2"];
      $_SESSION['field3'] = $user["field3"];
      $_SESSION["backup_folder"] = $user["backup_folder"];  
      //echo "backup=".$user["backup_folder"].$user["admin"];
      //echo "user=".$user["admin"];
      $_SESSION["admin"] = $user["admin"];
      if(isset($_SESSION["theme"]) == "td-DarkPlain")
        {
          $_SESSION["background-color"] = "black";
          $_SESSION["font-color"] = "white";
        }
          else
        {
          $_SESSION["background-color"] = "white";
          $_SESSION["font-color"] = "black";
        } 
        }
      }
}
else
{
  header('Location: '."start.php");
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

$LastBackup = "";
$LastEquipmentUpdate = "";
$LastFilterUpdate = "";
$Action="";
$OrderAmount="";

if(isset($_POST["action"])){$Action = $_POST["action"];}
?>

<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
 <script src="d3.min.js"></script>
    <title></title>
<style>
table tr td {
border: 2px solid green;
margin-top:30px;
}
table {
border: 2px solid green;
font-weight:bold;
}

     @property --rotate {
  syntax: "<angle>";
  initial-value: 132deg;
  inherits: false;
}

:root {
  --card-height: 65vh;
  --card-width: calc(var(--card-height) / 1.5);
}

  .background-image {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    z-index: -1;
    opacity: 0.2;
  }

img{
  border-radius: 8px;
}
body {
  min-height: 100vh;
  background-repeat: no-repeat;
  background-size: cover;
  background-image: url("images/fm3.jpg");
  background-color: rgba(0, 0, 0, 0.5);
  //background-repeat: repeat-x; 
  //background-size: 200px 100px;
  display: flex;
  align-items: center;
  flex-direction: column;
  padding-top: 2rem;
  padding-bottom: 2rem;
  box-sizing: border-box;
}
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

.card {
  background: #191c29;
  width: 120px;
  height: 120px;
  padding: 3px;
  position: relative;
  border-radius: 6px;
  justify-content: center;
  align-items: center;
  text-align: center;
  display: flex;
  font-size: 1.5em;
  color: rgb(88 199 250 / 0%);
  cursor: pointer;
  font-family: cursive;
}

.card:hover {
  color: rgb(88 199 250 / 100%);
  transition: color 1s;
}
.card:hover:before, .card:hover:after {
  animation: none;
  opacity: 0;
}


.card::before {
  content: "";
  width: 104%;
  height: 102%;
  border-radius: 8px;
  background-image: linear-gradient(
    var(--rotate)
    , #5ddcff, #3c67e3 43%, #4e00c2);
    position: absolute;
    z-index: -1;
    top: -1%;
    left: -2%;
    animation: spin 2.5s linear infinite;
}

.card::after {
  position: absolute;
  content: "";
  top: calc(var(--card-height) / 6);
  left: 0;
  right: 0;
  z-index: -1;
  height: 100%;
  width: 100%;
  margin: 0 auto;
  transform: scale(0.8);
  filter: blur(calc(var(--card-height) / 6));
  background-image: linear-gradient(
    var(--rotate)
    , #5ddcff, #3c67e3 43%, #4e00c2);
    opacity: 1;
  transition: opacity .5s;
  animation: spin 2.5s linear infinite;
}

@keyframes spin {
  0% {
    --rotate: 0deg;
  }
  100% {
    --rotate: 360deg;
  }
}

a {
  color: #212534;
  text-decoration: none;
  font-family: sans-serif;
  font-weight: bold;
  margin-top: 2rem;
}
.square {
  width: 100px;
  height: 100px;
  background-color: green;
  position: relative;
  animation: rotate 5s linear;
  animation-iteration-count: 4;
  
}

.letter {
  font-size: 20px;
  font-weight: bold;
  text-align: center;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
}

@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
</head>
<?php
// GET SERVER INFORMATION FROM table_users.json IF SESSION EXPIRED
if(!isset($_SESSION["backup_folder"]) && isset($_COOKIE["cookie_username"]))
{
  $file = "table_users.json";
  $jsonString = file_get_contents($file);
  $data = json_decode($jsonString, true);
  $found = false;
  foreach ($data as $user) 
    {
    if ($user === $_COOKIE["cookie_username"])
      {
      echo "User name: " . $_COOKIE["cookie_username"] . "\n";
      echo "Password: " . $user["password"] . "\n";
      $found = true;
      $UserNamePassed = "true"; 
      $_SESSION['user_name'] = $user["user_name"];
      $_SESSION['theme'] = $user["theme"];
      $_SESSION['font_family'] = $user["font_family"];
      $_SESSION['font_size'] = $user["font_size"];
      $_SESSION['admin'] = $user["admin"];
      $_SESSION['field2'] = $user["field2"];
      $_SESSION['field3'] = $user["field3"];
      $_SESSION["backup_folder"] = $user["backup_folder"];  
      if($user["theme"] == "td-DarkPlain")
         {
            $_SESSION["background-color"] = "black";
            $_SESSION["font-color"] = "white";
                }
                else
                {
                $_SESSION["background-color"] = "white";
                $_SESSION["font-color"] = "black";
                }
         }
        break;
  }
}

//GET DATE OF LAST DATA BACK UP BY CHECKING LAST TIME FILE WAS MODIFIED;

$filename = "sites/" . $_SESSION["backup_folder"] ."/FMdata.fm";
if (file_exists($filename)) {
    $LastBackup = date ("F d Y H:i:s.", filemtime($filename));
}
if($LastBackup == ""){$LastBackup = "(Not performed yet)";}
//GET LAST TIME FILTERS FILE WAS MODIFIED;
$filename = 'sites/'.$_SESSION["backup_folder"].'/data.json';
if (file_exists($filename)) {
    $LastFiltersModified = date ("F d Y H:i:s.", filemtime($filename));
}


function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false)
{

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
       
       switch( $str_interval){
           case "y": 
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h": 
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i": 
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s": 
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }
       if($diff->invert)
       {
            return -1 * $total;
       }else
       {
            return $total;
       }
}

$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
$FiltersDue = "";
$X=0;
$ND=0;
$OD=0;
$today = date('Y-m-d');
foreach ($data["equipment"] as $object) {
    foreach ($object as $key => $value) {
        if($key == "filters_due"){
                $FiltersDue = $value;
                $someDate = new DateTime($FiltersDue);
                $daysoverdue= s_datediff("d",$today,$someDate,true);
                if($daysoverdue < 0)
                    {
                        $OD = $OD + 1;
                    }
                if((int)$daysoverdue > 0)
                    {
	                    $ND=$ND+1;
                    }
              $X=$X+1;
            }
          }
        $P = $X - $OD;
        //echo "P=".$P." X=".$X." OD=".$OD." ND=".$ND."<br>";
}

?>
<body>
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<div class="card">
  <div style="color:green;">Filter Manager</div>
</div>
    <table><th><div style="color:black;margin-left:auto;margin-right:auto;width:fit-content;text-align:center;color:black;font-weight:bold;">DATABASE MANAGEMENT</div></th>
    <tr><td style="color:black;">Last time FM DATA was changed </td><td><?php echo $LastEquipmentUpdate ?></td></tr>
    <!--<tr><td style="color:black;">Last time FILTERS DATA was changed </td><td><?php echo $LastFiltersModified ?></td></tr>-->
    <tr><td style="color:black;">Last time FM DATA was backed up </td><td><?php echo $LastBackup ?></td></tr>
    <tr><td style="color:red;">Click Button to backup --></td><td><a href="DataBackUp.php?action=ask_to_update"><button class="btn btn-warning">Back Up Data Now</button></a></td></tr>
    </table>
    <div style="display:none;text-align:center;color:black;font-weight:bold;"><DIV style="color:black;margin:auto;" class="blink_me">*** NEW FEATURE ADDED ***</div>SELECTED TASKS (not yet submitted) STAY IN MEMORY WHEN YOU LEAVE PAGE</div>
    <span style="height:40px;"></span>
    <div style="color:white;background-color:red;">The following filters sizes are at or nearing 0 stock:</div>

<table style="border: 1px solid;color:black;background-color: rgba(0, 0, 0, 0.5);"><tr>
<?php
$AltBody="The following filters sizes are nearing 0 stock:<br><table style='border: 1px solid;'><tr>";
//GET FILTERS FROM data.json;
$file = 'sites/'.$_SESSION["backup_folder"].'/data.json';
$jsonString = file_get_contents($file);
$data = json_decode($jsonString, true);
$x=0;
foreach ($data["filters"] as $filter) { 
    {
         if($filter["filter_count"] <= $filter["par"] && $filter["par"] != 0 && $filter["filter_count"] != $filter["par"])
          {
            $OrderAmount = $filter["par"] - $filter["filter_count"];
            echo "<td style='border: 1px solid;color:white;text-align:left;padding:4px;'>".$filter["filter_size"] . " ".$filter["filter_type"]."</td>";
            $AltBody = $AltBody . "<td style='border: 1px solid;color:white;'>(".$OrderAmount.") ".$filter["filter_size"] . " ". $filter["filter_type"]."</td>";
            $x=$x+1;
            if($x > 5)
              {
              echo "</tr><tr>";
              $AltBody = $AltBody . "</tr><tr>";
              $x=0;
              }
          }
    }
}
$AltBody = $AltBody . "</tr></table>";
?>
</tr></table>
<div style="background-color :whitesmoke;"><a style="align:center;color:black;" href="order.php">CLICK HERE TO CREATE ORDER SHEET</a></div>
<form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="posT" style="background-color :black;color:whitesmoke"><input type="hidden" name="action" value="email_filters">CLICK <input type="submit" value="HERE"> TO EMAIL IT TO <input type="text" name="email_address" value="stewart.lowe@hrhclasvegas.com">RYAN LOWE</form>
    <!--<img src="images/filters.png" " alt="Filter Manager by DJ" height="200" width="200">-->
    <Table style="width%100";>
<tr><td><div style="background-color:red; color:white;height:60px;font-size:25px;font-weight:bold;text-align: center;">&nbsp;&nbsp;<?php echo $OD." UNITS OVER DUE"; ?>&nbsp;&nbsp;</div></td>
<td>
 <svg width="500" height="300"> </svg>
<script>
    var data = [<?php echo $OD .", ".$P; ?>];

    var svg = d3.select("svg"),
        width = svg.attr("width"),
        height = svg.attr("height"),
        radius = Math.min(width, height) / 2,
        g = svg.append("g").attr("transform", "translate(" + width / 2 + "," + height / 2 + ")");

    //var color = d3.scaleOrdinal(['#4daf4a','#377eb8']);
    var color = d3.scaleOrdinal([d3.color("red"),'#4daf4a'])
    // Generate the pie
    var pie = d3.pie();

    // Generate the arcs
    var arc = d3.arc()
                .innerRadius(0)
                .outerRadius(radius);

    //Generate groups
    var arcs = g.selectAll("arc")
                .data(pie(data))
                .enter()
                .append("g")
                .attr("class", "arc")

    //Draw arc paths
    arcs.append("path")
        .attr("fill", function(d, i) {
            return color(i);
        })
        .attr("d", arc);
</script>
</td>
<td><div style="background-color:green; color:white;height:60px;font-size:25px;font-weight:bold;text-align: center;">&nbsp;&nbsp;<?php echo $ND." COMPLETED"; ?>&nbsp;&nbsp;</div></td>
</tr></table>

</body>
</html>
<?php
if($Action == "email_filters")
{
//GET COMPANY NAME FOR ORDER SHEET
$sql = "select company_name from misc LIMIT 1;";
    if ($result = $con->query($sql)) 
      {
         while ($row = $result->fetch_assoc()) 
         	{
      $CompanyName = $row["company_name"];
            }
      }

  $email_address = "";
  if(isset($_POST["email_address"])){$email_address=$_POST["email_address"];}
    echo "email=".$email_address;
    $mail = new PHPMailer(true);
    $UserName = "";
    if (isset($_COOKIE["cookie_username"])) 
        {
          $UserName = $_COOKIE["cookie_username"];
        }
try {
    //Server settings
    $mail->SMTPDebug = 0;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.mboxhosting.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'admin@filtermanager.atwebpages.com';                     //SMTP username
    $mail->Password   = 'relays82';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('admin@filtermanager.atwebpages.com', 'Mailer');
    $mail->addAddress($email_address, '.$UserName.');     //Add a recipient
    //$mail->addAddress('admin@filtermanager.atwebpages.com', '.$UserName.');     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content

    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Air filter order for '. $CompanyName;
    $mail->Body    = $AltBody . "<br><a href='https://filtermanager.atwebpages.com/order.php'>Click to view/print order sheet</a><br>";
    $mail->AltBody = $AltBody;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} 

}
ob_end_flush();
      ?>