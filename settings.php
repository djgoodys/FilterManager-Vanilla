<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
//echo "no session";
  session_start();
}
//echo "session id=".session_id();
$LastBackup="";
$CompanyImage="";
//print_r($_POST);
?>

<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

</head>
<body >
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

</head>
<?php

include 'functions.php';
include 'fm.css';
if(isset($_POST["submit_file"]))
   {

   //echo "Your file=".basename($_FILES["fileToUpload"]["name"]);
$target_dir = "images/";
$OriginalFileName = $target_dir . basename($_FILES["fileToUpload"]["name"]);
//echo "$OriginalFileName=". $OriginalFileName."<br>";
$path = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$ext = pathinfo($path, PATHINFO_EXTENSION);
//echo "ext(".$ext.")<br>";
$FileNameOnly = "co_logo.".$ext;
//echo "FileNameOnly(".$FileNameOnly.")<br>";
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($OriginalFileName,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit_file"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {;
        $uploadOk = 1;
    } else {
        echo "File is not an image.<br>";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($FileNameOnly)) {
  gc_collect_cycles();
if (!unlink($FileNameOnly)) {
    echo ($FileNameOnly ." cannot be deleted due to an error");
}
else {
    echo ("$OriginalFileName has been deleted");
}

    //$uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 10000000) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else 
   {
    $path = $target_dir . basename($_FILES["fileToUpload"]["name"]);
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $FileNameOnly = "co_logo.".$ext;
            $newfilename="images/".$FileNameOnly;
                $imgData = resizeImage($_FILES["fileToUpload"]["tmp_name"], $newfilename,$ext, 300, 300);   
               imagepng($imgData, $newfilename);
            
            $jsonData = file_get_contents("sites/" . $_SESSION["backup_folder"] . "/data.json");
            $data = json_decode($jsonData, true); // Decode JSON string to associative array
          
            $data['misc']['company_image'] = $newfilename;
          

          // Encode the updated data back to JSON string
          $newData = json_encode($data, JSON_PRETTY_PRINT);

          // Write the updated data to the JSON file
          if (file_put_contents('data.json', $newData) === false) {
            throw new Exception('Failed to write data to data.json');
          }

          echo 'Data saved successfully!';    
   }
}

function resizeImage($file, $newFileName, $file_ext, $w, $h, $crop=false) {

    list($width, $height) = getimagesize($file);
    $r = $width / $height;
    if ($crop) {
        if ($width > $height) {
            $width = ceil($width-($width*abs($r-$w/$h)));
        } else {
            $height = ceil($height-($height*abs($r-$w/$h)));
        }
        $newwidth = $w;
        $newheight = $h;
    } else {
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
    }
  
    switch($file_ext){
        case "png":
            $src = imagecreatefrompng($file);
        break;
        case "jpeg":
        case "jpg":
            $src = imagecreatefromjpeg($file);
        break;
        case "gif":
            $src = imagecreatefromgif($file);
        break;
        default:
            $src = imagecreatefromjpeg($file);
        break;
    }
    
    $dst = imagecreatetruecolor($newwidth, $newheight);
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $dst;
}

//----------------ACTION----------------
$Action="";
if(isset($_POST["action"]))
   {  
      $Action=$_POST["action"];
   }
   if(isset($_GET["action"]))
   {  
      $Action=$_GET["action"];
   }
   
   //----------------FONT SIZE CHANGE----------------
$FontSize = "20";
if(isset($_SESSION["font_size"])){$FontSize = $_SESSION["font_size"];}
if(isset($_GET["font_size"]))
   {  
      $FontSize = $_GET["font_size"];
UpdateUserSettings("font_size", $_GET["font_size"], $_SESSION["user_name"]);
echo "<script>top.frames['iframe1'].location.href = 'web_login3.php';</script>";
   }

   //----------------FONT FAMILY CHANGE----------------

     if(isset($_SESSION["font_family"])){$FontFamily = $_SESSION["font_family"];}else{$FontFamily = "Arial, sans-serif";}
   if(isset($_GET["font_family"]))
      {  
         $FontFamily = $_GET["font_family"];
         UpdateUserSettings("font_family", $_GET["font_family"], $_SESSION["user_name"]);
echo "<script>top.frames['iframe1'].location.href = 'web_login3.php';</script>";
      }
    
  
//----------------THEME CHANGE----------------
$Theme="Light-tdPlain";
if(isset($_SESSION["theme"])){$Theme=$_SESSION["theme"];}
if(isset($_POST["theme"]))
   {  
      $Theme = $_POST["theme"];
      UpdateUserSettings("theme", $_POST["theme"], $_SESSION["user_name"]);
echo "<script>top.frames['iframe1'].location.href = 'web_login3.php';</script>";

   }

$CompanyName = "";
//SAVE COMPANY NAME----------------------------------------------------------------
if(strcmp($Action, "save_company_name")==0){
   
   if(isset($_POST["company_name"])){$CompanyName=$_POST["company_name"];}
   //echo "co name=".$CompanyName;
   $query = "UPDATE misc SET company_name='".$CompanyName."' LIMIT 1;";
           if (mysqli_query($con, $query)) {
               echo "<div class='bg-dark text-white text-center'>Company name changed to ".$CompanyName."</div>";
           } else {
               echo "Error Company name: " . mysqli_error($con);
           }
          
   }
//GET DATE OF LAST DATA BACK UP
$sql = "SELECT * FROM misc ORDER BY _id DESC LIMIT 1";
   
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   $LastBackup = $row["last_backup"];  
                   if(is_null($row["company_image"]) == 1)
                   {
                    $CompanyImage = "your_co_logo";
                  }
                  else
                  {
                    $CompanyImage = $row["company_image"];
                  }
                  // echo "CompanyImage=".$CompanyImage."<br>". "after=".$CompanyImage;
                   if(is_null($row["company_name"]) == 1){$CompanyName = "Your company name";}else{$CompanyName = $row["company_name"];}
                }
        }

        //----------------NEW EMAIL----------------
