<?php
if(session_id() == ''){
      session_start();
   }
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include('dbMirage_connect.php');
?>
<html>
    <title>Filter Manager- change password</title>
    <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body style="background-color:green;">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<style>
    table {
    margin-left:auto; 
    margin-right:auto;
    margin-top:100px;
  }
  </style>


<?php
$UserVerified="false";
$Action="none";
if(isset($_POST['action'])){$Action = $_POST["action"];}
//-------------------USERNAME-------------------
$UserName="";
if(isset($_COOKIE["cookie_username"]))
   {  
      $UserName=$_COOKIE["cookie_username"];
   }
   //--------------PASSWORD--------------
   $Password="";
if(isset($_POST["password"]))
   {  
      $Password=$_POST["password"];
   }
   if(isset($_POST["new_password"]))
   {  
      $NewPassword=$_POST["new_password"];
   }
?>
   <script>
function CheckPasswords(){
   p1=document.getElementById("password").value;
   p2=document.getElementById("password2").value;
   if(p2 == p1){
      var form = document.createElement("form");
      form.setAttribute("method", "post");
      form.setAttribute("action", "<?php echo $_SERVER["SCRIPT_NAME"] ?>");
      var F1 = document.createElement("input");
      F1.setAttribute("type", "hidden");
      F1.setAttribute("name", "username");
      F1.setAttribute("value", "<?php $UserName ?>");
      var F2 = document.createElement("input");
      F2.setAttribute("type", "hidden");
      F2.setAttribute("name", "action");
      F2.setAttribute("value", "update_password");
      var F3 = document.createElement("input");
      F3.setAttribute("type", "hidden");
      F3.setAttribute("name", "new_password");
      F3.setAttribute("value", p1);
      form.appendChild(F1);
      form.appendChild(F2);
      form.appendChild(F3);
      document.getElementsByTagName("body")[0].appendChild(form);
      form.submit();
      }
      else
      {
         document.getElementById("divPasswordsDontMatch").style.display="block";
      }
}
</script>
<?php

if($Action === "update_password"){
         $jsonString = file_get_contents("table_users.json");
          $data = json_decode($jsonString, true);
          foreach ($data as &$object) {
              if ($object['user_name'] === $_SESSION["user_name"]) {
                  $object['password'] = $NewPassword;
              }
          }
          $jsonString = json_encode($data, JSON_PRETTY_PRINT);
          file_put_contents('table_users.json', $jsonString);
          echo "<div style='font-size:1em;text-align:center;background-color:black;color:white'>".$UserName. " your password was successfully changed.</div>";
}
?>
<table><?php
if(strcmp($Action, "none")==0)
{
    ?>
<tr><td>
  <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmMultiUse">
  <input type="hidden" name="username" value="<?php echo $UserName ?>">
  <div style="color:white;font-weight:bold;">Verify identity. Enter current password:</div>
</td></tr>
<tr><td>
  <div class="input-group mb-3">
  <input type="text" class="form-control" placeholder="Password" name="password" aria-label="Username" aria-describedby="basic-addon1">
  <button name="action" value="verify_user" id="txtpassword" class="btn btn-success" id="basic-addon1" onclick="this.form.submit();">Submit</button>
</div>
  </div>
  </form></td></tr>
  <?php
}
//VERIFY USER FIRST
if(strcmp($Action,"verify_user")==0){
      $jsonString = file_get_contents('table_users.json');
      $data = json_decode($jsonString, true);
      foreach ($data as &$object) {
         if ($object['user_name'] === $_SESSION["user_name"] && $Password === $object['password']) {
            $UserVerified = "true";
            $UserEmail = $object['Email'];
         }
      }
   }

    //USER VERIFIED CREATE NEW PASSWORD
    if(strcmp($UserVerified, "true")==0)
         {
            ?>
            <tr>
            <td style='text-align:center;color:aqua;background-color:black;'>User Verified. Enter New Password:</td></tr>
            <tr><td><div id="divPasswordsDontMatch" style="background-color: antiquewhite;color:red;display:none;">Passwords do not match</div>
            <div class="input-group mb-3">
            <input type="password" class="form-control" id="password" placeholder="enter new password here" aria-label="Username" aria-describedby="basic-addon1" onkeyup="document.getElementById('divPasswordsDontMatch').style.display='none';">
            <input type="password" class="form-control" id="password2"  onkeyup="document.getElementById('divPasswordsDontMatch').style.display='none';"placeholder="re-enter new password here" aria-label="Username" aria-describedby="basic-addon1" name="new_password">
            <input type="hidden" name="username" value="<?php echo $UserName ?>" id="hdnUserName">
            <input type="hidden" name="action" value="update_password" id="hdnAction">
            <div class="input-group-prepend"></form>
            <button class="btn btn-success" onclick="CheckPasswords();">Submit</button>
            </div>
            </div></td></tr>
            </td></tr>
            <?php
               }
//USER NOT VERIFIED TRY AGAIN
if(strcmp($Action,"verify_user")==0 && strcmp($UserVerified, "false") == 0){
                ?>
                <tr><td style='text-align:center;color:red;background-color:black;'>Invalid password. Try again.</td></tr>
                <tr><td>
                <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmMultiUse2">
                <input type="hidden" name="username" value="<?php echo $UserName ?>">
                <div style="color:white;font-weight:bold;">Verify identity. Enter current password:</div>
                </td></tr>
                <tr><td>
                <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Password" name="password" aria-label="Username" aria-describedby="basic-addon1">
                <button name="action" value="verify_user" id="txtpassword" class="form-control" id="basic-addon1" onclick="this.form.submit();">Submit</button>
                </div>
                </div>
                </td></tr>
                </td></tr></table>
                <?php
               }