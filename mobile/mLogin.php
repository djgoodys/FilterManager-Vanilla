<?php
//echo "session=".session_status();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
$Action=null;


include("../dbMirage_connect.php");
include("../javafunctions.php");
//print_r($_COOKIE);
$LogInAttempt="";
$UserName="";
$Password="";
$NewUser = "";
$Response = "Login";
$UserNamePassed = "false";
$LoggedIn = "";
if(isset($_POST["action"]))
   {
      $Action = $_POST["action"];
   }
   
if(isset($_GET["action"]))
   {
      //echo "Post action=".$_POST["action"]. " len=".strlen($_POST["action"])."<br>";
      $Action = $_GET["action"];
   }
//echo "action=".$Action."<br>";
if(strcmp($Action, "logout")==0)
   {
      setcookie("username", "", time() - 3600);
   }
 
if(isset($_COOKIE["username"]))
   {
      $UserName=$_COOKIE["username"];
   }
   
   if(isset($_POST["password"]))
   {
      $Password=$_POST["password"];
   }

?>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
 <script src="../d3.min.js"></script>
    <title>Filter Manager Login</title>
<style>
     @property --rotate {
  syntax: "<angle>";
  initial-value: 132deg;
  inherits: false;
}

:root {
  --card-height: 65vh;
  --card-width: calc(var(--card-height) / 1.5);
}

img{
  border-radius: 8px;
}
body {
  min-height: 100vh;
  background-image: url("images/filters.png");
  background-repeat: repeat-x; 
  background-size: 200px 100px;
  display: flex;
  align-items: center;
  flex-direction: column;
  padding-top: 2rem;
  padding-bottom: 2rem;
  box-sizing: border-box;
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
<style>
input[type=text]{
   height:40px;
   width:150px;
   font-size:1em;
   color:black;
   border-style: solid;
   border-width: 4px;
   border-color: gold;
}
input[type=password]{
   height:40px;
   width:150px;
   font-size:1em;
   color:white;
   border-style: solid;
   border-width: 4px;
   border-color: gold;
}
/* Hide the browser's default checkbox */
.container input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

.myForm {
  border: none;
  padding: 1px 1px;
  text-decoration: none;
  display: inline-block;

  }

.myButton {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  font-size:35px;
  padding: 1px 1px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  width:230px; 
  height:60px; 
  border-radius: 30px;
  }
}


input[type=text]{
	height:35px;
	width:140px; 
}
</style>
<script>
function deleteallcookies(){
    document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
}
</script>

<script>
function setCookie(myCookie) { 
   console.log("setCookie="+myCookie);
  var now = new Date();
  var time = now.getTime();
  var expireTime = time + 1000*36000;
  now.setTime(expireTime);
  document.cookie = 'cookie='+myCookie+';expires='+now.toUTCString()+';path=/';
}
</script>
<script type="text/javascript">
   function whatmachine(){
        const userAgent = navigator.userAgent.toLowerCase();
        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
        if(isMobile){
          //alert("mobile device");
          document.cookie =("cookie_machine=mobile");
      }else{
         document.cookie =("cookie_machine=desktop");
          //alert("not mobile device");
      }
   }
</script>

<script>
function listCookies() {
    var theCookies = document.cookie.split(';');
    var aString = '';
    for (var i = 1 ; i <= theCookies.length; i++) {
        aString += i + ' ' + theCookies[i-1] + "\n";
    }
    return aString;
}

 function ckLoginSuccess()
{
console.log(document.cookie);
   if(getJavaCookie("username").length > 0) 
      {
         window.location.replace("mListEquipment.php");
         console.log("was already logged in as"+getJavaCookie("username"));
      }
   
   if(document.getElementById("divUserName").innerHTML.length > 0)
      {
            console.log("setting cookie as"+document.getElementById("divUserName").innerHTML);
         setJavaCookie( "username",document.getElementById("divUserName").innerHTML, 1);
      }
   if(getJavaCookie("username").length > 0)
      {
      console.log("just logged in as"+getJavaCookie("username"));
      window.location.replace("mListEquipment.php");
      }
 }

</script>
<script>
function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
</script>
<script>
function deletecookie(cname){
  // alert("deleting cookie");
   document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
   //alert("qry="+getCookie("cookie_lastquery"));
}
   </script>
