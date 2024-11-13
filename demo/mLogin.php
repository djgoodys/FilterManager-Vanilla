<html
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

<?php

include 'dbMirage_connect.php';

$NewUser="";
$Response="";

//print_r($_COOKIE);
?>

<?php
$Action=null;
$UserName="";
/*
foreach ($_POST as $key => $value) 
   {
      echo $key. "=". $value."<br>";
    }
*/
$UserName="";
$Password="";
$NewUser = "";
$Response = "Login";

 if(isset($_COOKIE["savelogon"]))
   {
      $savelogon = $_COOKIE["savelogon"];
   }
   
if(isset($_POST["action"]))
   {
      $Action = $_POST["action"];
   }
   
if(isset($_GET["action"]))
   {
      //echo "Post action=".$_POST["action"]. " len=".strlen($_POST["action"])."<br>";
      $Action = $_GET["action"];
   }
//echo "action = ".$Action."<br>";

if(isset($_COOKIE["username"]))
   {
      $UserName=$_COOKIE["username"];
      //echo "username is".$_COOKIE["username"];

   }
           
if(isset($_POST["username"]))
   {
      $UserName=$_POST["username"];
      $_COOKIE["username"]=$UserName;
   }
   
   if(isset($_POST["password"]))
   {
      $Password=$_POST["password"];
      
   }
   
if(isset($_GET["username"]))
   {
      $UserName=$_GET["username"];
      $_COOKIE["username"]=$UserName;
   }
   
if(strcmp($Action, "logout")==0)
   {


   }


if($Action == null)
   {
      $Action = "pageUserName";
   }
  //echo "action=".$Action."<br>";

if(isset($_GET["response"]))
   {
      $Response=$_GET["response"];
   }
   else
   {
     // $Response="WELCOME";
   }
   
//NAME AND PASSWORD BOTH STEPS SENT LAST STEP:
if(strcmp($Action, "Login")==0)
{  
    $LoginSuccess = "false";
    $savelogon="false";
      if(isset($_POST["username"])) {$UserName = $_POST["username"];}
      if(isset($_POST["password"])) {$Password = $_POST["password"];}
      //echo "starting login scripts"."<br>"." Username=".$UserName." Password=".$Password."<br>";
      //echo "Post Uname=".$_POST["username"]. " POST PASSW=". $_POST["password"];
          $query="SELECT _id, user_name, password, userscol FROM users WHERE user_name = '" . $UserName. "';";
          if ($stmt = $con->prepare($query)) 
            {
               $stmt->execute();
               //Bind the fetched data to $unitId and $unitName
               $stmt->bind_result($RecId, $User_Name, $Pass_word, $UsersCol);
               //Fetch 1 row at a time
               while ($stmt->fetch()) 
                  {
                     if (strcmp($Password, $Pass_word)==0 && strcmp($UserName, $User_Name)==0) 
                        {
                           $LoginSuccess = "true";
                           echo $UserName." you are logged in.";
                        }
                  }
            // echo "Uname=".$UserName." passw=".$Password;
          if (strcmp($LoginSuccess, "true")==0) 
            {
               //ECHO "LOGIN SUCCESS<BR>";
               if(isset($_POST["cksavelogon"])){$savelogon="true";}
               if(strcmp($savelogon, "true")==0)
                  {
                        $_SESSION["username"] = $UserName;
                        $_SESSION["password"] = $Password;
                        $_SESSION["save_login"] = "true";
                        $cookie_name1 = "username";
                        $cookie_value1 = $UserName;
                        $cookie_name2 = "password";
                        $cookie_value2 = $Password;
                        $cookie_name3 = "savelogon";
                        $cookie_value3 = "true";
                        setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30),'/'); // 86400 = 1 day
                        setcookie($cookie_name2, $cookie_value2, time() + (86400 * 30),'/');
                        setcookie($cookie_name3, $cookie_value3, time() + (86400 * 30),'/');
                        $_COOKIE['username'] = $UserName;
                        
                  }
                  else
                  {
                        $_SESSION["username"] = $UserName;
                        $_SESSION["password"] = "none";
                        $_SESSION["save_login"] = "false";
                        // set the expiration date to one hour ago
                        $cookie_name1 = "username";
                        $cookie_value1 = $UserName;
                        setcookie($cookie_name1, $cookie_value1, time() + (86400 * 30));
                        setcookie("username", "", time() + (86400 * 30),'/');
                        setcookie("password", "", time() + (86400 * 30),'/');
                        setcookie("savelogon", "", time() + (86400 * 30),'/');
                        $_COOKIE['username'] = $UserName;
                  }
                  //echo "login successfull<br>";
                  //$_SESSION["username"] = $UserName;
                   if(isset($_POST["username"])) {$UserName = $_POST["username"];}
                if(isset($_POST["password"])) {$Password = $_POST["password"];}
            } 
          if (strcmp($LoginSuccess, "false")==0) 
               {
                  echo "LOGIN FAILED!!!!!!<br>";
                  
                 if($Action == "login"){$Response = "login denied";}
               }
              $stmt->close();
          }
}

?>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<meta charset="utf-8"
     name="viewport" content="width=device-width, initial-scale=1">
     
    <title>Filter Manager Login</title>
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
<script type="text/javascript">
   function whatmachine(){
        const userAgent = navigator.userAgent.toLowerCase();

        var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
        if(isMobile){
          //alert("mobile device");
          document.cookie =("machine=mobile");
      }else{
         document.cookie =("machine=desktop");
          //alert("not mobile device");
      }
   }
  </script>
