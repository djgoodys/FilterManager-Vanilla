<?php
if(session_id() == ''){
      session_start();
   }

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

?>
<html>
    <title>Filter Manager- change email</title>
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
if(isset($_SESSION["user_name"]))
   {  
      $UserName=$_SESSION["user_name"];
   }
   //--------------PASSWORD--------------
   $Password="";
if(isset($_POST["password"]))
   {  
      $Password=$_POST["password"];
   }
//----------------NEW EMAIL----------------
$NewEmail="";
if(isset($_POST["email"]))
   {  
      $NewEmail=$_POST["email"];
   }
?>
   
<?php
if(strcmp($Action, "update_email")==0)
            {
       $jsonString = file_get_contents('table_users.json');
          $data = json_decode($jsonString, true);
          foreach ($data as &$object) {
              if (isset($object['user_name']) && $object['user_name'] === $_SESSION["user_name"]) {
                  // Change the value of the specified key
                  $object['Email'] = $NewEmail;
              }
          }
          $jsonString = json_encode($data, JSON_PRETTY_PRINT);
          file_put_contents('table_users.json', $jsonString);
          echo "<div style='font-size:1em;text-align:center;background-color:black;color:white'>".$UserName. " your email was successfully changed to ".$NewEmail.".</div>";
         }
?>
<table>
    <?php
if(strcmp($Action, "none")==0)
{
    ?>
    <tr><td>
    <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
    <input type="hidden" name="username" value="<?php echo $UserName ?>">
    <div style="color:white;font-weight:bold;">Verify identity. Enter current password:</div>
    </td></tr>
    <tr><td>
    <div class="input-group mb-3">
    <input type="text" class="form-control" placeholder="Password" name="password" aria-label="password" aria-describedby="basic-addon1">
    <input type="submit" name="action" value="verify_user" id="txtpassword" class="btn btn-success" id="basic-addon1" value="Submit">
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
    if ($object['password'] === $Password && $object['user_name'] === $_SESSION["user_name"]) {
        $UserVerified = "true";
    }
}

}

    //USER VERIFIED , CHANGE EMAIL ADDRESS
    if(strcmp($UserVerified, "true")==0)
         {
            ?>
            <tr>
            <td style='text-align:center;color:white;background-color:black;'>User Verified. Enter new email address:</td></tr>
            <tr><td><form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
            <input type="hidden" name="username" value="<?php echo $UserName ?>" id="hdnUserName">
            <input type="hidden" name="action" value="update_email" id="hdnAction">
            <div class="input-group mb-3">
            <input type="text" name="email" value="" class="form-control" placeholder="enter new email here" aria-label="Email" aria-describedby="basic-addon1">
            <div class="input-group-prepend">
            <button type="submit" class="btn btn-success">Submit</button>
            </form>
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
                <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id="frmMultiUse">
                <input type="hidden" name="username" value="<?php echo $UserName ?>">
                <div style="color:white;font-weight:bold;">Verify identity. Enter current password:</div>
                </td></tr>
                <tr><td>
                <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Password" name="password" aria-label="Username" aria-describedby="basic-addon1"><button type="submit" name="action" value="verify_user" class="form-control" id="basic-addon1">Submit</button>
                </div>
                </div>
                </td></tr>
                </td></tr></table>
                <?php
               }