</head>
<body onload="whatmachine();ckLoginSuccess();" style="background-color:green;">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<?php
//echo "<br>Action=".$Action."<br><button onclick='deleteallcookies();'>delete all cookies</button>";
if(strcmp($Action, "login")==0 && $LoggedIn != "true")
{
      $LogInAttempt = "true";
      if(isset($_POST["username"])){$UserName = $_POST["username"];}
      if(isset($_POST["password"])){$Password = $_POST["password"];}
    $query="SELECT user_name, password FROM users WHERE user_name = '" . $UserName . "';";
    if ($stmt = $con->prepare($query)) 
            {
               $stmt->execute();
               $stmt->bind_result($User_Name, $Pass_word);
               while ($stmt->fetch()) 
                  {
                     if (strcmp($Password, $Pass_word)==0) 
                        {
                           $UserNamePassed = "true";
                           $LoggedIn="true";
                        }
                   }
            }
}

  
?>
<script>
function delete_cookie( name) {
document.cookie = name + '=; Max-Age=0'
}
</script>
<div id='divLoggedin' style='display:none;background-color=red;width:150px;'><?php echo $LoggedIn ?></div><div id='divUserName' style="display:block;background-color:blue;width:200px;"><?php if($LoggedIn == "true"){echo $UserName;} ?></div><div id="LogInAttempt" style="display:none;width:200px;background-color:yellow;color:black;"><?php echo $LogInAttempt ?></div>
<table style="margin-top:20px;">
</tr>
<tr style="text-align: center;"><td style="text-align: center;">
<form action='<?php echo $_SERVER["SCRIPT_NAME"] ?>' method='post'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="txtUserName" name="username" value="" size="21" maxlength="10" height="40" placeholder="ENTER USERNAME HERE" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password" id="txtPassword" style="color:black;" placeholder="ENTER PASSWORD HERE">&nbsp;&nbsp;&nbsp;<input type="submit" id="btnLogon" style="text-align: center;' class="myButton" name="action" value="login"><a href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?action=login"><div  style="color:#ffff80;width: 400px;text-align:right;">Forgot user name or password?</div></a></td></tr>
</form></td></tr><tr><td></td></tr></table>
		<script>
      if(getJavaCookie('LoggedIn') == "" && document.getElementById('LogInAttempt').innerHTML == "true")
         {
         document.write("<div style='background-color:red;color:white;margin-left: 0px;text-align:left;'>Invalid user name or password</div>");
         }
      </script>
<?php
include('../dbMirage_connect.php');
//GET DATE OF LAST DATA BACK UP
$sql = "SELECT * FROM misc ORDER BY _id DESC LIMIT 1";
   
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   $LastBackup = $row["last_backup"];  
                }
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

$query="SELECT * FROM equipment;";
if ($stmt = $con->prepare($query)) 
    {
        $stmt->execute();
        $stmt->bind_result($unitId, $UnitName, $location, $AreaServed, $FilterSize, $FiltersDue, $FilterType, $belts, $notes, $FilterRotation, $FiltersLastChanged, $AssignedTo, $Image );
        $X=0;
        $ND=0;
        $OD=0;
        $EquipmentList="";
        while ($stmt->fetch()) 
            {
                $EquipmentList= $EquipmentList . "{\"units\":[  {\"_id\":\"".$unitId."\", \"unit_name\":\"".$UnitName."\",\"location\":\"".$location."\"}]}";
                $today = date('Y-m-d');
                $someDate = new DateTime($FiltersDue);
                $daysoverdue= s_datediff("d",$today,$someDate,true);
                if($daysoverdue < 0)
                    {
                        //echo "<font color='red'>".$UnitName."is".$daysoverdue." days overdue.<br />";
                        $OD = $OD + 1;
                    }
                if((int)$daysoverdue > 0)
                    {
                       // echo "<font color='black'>".$UnitName."is not".$daysoverdue." days overdue.<br />";
	                    $ND=$ND+1;
                    }
                    $X=$X+1;
            }
        $P = $X - $OD;
        //echo "P=".$P." X=".$X." OD=".$OD." ND=".$ND."<br>";
}
?>
<body>

<div class="card">
  <div style="color:green;">Filter Manager</div>
</div>
    <h2 style="color:red;"></h2>
    <h3 style="color:black;">Last data back up performed on:<?php echo $LastBackup ?></h3>
    <div style="color:black;">Bug fixes 4/18/2023: Reassigning a task is now working.</div>
    <span style="height:40px;"></span>
    
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
<td><div style="background-color:#4daf4a; color:white;height:60px;font-size:25px;font-weight:bold;text-align: center;">&nbsp;&nbsp;<?php echo $P." COMPLETED"; ?>&nbsp;&nbsp;</div></td>
</tr></table>
</body>