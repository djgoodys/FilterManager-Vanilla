
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
        if(isset($_POST["username"])){$UserName=$_POST["username"];}
        try {
        $to = 'djgoodys@gmail.com';
        $subject = 'FilterManager Bug Report sent by:'.$UserName;
        $message = "Was on page: ".$Page. " Problem: ".$Problem;
        $headers = 'From: servicex';
        
        mail($to, $subject, $message, $headers);
        echo "<div style='background-color:black;color:white;'>Message was sent, Thank you for your time and effort in making this app function correctly";
        } catch (Exception $e) {
            echo "<div style='background-color:red;color:white;'>Message could not be sent. Mailer Error: {$e}</div>";
        } 
        
        }


      ?>