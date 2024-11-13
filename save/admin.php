<?php
if(session_id() == ''){
      session_start();
   }
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

//$UsersCon = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);

include 'functions.php';
include 'fm.css';
include 'CustomConfirmBox.css';
include 'dbMirage_connect.php';
//print_r($_POST);

$UpdateMessage ="";
$Message="";
$Action = "";
$UserName="";
$AddMessage = "";
$EditUserAdmin="";
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
?>
<html>
<title>Admin console</title>
<head>
    <script>
        function DoesUserExist(thisuser){
            console.log("starting DoesUserExist");
     var users = document.getElementById('slctEditUser');

     for(i=0; i < users.options.length;i++){
       user = users[i];
       if (thisuser == users.options[i].value) {
           document.getElementById("divNewUserExists").style.display="block";
           document.getElementById("btnSubmit").disabled = true;
       }
       else
       {
        document.getElementById("divNewUserExists").style.display="none";
       }
     }
   }
    </script>
<script>
function getUserInfo(user)
{
    const jUsers = document.getElementById("divAllUsers").innerHTML.split("*");
    console.log("user="+user+ " length="+jUsers.length);
found = false;
document.getElementById("rdoEditAdminNo").checked = true;
for(var i = 0, len = jUsers.length; i < len; i++ ) 
{
    //if( jUsers[i] == user ) 
    //{
        //console.log(jUsers[i]);
        //arThisUser=jUsers[i].split(",");
        auser=jUsers[i].replace("[", "");
        buser=auser.replace("]", "");
        arThisUser=buser.split(",");
        //console.log("userfound:"+arThisUser);

        for(var b = 0, len = arThisUser.length; b < len; b++ ) 
            {
//console.log(arThisUser[b]);
                if(arThisUser[b] == user)
                    {
                     found=true;               
                        mystring=jUsers[i];

                        for(var c = 0; c < mystring.length; c++ ) 
                        {

                            a=mystring.replace("[", "");
                             b=a.replace("]", "");
                            ThisUser=b.split(",");
                            x=0;
                            for(var d = 0; d < ThisUser.length; d++ ) 
                            {
                                x = x + 1;
                                if(found == true)
                                    {
                                    if(x==2){document.getElementById("EditPassword").   value = ThisUser[d];}
                                        if(x==3){document.getElementById("EditEmail").value = ThisUser[d];}
                                        if(x==4){
                                                    console.log("4="+ThisUser[d]);
                                                    if(ThisUser[d] == "yes")
                                                    {
                                                    document.getElementById("rdoEditAdminYes").checked = true;
                                                    }
                                                    else
                                                    {
                                                    document.getElementById("rdoEditAdminYes").checked = false;
                                                    }
                                                x=0;
                                                found = false;
                                                break;
                                                }
                                            }
                                        }
                     }   }
                  
            }
        //break;
    //}
//console.log(jUsers[i].split(","));
var arrUsers = "["+jUsers[i].split(",")+"]";
for (let i = 0; i < arrUsers.length; i++) {
  //console.log(arrUsers[i][0] );
}
}

}
</script>
<script>
function confirm_delete(user)
{
alert("delete");
    if (confirm('Are you sure you want to permentaly delete '+user+'?')) 
        {
          document.getElementById('frmDelete').submit();
        } 
        else 
        {
          alert('delete canceled');
        }
}
</script>
<style>

.row {
  display: flex;
  margin-left:-5px;
  margin-right:-5px;
}

.column {
  flex: 50%;
  padding: 5px;
}
</style>
</head>
<body>
<?php
if(strcmp($Action,"delete_user")==0)
     {
        $query = "DELETE FROM users WHERE user_name='" . $UserToDelete."';";
             if (mysqli_query($UsersCon, $query)) {
                $Message = "<div style='width:100%;background-color:red;text-align:center;color:white;margin:auto;'>".$UserToDelete. " has been deleted.</div>";
             } else {
                 $Message = "<div style='width:100%;background-color:red;text-align:center;color:white;margin:auto;'>Error deleting record." . mysqli_error($UsersCon);
             }
     }
if(strcmp($Action,"updatenow")==0)
     {
     //$query = "UPDATE equipment SET unit_name=?,area_served=?,filter_size=?,filters_due=?,location=?,filter_rotation=?,belts=?,notes=? WHERE _id='".$UnitName."'";
     $query = "UPDATE users SET password='" . $EditUserPassword . "', admin='" . $EditUserAdmin . "',email='" . $EditUserEmail . "' WHERE user_name='" . $UserToEdit. "';";
     if (mysqli_query($UsersCon, $query)) {
        $UpdateMessage =  "<div style='width:100%;background-color:green;text-align:center;color:white;margin:auto;'>".$UserToEdit." updated successfully.";
     } else {
         $UpdateMessage = "<div style='width:100%;background-color:red;text-align:center;color:white;margin:auto;'>Error updating record: " . mysqli_error($UsersCon);
     }
 }

