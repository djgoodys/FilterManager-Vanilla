<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
?>
<html>
    <title>Filter Manager Bug Report</title>
    <head>


    </head>
    <body style="background-color: darkgreen;">
        <h3 style="color: aqua;">Bug Reports</h3>
    <font color="white"><div>Enter what page you were on(i.e. Filters, Unit List, Add new unit etc...)</div><br>
    <form action="BugReport.php" method="post">
        <input type="text" name="page" style="width:420px;"><br><br>
        Enter what you were doing when the error occured<br>
        <textarea name="problem" cols="50" rows="8"></textarea><br>
        <input type="submit" name="email">
        </form>
    Click submit button after filling out the top 2 areas
    <div style="text-align: center;"> </div></font>
    
    </body>
</html>

<?php


if(isset($_POST["email"])){

$Problem="";
$Page="";
if(isset($_POST["problem"])){$Problem=$_POST["problem"];}
if(isset($_POST["page"])){$Page=$_POST["page"];}
require 'src/Exception.php';
require '/src/PHPMailer.php';
require '/src/SMTP.php';
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
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
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $UserName = "";
    if (isset($_COOKIE["cookie_username"])) {
        $UserName = $_COOKIE["cookie_username"];
        }
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Filter Manager Bug Report sent by:'.$UserName;
    $mail->Body    = $Page;
    $mail->AltBody = $Problem;

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} 

}
      ?>