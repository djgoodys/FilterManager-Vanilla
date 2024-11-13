<?php

include 'dbMirage_connect.php';
//echo "all session vars:".var_dump($_SESSION)."<br>";
$Action = "";
if (isset($_COOKIE["theme"])) {
    $Theme = $_COOKIE["theme"];
    //echo "<font color='white'>cookie theme=".$Theme;
}
$UserName="none";
if(isset($_COOKIE["user"])){
   $UserName=$_COOKIE["user"];
   // echo "cookie ******user*****=".$UserName;
}
if(isset($_COOKIE["username"]))
         {
            $UserName=$_COOKIE["username"];
         //echo "cookie username=".$UserName;
         }
if(!isset($_SESSION["username"]))
{
   //$UserName=$_SESSION["username"];
   //echo "NO SESSION UNAME=";
}
//echo "user name=".$UserName."<br>";
?>
<html>
<head>
<meta charset="utf-8"
    name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style_sheet.css.css">

<style>
#option.myoption {
    background-color: #cc0000; 
    font-weight: bold; 
    font-size: 12px; 
    color: white;
}

.Dark-tdOverDue{
background-color:red;
color:white;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;
padding: 2px;
white-space:nowrap;
}
.Dark-tdPlain{
background-color:black;
color:white;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;black;
padding: 2px;
white-space:nowrap;
}
.Light-tdOverDue{
background-color:white;
vertical-align: middle;
color:red;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;
padding: 2px;
white-space:nowrap;
}
.tdUnitInfo{
   padding:0px; 
   height:10px; 
   background-color:blue; 
   color:white;"
   
.Light-Plain{
tr.hover{ background-color: #FFD700;}
}
.Light-tdPlain{
background-color:white;
color:black;
font-weight: bold;
font-size: <?php echo $FontSize ?>px;
text-align:center;black;
padding: 2px;
white-space:nowrap;
}
</style>
    </head>
<body onclick="showelements();">
<script type="text/javascript">
function printpage(){
   document.getElementById("btnPrint").style.display = "none";
   document.getElementById("btnGoToUnitlist").style.display = "none";
window.print();
}

function showelements(){
   document.getElementById("btnPrint").style.display = "block";
   document.getElementById("btnGoToUnitlist").style.display = "block";
}
</script>
<?php
if(isset($_POST["username"]))
         {
            $UserName=$_POST["username"];
            //echo "POST username=".$UserName. " LENGTH=" .strlen($UserName);
         }
if(isset($_GET["username"]) || isset($_GET["username"]))
         {
            $UserName=$_GET["username"];
            //echo "GET username=".$UserName;
         }
//echo "username=====".$UserName."<br>";
if(strcmp($UserName, "none")==0)
   {
   //echo "Loggedin<br>";
      //header("Location: http://filtermanager.net/web_login.php"); 
        //                exit;
   }
$txtServerMsg = "";
$display = "hidden";
$Action = "start";
if(isset($_POST["action"]))
   {
       $Action = $_POST["action"];
   }
if(isset($_GET["action"]))
   {
       $Action = $_GET["action"];
   }
if(strcmp($Action, "start")==0)
   {
      $Action="getalltasks";
   }

if($Action == "getalltasks" || $Action == "") 
   {
      $sql = "SELECT * FROM tasks WHERE task_date_completed IS NULL AND employee_name='".$UserName."';";
      ?>
      <Table border='1' style="max-width: 100%;" align='left'>
      <th style="text-align:center;font-weight:bold;"><?php echo $UserName ?> Tasks</th>
      <tr>
      <th style="text-align:center;font-weight:bold;" width='800'>Unit name
      </th><th>Unit location</th><th>Filter size</th>
      </tr>
      <?php
      if (!$result = $con->query($sql)) 
         {
             die ('There was an error running query[' . $con->error  . ']');
         }
      $row_cnt = $result->num_rows;
     
      if($row_cnt > 0)
         {
            while ($row = $result->fetch_assoc()) 
               {
                  $Rowid=$row["task_id"];
                  ?>
                  <tr><td style="text-align:center;;width:33%;font-weight:bold;"><div style="width: 100%; height: 100%;">
                  </div><?php echo $row["unit_name"] ?></td><td style="text-align:center;;width:33%;font-weight:bold;"><?php echo $row["unit_location"] ?></td>
                  <td style="text-align:center;width:33%;font-weight:bold;"><?php echo $row["filters_needed"] ?></td>
                  </tr>
                  <?php
               }
         }
         ?>
         <tr><td><form action='ListEquipment.php' method='post'> 
                  <input type="submit" value="CANCEL" onclick="goHome()" 
                  style="font-size : 20px; width: 200px; height:70px;color:gold; background-color:green;" id='btnGoToUnitlist' name='btnGoUnitList'>
                  </td>
                  </form></td>
                  <td>
                  <BUTTON id="btnPrint" onclick="printpage();" style="background-color:green;color:gold;font-size : 20px; width: 200px; height:70px;">PRINT</BUTTON></td></tr>
                  </table>
         <?php
   }
?>