<?php
if(session_id() == ''){
      session_start();
   }
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


include 'functions.php';
include 'fm.css';
include 'CustomConfirmBox.css';
//print_r($_POST);

$UpdateMessage ="";
$Message="";
$Action = "";
$UserName="";
$AddMessage = "";
if(isset($_POST["action"])){$Action = $_POST["action"];}
if(isset($_POST["new_user_name"])){$NewUserName = $_POST["new_user_name"];}
if(isset($_POST["user_to_edit"])){$UserToEdit = $_POST["user_to_edit"];}
if(isset($_POST["email"])){$NewUserEmail = $_POST["email"];}
if(isset($_POST["edit_email"])){$EditUserEmail = $_POST["edit_email"];}
if(isset($_POST["new_user_password"])){$NewUserPassword = $_POST["new_user_password"];}
if(isset($_POST["edit_password"])){$EditUserPassword = $_POST["edit_password"];}
if(isset($_POST["admin"])){$NewUserAdmin = $_POST["admin"];}
if(isset($_POST["edit_admin"])){$EditUserAdmin = $_POST["edit_admin"];}
if(isset($_POST["user_to_delete"])){$UserToDelete = $_POST["user_to_delete"];}

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
<html>
<title>Admin console</title>
<head>
<script>
function DoesUserExist(thisuser){
console.log("starting DoesUserExist thisuser="+thisuser);
var users = document.getElementById('slctEditUser');
AllUsers=document.getElementById("divAllUsers").innerHTML.toLowerCase();
thisuser="["+thisuser + "]";
if(thisuser.length >= 2){
    if(AllUsers.includes(thisuser))
        {
            document.getElementById("divNewUserExists").style.display="block";
            document.getElementById("btnSubmit").disabled=true;
        }
        else
        {
            document.getElementById("divNewUserExists").style.display="none";
        }
    }
     
}
    </script>
    <script>
function getUserData(uname){
    document.getElementById("EditPassword").value = "";
    document.getElementById("EditEmail").value = "";
    document.getElementById("rdoEditAdminNo").checked = true;
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'table_users.json', true);
    xhr.onreadystatechange = function() {
    if (xhr.readyState == 4 && xhr.status == 200) {
        var data = JSON.parse(xhr.responseText);
        var row = [];
        for (var i = 0; i < data.length; i++) {
            if (data[i].user_name == uname) {
                row.push(data[i]);
            }
        }
        console.log(row[0].user_name);
        document.getElementById("EditPassword").value = row[0].password;
        document.getElementById("EditEmail").value = row[0].Email;
        if(row[0].admin == "yes"){document.getElementById("rdoEditAdminYes").checked = true;}
        //if(row[0].admin == "no"){document.getElementById("rdoEditAdminNo").checked = false;}
    }
};
xhr.send();
}
</script>
<script>
function myConfirmBox(message, frmID) {
    let element = document.createElement("div");
    const list = element.classList;
    list.add("box-background:red;");
    element.innerHTML = "<div class='box' style='z-index:1;opacity: 1;margin-left:auto;margin-right:auto;width:500px;height:300px;top:5px;background-color:gold;'>"+ message + "<button id='trueButton' class='btn green'>Yes</button><button id='falseButton' class='btn red'>No</button></div>";
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
        });
    })
}
</script>
<script>
function confirm_delete(user)
{
    var userSelect = document.getElementById("slctUser");
  var optionsLength = userSelect.options.length;
  // Check if there's only one option (including the default option)
  if (optionsLength <= 2) {
    document.getElementById("alertbox").style.display="flex";
  }
  else
  {
    myConfirmBox("Permentaly remove "+user+"?", "frmDelete");
  }
}
</script>
<style>

th{
    background-color:green;
    color:white;
}
.row {
  display: flex;
  margin-left:-5px;
  margin-right:-5px;
}

.column {
  flex: 50%;
  padding: 5px;
}
/* The alert message box */
.alert {
  padding: 20px;
  background-color: #f44336; /* Red */
  color: white;
  margin-bottom: 15px;
  display:none;
}

/* The close button */
.closebtn {
  margin-left: 15px;
  color: white;
  font-weight: bold;
  float: right;
  font-size: 44px;
  line-height: 20px;
  cursor: pointer;
  transition: 0.3s;
}

/* When moving the mouse over the close button */
.closebtn:hover {
  color: black;
}
</style>
</head>
<body>
<div class="alert" id="alertbox">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
  &nbsp;&nbsp;<font-size="2em">You are the only admin. You can not be deleted.</font>
</div>
<?php
if(strcmp($Action,"delete_user")==0)
     {
        $jsonString = file_get_contents('table_users.json');
        $data = json_decode($jsonString, true);
        $index = -1;
        foreach ($data as $key => $object) {
            if ($object['user_name'] === $UserToDelete) {
                $index = $key;
                break;
            }
        }
        if ($index > -1) {
            unset($data[$index]);
            $data = array_values($data);
        }

        $jsonString = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents('table_users.json', $jsonString);
        $UserToDelete . " was removed successfully";
     }