if($Action == "Add user"){
$query = "INSERT INTO users (user_name, password, Email, admin) VALUES ('".$NewUserName."','".$NewUserPassword."','". $NewUserEmail."','". $NewUserAdmin."');";
        if (mysqli_query($UsersCon, $query)) {
            $AddMessage = "<div style='width:100%;background-color:green;text-align:center;color:white;margin:auto;'>New user (".$NewUserName.") was created successfully";
        } else {
            $AddMessage = "<div style='width:100%;background-color:red;text-align:center;color:white;margin:auto;'>Error creating new user: " . mysqli_error($UsersCon);
        }
}
if(isset($_SESSION["admin"])){
if($_SESSION["admin"] == "yes"){
    //echo $_SESSION["user_name"]." you are an admin.";   
    }
    else
    {
    //echo $_SESSION["user_name"]." you are not an admin."; 
    }
}
//Create a hidden container of all user names;
$Users=array();
$varAllUsers="";
$I=0;                          
$sql = "SELECT * FROM users;";
            if ($result = $UsersCon->query($sql)) 
                {
                    while ($row = $result->fetch_assoc()) 
                        {
                            $ThisUser = "[".$row["user_name"].", ".$row["password"].", ".$row["Email"]." ,".$row["admin"]."]*";  
                            $I = $I + 1;
                            $varAllUsers=$varAllUsers . $ThisUser;
                            array_push($Users, $row["user_name"]);                         
                        }
                }
echo "</div>;";
echo "<div id='divAllUsers' style='display:none;'>".$varAllUsers."</div>"; 

?>
<div style="text-align:center;font-size:2em;font-weight:bold;width:100%;height:10%;background-color:blue;color:white;">ADMIN CONTROL PANEL</div>
<div class="row">
  <div class="column">
<form id="frmNewUser" method="post" action="<?php ECHO $_SERVER["SCRIPT_NAME"] ?>">
<table style="border:2px solid green" id="tblAddNewUser">
<th></th><th>Add new user</th>
<tr><td><div id='divNewUserName'>New User Name <p  style="font-size:.5em;color:red;">(User name must be at least 2 characters)</p></td><td><input type="text" id="newuser" name="new_user_name" onkeyup="document.getElementById('divNewUserExists').style.display='none';if(this.value.length >1){document.getElementById('btnSubmit').disabled=false;}else{document.getElementById('btnSubmit').disabled=true;};DoesUserExist(this.value);"></div>&nbsp;<div id="divNewUserExists" style="display:none;color:red;">User already exists</td></tr>
<tr><td><div id="divNewUserPassord">New User Password</div></td><td><input type="password" id="password" name="new_user_password"><img src="images/showpassword.png" style="height:1vw;width:auto;" onclick="showPassword('password');"></td></tr>
<tr><td><div id="divEmail">Email</div></td><td><input type="text" id="email" name="email"</td><tr>
<?php if($_SESSION["admin"] == "none") 
{ 
    echo "<tr><td style='padding-left:auto;padding-right:auto;'>Your are admin by default. <br><input type='radio' name='admin'  id='rdoAdminNo' disabled><label for='rdoAdminNo'>NO</label><input type='radio' name='admin' id='rdoAdminYes' checked ><label for='rdoAdminYes'>YES</label></td><td>admins use this page</td></tr>";
}
else
{
    echo "<tr><td style='padding-left:auto;padding-right:auto;'>Give user admin rights?<br><input type='radio' name='admin'  id='rdoAdminNo' checked><label for='rdoAdminNo'>NO</label><input type='radio' name='admin' id='rdoAdminYes' ><label for='rdoAdminYes'>YES</label></td><td>admins use this page</td></tr>";
}
?>
<tr><td style="color:green;"><?php echo $AddMessage ?></td><td><button id="btnSubmit" disabled onclick="checkForm('frmNewUser');" >Submit</button><input type="hidden" name="action" value="Add user"></td></tr>
</table>
</form>
  </div>
  <div class="column">
<form id="frmEditUser" method="post" action="<?php ECHO $_SERVER["SCRIPT_NAME"] ?>">
<table style="border:2px solid green">
<th></th><th>Edit user</th>
<tr><td>User Name</td><td><input type="hidden" name="action" value="edit_user">
<select id="slctEditUser" name="user_to_edit" onchange="getUserInfo(this.options[this.selectedIndex].text);if(this.options[this.selectedIndex].text=='Select user to edit'){document.getElementById('btnSubmitEdit').disabled=true;}else{document.getElementById('btnSubmitEdit').disabled=false;}">
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
<tr><td style="padding-left:auto;padding-right:auto;">Give this user admin rights?<br><input type="radio" name="edit_admin"  id="rdoEditAdminNo" checked><label for="rdoEditAdminNo" value="no">NO</label><input type="radio" name="edit_admin" id="rdoEditAdminYes" value="yes"><label for="rdoEditAdminYes">YES</label></td><td>admins use this page</td></tr>
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
<tr><th>Remove user</th></tr>
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