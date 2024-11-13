<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
//echo "no session";
  session_start();
}
//echo "session id=".session_id();
$LastBackup="";
$CompanyImage="";

//print_r($_POST);

include 'functions.php';
?>

<head>
<style>
* {
    background-color: <?php echo  $_SESSION["background_color"] ?>;
    color:<?php echo $FontColor ?>;
    font-size:<?php echo $FontSize ?>;
}
</style>
<script src="jquery.js"></script>
 <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
    </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<?php
$jsonString = file_get_contents('table_users.json');
$data = json_decode($jsonString, true);
foreach ($data as &$object) {
    if ($object['user_name'] == $_SESSION["user_name"]) {
      $_SESSION["background_color"] = $object["background_color"];
     
        break;
    }
}
?>

</head>
<body style="background-color: <?php echo $_SESSION["background_color"] ?>;">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
function refreshIframe(iframe) {
  const frame = window.parent.document.getElementById(iframe); // Replace with actual iframe ID
  if (frame) {
    frame.contentWindow.location.reload(true); // Reload iframe
  }
}
</script>
<script>
function hexToRgb(hex) {
  // Remove any leading '#' character
  hex = hex.replace(/^#?/, '');

  // Check if the hex string is valid (length of 3 or 6)
  if (hex.length !== 3 && hex.length !== 6) {
    return null; // Return null for invalid hex code
  }

  // Convert hex characters to integers (base 16)
  const r = parseInt(hex.substring(0, 2), 16);
  const g = parseInt(hex.substring(2, 4), 16);
  const b = parseInt(hex.substring(4, 6), 16);

  // Check for values outside the 0-255 range
  if (r < 0 || r > 255 || g < 0 || g > 255 || b < 0 || b > 255) {
    return null; // Return null for invalid color values
  }
 
  // Return the RGB object
  return r +","+g+","+ b ;
}

// Example usage:
const hexColor = "#ff00ff";
const rgbColor = hexToRgb(hexColor);

if (rgbColor) {
  console.log(`RGB: ${rgbColor.r}, ${rgbColor.g}, ${rgbColor.b}`);
} else {
  console.log("Invalid hex color code.");
}

</script>
<script>
function sendAjaxMessage(field, xvalue)
{
  console.log("field="+field+" value="+xvalue);
  if(field == 'font_size')
    {
      switch (xvalue)
      {
        case "10":
        myvalue = "Small";
        break;
        case "15":
        myvalue = "Medium";
        break;
        case "20":
        myvalue =  "Large";
        break;
        case "25":
        myvalue = "X-Large";
        break;
      }
      document.getElementById("slctfontsize").innerText = myvalue;
    }

   var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("AjaxResponse").innerHTML = this.responseText;
    }
  };
  myurl = "ajax_receiver.php?action=update_server_variables&field="+field+"&value="+xvalue;
  xhttp.open("GET", myurl, true);
  xhttp.send();
  
      const allTextElements = document.querySelectorAll('body *');
      for (const element of allTextElements) 
        {
          if (element.tagName !== 'SCRIPT' && element.tagName !== 'STYLE' && element.className !== 'dropdown-item') 
            { 
              if(field == "font_family"){element.style.fontFamily = xvalue;}
              if(field == "font_size"){element.style.fontSize = xvalue;}
            }
        }
}

</script>
<?php
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
            $newfilename="sites/".$_SESSION["backup_folder"]."/".$FileNameOnly;
            $imgData = resizeImage($_FILES["fileToUpload"]["tmp_name"], $newfilename,$ext, 300, 300);   
            imagepng($imgData, $newfilename);
            $datafile = 'sites/'.$_SESSION["backup_folder"].'/data.json';
            $jsonString = file_get_contents($datafile);
            $data = json_decode($jsonString, true);
            // Check if the "misc" array exists and create it if it does not
            if (!isset($data['misc'][0]['_id'])) {
              echo "not found";
                $data['misc'] = array("_id" => "", "last_backup" => "", "company_name" => "", "company_image" => "");
            }
            $data['misc'][0]['company_image'] = $newfilename;
            $newJsonString = json_encode($data);
            file_put_contents($datafile, $newJsonString);      
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
      echo "<style>*{font-size:".$_GET["font_size"] .";}</style>";
   }

   //----------------FONT FAMILY CHANGE----------------