if(strcmp($Action,"updatenow")==0)
     {
        $jsonString = file_get_contents('table_users.json');
        $data = json_decode($jsonString, true);
        foreach ($data as &$object) {
            if ($object['user_name'] === $UserToEdit) {
                // Change the value of the specified key
                $object['password'] = $EditUserPassword;
                $object['Email'] = $EditUserEmail;
                $object['admin'] = $EditUserAdmin;
            }
}
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('table_users.json', $jsonString);
 }

if($Action == "Add user"){
    $id = getUniqueID();
    $jsonString = file_get_contents('table_users.json');
    $data = json_decode($jsonString, true);
    $newUser = array(
        '_id' => $id,
        'user_name' => $NewUserName,
        'password' => $NewUserPassword,
        'Email' => $NewUserEmail,
        'admin' => $NewUserAdmin,
        'field2' => "",
        'field3' => "",
        'font_family' => "",
        'font_size' => "",
        'theme' => "",
        'backup_folder' => $_SESSION["backup_folder"],
        "clickme" => "false",
        "didYouKnow" => [
            "dont_show",
            "dont_show",
            "dont_show",
            "dont_show",
            "dont_show",
            "",
            "",
            "",
            "",
            ""
        ]
    );
    $data[] = $newUser;
    $jsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('table_users.json', $jsonString);
    echo $NewUserName . " was added successfully.";
}


//Create a hidden container of all user names;
$Users=array();
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
foreach ($data as $object) {
    if(isset( $object['user_name']) && $_SESSION["backup_folder"] === $object["backup_folder"]) {$Users[] = $object['user_name'];}
}
//print_r($Users);

echo "<div id='divAllUsers' style='display:none;color:red;'>";
 foreach ($Users as $value) 
    {
      echo "[".$value."]";
    }
echo "</div>"; 
?>
<div style="text-align:center;font-size:2em;font-weight:bold;width:100%;height:2%;background-color:blue;color:white;">ADMIN CONTROL PANEL</div>
<div class="row">
  <div class="column">
<form id="frmNewUser" autocomplete="off" method="post" action="<?php ECHO $_SERVER["SCRIPT_NAME"] ?>">
<table style="border:2px solid green" id="tblAddNewUser">
<th></th><th>ADD NEW USER</th>
<tr><td><div id='divNewUserName'>New User Name <p  style="font-size:.5em;color:red;">(User name must be at least 2 characters)</p></td>
<td >
    <input type="text" id="newuser" name="new_user_name" onkeyup="if(this.value.length > 1)
        {document.getElementById('btnSubmit').disabled=false;}
        else
        {document.getElementById('btnSubmit').disabled=true;};DoesUserExist(this.value);">
        </div>&nbsp;<div id="divNewUserExists" style="display:none;color:red;">User already exists

        </td></tr>
<tr><td><div id="divNewUserPassord">New User Password</div></td><td><input type="password" id="password" name="new_user_password"><img src="images/showpassword.png" style="height:1vw;width:auto;" onclick="showPassword('password');"></td></tr>
<tr><td><div id="divEmail">Email</div></td><td><input type="text" id="email" name="email"</td><tr>
<tr><td style="padding-left:auto;padding-right:auto;">Give this user admin rights?<br><input type="radio" name="admin" value="no"  id="rdoAdminNo" checked><label for="rdoAdminNo">NO</label><input type="radio" name="admin" value="yes" id="rdoAdminYes"><label for="rdoAdminYes">YES</label></td><td>(can add new users)</td>
<tr><td style="color:green;"><?php echo $AddMessage ?></td><td><button id="btnSubmit" disabled onclick="checkForm('frmNewUser');" >Submit</button><input type="hidden" name="action" value="Add user"></td></tr>
</table>
</form>
  </div>
  <div class="column">
<form id="frmEditUser" method="post" action="<?php ECHO $_SERVER["SCRIPT_NAME"] ?>">
<table style="border:2px solid green">
<th></th><th>EDIT USER</th>
<tr><td>User Name</td><td><input type="hidden" name="action" value="edit_user">
<select id="slctEditUser" name="user_to_edit" onchange="getUserData(this.options[this.selectedIndex].text);if(this.options[this.selectedIndex].text=='Select user to edit'){document.getElementById('btnSubmitEdit').disabled=true;}else{document.getElementById('btnSubmitEdit').disabled=false;}">
<option>Select user to edit</option>
                              <?php
                              foreach ($Users as $value) 
                                 {
                                    echo "<option value='".$value."'>".$value."</option>";
                                 }
                              echo "</select></td>";
                                ?></td><div style="display:none;" id="divEditUserName">Select user to edit</div></tr>