$NewEmail="";
if(isset($_POST["email"]))
   {  
      $NewEmail=$_POST["email"];
   }
   
   //echo "theme=".$_SESSION["theme"];
   ?>

<div style=";height: 100px;width:100%;font-size:3em;text-align:center;">
FILTER MANAGER SETTINGS
</div>
<table class="table" style="width:100%;margin-left:50px;">
    <thead>
      <tr>
        <th style='width:33%;'>FONT SIZE</th>
        <th style='width:33%;'>FONT FAMILY</TH>
        <th style="width:33%;">THEME</th>
      </tr>
    </thead>
    <tbody>
    <tr>
        <td>
<div class="dropdown">
  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php
    switch ($FontSize) {
    case "10":
    echo "Small";
    break;
    case "15":
    echo "Medium";
    break;
    case "20":
    echo "Large";
    break;
    case "25":
    echo "X-Large";
    break;
    }
?>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_size=10">Small</a>
    <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_size=15">Medium</a>
    <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_size=20">Large</a>
<a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_size=25">X-Large</a>
  </div>
</div>
        </td>
        <td>
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php echo $FontFamily ?>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_family=Arial, sans-serif" style="font-family:Verdana, sans-serif;">Arial, sans-serif</a>
    <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_family=Verdana, sans-serif"  style="font-family:Verdana, sans-serif;">Verdana, sans-serif</a>
    <a class="dropdown-item" style="font-family:Andale Mono, monospace;"  href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_family=Andale Mono, monospace">Andale Mono, monospace</a>
 <a class="dropdown-item" style="font-family:Century Gothic;"  href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_family=Century Gothic">Century Gothic</a>
 <a class="dropdown-item" style="font-family:Impact, fantasy;"  href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_family=Impact, fantasy">Impact, fantasy</a>
 <a class="dropdown-item" style="font-family:Brush Script MT', cursive;"  href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_family=Brush Script MT, cursive">Brush Script MT', cursive</a>
<a class="dropdown-item" style="font-family:Franklin Gothic Medium;"  href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?font_family=Franklin Gothic Medium">Franklin Gothic Medium</a>
</div>
         </td>
        <td style="width:33%;">
 

          <div class="dropdown">
          <button class="btn btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php 
              if($Theme == "Light-tdPlain"){echo "Light";}
              if($Theme == "Dark-tdPlain"){echo "Dark";}
           ?>
          </button>
         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <button style="background-color:white-smoke;color:black;" class="dropdown-item" href="#" onclick="document.getElementById('theme').value='Light-tdPlain';document.getElementById('frmTheme').submit();" onmouseover="this.style.backgroundColor='green';" onmouseout="this.style.backgroundColor='white';">Light</button>
          <button class="dropdown-item" style="background-color:black;color:white;"  href="#" onmouseover="this.style.backgroundColor='green';" onmouseout="this.style.backgroundColor='black';"  onclick="document.getElementById('theme').value='Dark-tdPlain';document.getElementById('frmTheme').submit();">Dark</button>
        </div>
      </div>
<form id="frmTheme" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" target="iframe2">
<input type="hidden" name="theme" value="" id="theme">
</form>
        </td>
        
      </tr>      


<?php
$UserVerified = "false";
$Email="";
$UserEmail="";
$Pass_word="";

           if(strcmp($UserVerified, "true")==0 && strcmp("change_email", $Action2) == 0)
               {
                  ?>
                  <tr><td>
                  <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" id='frmChangeEmail'>
                  <input type="hidden" name="username" value="<?php echo $UserName ?>">
                  <div style='color:#f2f2f2;;'>User Verified. Enter New Email:<input type='text' placeholder='new email here' name='email' id='email'>
               <input type="submit" class='btn btn-primary users-style' name='action' value='update_email' value="Submit">
               </form></div></td>
               <?php
               }

            if(strcmp($Action, "update_email")==0)
               {
                  $query = "UPDATE users SET email='" . $NewEmail . "' WHERE user_name='" . $UserName . "';";
                  if (mysqli_query($con, $query)) 
                  {
                      echo "<div style=';color:white'>".$UserName. " your email was successfully has been changed.</div>";
                  }
                  else 
                     echo "<div style=';color:red'>Error updating email: " . mysqli_error($con);
               }
               
            

