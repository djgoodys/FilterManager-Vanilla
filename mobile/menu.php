<?php
if(session_id() == ''){
      session_start();
   }
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include ('../dbMirage_connect.php');
include '../functions.php';
include '../fm.css';
include '../checkbox2.css';
?>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
   function logout(){
      document.getElementById('tblNavigation').style.display = 'none';
      document.getElementById('txtUserName').style.display = 'inline-block';
      document.getElementById('txtPassword').style.display = 'inline-block';
      document.getElementById('btnLogon').style.display = 'inline-block';
      document.getElementById('tblLogin').style.display = 'inline-block';
      document.getElementById('divUserName').innerHTML = "";
      top.frames['iframe2'].location.href = "welcome1.php";
   }divLoggedin
</script>
<?php
$NewUser="";
$Response="";

//print_r($_COOKIE);

//print_r($_SESSION);
$Action=null;
$UserName="";
$User_Name="";
$Password="";
$NewUser = "";
$Response = "Login";


//CREATING ARRAY OF ALL UNIT NAMES FOR SEARCH BOXES

   $sql = "SELECT unit_name FROM equipment;";
   $arUnits = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                   array_push($arUnits, $row["unit_name"]);
                }
        }
//print_r($arUnits);
?>
<script>
<?php
$js_array = json_encode($arUnits);
echo "var unitsArray = ". $js_array . ";\n";
?>
</script>

<style>
.myButton{
background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  font-size:35px;
  font-family:<?php echo $_SESSION["FontFamily"] ?>;
  padding: 1px 1px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  width:230px; 
  height:60px; 
  border-radius: 30px;
box-shadow: 4px 4px black;
  }
.myButton2{
   background-color: #4CAF50; /* Green */
   border: none;
   color: white;
   font-size:20px;
   padding: 1px 1px;
   text-align: left;
   text-decoration: none;
   display: inline-block;
   width:300px; 
   height:60px; 
   border-radius: 30px;
  }
#divUserName:hover {
 background-color:white;
color:green;
transition: 0.2s;
}

* { box-sizing: border-box; }
body {
  font: 16px Arial;
background-color:<?php echo $BackGroundColor ?>;
}
.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
}
input {
  border: 1px solid transparent;
  background-color: #f1f1f1;
  padding: 10px;
  font-size: 16px;
}
input[type=text] {
  background-color: #f1f1f1;
  width: 100%;
}
input[type=submit] {
  background-color: #4CAF50;
  color: #fff;
}
.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}
.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff;
  border-bottom: 1px solid #d4d4d4;
}
.autocomplete-items div:hover {
  /*when hovering an item:*/
  background-color: #e9e9e9;
}
.autocomplete-active {
  /*when navigating through the items using the arrow keys:*/
  background-color: DodgerBlue !important;
  color: #ffffff;
}
</style>
<?php
if(isset($_POST["action"])){$Action = $_POST["action"];}
if(isset($_GET["action"])){$Action = $_GET["action"];}       
if(isset($_POST["username"])){$UserName=$_POST["username"];}
if(isset($_POST["password"])){$Password=$_POST["password"];}
?>
<table id='tblNavigation' style='display: table;'>
		<tr id="trButtons" style="display:inline-block;text-align:center">
                     
			<td id="td1" class="super-centered">
         <div class="dropdown">
  <button class="btn btn-primary dropdown-toggle myButton" type="button" data-toggle="dropdown">Units
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="mAddUnit.php">Add New Unit</a></li>
    <li><a href="mListEquipment.php">Units List</a></li>

  </ul>
</div>
      </td><tr>
<td>
  <a href="mWebTasks.php?action=getalltasks"><button class="myButton">Tasks</button></a>
