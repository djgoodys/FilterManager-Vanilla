<?php
include 'dbMirage_connect.php';
//print_r($_POST);
if(isset($_POST["email"])) {
    $Email=$_POST["email"];
    $UserName=$_POST["myusername"];
    $query = "UPDATE users SET Email='".$Email."' WHERE user_name='" . $UserName . "';";
        if (mysqli_query($con, $query)) {
            setcookie("cookie_email", $Email, time() + (10 * 365 * 24 * 60 * 60), "/"); // 10 years
            echo "<body style='background-color:green;'><h3 style='color:white;font-weight:bold;font-size:30px;text-align:center;'>Thank you your email has been stored.<br><a href='web_login3.php'> Go Back</a>";
        } else {
            echo "<body style='background-color:green;'><h3 style='color:red;font-weight:bold;;text-align:center;'>Error updating Email. Please tell DJ.<br><a href='web_login3.php'> Go Back</a>";
        }

}