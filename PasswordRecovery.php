<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
include 'dbMirage_connect.php';
?>
<html>
    <title>Filter Manager Password/Username Recovery</title>
    <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    </head>
    <body style="background-color: darkgreen;text-align:center;">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <h1 style="color:red;">Password Recovery</h1>
    <?php
    $Action="";
    $user_email="";
    $UserName="";
    if(isset($_POST["username"])){$UserName=$_POST["username"];}
    if(isset($_POST["action"])){$Action=$_POST["action"];}
   if(strcmp($Action, "")==0){
        ?>
        <h2 style="color:yellow;">Enter your username and click submit</h2>
        <form action="PasswordRecovery.php" method="post">
        <input type="hidden" name="action" value="recover">        
        <div class="input-group input-group-sm mb-3 w-25" style="margin: auto;" name="username">
        <input type="text" class="form-control w-25" name="username" placeholder="user name here" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
        <div class="input-group-prepend">
        <div class="input-group-prepend">
    <button class="btn btn-outline-secondary bg-warning text-center" style="height:32px;" type="button" onclick="this.form.submit();">Submit</button>
  </div>
        </div>
        </div>
        </form>
        <?php
    }

    if(strcmp($Action, "recover")==0)
    {
        $query="SELECT user_name, password, Email FROM users WHERE user_name = '" . $UserName . "';";
        if ($stmt = $con->prepare($query)) 
                {
                $stmt->execute();
                //Bind the fetched data to $unitId and $unitName
                $stmt->bind_result($User_Name, $Pass_word,$Email);
                //Fetch 1 row at a time
                while ($stmt->fetch()) 
                    {
                        //echo $User_Name."<br>";
                        if (strcmp($UserName, $User_Name)==0) 
                            {
                            $user_email = $Email;
                            //echo "email=".$user_email;
                            }
                    }
                }


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
            $mail->addAddress($user_email,$UserName);     //Add a recipient
          
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = 'Filter Manager password recovery for:'.$UserName;
            $mail->Body    = "Your password is ".$Pass_word;
            $mail->AltBody = "Your password is ".$Pass_word;
        
            $mail->send();
            echo "<h4 style='background-color:black;color:yellow;'>A message containing your password has been sent to the email on file for :".$UserName."</h4><br>
            <a href='web_login3.php'><button class='btn btn-warning'>Back to login page</button></a>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        } 
        
        }
              ?>

    
    </body>
</html>