if(isset($_SESSION["font_family"])){$FontFamily = $_SESSION["font_family"];
}
else
{
  $FontFamily = "Arial, sans-serif";
}
if(isset($_GET["font_family"]))
  {  
      $FontFamily = $_GET["font_family"];
      UpdateUserSettings("font_family", $_GET["font_family"], $_SESSION["user_name"]);
      
  }
    
  
//----------------THEME CHANGE----------------
print_r($_POST);




$CompanyName = "";
//SAVE COMPANY NAME----------------------------------------------------------------
if(strcmp($Action, "save_company_name")==0)
  {
   
    if(isset($_POST["company_name"])){$CompanyName=$_POST["company_name"];}
    //echo "co name=".$CompanyName;
    $query = "UPDATE misc SET company_name='".$CompanyName."' LIMIT 1;";
    $jsonString = file_get_contents('sites/'. $_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    $data["misc"][0]["company_name"] = $CompanyName;
  
    $jsonString = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents('sites/'. $_SESSION["backup_folder"].'/data.json', $jsonString);
          
   }
//GET DATE OF LAST DATA BACK UP
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
if(isset($data['misc'][0]['last_backup'])){$LastBackup = $data['misc'][0]['last_backup'];}
      if(isset($data['misc'][0]['company_image']) && is_null($data['misc'][0]['company_image']) == 1)
      {
      $CompanyImage = "your_co_logo";
    }
    else
    {
      if(isset($data['misc'][0]['company_image'])){$CompanyImage = $data['misc'][0]["company_image"];}
    }
    // echo "CompanyImage=".$CompanyImage."<br>". "after=".$CompanyImage;
      if(isset($data['misc'][0]['company_name']) && is_null($data['misc'][0]['company_name']) == 1)
      {
        $CompanyName = "Your company name";
      }
      else
      {
        if(isset($data['misc'][0]['company_name'])){$CompanyName = $data['misc'][0]["company_name"];}
      }

        //----------------NEW EMAIL----------------
$NewEmail="";
if(isset($_POST["email"]))
   {  
      $NewEmail=$_POST["email"];
   }
   ?>

<div style="height: 100px;width:100%;font-size:3em;text-align:center;background-color: <?php echo $_SESSION["background_color"] ?>">
FILTER MANAGER SETTINGS
</div>
ajax response<div id="AjaxResponse" style="background-color: <?php echo $_SESSION["background_color"] ?>;color:black;"></div>
<table class="table" style="width:100%;margin-left:50px;">
    <thead>
      <tr>
        <th style='width:33%;'>FONT SIZE</th>
        <th style='width:33%;'>FONT FAMILY</TH>
        <th style="width:33%;">APP BACK GROUND COLOR</th>
      </tr>
    </thead>
    <tbody>
    <tr>
        <td>
<div class="dropdown">
  <button class="btn btn-success dropdown-toggle" type="button" id="slctfontsize" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    <a class="dropdown-item" href="#" onclick="sendAjaxMessage('font_size', '10');reloadIframe1('10');">small</a>
    <a class="dropdown-item"href="#" onclick="sendAjaxMessage('font_size', '15');reloadIframe1('15');">Medium</a>
    <a class="dropdown-item" href="#" onclick="sendAjaxMessage('font_size', '20');reloadIframe1('20');">Large</a>
<a class="dropdown-item" href="#" onclick="sendAjaxMessage('font_size', '25');reloadIframe1('25');">X-Large</a>
  </div>
</div>
        </td>
        <td>
<div class="dropdown">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="slctfontfamily" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php echo $_SESSION["font_family"] ?>
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="#" onclick="sendAjaxMessage('font_family', 'Arial, sans-serif');reloadIframe1();" style="font-family:Arial, sans-serif;">Arial, sans-serif</a>
    <a class="dropdown-item" href="#" onclick="sendAjaxMessage('font_family','Verdana, sans-serif');reloadIframe1();"  style="font-family:Verdana, sans-serif;">Verdana, sans-serif</a>
    <a class="dropdown-item" style="font-family:Andale Mono, monospace;"  href="#" onclick="sendAjaxMessage('font_family','Andale Mono, monospace');reloadIframe1()">Andale Mono, monospace</a>
 <a class="dropdown-item" style="font-family:Century Gothic;" href="#" onclick="sendAjaxMessage('font_family','Century Gothic');reloadIframe1();">Century Gothic</a>
 <a class="dropdown-item" style="font-family:Impact, fantasy;"  href="#" onclick="sendAjaxMessage('font_family','Impact, fantasy');reloadIframe1();">Impact, fantasy</a>
 <a class="dropdown-item" style="font-family:Brush Script MT', cursive;"  href="#" onclick="sendAjaxMessage('font_family','Brush Script MT, cursive');reloadIframe1();">Brush Script MT', cursive</a>
<a class="dropdown-item" style="font-family:Franklin Gothic Medium;"  href="#" onclick="sendAjaxMessage('font_family','Franklin Gothic Medium');reloadIframe1();">Franklin Gothic Medium</a>
</div>
         </td>
        <td style="width:33%;">
 
<div style="border-radius:25%;height:30px;width:fit-content;display:flex;flex-direction: row;">Choose color<div style="height:30px;width:30px;text-align:center;background-color: white;"><input type="radio" style="margin-top:8px;background-color: white;"  name="bgcolor" value="Light" onchange="changeBackGroundColor(this.style.backgroundColor);"></div><div style="height:30px;width:30px;text-align:center;background-color: #ff7750;"><input type="radio" style="margin-top:8px;background-color:#ff7750;"  name="bgcolor"  value="dark" onchange="changeBackGroundColor(this.style.backgroundColor);"></div><div style="height:30px;width:30px;text-align:center;background-color: #dbffa2;"><input type="radio" style="margin-top:8px;background-color: #dbffa2;"  name="bgcolor"  value="dark" onchange="changeBackGroundColor(this.style.backgroundColor);"></div><div style="height:30px;width:30px;text-align:center;background-color: #3F51B5;"><input type="radio" style="margin-top:8px;background-color: #3F51B5;"  name="bgcolor"  value="dark" onchange="changeBackGroundColor(this.style.backgroundColor);"></div><div style="height:30px;width:30px;text-align:center;background-color: #000000;"><input type="radio" style="margin-top:8px;background-color: #000000;"  name="bgcolor"  value="dark" onchange="changeBackGroundColor(this.style.backgroundColor);"></div></div><br><label for="colorPicker">Create color:</label>
<input type="color" id="colorPicker" name="color" onchange="changeBackGroundColor(this.value);">
          
<form id="frmBackGroundColor" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
<input type="hidden" name="back_ground_color" value="" id="back_ground_color">
</form></td></tr>    

<?php
$UserVerified = "false";
$Email="";
$UserEmail="";
$Pass_word="";

//VERIFY USER WHEN CHANGING EMAIL
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

//UPDATE EMAIL
  if(strcmp($Action, "update_email")==0)
    {
      $jsonString = file_get_contents('table_users.json');
      $data = json_decode($jsonString, true);
      foreach ($data as &$object) {
          if ($object['user_name'] === $_SESSION["user_name"]) {
              // Change the value of the specified key
              $object['Email'] = $NewEmail;
          }
      }
      $jsonString = json_encode($data, JSON_PRETTY_PRINT);
      file_put_contents('table_users.json', $jsonString);
      echo "<div style='font-size:1em;text-align:center;background-color:black;color:white'>".$UserName. " your email was successfully changed to ".$NewEmail.".</div>";
    }
    //SAVE COMPLAY NAME
  if(strcmp($Action,"")==0 || strcmp($Action, "save_company_name")==0){
  
   ?>
   <tr><td style="font-weight:bold;">PASSWORD</td><td style="font-weight:bold;">EMAIL</td></tr>
   
  <tr><td style="width:33%;"><a href="ChangePassword.php"><button class="btn btn-primary users-style" id="btnChangePassword" >Change password</button></a>
  
</td><td style="width:33%;"><a href="ChangeEmail.php"><button class="btn btn-primary users-style" id="btnChangeEmail" >Change email</button></a></td>
</tr>
<tr><td style="font-weight:bold;">DATA RESTORE</td><td style="font-weight:bold;">DATA BACK-UP</td></tr>
<tr><TD><A HREF="updateDB.php?action=ask_to_update"><button type="button" class="btn btn-warning users-style">IMPORT UNIT DATA</button></a></td><td><A Href="DataBackUp.php"><button type="button" class="btn btn-info users-style">EXPORT ALL DATA</button></A><br><div style="background-color:black;color:#f2f2f2;font-size:8px;width:fit-content;">(LAST DATA BACK UP PERFORMED ON: <?php echo $LastBackup ?>)</div></td></tr>
<tr><td style="font-weight:bold;">COMPANY NAME</td><td style="font-weight:bold;">SAVE DATA AS CSV FILE</td></tr>
<tr><td class="d-block"><div style="display:inline-block"><div class="users-style text-white bg-primary" id="divCompanyName" class="bg-primary"><?php echo $CompanyName ?><img id="imgedit" data-toggle="modal" data-target="#exampleModal"  title="edit company name" src="images/edit.png" style="height:3em;width:auto;margin-left:10px;"></div><form id="frmCompanyName" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" style="display: flex;"><input type="text" name="company_name" id="txtCompanyName" class="bg-success" style="display:none;" value="<?php echo $CompanyName ?>"><input type="image" alt="Submit" id="imgsave" src="images/save.png" style="display:none;"><input type="hidden" name="action" value="save_company_name"></form><input type="text" name="company_name" id="txtCompanyName" class="bg-success" style="display:none;" value="<?php echo $CompanyName ?>"></td></td><td><A HREF="backuptocsv.php"><button type="button" class="btn btn-success users-style">Save data as CSV file</button></a><br><div style="display:inline;text-align: center;background: black;color:#f2f2f2;position: absolute;font-size:10px;">(This file can not be used to restore FilterManager.<br>It is used in programs such as MicroSoft XL or Google Sheets)</div></div></td></tr>
<tr><td style="font-weight:bold;">Company Logo
<?php
if(file_exists($CompanyImage)) {
   echo "<img src='sites/".$_SESSION["backup_folder"] ."/co_logo.jpg' style='width:40px;height:40px;'><br>
   <form action='".$_SERVER["SCRIPT_NAME"]."' method='POST'>
   <input type='hidden' name='image_name' value='".$CompanyImage."'>
   <input type='submit' class='btn btn-danger' value='Delete_image' style='margin-top:10px;'></form>
   <img src='images/download.gif' id='imgUpload' width='180px' height='100px' style='display: none';>";
}
else
{
   
?>

    <tr><td><div class="Color-Light">
    <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" enctype="multipart/form-data">
    Select company logo image to upload if you wish.<br> (Max file size 500 kb.)<br>
    <input type='file' style='height:50px;width:220px;' name="fileToUpload" value='Upload file now' id="fileToUpload"><br>
    <script>
      function changeBackGroundColor(color){
        if(color.charAt(0) == "#"){color = hexToRgb(color)}
        sendAjaxMessage('background_color', color);

  
        //refreshIframe('iframe1');
        //refreshIframe('iframe2');
      }
      </script>
<script>
$("#fileToUpload").change(function(){
         if ($('#fileToUpload')[0].files.length === 0) 
         {
          document.getElementById("cmdSubmit").disabled = true;
         }
          else
          {
            document.getElementById("cmdSubmit").disabled = false;
          }
 });

</script>
    <input type="submit" class="btn btn-success" onclick="document.getElementById('imgUpload').style.display='visible';"; value="Upload Image" name="submit_file" id='btnUploadFile'></div>
</form>
<?php
}
?>
</td><td></tr>
</table>
<!-- Modal -->
<div class="modal fade users-style" style="top:450;" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog users-style" role="document">
    <div class="modal-content users-style">
      <div class="modal-header users-style">
        <h5 class="modal-title users-style" id="exampleModalLabel">Enter new company name in blue box.<br>Then click save changes button.
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
