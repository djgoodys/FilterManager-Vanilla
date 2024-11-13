<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
$Action = "";
$emailAddress='';
$Message = "";
?>
<html>
    <title>Filter Manager Login Help</title>
    <head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="jquery.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<meta charset="utf-8"
     name="viewport" content="width=device-width, initial-scale=1">


    </head>
<body style="background-color:tan;">
<?php

if(isset($_POST["action"])){$Action = $_POST["action"];}
if(isset($_POST["user_id"])){$UserName = $_POST["user_id"];}
//print_r($_POST);
include "dbMirage_connect.php";
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<?php

?>
<div style="width:200px;font-weight:bold;margin-left:300px;margin-top:20px;">Password help</div><div style="margin-left:300px;display:flex;flex-direction:row;"><button style="height:40vh;padding-top:1vh;"  onclick="window.history.go(-1); return false;" class="btn btn-primary">Back to Login</button>
<form method="post" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>"><div style="margin-left:0px;margin-top:0px;"><input type="text" style="height:40vh;" name="user_id" placeholder="What is user id?" onkeyup="if(this.value.length > 0){document.getElementById('action').disabled = false;document.getElementById('action').className = 'btn btn-success';}else{document.getElementById('action').disabled = true;document.getElementById('action').className = 'btn btn-secondary';}" ><input class="btn btn-secondary" style="margin-top:0px;height:40vh;padding-top:1vh;" type="submit" id="action" name="action" disabled></form></div>
<?php


if($Action == "Submit"){
$query = "SELECT * FROM users WHERE user_name = '".$UserName."';";
    $result = $con -> query($query);
   $row = $result -> fetch_assoc(); 
    $rowcount = mysqli_num_rows( $result );
    if($rowcount > 0 )     
        {
        $emailAddress = $row["Email"];
        if(strLen($emailAddress) <= 0)
            {
                $Action = "error";
                $Message= "<div style='background-color:red;color:white;width:400px;'>Invalid or missing email on file for ".$UserName. ". Unable to submit email. Please contact system admin for help.</div>";
            }
        }
        else
        {
            $Action = "error";
            $Message = "<div style='background-color:red;color:white;width:400px;'>No one with user name ". $UserName . " exists in system.</div>";
        }
    if(strLen($emailAddress > 0) && $Action <> "error")
        {
            require 'src/Exception.php';
            require 'src/PHPMailer.php';
            require 'src/SMTP.php';
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->SMTPDebug = 0;                      //Enable verbose debug output
                $mail->isSMTP();                                            //Send using SMTP
                $mail->Host       = 'smtp.mboxhosting.com';                     //Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                $mail->Username   = 'admin@filtermanager.atwebpages.com';                     //SMTP username
                $mail->Password   = 'relays82';                               //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('admin@filtermanager.atwebpages.com', 'Mailer');
                $mail->addAddress('admin@filtermanager.atwebpages.com', 'David J');     //Add a recipient
                $mail->addAddress($emailAddress);               //Name is optional
                //$mail->addReplyTo('info@example.com', 'Information');
                //$mail->addCC('cc@example.com');
                //$mail->addBCC('bcc@example.com');

                //Attachments
                //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

                //Content
                $mail->isHTML(true);                                  //Set email format to HTML
                $mail->Subject = $UserName ."  Filter manager log in";
                $mail->Body    = "Your password is ".$row["password"];
                $mail->AltBody = "Your password is ".$row["password"];

                $mail->send();
                $Message = "<div style='margin-left:0px;background-color:black;color:white;width:370px;'>An email with your password has been sent to ". $row["Email"] ."</div>";

            } catch (Exception $e) {
                $Message = "<div style='margin-left:300px;background-color:red;color:white;width:400px;'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
            } 

            
        }
        
            echo $Message;
}
?>
</body>