if(strcmp($Action,"")==0 || strcmp($Action, "save_company_name")==0){
  
   ?>
   <tr><td style="font-weight:bold;">PASSWORD</td><td style="font-weight:bold;">EMAIL</td></tr>
   
  <tr><td style="width:33%;"><a href="ChangePassword.php"><button class="btn btn-primary users-style" id="btnChangePassword" >Change password</button></a>
  
</td><td style="width:33%;"><a href="ChangeEmail.php"><button class="btn btn-primary users-style" id="btnChangeEmail" >Change email</button></a></td>
</tr>
<tr><td style="font-weight:bold;">DATA RESTORE</td><td style="font-weight:bold;">DATA BACK-UP</td></tr>
<tr><TD><A HREF="updateDB.php"><button type="button" class="btn btn-warning users-style">IMPORT UNIT DATA</button></a></td><td><A Href="DataBackUp.php"><button type="button" class="btn btn-info users-style">EXPORT ALL DATA</button></A><br><div style="background-color:black;color:#f2f2f2;font-size:8px;width:fit-content;">(LAST DATA BACK UP PERFORMED ON: <?php echo $LastBackup ?>)</div></td></tr>
<tr><td style="font-weight:bold;">COMPANY NAME</td><td style="font-weight:bold;">SAVE DATA AS CSV FILE</td></tr>
<tr><td class="d-block"><div style="display:flex"><div class="users-style text-white bg-primary" id="divCompanyName" class="bg-primary"><?php echo $CompanyName ?><img id="imgedit" data-toggle="modal" data-target="#exampleModal"  title="edit company name" src="images/edit.png" style="height:3em;width=auto;></div><form id="frmCompanyName" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" style="display: flex;"><input type="text" name="company_name" id="txtCompanyName" class="bg-success" style="display:none;" value="<?php echo $CompanyName ?>"><input type="image" alt="Submit" id="imgsave" src="images/save.png" style="display:none;"><input type="hidden" name="action" value="save_company_name"></form><input type="text" name="company_name" id="txtCompanyName" class="bg-success" style="display:none;" value="<?php echo $CompanyName ?>"></td></td><td><A HREF="backuptocsv.php"><button type="button" class="btn btn-success users-style">Save data as CSV file</button></a><br><div style="display:inline;text-align: center;background: black;color:#f2f2f2;position: absolute;font-size:10px;">(This file can not be used to restore FilterManager.<br>It is used in programs such as MicroSoft XL or Google Sheets)</div></div></td></tr>
<tr><td style="font-weight:bold;">Company Logo<img src="images/<?php echo $CompanyImage ?>" style="width:40px;height:40px;">
<?php
if(file_exists($CompanyImage)) {
   echo "<img src='images/".$CompanyImage."' width='200px' height='200px'><br>
   <form action='".$_SERVER["SCRIPT_NAME"]."' method='POST'>
   <input type='hidden' name='image_name' value='".$CompanyImage."'>
   <input type='submit' class='btn btn-danger' value='Delete_image'></form>
   <img src='images/download.gif' id='imgUpload' width='200px' height='200px' style='display: none';>";
}
else
{
   
?>

    <tr><td><div class="Color-Light">
    <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" enctype="multipart/form-data">
    Select company logo image to upload if you wish.<br> (Max file size 500 kb.)<br>
    <input type='file' style='height:50px;width:220px;' name="fileToUpload" value='Upload file now'><br>

    <input type="submit" class="btn btn-success" onclick='document.getElementById("imgUpload").style.display='visible''; value="Upload Image" name="submit_file" id='btnUploadFile'></div>
</form>
<?php
}
?>
</td><td></tr>
</table>
<!-- Modal -->
<div class="modal fade users-style" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog users-style" role="document">
    <div class="modal-content users-style">
      <div class="modal-header users-style">
        <h5 class="modal-title users-style" id="exampleModalLabel">Change Company Name?</h5>
        <button type="button" class="close users-style" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body users-style">
      <form id="frmCompanyName" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
      <input type="hidden" name="action" value="save_company_name">
        <input type="text" name="company_name" id="txtCompanyName" class="bg-primary text-white" value="<?php echo $CompanyName ?>">
      </div>
      <div class="modal-footer">
      
        <input type="submit" class="btn btn-success" value="Save changes"></form><br>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><br>
      </div>
    </div>
  </div>
</div>
<!-- MODAL END-->

<?php
}
?>
