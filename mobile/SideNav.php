<script>
function setJavaCookie( name, value, days) {
if (days) {
var date = new Date();
date . setTime(date . getTime() +(days * 24 * 60 * 60 * 1000));
var expires = "; expires=" +date . toGMTString();}
else {
var expires = "";
document . cookie = name + "=" +value +expires + "; path=/";
}
}
</script>
<script>
   function logout(){
      //top.frames['iframe1'].location.href = "mweb_login.php";
      document.cookie = "username=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
      setJavaCookie("LoggedIn", "false",300);
      window.location.href = "mLogin.php";
   }
</script>
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <div class="dropdown">
    <button class="dropdown-toggle myButton" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:1.5em;">
    Units
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color:green;color:white;width:100%;">
        <a href="mListEquipment.php"><button class= 'dropdown-item text-white myButton2' id='btnUnits' name='btnUnits'  title="Go to unit list" style="font-size:1.5em;background-color:green;">List</button></a>
        <a href="webAddUnit.php"><button class='dropdown-item text-white myButton2' style="font-size:1.5em;background-color:green;">Create New</button></a>
    </div>
  </div> 
<div class="dropdown">
  <button class="dropdown-toggle myButton" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size:1.5em;">
    Filters
  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="background-color:green;color:white;width:100%;">
    <a href="../web_add_filter.php"><button class='myButton2'>New</button></a>
    <a href="mweb_update_filters.php"><button class='myButton2'>Inventory</button></a>
    <a href="../order.php"><button class='myButton2'>Order</button></a>
    <a href="../ManageFilterTypes.php"><button class='myButton2'>Types</button></a>
  </div>
</div>
 <div><a href="mWebTasks.php?action=getalltasks"><button class="myButton" id="btnGoToTasks" name="btnGoTasks"  style="margin-bottom:0px;">
    Tasks
  </button></a>
  
</div>
        <a href='BugReport.php'><button class='myButton' id="btnBugReport" title="Report a problem with this software">Bug Report</button></a>
		    <a href='#'><button class="myButton" onclick="logout();" id="btnLogOut">Log Out</button></a>
		    <a href='#'><button class="myButton" onclick="window.open('../help/HelpIndex.html', '_blank');"data-toggle="tooltip" data-placement="top" title="Help using Filter Manager" id='btnHelp'>Help</button></a>
		    <a href="../index.html"><button class="myButton" class='mybutton' title="For desktop computers or large tablets" id='btnDeskTop'>Desk Top</button></a>
		   <a href='#'><button class='myButton' title="About this app" id='btnApp'>About</button></a>
</div>



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


