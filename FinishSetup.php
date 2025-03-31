<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
//print_r($_POST);
$CreatedSuccess = "";
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
    if(isset($_POST['app_admin_name'])) {
      $AppAdminName = $_POST['app_admin_name'];
      setcookie("app_admin_name", $_POST['app_admin_name'], time() + 60 * 60 * 24, "/", "", false, true);
      }
    if(isset($_POST["app_admin_password"])) {
      $AppAdminPassword = $_POST['app_admin_password'];
      setcookie("app_admin_password", $_POST['app_admin_password'], time() + 60 * 60 * 24, "/", "", false, true);
      }
if(isset($_POST['action'])) {$Action = $_POST['action'];}
if (isset($_POST["checkout_session_id"])){$Checkout_Session_ID = $_POST["checkout_session_id"];}
if ($Action == "sign_up") {
    setcookie("business_name", $_POST['business_name'], time() + 60 * 60 * 24, "/", "", false, true);
    $BusinessName = strtolower(trim($_POST['business_name']));
    $BusinessName = str_replace(' ', '_', $BusinessName);
    $Email = strtolower($_POST['email']);
    $sitesFolder = "sites";
    $customerFolder = $sitesFolder . "/" . $BusinessName;
    if (!is_dir($customerFolder)) {
      // Create the folder if it doesn't exist
      if (mkdir($customerFolder, 0775, true)) {
        $_SESSION["folder_created"] = true;
      $filePath = 'sites/'. $BusinessName.'/data.json';
      $data = array(
      "equipment" => array(),
      "filters" => array(),
      "storage" => array(),
      "filter_orders" => array(),
      "filter_types" => array(),
      "misc" => array(
        "checkout_session_id" => $Checkout_Session_ID
      )
      );
  
      if (!file_exists($filePath)) 
          {
              $json = json_encode($data, JSON_PRETTY_PRINT);
              $CreatedSuccess = false;
              if (file_put_contents($filePath, $json) === false) 
                  {
                      echo "Error creating file: $filePath";
                  } 
                  else 
                  {
                      $CreatedSuccess = true;
                  }
          } 
          else 
          {
          echo "File already exists: $filePath";
          }
  
      } 
      else 
      {
        echo "Error creating folder for customer: $BusinessName";
      }
    } else {
      $bName = str_replace('_', ' ', $BusinessName);
      echo "business name already exists";
    }
  $id = getUniqueID();
      $jsonString = file_get_contents('table_users.json');
      $data = json_decode($jsonString, true);
      $newUser = array(
          '_id' => $id,
          'user_name' => $AppAdminName,
          'password' => $AppAdminPassword,
          'email' => $Email,
          'admin' => 'yes',
          'clickme' => 'true',
          'field2' => "",
          'field3' => "",
          "didYouKnow" => array(
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            ""
          ),
          'font_family' => "",
          'font_size' => "",
          'theme' => "",
          'backup_folder' => $BusinessName,
          "misc" => array(
            "checkout_session_id" => $Checkout_Session_ID
          )
      );
      $data[] = $newUser;
      $jsonString = json_encode($data, JSON_PRETTY_PRINT);
      file_put_contents('table_users.json', $jsonString);
  
  }

  if($CreatedSuccess == true)
  {

   $EmailBody = "<table style='border-collapse: collapse;color:green;'><tr style='border: 1px solid aqua'><td>You FilterManager app address is: </td><td>filtermanager.net
    </td><tr><td>Your company is registered under the name:</td><td>".$_POST['business_name'] ."</td></tr>
    <tr><td>Your username for access is:</td><td>".$_POST['app_admin_name'] ."</td></tr><tr><td>Your password is:</td><td>". $_POST['app_admin_password'] ."</td></tr><tr><td>Your email:</td><td>". $Email ."</td></tr></table>Click <a href='https://www.filtermanager.net'>HERE</a> to log into your app.";
   try {
  //Server settings
  $mail = new PHPMailer;
  $mail->SMTPDebug = 0;  //Enable verbose debug output
  $mail->isSMTP();                                            //Send using SMTP
  $mail->Host       = 'smtp.mboxhosting.com'; //Set the SMTP server to send through
  $mail->SMTPAuth   = true;    //Enable SMTP authentication
  $mail->Username   = 'admin@filtermanager.net';   //SMTP username
  $mail->Password   = 'relays82';     //SMTP password
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  //Enable implicit TLS encryption
  $mail->Port       = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
  //Recipients
  $mail->setFrom('admin@filtermanager.net', 'Admin');
  $mail->addAddress($Email, $AppAdminName);     //sent to new customer
  
$mail->addAddress('admin@filtermanager.net', 'David');     //Add a recipient
  //$mail->addAddress('ellen@example.com');               //Name is optional
  //$mail->addReplyTo('info@example.com', 'Information');
  //$mail->addCC('cc@example.com');
  //$mail->addBCC('bcc@example.com');

  //Attachments
  //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

  //Content
  $AltBody="";
  $mail->isHTML(true);                                  //Set email format to HTML
  $mail->Subject = 'New Account setup with FilterManager.net';
  $mail->Body    = $EmailBody;
  $mail->AltBody = $AltBody;

  $mail->send();
  setcookie("checkout_session_id", "true", time() + 3600, "/");
  echo 'setup_complete';
} catch (Exception $e) {
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} 


  }