</td></tr>
<tr>
<td id="td4" class="super-centered">
<div class="dropdown">
  <button class="btn btn-primary dropdown-toggle myButton" type="button" data-toggle="dropdown">Filters
  <span class="caret"></span></button>
  <ul class="dropdown-menu">
    <li><a href="web_update_filters.php">Inventory</a></li>
    <li><a href="web_add_filter.php">Add New</a></li>
    <li><a href="order.php">Order Filters</a></li>
  </ul>
</div>
</td>
</tr>
<tr>

<td id="td4" class="super-centered">
<button class='myButton' onclick="window.location.href='../BugReport.php';" id="btnBugReport" Value="Bug Report" data-placement="top" title="Report a problem with this software">Bug Report</button>
</td></tr><tr>
<td id="tdLogout" class="super-centered">
		  <button class='mybutton' onclick="logout();" data-toggle="tooltip" data-placement="right" title="Log out of filter manager" id='btnLogOut' name='action'>Log Out</button></td></tr>
<tr>
         <td id="td5" class="super-centered">
		   <input type='submit' onclick=" window.open('help/HelpIndex.html', '_blank');" class='mybutton' value='Help' data-toggle="tooltip" data-placement="top" title="Help using Filter Manager" id='btnHelp' name='action'>
         </td></tr>
<tr>
         <td id="td6" class="super-centered">
		   <input type='submit' onclick="window.open('../index.html');" class='mybutton' value='Desk Top' data-toggle="tooltip" data-placement="top" title="For desk tops." id='btnMobile' name='action'></tr>
<tr>
         <td id="td7" class="super-centered">
		   <button data-toggle="modal" data-target="#exampleModal" class='mybutton' data-placement="top" title="About this app" id='btnApp'>About</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel" style="color:green;font-weight:bold;">Filter Manager</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="background-color:green;color:white;font-size:1.5em;">
        Filter Manager was written and developed by David Johnson. All rights reserved. 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>
         </td>

  </tr>
        </table>
        <table id='tblTools' style='margin-top:20px;display:<?php echo $DisplayType ?>;width:100%'>
        <tr style="margin-left:50px;"><td style="vertical-align:top;">
         <div class="square" style="margin-left:30px;">
         <div class="letter">Filter Manager</div>
         </div></td>
         <td style="display:block;padding-left:20px;padding-top:0px;vertical-align:top;height:140px;">
<div class="input-group rounded" style="margin-top:0px;vertical-align:top;">
<form id="frmSearch" autocomplete="off" action="ListEquipment.php" target="iframe2" method="post" style="margin: auto;vertical-align:top;">
<input type="hidden" name="showsize" value="search">
<span class="input-group-text border-0 flex-nowrap." id="search-addon" style="margin-left:60px;vertical-align:top;width:220px;">
<div class="btn-danger d-none" id="clearsearch" style="box-shadow: 4px 4px black;font-size:30px;border-radius:5px;font-weight:bold;text-align:top;height:40px;width: 160px;color:white;" onclick="clearsearch()">X</div>
<div class="autocomplete" style="width:300px;height:auto;background-color:black;color:black;box-shadow: 4px 4px black;">
<input type="search" onkeypress="enterKeyPressed(event);" style="margin: auto;vertical-align:top;width:fit-content;max-width:140px;" name="search_words" id="myInput" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" onkeyup="showClearSearch();">
     </div></form><img src="images/search.png" style="box-shadow: 4px 4px black;height:50px;width:50px;margin-left:5px;border-radius:30px;" id="img_search" text-align="middle" onclick="resetElements('search');setLastQuery('search');">
<script>
        $("#myInput").keyup(function (event) {
            console.log("key pressed was "+ event.keyCode);
            if (event.keyCode === 13) {
                $("#img_search").click();
               //document.getElementById('img_search').click();
            }
        });
</script>
    <i class="fas fa-search"></i>
  </span></div>
 </td>
         </td>
         <td style="padding-left:20px;vertical-align:top;">
         <form action="ListEquipment.php" method="POST" id="frmcklate" target="iframe2">
