<?php

include 'javafunctions.php';
$Email = "";
if(isset($_GET["email"])){$Email = $_GET["email"];}

function getUniqueID(){
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
$maxId = 0;
foreach ($data as &$object) 
{
    if (intval($object['_id']) > $maxId) 
    {
        $maxId = $object['_id'];
    }
}
$maxId = $maxId + 1;
foreach ($data as &$object) 
{
    if (intval($object['_id']) > $maxId) 
    {
        $maxId = $object['_id'] + 1;
    }
}
return $maxId;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FilterManager.net - Sign Up</title>
  <?php
  include "javafunctions.php";
  $Action = "";
  $CheckOutSessionID = "";
  $SetupComplete = "";
  if (isset($_POST["action"])){$Action = $_POST["action"];}


if (!empty($_GET)) {
    foreach ($_GET as $key => $value) {
        // Print each key-value pair to the browser
        echo htmlspecialchars($key) . ": " . htmlspecialchars($value) . "<br>";

        // Print each key-value pair to the console
        error_log($key . ": " . $value);
    }
} else {
    echo "No GET variables found.";
    error_log("No GET variables found.");
}

  if (isset($_GET["session_id"])){$CheckOutSessionID = $_GET["session_id"];}

if(!isset($CheckOutSessionID)){$checkout_sessionID=false;}

$dataFile = file_get_contents("table_users.json");
$dataArray = json_decode($dataFile, true); // Decode JSON into an array
$checkout_sessionID = false;
foreach ($dataArray as $entry) {
    if (isset($entry["misc"]["checkout_session_id"]) && $entry["misc"]["checkout_session_id"] == $CheckOutSessionID && $CheckOutSessionID != "") {
        //echo "Subscription ID found in the data!";
        $checkout_sessionID = true;
        $SetupComplete = "true";
        echo "<div style='background-color:red;color:white;width:fit-content;margin-right:auto;margin-left:auto;'>Thank you for signing up for FilterManager.net. Your account was already set up. Check your email for details.</div>";
        break; 
    }
}
  
?>
  <style>
  table {
  border: 1px solid black; /* Border for the entire table */
}

th, td {
  border: 1px solid gray; /* Border for individual cells */
  padding: 5px; /* Add some padding for better spacing */
}

    .redBorder {
      border: 4px solid red;
    }
     .normalBorder {
      border: 4px solid red;
    }

    .normalBorder{
      border: .5px solid gray;
      border-radius: 5%;
    }
  </style>
<!-- Google tag (gtag.js) --> 
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16542837705"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-16542837705'); </script>
<!-- Event snippet for Page view conversion page --> <script> gtag('event', 'conversion', { 'send_to': 'AW-16542837705/UzPrCIqo8qkZEMnXntA9', 'value': 1.0, 'currency': 'USD' }); </script>
<script>
function validateForm() {
  console.log("validating form");
  if(document.getElementById("app_admin_name").value.length > 0 && document.getElementById("app_admin_password").value.length > 0 && document.getElementById("business_name").value.length > 0 && document.getElementById("email").value.length > 0){
    console.log("begin validateForm");
    document.getElementById("pErrorMessage").style.display="none";
    var UserNameOk = true;
    var EmailOK = true;
    var PassWordOk = true;
    var BnameOk  = true;
    var Elem = "";
    var Message = "";

    //EMAIL CHECK
  email = document.getElementById("email").value;
  if (!email || typeof email !== 'string') {
    showError("email", "Email is required and must be a string.");
    EmailOK = false;
  }
 
  if(email.indexOf(" ") > -1) {
    showError("email", "Email cannot contain empty spaces.");
    EmailOK = false;
  }
 
var isEmailValid = email.indexOf("@");
console.log("email valid="+email.indexOf("@"));
if(isEmailValid < 0)
 {
    showError("email", "Not a valid email address. @ is required.");
    EmailOK = false;
  }

var isEmailValid = email.indexOf(".");
console.log("email valid index="+email.indexOf("."));
if(isEmailValid < 0)
 {
    showError("email", "Not a valid email address. '.' is required.");
    EmailOK = false;
  }

// Function to validate username
  var username = document.getElementById("app_admin_name").value.trim();
  element = document.getElementById("app_admin_name");
  // Check if username is empty
  if (username.length == 0) {
    showError(usernameInput, "User name is required.");
    document.getElementById("app_admin_name").className="redBorder";
    UserNameOk = false;
  }

// Function to validate password
 var password = document.getElementById("app_admin_password").value.trim();
  if (password == "" || password.length == 0) {
    showError("app_admin_password", "Password is required.");
    PassWordOk = false;
  }

 if (password.indexOf(" ") > -1) {
    showError("app_admin_password", "Password can not contain empty spaces.");
    PassWordOk = false;
  }

if(document.getElementById("business_name").value == ""){BnameOk = false;}

if(EmailOK == true && UserNameOk == true && PassWordOk == true && BnameOk == true){
    document.getElementById("btnSignUp").disabled = false;
  }
}
}
</script>

<script>
function showError(field, message) {
document.getElementById("btnSignUp").disabled = true;
var usernameInput = document.getElementById("app_admin_name");
const passwordInput = document.getElementById("password");
const emailInput = document.getElementById("email");
const errorElement = document.getElementById("pErrorMessage");
errorElement.style.display = "block";
switch(field) {
  case "bname":
  document.getElementById("business_name").className = "redBorder";
  break;
  case "email":
  document.getElementById("email").className = "redBorder";
  break;
}
console.log("message="+message);
  errorElement.style.display="block";
  errorElement.innerText = message;
}

// Function to remove any existing error message below a field
function removeError(element) {
  document.getElementById("pErrorMessage").style.display="none";
  switch(element) {
    case "username":
    document.getElementById("app_admin_name").className = "normalBorder";
    document.getElementById("pErrorMessage").innerText="";
    break;
      
    case "bname":
    document.getElementById("business_name").className = "normalBorder";
    document.getElementById("pErrorMessage").innerText="";
    break;

  }
}
</script>
<script>
function checkBnameAvailability(bname) {
    console.log("Begin ckbname="+bname);
var bnameInput = bname.replace(" ", "_");

//document.getElementById("business_name");
  fetch(`ajax_user_exists.php?action=check_bname&bname=${bnameInput}`)
    .then(response => response.text()) // Assuming the server sends a plain text response
    .then(data => {
      // Handle the response from the server
      console.log("Response:", data); // Log the response to the console for debugging

      if (data == "available") {
       removeError("bname");
      } else {
        showError("bname", "A business with that name already exists. Choose a different name.");
      }
    })
    .catch(error => {
      console.error("Error checking username availability:", error);
      alert("An error occurred while checking business name availability.");
    });
}

</script>
<script>
function checkUsernameAvailability(username) {
    console.log("Begin ckusername");
var usernameInput = document.getElementById("app_admin_name");
  fetch(`ajax_user_exists.php?action=check_uname&username=${username}`)
    .then(response => response.text()) // Assuming the server sends a plain text response
    .then(data => {
      // Handle the response from the server
      console.log("Response:", data); // Log the response to the console for debugging
      
      if (data == "available") {
        document.getElementById("app_admin_name").className="normalBorder";
        removeError("username");
      } else {
        showError("app_admin_name", "Username already exists. Choose a different name.");
      }
    })
    .catch(error => {
      console.error("Error checking username availability:", error);
      alert("An error occurred while checking username availability."+error);
    });
}

</script>
</head>
<body style="background-color: black;color:white;display: grid;
  place-items: center;" onload="isSignUpDone();">
<script>
  function isSignUpDone(){
    console.log("setup done="+getJavaCookie("Subscription_done"))
if(getJavaCookie("checkout_session_id").length > 0)
{
  document.getElementById("tblSignUp").style.display="none";
  alert("Your subscription has already been set up. Check your email for details.")
}
  }
  </script>
  <script>
    function FinishSetup(){
     document.getElementById("divProcessing").style.display="flex";
     document.getElementById("gifProcessing").style.display="flex";
    document.getElementById("ajax_message").innerHTML="";
    document.getElementById("divSignUp").style.display="none";
    document.getElementById("tblSignUp").style.display="none";
      form = document.getElementById("frmSetup")
      const formData = new FormData(form);

 fetch(`FinishSetup.php`,{
    method: "POST", // Use POST for form data
    body: formData}
 )
    .then(response => response.text()) 
    .then(data => {
     document.getElementById("ajax_message").innerText = data;

      if (data == "business name already exists") {
       showError("bname", "business name already exists. Please try a different nam")
      } 
      if(data== "setup_complete") {
       document.getElementById("divSetup_complete").style.display="flex";
       document.getElementById("tblSetup_complete").style.display="flex";
       document.getElementById("gifProcessing").style.display="none";
       document.getElementById("divEmail").style.display="flex";
       document.getElementById("tblSignUp").style.display="none";
       document.getElementById("divProcessing").style.display="none";
      }
    })
    .catch(error => {
      console.error("Error finishing setup:", error);
      alert("An error occurred while sending form data " +error);
    });
  }
    </Script>
    <script>
      function deleteAllCookies() {
  const cookies = document.cookie.split(";");

  for (let i = 0; i < cookies.length; i++) {
    const cookie = cookies[i].trim();
    const eqPos = cookie.indexOf("=");
    let name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
    document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 UTC;path=/;";
  }
}


</script>


<?php
if($Action == ""){
  //SUBSCRIPT ID NOT FOUND IN table_users OK TO PROCEED WITH SETUP
  ?>
  <button id="btnDeleteCookies" style="display:none;" onclick="deletecookie('checkout_session_id');">DELETE COOKIE</button>
    <?php if(!isset($_COOKIE["checkout_session_id"])){echo "<div id='divSignUp' style='margin-left:auto;margin-right:auto;background-color:green;color:white;width:500px;border-radius: 5px;'>Your FilterManager app will be ready for use as soon as you supply the information needed below and click Next.</div>";}else{echo  "<div id='divSignUp' style='margin-left:auto;margin-right:auto;background-color:red;color:white;width:500px;border-radius: 5px;'>Your setup for FilterManager.net was already completed</div><div style='background-color: black;color:white;font-size;2em;display:block;justify-content:center;text-align:center;' id='divSetup_complete'>Account setup complete.<br>Very important:Copy the following information down and store for you records. </div>
    <table id='tblSetup_complete2' style='display:block;border-collapse: collapse;color:green;'><tr style='border: 1px solid aqua'><td>Your FilterManager app address is: </td><td>filtermanager.net
    </td><tr><td>Your company is registered under the name:</td><td id='tdBusinessName2' style='color:white;'>". $_COOKIE["app_admin_password"] ."</td></tr>
    <tr><td>Your username for access is:</td><td id='tdAppAdminName2'>".$_COOKIE["app_admin_name"] ."</td></tr><tr><td>Your password is:</td><td id='tdPassword2'>". $_COOKIE["app_admin_password"] . "</td></tr></table>
    <div id='divEmail2' style='display:block;text-align:center;width:600px;height:fit-content;'> An email has also been sent to the email provided with this information.<br>If you don't see it in your email in box check your spam box.<br>
    Click <a href='https://www.filtermanager.net'>HERE</a> to log into your app.</div>";}?>
  <button onclick="deleteAllCookies();" style="display:block;">delete all</button>
    <div id="divProcessing" style="display:none;justify-content: center;text-align:center;">Your app is being created now. Please wait a moment...</div><br><img src="images/processing.gif" id="gifProcessing" style="display:none;">
<table style="margin-left:auto;margin-right:auto;" id="tblSignUp">
  <tr><th>FilterManager.net - Sign Up</th><th><p id="pErrorMessage" style="display:none;font-size:.75em;color:red;">Username already exists. Please choose a different one.<p></th></tr>
  <tr><td><form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmSetup">
  <input type="hidden" name="checkout_session_id" value="<?php echo $CheckOutSessionID ?>">
    <label for="business_name">Your business name:</label></td>
    <td>
    <input type="text" name="business_name" id="business_name" onkeyup="checkBnameAvailability(this.value);validateForm();" required ></td></tr>
    <input type="hidden" name="action" value="sign_up">
    <tr><td>
    <label for="app_admin_name">App Administrator Name (Max 4 Characters):</label></td><td>
    <input type="text" maxlength="4" name="app_admin_name" id="app_admin_name" maxlength="4" required onkeyup="checkUsernameAvailability(this.value);validateForm();"></td><tr><td><div style="color:aqua;font-size:.75em;font-weight:italic;">(App admin: person who adds/removes other users)</td><td></td></tr>
    <td>Password</td><td><input type="password" name="app_admin_password" id="app_admin_password" onkeyup="validateForm('password');"  required></td><tr><td></td><td></td></tr>
    <tr><th>Contact Information</th><th></th></tr>
    <tr><td>Email address:</td><td><input type="text" name="email" value="<?php echo $Email ?>" id="email" onkeyup="validateForm('email');" required></td></tr><tr><td></td><td><button type='button' id='btnSignUp' onclick="if(getJavaCookie('Subscription_done')==''){FinishSetup();}else{alert('Subscription already set');}" disabled>Next</button></td></tr>
  </form></table><div id="ajax_message" style="display:block;"></div>

  <div style="background-green: black;color:white;font-size;2em;display:none;justify-content:center;text-align:center;" id="divSetup_complete">Account setup complete.<br>Very important:Copy the following information down and store for you records. </div>
    <table id="tblSetup_complete" style="display:none;border-collapse: collapse;color:green;"><tr style="border: 1px solid aqua"><td>Your FilterManager app address is: </td><td>filtermanager.net
    </td><tr><td>Your company is registered under the name:</td><td id="tdBusinessName" style="color:white;"></td></tr>
    <tr><td>Your username for access is:</td><td id="tdAppAdminName"></td></tr><tr><td>Your password is:</td><td id="tdPassword"></td></tr></table>
    <div id="divEmail" style="display:none;text-align:center;width:600px;height:fit-content;"> An email has also been sent to the email provided with this information.<br>If you don't see it in your email in box check your spam box.<br>
    Click <a href="https://www.filtermanager.net">HERE</a> to log into your app.</div>
<?php
}

?>
<script defer>
const businessNameTextbox = document.getElementById("business_name");
const tdBusinessName = document.getElementById("tdBusinessName");
businessNameTextbox.addEventListener("input", (event) => {
  tdBusinessName.textContent = event.target.value;
});

const AppAdimnNameTextbox = document.getElementById("app_admin_name");
const tdAdminName = document.getElementById("tdAppAdminName");
AppAdimnNameTextbox.addEventListener("input", (event) => {
  tdAdminName.textContent = event.target.value;
});

const PasswordTextbox = document.getElementById("app_admin_password");
const tdPassword= document.getElementById("tdPassword");
PasswordTextbox.addEventListener("input", (event) => {
  tdPassword.textContent = event.target.value;
});

  </script>
<script>
document.addEventListener("keydown", (event) => {
  if (event.ctrlKey && event.key === "b") {
    const button = document.getElementById("btnDeleteCookies"); 
    button.style.display = "block"; 
  }
});
</script>
</body>
</html>
<?php
 $CreatedSuccess="";
$Action ="";
$BusinessName = "";
$AppAdminName = "";
 $AppAdminPassword = "";
if(isset($_POST['app_admin_name'])) {$AppAdminName = $_POST['app_admin_name'];}
if(isset($_POST['app_admin_password'])) {$AppAdminPassword = $_POST['app_admin_password'];}
if(isset($_POST['action'])) {$Action = $_POST['action'];}


  