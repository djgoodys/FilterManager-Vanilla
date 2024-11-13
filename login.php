<?php
session_start();



header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include 'dbMirage_connect.php';

$Password="";
$UserName="";
$NewUser = "false";

if(isset($_GET["newuser"])){$NewUser=$_GET["newuser"];}
//echo "new user=" . $NewUser;

if(isset($_GET["user_name"])) {$UserName = $_GET["user_name"];}
//echo "userName=". $UserName;
if(isset($_GET["password"])) {$Password = $_GET["password"];}
//echo "Password=". $Password;
    if ($NewUser == "true") {
        if ($result = $con->query("SELECT user_name FROM users WHERE user_name = '" . $_GET["user_name"] . "';"))
            $row_cnt = $result-> num_rows;
        if($row_cnt > 0){
            $assigned["success"] = 0;
            $assigned["error"] = "That user name is already taken.";
            $assigned["message"] = "That user name is already taken.";
            $response["data"] = $assigned;

        }else {
            //CREATE NEW USER HERE
            $sql = "INSERT INTO users(user_name, password) VALUES('".$UserName."','". $Password."');";
            //echo "sql=" . $sql;
            if(mysqli_query($con, $sql)){
                $assigned["success"] = 1;
                $assigned["message"] = "Your Name and Password Successfully Added.";
                $response["data"] = $assigned;
            } else{
                $assigned["success"] = 0;
                $assigned["message"] = "Error while adding new user.";
                $response["data"] = $assigned;
            }
        }
//Displaying JSON response

}
if(isset($_GET["user_name"]) && $NewUser == "false") {
    $query="SELECT _id, user_name, password, userscol FROM users WHERE user_name = '" . $_GET["user_name"] . "';";
    if ($stmt = $con->prepare($query)) {
        $stmt->execute();
        //Bind the fetched data to $unitId and $unitName
        $stmt->bind_result($RecId, $UserName, $Password, $UsersCol);
        //Fetch 1 row at a time
        while ($stmt->fetch()) {
            $userArray["_id"] = $RecId;
            $userArray["user_name"] = $UserName;
            $userArray["password"] = $Password;
            $userArray["userscol"] = $UsersCol;
            $result = $userArray;
        }
        if (ISSET($_GET["user_name"])) {
            if ($_GET["user_name"] = $UserName AND $_GET["password"] = $Password) {
                $result["success"] = 1;
                $result["message"] = "login successful";
                $response["data"] = $result;
            } else {
                $result["success"] = 0;
                $result["message"] = "login denied";
                $response["data"] = $result;
            }
            $response["data"] = $result;
            //echo json_encode($response);
        }
        $stmt->close();

        //Display JSON response
    }
}
echo json_encode($response);
?>
