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
  if (isset($_GET["session_id"])){$CheckOutSessionID = $_GET["session_id"];}

if(!isset($CheckOutSessionID)){$checkout_sessionID=false;}

$dataFile = file_get_contents("table_users.json");
$dataArray = json_decode($dataFile, true); // Decode JSON into an array
$checkout_sessionID = false;
foreach ($dataArray as $entry) {
    if (isset($entry["misc"]["app_admin_name"]) && $entry["misc"]["app_admin_name"] == $CheckOutSessionID && $CheckOutSessionID != "") {
        //echo "Subscription ID found in the data!";
        $checkout_sessionID = true;
        $SetupComplete = "true";
        echo "<div style='background-color:red;color:white;width:fit-content;margin-right:auto;margin-left:auto;'>Thank you for signing up for FilterManager.net. Your account was already set up. Check your email for details.</div>";
        break; 
    }
}
  
?>
  <style>
    .tblSignUp {
      width:33%;
      margin: 0 auto;
      margin-top:0px;
      display:table;
      border: 1px solid black; 
      margin-top: 0px;
    }
    .tblSignUp th, td{
      font-size:1.5em;
      border: 1px solid gray; /* Border for individual cells */
  padding: 5px; /* Add some padding for better spacing */
    }
    .show-password-icon {
      width:30px;
      height:auto;
      vertical-align: middle;
    }

    .redBorder {
      border: 4px solid red;
    }
     .error-message {
      color: red;
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
function validateForm(elementId) {
  if(elementId){
    switch (elementId) {
    case "app_admin_name":
      if(document.getElementById(elementId).value.length > 0){
         checkUsernameAvailability(document.getElementById(elementId).value);
        }
        else
        {
          showError('app_admin_name', 'User name can not be blank');
        }
      break;
    case "delete":
      console.log("Deleting item...");
      // Your code to delete item
      break;
    case "edit":
      console.log("Editing data...");
      // Your code to edit data
      break;
    default:
      
  }
  }
  else 
  {
   const errorElements = document.querySelectorAll(".error-message");
  for (const errorElement of errorElements) {
    errorElement.remove();
  }
  var UserNameOk = true;
  var EmailOK = true;
  var PassWordok = true;
  var BnameOk  = true;
  var Elem = "";
  var Message = "";
  document.getElementById("business_name").className="normalBorder";
  document.getElementById("app_admin_name").className="normalBorder";
  document.getElementById("app_admin_password").className="normalBorder";
  document.getElementById("email").className="normalBorder";

  if(document.getElementById("app_admin_name").value.length > 0 && document.getElementById("app_admin_password").value.length > 0 && document.getElementById("business_name").value.length > 0 && document.getElementById("email").value.length > 0){
  }
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
  console.log("email valid index="+email.indexOf(" . "));
  if(isEmailValid < 0)
  {
      showError("email", "Not a valid email address. ' . ' is required.");
      EmailOK = false;
    }
if(EmailOK == true){removeError("email");}
  // Function to validate username
    var username = document.getElementById("app_admin_name").value.trim();
    element = document.getElementById("app_admin_name");
    // Check if username is empty
    if (username.length == 0) {
      showError("username", "User name is required.");
      UserNameOk = false;
    }

  // Function to validate password
  var password = document.getElementById("app_admin_password").value.trim();
    if (password == "" || password.length == 0) {
      showError("app_admin_password", "Password is required.");
      PassWordok = false;
    }

  if (password.indexOf(" ") > -1) {
      showError("app_admin_password", "Password can not contain empty spaces.");
      PassWordok = false;
    }

    //validate business name
  if(document.getElementById("business_name").value.length == 0){
    showError("business_name", "Business name can not be blank.");
    BnameOk = false;
  }

    if(UserNameOk == true && EmailOK == true && PassWordok == true && BnameOk == true){
      FinishSetup();
    }
  }
}
</script>
<script>
function removeError(elementId){
  targetElement = document.getElementById(elementId);
  const errorDiv = targetElement.parentNode.querySelector(".error-Message");
  
    if (errorDiv) {
      errorDiv.remove();
    }
    //targetElement.className="redBorder";
    // Remove existing error message for this element
    if (errorDiv) {
      errorDiv.remove();
      targetElement.style.border="1px inset rgb(118, 118, 118)";
    }
}
function getBorderStyle(element) {
  const computedStyle = window.getComputedStyle(element);

  // Get individual border properties
  const borderWidth = computedStyle.getPropertyValue('border-width');
  const borderStyle = computedStyle.getPropertyValue('border-style');
  const borderColor = computedStyle.getPropertyValue('border-color');

  // You can return an object with all properties or combine them as needed
  return borderWidth + " " + borderStyle + " " + borderColor;

}



function showError(elementId, errorMessage) {
  const targetElement = document.getElementById(elementId);
  if (targetElement) {
    if (errorMessage) {
      const newErrorDiv = document.createElement("div");
      newErrorDiv.classList.add("error-message");
      newErrorDiv.textContent = errorMessage;
      targetElement.classList.add("redBorder");
      targetElement.parentNode.insertBefore(newErrorDiv, targetElement.nextSibling);
    }
  }
}

// Example usage: Call the function with the element ID and error message
showError("app_admin_name", "Name already in use. Choose another"); // Example for app_admin_name
showError("your_email_input", "Invalid email format"); // Example for your_email_input (replace with your element ID)

</script>

<script>
function checkBnameAvailability(bname) {
  var bnameInput = bname.replace(" ", "_");
  fetch(`ajax_user_exists.php?action=check_bname&bname=${bnameInput}`)
    .then(response => response.text()) // Assuming the server sends a plain text response
    .then(data => {
      document.getElementById("ajax_message").innerHTML=data; 

      if (data == "available") {
       removeError("business_name");
      } 
      if (data == "unavailable") {
        showError("business_name", "A business with that name already exists. Choose a different name.");
        //showError("bname", "A business with that name already exists. Choose a different name.");
      }
    })
    .catch(error => {
      console.error("Error checking username availability:", error);
      alert("An error occurred while checking business name availability. "+ error);
    });
}

</script>

<script>
function checkUsernameAvailability(username) {
  fetch(`ajax_user_exists.php?action=check_uname&username=${username}`)
    .then(response => response.text()) // Assuming the server sends a plain text response
    .then(data => {
      document.getElementById("ajax_message").innerText=data;
      if (data == "available") {
        removeError("app_admin_name");
      } 
      if(data == "unavailable"){
        showError('app_admin_name', 'Name already in use. Choose another name.');
      }
      if (document.getElementById("app_admin_name").length > 0 && data != "unavailable") {
        removeError("username");
      } 
      if(document.getElementById("app_admin_name").value.length == 1) {
        showError("app_admin_name", "Username can not be blank.");
      }
    })
    .catch(error => {
      console.error("Error checking username availability:", error);
      alert("An error occurred while checking username availability."+error);
    });
}

</script>
</head>
<body style="background-color: white;color:black;text-align: center;" onload="isSignUpDone();">
<div id="ajax_message" style="display:block;"></div>
<script>
  function isSignUpDone(){
    if(localStorage.getItem("app_admin_name") != null)
      {
        document.getElementById("divSignUp").innerText="Your FilterManager app setup is complete.Very important:Copy the following information down and store for you records. You will need it to log into FilterManager.net";
        document.getElementById("divRemoveLocalStorage").style.display="block";
      }
      
      if(localStorage.getItem("app_admin_name") == null)
      {
        document.getElementById("divRemoveLocalStorage").style.display="none";
      }
  }
  </script>
  <script>
    function FinishSetup(){
     document.getElementById("divProcessing").style.display="flex";
     document.getElementById("gifProcessing").style.display="flex";
     document.getElementById("tblSignUp").style.display="none";
      document.getElementById("ajax_message").innerHTML="";
      form = document.getElementById("frmSetup");
      const formData = new FormData(form);

 fetch(`FinishSetup.php`,{
    method: "POST", // Use POST for form data
    body: formData}
 )
    .then(response => response.text()) // Assuming the server sends a plain text response
    .then(data => {
   
            document.getElementById("ajax_message").innerText = data; 
            if (data == "business name already exists") 
              {
                showError("bname", "business name already exists. Please try a different nam")
              } 
              var completeSetup = "";
              if(isValidJSON){
                try {
              const data2 = JSON.parse(data);

              for (const key in data2) 
                {
                  console.log(key+"="+data2[key]);
                  if(key == "setup_complete" && data2[key] == "true"){completeSetup = "now";}
                  localStorage.setItem(key, data2[key]);
                }
              } catch (error) {
    console.error("Error parsing JSON:", error); // Log the error for debugging
  }
            }
              if(completeSetup == "now") 
                {
                  document.getElementById("frmSetup").style.borderColor = "green";
                  document.getElementById("divSignUp").style.backgroundColor = "green";
                  document.getElementById("divSignUp").innerText="Your app setup complete.Very important:Copy the following information down and store for you records.";
                  document.getElementById("tblSignUp").style.display="table";
                  document.getElementById("gifProcessing").style.display="none";
                  document.getElementById("divProcessing").style.display="none";
                  document.getElementById("email").style.display="none";
                  document.getElementById("tdEmail").innerText = document.getElementById("email").value;
                  document.getElementById("tdBusinessName").innerText = document.getElementById("business_name").value; 
                  document.getElementById("tdAppAdminName").innerText = document.getElementById("app_admin_name").value; 
                  document.getElementById("tdPassword").innerText = document.getElementById("app_admin_password").value; 
                  document.getElementById("divRemoveLocalStorage").style="block";
                }
    });
  }
    </Script>
   <script>
    function isValidJSON(jsonString) {
      try {
        JSON.parse(jsonString);
        return true;
      } catch (error) {
        if (error instanceof SyntaxError) {
          return false;
        }
        // Handle other potential errors (optional)
        throw error;
      }
}
</script>
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
<button onclick="localStorage.removeItem('app_admin_name') ;">delete app_admin_name</button>

<?php

if($Action == ""){
  //SUBSCRIPT ID NOT FOUND IN table_users OK TO PROCEED WITH SETUP
  ?>

    <div id='divSignUp' style='margin-left:auto;margin-right:auto;background-color:blue;color:white;width:500px;border-radius: 5px;text-align:center;font-size:1.5em;'>Your FilterManager app will be ready for use as soon as you supply the information needed below and click Next.</div>
    <div id="divProcessing" style="display:none;justify-content: center;text-align:center;">Your app is being created now. Please wait a moment...</div><br><img src="images/processing.gif" id="gifProcessing" style="display:none;margin-left:auto;margin-right:auto;">
    <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmSetup" style="text-align:center;width:fit-content;margin: 0 auto;border:2px blue solid;">
    <table id="tblSignUp">
  <tr><th>FilterManager.net - Sign Up</th><th></th></tr>
  <tr><td>
  <input type="hidden" name="app_admin_name" value="<?php echo $CheckOutSessionID ?>">
    <label for="business_name">Your business name:</label></td>
    <td id="tdBusinessName">
      <script>
      if(localStorage.getItem("app_admin_name") === null)
      {
        document.write("<input type='text' name='BusinessName' id='business_name' onkeyup='if(this.value.length > 0){checkBnameAvailability(this.value);}'>");
      }
      else
      {
        document.write("<div>"+localStorage.getItem("business_name") + "</div>");
      }
      </script></td></tr>
    <input type="hidden" name="action" value="sign_up">
    <tr><td>
    <label for="app_admin_name">Login name :</label></td>
    <td id="tdAppAdminName">
      <script>
      if(localStorage.getItem("app_admin_name") == null)
      {
        const inputElement = document.createElement("input");
        inputElement.type = "text";
        inputElement.maxlength = 14;
        inputElement.name = "app_admin_name";
        inputElement.id = "app_admin_name";
        const container = document.getElementById("tdAppAdminName");
        container.appendChild(inputElement);
        inputElement.addEventListener("keyup", function() {
        validateForm(document.getElementById("app_admin_name").id);});
      }
      else
      {
        document.getElementById("tdAppAdminName").innerText = localStorage.getItem("app_admin_name");
      }
      </script>
    </td><tr><td><div style="background-color:green;color:white;font-size:.75em;font-weight:italic;">(App admin: user with special rights. i.e. adds/removes other users.)</td><td id="tdAppAdminName"></td></tr>
    <td>Password</td><td id="tdPassword">
      <script>
  if(localStorage.getItem("app_admin_name") == null)
    {
      const inputElement = document.createElement("input");
        inputElement.type = "password";
        inputElement.maxlength = 14;
        inputElement.name = "app_admin_password";
        inputElement.id = "app_admin_password";
        const container = document.getElementById("tdPassword");
        container.appendChild(inputElement);
        const passwordInput = document.getElementById("app_admin_password"); // Replace with your password input ID

        // Create the image element
        const passwordVisibilityImage = document.createElement("img");
        passwordVisibilityImage.src = "images/showpassword.png";
        passwordVisibilityImage.classList.add("show-password-icon"); // Add a CSS class for styling

        // Optional: Toggle password visibility on click
        passwordVisibilityImage.addEventListener("click", function() {
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
        } else {
          passwordInput.type = "password";
        }
      });

      const passwordContainer = passwordInput.parentElement; 
      passwordContainer.appendChild(passwordVisibilityImage);

    }
    else
    {
      document.getElementById("tdPassword").innerText = localStorage.getItem("app_admin_password");
    }
    </script>
    </td></tr>
    <tr><th>Contact Information</th><th><font size=1em">May be needed for password retrieval </th></tr>
    <tr><td>Email address:</td><td id="tdEmail">
    <script>
  if(localStorage.getItem("app_admin_name") == null)
    {
      const inputElement = document.createElement("input");
        inputElement.type = "text";
        inputElement.maxlength = 50;
        inputElement.name = "email";
        inputElement.id = "email";
        const container = document.getElementById("tdEmail");
        container.appendChild(inputElement);
    }
    else
    {
      document.getElementById("tdEmail").innerText = localStorage.getItem("email");
    }
    </script>
    </td></tr><tr><td></td><td><button type='button' id='btnSignUp' onclick="validateForm();">Next</button></td></tr>
</table>  </form>
      <div id="divRemoveLocalStorage" style="text-align:center;"><button onclick="localStorage.removeItem('app_admin_name');window.location.reload();">Create another filter manager account</button></div>
<?php
}

?>
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


  