<tr><td><div id="divEditUserPassord">User Password</div></td><td><input type="password" id="EditPassword" name="edit_password"><img src="images/showpassword.png" style="height:1vw;width:auto;" onclick="showPassword('EditPassword');"></td></tr>
<tr><td><div id="divEditEmail">Email</div></td><td><input type="text" id="EditEmail" name="edit_email"</td><tr>
<tr><td style="padding-left:auto;padding-right:auto;">Give this user admin rights?<br><input type="radio" name="edit_admin" value="no"  id="rdoEditAdminNo" checked><label for="rdoEditAdminNo">NO</label><input type="radio" value="yes" name="edit_admin" id="rdoEditAdminYes"><label for="rdoEditAdminYes">YES</label></td><td>(can add new users)</td>
<tr><td style="color:green;"><?php echo $UpdateMessage ?></td><td><input type="button" id="btnSubmitEdit" disabled onclick="checkForm('frmEditUser');" name="action" value="Update user"><input type="hidden" name="action" value="updatenow"></td></tr>
</table>
</form>
  </div>
  <div class="column">
<table style="border:2px solid green;" id="tblRemoveUser">
<script>
document.write(document.getElementById("tblAddNewUser").width);
document.getElementById("tblRemoveUser").style.width = document.getElementById("tblAddNewUser").width;
</script>
<tr><th>REMOVE USER</th></tr>
<tr><td>
<?php if($Action == "delete_user"){echo $Message;} ?>
<form id="frmDelete" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
<input type="hidden" name="action" value="delete_user">
<select id="slctUser" name="user_to_delete" onchange="confirm_delete(this.options[this.selectedIndex].text);">
<option>Select user to remove</option>
                              <?php
                              foreach ($Users as $value) 
                                 {
                                    echo "<option value='".$value."'>".$value."</option>";
                                 }
                              echo "</select></td>";
                                ?>
</form>
</td></t></table>
</div></div>
<script>
function showPassword(whatTextBox){
if(whatTextBox =="password"){
input = document.getElementById("password");
}
else
{
input = document.getElementById("EditPassword");
}
// When an input is checked, or whatever...
if (input.getAttribute("type") == "password") {
  input.setAttribute("type", "text");
} else {
  input.setAttribute("type", "password");
}
}

</script>

<script>
function checkForm(whatForm){
//NEW USER FORM
    if(whatForm == 'frmNewUser'){
    var searchValue = document.getElementById("newuser").value.toLowerCase();
    var error=false;
    //all fields filled out;
    if(document.getElementById("newuser").value.trim()==""){
    document.getElementById("divNewUserName").style.color="red";
    error=true;
    }
    if(document.getElementById("password").value.trim()==""){
    document.getElementById("divNewUserPassord").style.color="red";
    error=true;
    }
    if(document.getElementById("email").value.trim()==""){
    document.getElementById("divEmail").style.color="red";
    error=true;
    }
    //IS IT VALID EMAIL FORMAT?
    let result = document.getElementById("email").value.includes("@");
    if(result==false){
    document.getElementById("divEmail").style.color="red";
    document.getElementById("divEmail").innerHTML = "NOT A VALID EMAIL ADDRESS";
    error=true;
    }

    if (searchValue != ""){
    if(checkWhitespace(searchValue))
    {
    //alert("white spaces exist"); 
    }
    else
    {
    AllUsers=document.getElementById("divAllUsers").innerHTML.toLowerCase();

    if(AllUsers.includes(searchValue))
        {
            document.getElementById("divNewUserExists").style.display="block";
            error=true;
        }
        else
        {
            document.getElementById("divNewUserExists").style.display="none";
        }
    }
    }
alert(error);
    if(error == false){document.getElementById("frmNewUser").submit();}
    }

//EDIT USER FORM
 if(whatForm == 'frmEditUser'){
    var uName = document.getElementById("slctEditUser");
    var text = uName.options[uName.selectedIndex].text;
    var error=false;
    //all fields filled out;
    if(text=="Select user to edit"){
    document.getElementById("divEditUserName").style.color="red";
    error=true;
    }
    if(document.getElementById("EditPassword").value.trim()==""){
    document.getElementById("divEditUserPassord").style.color="red";
    error=true;
    }
    if(document.getElementById("EditEmail").value.trim()==""){
    document.getElementById("divEditEmail").style.color="red";
    error=true;
    }
    //IS IT VALID EMAIL FORMAT?
    let result = document.getElementById("EditEmail").value.includes("@");
    if(result==false){
    document.getElementById("divEditEmail").style.color="red";
    document.getElementById("divEditEmail").innerHTML = "NOT A VALID EMAIL ADDRESS";
    error=true;
    }

    if(error == false){document.getElementById("frmEditUser").submit();}
    }
}
function checkWhitespace(str) { 
    return /\s/.test(str); 
} 

</script>

</body>