<label class="container" name="checkbox" id="lblCheckBox" title="display overdue filters only">Over Due
  <input type="checkbox" id="ckoverdue" name="ckoverdue" onChange="setCookie('cookie_ckoverdue='+ this.checked);resetElements('ckoverdue');setLastQuery('overdue');" <?php echo $ShowOverDue ?>>
  <span class="checkmark"></span>
</label>
<script>
var checkbox = document.getElementById("ckoverdue")

checkbox.addEventListener('change', function() {
  if (this.checked) {
    document.getElementById("lblCheckBox").style.color="red";
  } else {
    document.getElementById("lblCheckBox").style.color="green";
  }
});
</script>
<script>
autocomplete(document.getElementById("myInput"), unitsArray);
</script>
         </td>
         </form><td style="padding-left:20px;vertical-align:top;">
         <form action="ListEquipment.php" target="iframe2" method="POST" id="frmByDate">
         <!--<SELECT id="bydate" name="bydate" onchange="setLastQuery('bydate');this.form.submit();resetElements('bydate')"   class="form-select form-select-lg mb-3">
         <option value="order by date">Order by date</option>
         <option value="newest">Newest to oldest</option>
         <option value="oldest">Oldest to newest</option>
         <option value="today">Due today</option>
         <option value="normal">Normal</option>
         </SELECT>-->
<input type = "text" name="bydate" id="txtByDate" style="display:none;">
<div class="dropdown show" style="box-shadow: 4px 4px;">
  <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php if(isset($_POST["bydate"])){echo $ByDate;} else { echo "Sort by date";} ?>
  </a>

  <div class="dropdown-menu bg-success text-info" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Newest to Oldest'; document.getElementById('txtByDate').value='newest';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');">Newest to oldest</a>
    <a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Oldest to Newest'; document.getElementById('txtByDate').value='oldest';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');">Oldest to Newest</a>
    <a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Today'; document.getElementById('txtByDate').value='today';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');">Today</a>
   <a class="dropdown-item" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Normal'; document.getElementById('txtByDate').value='normal';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');">Normal</a>
  </div>
</div>
         </FORM>
         </td><td style="padding-left:20px;vertical-align:top;"><a href="print.php" target="iframe2"><img src="images/print2.png" title="print unit list" style="vertical-align:top;height:50px;width;50px;border-radius:50%;box-shadow: 4px 4px black;"></a></td>
         <td style="padding-left:20px;vertical-align:top;"><a href="settings.php" title="go to app settings" target="iframe2"><img src="images/settings.png" style="height:50px;width:50px;"></a>
         <td id="tdSettings" style="display:none;"></td>
		 <td style="padding-left:20px;vertical-align:top;text-align:center;"><div style="box-shadow: 4px 4px black;"><div style="white-space: nowrap;background-color:orange;font-size:1.25em;color:black;">Out Of Stock</div>    
       <div style="background-color:red;white-space: nowrap;display: flex;font-size:1.25em;color:white;text-align:center;">Filters Over Due</div></div></td>
       <td style="padding-left:20px;vertical-align:top;"></td>
       <td style="padding-left:20px;vertical-align:top;"><input type="textbox" value="<?php echo $UserName ?>" disabled style="box-shadow: 3px 3px black;border-radius:50%;
   </table>


  <tr><td style='color:white;font-size:1.25em;background-color:red;'><?php echo $Response2 ?></td></tr>
  <tr><td><a href="PasswordRecovery.php?action=passwordreset"><div  style="color:#ffff80;font-size:1.5em;">Forgot user name or password?</div></a></td></tr>
</table>
<div class="dropdown">
<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Dropdown Example
<span class="caret"></span></button>
<ul class="dropdown-menu">
<li><a href="#">HTML</a></li>
<li><a href="#">CSS</a></li>
<li><a href="#">JavaScript</a></li>
</ul>
</div>
  


          