<script>
   function checkCookie(ckname) {
  let myvalue = getCookie(ckname);
  if (myvalue != "") {
   alert("machine " + myvalue);
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
   function getCookieByName(cname) {

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
   function getcookies(cname) {
        var cookies = document.cookie.split(';');
        var ret = '';
        for(var i = 1; i <= cookies.length; i++) {
            ret += i + ' - ' + cookies[i - 1] + "<br>";
        }
        return ret;
    }
    </script>
     <script>
   function logout(){
      document.getElementById('tblNavigation').style.display = 'none';
      document.getElementById('txtUserName').style.display = 'inline-block';
      document.getElementById('txtPassword').style.display = 'inline-block';
      document.getElementById('btnLogon').style.display = 'inline-block';
      document.getElementById('tblLogin').style.display = 'inline-block';
      document.getElementById('divUserName').innerHTML = "";
      top.frames['iframe2'].location.href = "welcome1.php";
   }
</script>

<script>
function getusername(){
if(getCookieByName('loggedon') === "yes"){window.location.assign("mListEquipment.php");}
      
}
</script>
<script>
 function checkIfLoggedIn($gotoLocation){
 	 divLoggedin=document.getElementById("divLoggedin");
    divUserName=document.getElementById("divUsername");
     //alert(document.getElementById("divLoggedin").innerHTML);
     let loggedin=divLoggedin.innerHTML;
     let username=divUserName.innerHTML;
     if(loggedin.localeCompare("true") === 0){ 
        var date = new Date();
        date.setDate(date.getDate() + 1);
        document.cookie = "loggedin=true; expires="+date+" 23:00:00 UTC";
        document.cookie = "username="+username+"; expires="+date+" 23:00:00 UTC";
        window.location.assign($gotoLocation);
    }
 }
</script>
</head>
<body onload="whatmachine();checkIfLoggedIn('mListEquipment.php');" style="background-color:green;">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>


<?php
$UserNamePassed="";
if(strcmp($Action, "login")==0)
{
      $UserNamePassed = "false";
      if(isset($_POST["username"])) {$UserName = $_POST["username"];}
      if(isset($_POST["password"])) 
		{  
		  $Password = $_POST["password"];
		}
	//echo "Post Uname=".$_POST["username"]. " POST PASSW=". $_POST["password"];
    $query="SELECT user_name, password FROM users WHERE user_name = '" . $UserName . "';";
    if ($stmt = $con->prepare($query)) 
            {
               $stmt->execute();
               //Bind the fetched data to $unitId and $unitName
               $stmt->bind_result($User_Name, $Pass_word);
               //Fetch 1 row at a time
               while ($stmt->fetch()) 
                  {
					  //echo $User_Name."<br>";
                     if (strcmp($Password, $Pass_word)==0) 
                        {
                           $UserNamePassed = "true";
                        }
                   }
            }
}

  ?>
  
<table id='tblLogin' style='display: none;text-align: center'>

<tr style="text-align: center;"><td style="text-align: center;"><form action='web_login3.php' method='post'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="txtUserName" name="username" value="" size="21" maxlength="10" height="40" style='display: <?php echo $DisplayType ?>;' />
&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password" id="txtPassword" style="color:black;display: <?php echo $DisplayType ?>;" placeholder="ENTER PASSWORD HERE">&nbsp;&nbsp;&nbsp;
<input type="submit" id="btnLogon" style='display: <?php echo $DisplayType ?>;text-align: center;' class="myButton" name="action" value="login">
<a href="web_login3.php?action=passwordreset"><div  style="color:#ffff80;">Forgot user name or password?</div></a></td></tr>
</form></td></tr><tr><td></td></tr></table>
<?php
      if($UserNamePassed == "true")
         {
            echo "<div id='divLoggedin'>true</div><div id='divUsername'>".$UserName."</div>";
         }
		?>
<tr style="text-align: center;"><td style="text-align: center;"><form action='<?php echo $_SERVER["SCRIPT_NAME"] ?>' method='post'>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="txtUserName" name="username" value="Guest" disabled size="21" maxlength="10" height="40" onclick="alert('Not available in Demo mode.');" style='display: <?php echo $DisplayType ?>;' />&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password" id="txtPassword" style="color:black;display: <?php echo $DisplayType ?>;" placeholder="ENTER PASSWORD HERE">&nbsp;&nbsp;&nbsp;<input type="submit" id="btnLogon" style='display: <?php echo $DisplayType ?>;text-align: center;' class="myButton" name="action" value="login"><a href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?action=passwordreset"><div  style="color:#ffff80;">Forgot user name or password?</div></a></td></tr>
</form></td></tr><tr><td></td></tr></table>
<div style='background-color:black;color:white;width:50px;' id='divUserName'></div>
<button onclick="cookie('username='';expires=Thu, 01 Jan 1970 00:00:01 GMT';)">delete cookie</button>
<?php
if(strcmp($UserNamePassed, "false")==0 )
   {    
        echo "<div id='divLoggedin'>false</div><div id='divUsername'></div>";
		$Response2="Invalid user name or password";
		?>
		<form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
		<table style="margin-left: 480px;">
		 <?php 
		  if(strcmp($Response, "login denied")==0 || strcmp($Response, "loginfailure")==0)
			  {
				 $Response="Login attempt failed";
				 $FontColor="red";
			  }
			  else
			  {
				 $FontColor="black";
			  } 
		  if(isset($_COOKIE["savePassword"]))
		   {
			  if(strcmp($_COOKIE["savePassword"], "true")==0)
				{
					$Password=$_COOKIE["password"];
				}
			}
 ?>
  <TH style='color:#ff0000;font-size:1.25em;background-color:white;'><?php echo $Response2 ?></TH>
</table>
<?php
   }
   ?>

          