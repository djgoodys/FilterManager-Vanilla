<?php
if(session_id() == ''){
      session_start();
   }

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//SCREEN WIDTH ON MY MOBILE IN PORTAIT MODE IS 384
//SCREEN WIDTH ON MY MOBILE IN LANDSCAPE MODE IS 854COOKIE_

include 'functions.php';
include 'javafunctions.php';
include 'fm.css';
include 'checkbox2.css';

$NewUser="";
$Response="";

//print_r($_COOKIE);

//print_r($_POST);
//print_r($_SESSION);
//echo "last query=".$_COOKIE["cookie_lastquery"];
$Action=null;
$UserName="";
$User_Name="";
$Password="";
$NewUser = "";
$Response = "";

function PHPIsMobile() {
$isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"));
return $isMob;
}

?>


<style>
.menu {
  display:inline-block;
  background-image: url('images/menu3.png');
  width:70px;
  height:70px;
  margin-left:0px;
  box-shadow: 4px 4px black;
  border-radius:8px;
  background-size: 77px 77px; /* Replace with your desired dimensions */
  background-position: center; 
  background-repeat: no-repeat; 
}
table {
  margin-right:auto;
  margin-left:auto;
}
.overlay-mobile{
  padding: 1px;
  text-decoration: none;
  font-size: 28px;
  color:yellow;
  display: inline-block; /* Display block instead of inline */
  transition: 0.3s; /* Transition effects on hover (color) */
}
/* The Overlay (background) */
.overlay {
  /* Height & width depends on how you want to reveal the overlay (see JS below) */   
  height: 100%;
  width: 0;
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  background-color: rgb(0,0,0); /* Black fallback color */
  background-color: rgba(0,0,0, 0.9); /* Black w/opacity */
  overflow-x: hidden; /* Disable horizontal scroll */
  transition: 0.5s; /* 0.5 second transition effect to slide in or slide down the overlay (height or width, depending on reveal) */
}

/* Position the content inside the overlay */
.overlay-content {
  position: relative;
  top: 1%; /* 25% from the top */
  width: 100%; /* 100% width */
  text-align: center; /* Centered text/links */
  margin-top: 5px; /* 30px top margin to avoid conflict with the close button on smaller screens */
}

/* The navigation links inside the overlay */
.overlay a {
  padding: 1px;
  text-decoration: none;
  font-size: inherit;
  color:yellow;
  display: inline-block; /* Display block instead of inline */
  transition: 0.3s; /* Transition effects on hover (color) */
}

/* When you mouse over the navigation links, change their color */
.overlay a:hover, .overlay a:focus {
  color: #f1f1f1;
}

/* Position the close button (top right corner) */
.overlay .closebtn {
  top: 0px;
  right: 5px;
  padding-left:45px;
  font-size: 60px;
}

/* When the height of the screen is less than 450 pixels, change the font-size of the links and position the close button again, so they don't overlap */
/* FOR MY TEST MOBILE SCREEN HEIGHT WAS 80 PC WAS 125 */
@media screen and (min-height: 144px) and (max-height: 450px) {
  .overlay a {font-size: 2em;}
  .overlay .closebtn {
    font-size: 2em;
    top: 15px;
    right: 35px;
  }
}
/* BELOW IS FOR MOBILE SETTINGS */
@media screen and (min-height: 80px) and (max-height: 125px) {
  .overlay a {
    font-size: 1em;
    }
  .overlay .closebtn {
    font-size: 2em;
    top: 0px;
    right: 35px;
  }
}
.tblLogin {
display:block;
width:800px;
margin-left:22vw;
margin-right:auto;
}
input[type="search"]::-webkit-search-cancel-button {

  /* Remove default */
  -webkit-appearance: none;

  /* Now your own custom styles */
  height: 10px;
  width: 10px;

  background-repeat: no-repeat;
  background-size: 12px;
  background-position: top left;
  background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFIAAABSAQMAAAD94hHYAAAABlBMVEUAAACXl5cNUA2AAAAAAnRSTlP9B0nlx2sAAABxSURBVHhe7dK9DYYgFIXhYywsGYFRHA3djMRFGIGS4gb0XoRg4s9XfRVvcfIMcJCOnKwVLzCN52pAFxOgxISzySIUj1f7Ow/PTm5O3JB+9ha1L15JOfN/d3fLDz++qpkxW7EpG9KrffbIDo0pe2LX7A7qXBSslhfN8AAAAABJRU5ErkJggg==);
}

table, td {
text-align: center; 
display: inline-block;
vertical-align: middle;
padding-left:10px;
padding-right:10px;
padding-top:auto;
padding-bottom:auto;
}

.popup {
  position: relative;
  display: inline-block;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* The actual popup */
.popup .popuptext {
  visibility: hidden;
  width: 260px;
  background-color: red;
  font-weight:bold;
  color: #fff;
  text-align: center;
  border-radius: 6px;
  padding: 8px 0;
  position: absolute;
  z-index: 1;
  bottom: 125%;
  left: 50%;
  margin-left: -80px;
}

/* Popup arrow */
.popup .popuptext::after {
  content: "";
  position: absolute;
  top: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: #555 transparent transparent transparent;
}

/* Toggle this class - hide and show the popup */
.popup .show {
  visibility: visible;
  -webkit-animation: fadeIn 1s;
  animation: fadeIn 1s;
}

/* Add animation (fade in the popup) */
@-webkit-keyframes fadeIn {
  from {opacity: 0;} 
  to {opacity: 1;}
}

@keyframes fadeIn {
  from {opacity: 0;}
  to {opacity:1 ;}
}

#txtUserName:hover {
 background-color:white;
color:green;
transition: 0.2s;
}

* { box-sizing: border-box; }
body {
  font: 16px Arial;
background-color:<?php echo $BackGroundColor ?>;
overflow-y:auto;
text-align: center;
overflow-x:auto;
}
.autocomplete {

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
$CheckBoxLate="";
if(isset($_POST["action"])){$Action = $_POST["action"];}
if(isset($_GET["action"])){$Action = $_GET["action"];}       
if(isset($_POST["username"])){$UserName=$_POST["username"];}
if(isset($_POST["password"])){$Password=$_POST["password"];}
$ShowOverDue="";
//DEFAULT BOX
$CheckBoxLate="<div id='divCkLate' class='divCkLate-black'  onclick='changeImage();' ><p>over</p><p>due</p></div>";

if(isset($_COOKIE["cookie_ckoverdue"])){
$ShowOverDue=$_COOKIE["cookie_ckoverdue"];

if($_COOKIE["cookie_ckoverdue"] == "true"){
$CheckBoxLate="<div class='divCkLate-red' id='divCkLate' onclick='changeImage();'><p>over</p><p>due</p></div>";
}
}
if(isset($_COOKIE["cookie_ckoverdue"])){
$ShowOverDue = $_COOKIE["cookie_ckoverdue"];}
if(isset($_POST['ckoverdue']))
   {
     
      if($_POST['ckoverdue'] == true)
         {
            echo("<br>POST overdue=".$_POST['ckoverdue']);
         }
    }


  
?>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="jquery.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<meta charset="utf-8"
     name="viewport" content="width=device-width, initial-scale=1">
      <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">
    </script>
    <title>Filter Manager Login</title>

<style>
.divCkLate-red {
font-size: clamp(20px, 50% + 20px, 10px);
width: 50px;
height: 50px;
margin-top:5px ;
padding-top: 12px;
display:flex;
flex-direction:column;
border:3px solid red;
color:white;
background-color:red;
font-weight:bold;
border-radius:25%;
box-shadow: 0px 0px 8px 2px black;
line-height: 2px;
}
.divCkLate-black {
font-size: clamp(20px, 50% + 20px, 10px);
width:52px;
height:52px;
border-radius:25%;
vertical-align:-webkit-baseline-middle;
margin-top:5px ;
padding-top: 13px;
line-height: 2px;
display:flex;
flex-direction:column;
border:3px solid green;
background-color:#97D09D;
color:black;
font-weight:bold;
box-shadow: 4px 4px black;
}

.square {
  width: 70px;
  height: 70px;
  background-color: blue;
  position: relative;
  animation: rotate 3s linear;
  animation-iteration-count: 1;
  animation-fill-mode: forwards;
}

.letter {
  font-size: 15px;
  font-weight: bold;
  text-align: center;
  position: absolute;
  color: white;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  animation-fill-mode: forwards;
}
@keyframes fadeIn {
    from { opacity: 0; }
      to { opacity: 1; }
}


@keyframes rotate {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
  to {
        width:0;
        height:0;
        visibility:hidden;
    }
}

   /* Dropdown Button */
.dropbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px;
  font-size: 16px;
  border: none;
}


.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: green;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: green;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #ddd;}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {display: block;}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {background-color: #3e8e41;}  

input[type=text]{
   height:40px;
   width:150px;
   font-size:1em;
   color:black;
   border-style: solid;
   border-width: 4px;
   border-color: gold;
}
input[type=password]{
   height:40px;
   width:150px;
   font-size:1em;
   color:white;
   border-style: solid;
   border-width: 4px;
   border-color: gold;
}


.myForm {
  border: none;
  padding: 1px 1px;
  text-decoration: none;
  display: inline-block;

  }

.myButton {
  background-color: #97D09D; 
  border:3px solid green;
  color: black;
  font-size:1em;
  font-family:Arial, sans-serif;
  padding: 1px 1px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  width:120px; 
  height:40px;
  border-radius: 30px;
box-shadow: 4px 4px black;
  }
.myButton3 {
  background-color: #4CAF50; /* Green */
  border: none;
  color: yellow;
  font-size:1.5em;
  padding: 1px 1px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  width:15vw; 
  height:20vhw; 
border-radius:25px;
box-shadow:4px 4px black;
margin-top:20px;
  } 
  .myButton2 {
  background-color: #4CAF50; /* Green */
  border: none;
  color: yellow;
  font-weight:bold;
  font-size:1.25vw;
  padding: 1px 1px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  width:230px; 
  height:30px; 
  }

.myButtonMobile {
  background-color: #4CAF50; /* Green */
  border: none;
  color: white;
  font-size:1.25em;
  font-family:Arial, sans-serif;
  padding: 1px 1px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  width:8vw; 
  height:70px; 
  border-radius: 5px;
  box-shadow: 4px 4px black;
  }

  #tblNavigation{
      margin: 0 auto;
  }

  #tblNavigation tr {
      display:flex;
      flex-direction:row;
  }
  .myButton2Mobile {
  background-color: #4CAF50; /* Green */
  border: none;
  color: yellow;
  font-weight:bold;
  font-size:1.25vw;
  padding: 1px 1px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  width:230px; 
  height:30px; 
  }
.myButton2:hover {
  background-color: yellow;
color:green;
}
</style>

<script>
function adjustIframe(xframe, xtop, xheight) 
  {
    const parentWindow = window.parent;
    parentWindow.postMessage({ action: 'resize', id: xframe, height: xheight, top: xtop}, "*");
  } 
</script>

<script>
function addMenuButton() {
  const theCell = document.getElementById("tdMenu");
  //const image = document.createElement("img");
  const image = document.createElement("div");
   image.id ="menuNavigation"; 
   image.classList.add('menu');
   image.setAttribute("onclick", "toggleNavigation('tblTools');");
  //image.src = 'images/menu.png'; 
  theCell.appendChild(image);
}
</script>
<script>
function changeImage(){
var divCkLate = document.getElementById("divCkLate");
//var imgCkLate = document.getElementById("imgCkLate");
var imgBorder = document.getElementById("divImgBorder");
var divfont = document.getElementById("divcklatefont");


  if (divCkLate.className==
"divCkLate-red") 
    {
      divCkLate.className="divCkLate-black";
    } 
      else 
    {
      divCkLate.className="divCkLate-red";
    }
    $('#ckoverdue').trigger('click');

resetElements('ckoverdue');
setLastQuery('overdue');
}
</script>
<script>
/* Open when someone clicks on the span element */
function openNav() {
  document.getElementById("myNav").style.width = "100%";
}

/* Close when someone clicks on the "x" symbol inside the overlay */
function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}
</script>

<script>
function autocomplete(inp, arr) {
  
var currentFocus;
inp.addEventListener("input", function(e) 
{
    var a, b, i, val = this.value;
    closeAllLists();
    if (!val) 
        { 
            return false;
        }
    currentFocus = -1;
    a = document.createElement("DIV");
    a.setAttribute("id", this.id + "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");
    a.setAttribute("onclick", "if(document.getElementById('divCkLate').className=='divCkLate-red'){changeImage();};setLastQuery('search');isMobileDevice();");
    this.parentNode.appendChild(a);
    for (i = 0; i < arr.length; i++) 
        {
            if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) 
                {
                    adjustIframe("iframe1", "0", "800px");
                    b = document.createElement("DIV");
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    b.addEventListener("click", function(e)
                          {
                        inp.value = this.getElementsByTagName("input")[0].value;
                        closeAllLists();
                        //setFrame2();
                        setLastQuery('search');
                        isMobileDevice();
                        });
                    a.appendChild(b);
                }
        }
  });
  inp.addEventListener("focusout", function(e)
  {
    adjustIframe("iframe1", "0", "125px");
  });

  inp.addEventListener("keydown", function(e) {                                
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        currentFocus++;
        addActive(x);
      } else if (e.keyCode == 38) { //up
        currentFocus--;
        addActive(x);
      } else if (e.keyCode == 13) {
        e.preventDefault();
        if (currentFocus > -1) {
          if (x) x[currentFocus].click();
        }
      }
  });

  function addActive(x) {
    if (!x) return false;
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    x[currentFocus].classList.add("autocomplete-active");
  }
  
  function removeActive(x) {
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}

document.addEventListener("click", function (e) {
    closeAllLists(e.target);
Top =getJavaCookie('cookie_iframe2.top') 
    //window.parent.document.getElementById('iframe1').style.height = "124";
});
}
</script>
<script>
   function enterKeyPressed(event) {
      if (event.keyCode == 13) {
         resetElements('search');
          setLastQuery('search');
         return true;
      } else {
         return false;
      }
   }
</script>

<script>
function setLastQuery(element)
{
   let exdate = new Date(Date.now() + 86400e3);
   exdate = exdate.toUTCString();
   var Selected = document.getElementById("txtByDate").value;
   setCookie("cookie_bydate="+Selected);

   switch(element) 
    {
      case "overdue":
      cklate = document.getElementById("divCkLate");
      if(document.getElementById("divCkLate").className =="divCkLate-red")
        {
          setCookie("cookie_lastquery=OVERDUE"); 
          setCookie("cookie_ckoverdue=true");
        }
        else
        {
        setJavaCookie("cookie_lastquery", "SELECT", 1); 
        deletecookie("cookie_ckoverdue");
        }
        parent.document.getElementById("iframe2").src="ListEquipment.php";
        break;

        case "bydate":
        var text = document.getElementById('txtByDate').value;
        var currentdate = new Date();
        var date = currentdate.getDay() + "-" + currentdate.getMonth() + "-" + currentdate.getFullYear();
        var sql="";
        if(text=="newest")
          {
            sql="DESC";
          }
        if(text=="oldest")
          {
            sql="ASC";
          }
        if(text=="today")
          {
            sql="today";
          }
        if(text=="normal")
          {
            sql="NORMAL";
          }
        console.log("text="+ text+" cookie_lastquery="+sql+"; expires="+exdate+"; path=/");
        setCookie("cookie_lastquery="+sql);
        parent.document.getElementById("iframe2").src="ListEquipment.php";
        break;

        case "search":      
        SearchWords=document.getElementById("myInput").value.trim();
        setCookie("SearchWords="+SearchWords);
        if(SearchWords.length > 0)
          {
            sql= "SELECT  _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment WHERE location LIKE '%"+SearchWords+"%' OR unit_name LIKE '%"+SearchWords+"%' OR area_served LIKE '%"+SearchWords+"%' OR filter_size LIKE '%"+SearchWords+"%' OR notes LIKE '%"+SearchWords+"%' OR filters_due LIKE '%"+SearchWords+"%' OR belts LIKE '%"+SearchWords+"%' OR assigned_to LIKE '%"+SearchWords+"%' OR filter_type LIKE '%"+SearchWords+"%' OR filters_last_changed LIKE '%"+SearchWords+"%'";
            let encoded = encodeURIComponent(sql);
            setCookie("cookie_lastquery="+encoded);
            //document.getElementById("frmSearch").submit();
            parent.document.getElementById("iframe2").src="ListEquipment.php";
          }
      }
}
</script>
<script>
function resetElements(element){
isMobileDevice();
   switch(element){
      case "ckoverdue":
         if(document.getElementById("divCkLate").className == "divCkLate-red")
          {
            document.getElementById("myInput").value = "";
          }

         break;

      case "bydate":
         Selected = document.getElementById("txtByDate").value;
         if(Selected == "today"){myvalue = "&nbsp;&nbsp;Today&nbsp;&nbsp;";}
         if(Selected == "oldest"){myvalue = "&nbsp;&nbsp;Oldest to Newest&nbsp;&nbsp;";}
         if(Selected == "newest"){myvalue = "&nbsp;&nbsp;Newest to Oldest&nbsp;&nbsp;";}
         if(Selected == "off")
            {
              innerHTML="&nbsp;&nbsp;Sort by...&nbsp;&nbsp;";
            }
          document.getElementById("divCkLate").className = "divCkLate-black"
          document.getElementById("myInput").value = "";
        break;
         
      case "search":
         if(document.getElementById("myInput").value != "")
          {

          }
          break;
   }
}
</script>


<script>
function deletecookie(cname){
   document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
}
   </script>


<script>
function getCookie(cname) {
   let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
</script>

     <script>
   function logout(){
      deletecookie("cookie_username");
      document.getElementById('tblNavigation').style.display = 'none';
      document.getElementById('tblTools').style.display = 'none';
      document.getElementById('txtUserName').value= "";
      parent.document.getElementById('iframe2').src  = "start.php";
      parent.document.getElementById('iframe1').src = "<?php echo $_SERVER
      ['SCRIPT_NAME'] ?>?action=logout'>";

   }
</script>

<script>
function setCookie(cookie_string){
   const d = new Date();
               d.setTime(d.getTime() + (1*24*60*60*1000));
               let expires = "expires="+ d.toUTCString();
               document.cookie = cookie_string + ";" + expires + ";path=/";
}
</script>

<script>

function DoOnLoadStuff()
  { 
    //LOGGED IN OR NOT
    var LoggedIn = false;
    if(document.getElementById("txtLoggedIn").value == "true"){
     LoggedIn = true;
    }

    var orient = "landscape";
    if(screen.availHeight > screen.availWidth)
      {
        orient = "portrait";
      }
    
    if(LoggedIn == true)
      {
        setJavaCookie("cookie_username",  document.getElementById("UserName").value);
        window.parent.document.getElementById("iframe2").src = "welcome1.php?backupfolder=<?php echo $_SESSION["backup_folder"] ?>";
      }

      mTable=document.getElementById("tblNavigation");
      var mButtons = mTable.getElementsByTagName("button");
      if(isMobileDevice() == true)
          {
            document.getElementById("btnBugReport").classList.replace("myButton", "myButtonMobile");
            document.getElementById("btnHelp").classList.replace("myButton", "myButtonMobile");
            document.getElementById("btnLogOut").classList.replace("myButton", "myButtonMobile");
            document.getElementById("btnFilters").classList.replace("myButton", "myButtonMobile");
            mButtons = document.getElementById("trButtons").getElementsByClassName('myButton');
            for(let i=0; i < mButtons.length; i++)
              {
                mButtons[i].classList.replace("myButton", "myButtonMobile");
              }
          }

      if(LoggedIn == true && orient == "portrait" && isMobileDevice() == true)
        {
             console.log("orient = "+orient+" mobile = "+isMobileDevice()+ " logged in= "+LoggedIn);
            adjustIframe("iframe1", "0", "100px");
            adjustIframe("iframe2", "0", "2300px");
            //addMenuButton();
            document.getElementById("tblNavigation").style.display="table";
            if(document.getElementById("menuNavigation") != null){document.getElementById("menuNavigation").style.display="none";}
        }

      if(LoggedIn == true && isMobileDevice() == false)
        {
          console.log("isMobileDevice == false");
          document.getElementById("tblTools").style.display="inline-block";
        }

      if(LoggedIn == true && isMobileDevice() == true)
      {
        //addMenuButton("myTable", "menu.jpg");
        document.getElementById("tblNavigation").style.display="none";
        document.getElementById("btnApp").className="myButtonMobile";
        mButtons = document.getElementsByClassName('myButton');
        for(let i=0; i < mButtons.length; i++)
            {
              mButtons.item(i).style.fontSize="1em";
              mButtons.item(i).className="myButtonMobile";
            }
      }
     //PUT HERE SO WOULD HAPPEN ON BODY LOAD-SETS CKLATE CHECKBOX
    
     if (getCookie("cookie_ckoverdue") == "true")
     {
          //console.log("cookie ckoverdue="+getCookie("cookie_ckoverdue"));
         document.getElementById("ckoverdue").checked = true;
     }
     else
     {
          document.getElementById("ckoverdue").checked = false;
     }
     
      //PUTS LAST SEARCH WORDS IN SEARCH BOX and shows clear search X SO A RETURN TO WEB PAGE SHOWS WORDS
         document.getElementById("myInput").value = getCookie("SearchWords");
  }
</script>

<script>
 function ckifloggedin($gotoLocation){
   username = getCookie("cookie_username");
    if($gotoLocation == "nowhere" || $gotoLocation == "")
            {
            window.parent.document.getElementById('iframe2').src = "start.php";
            }
    if (username.length > 0)
      {
        window.parent.document.getElementById('iframe2').src = $gotoLocation;
      }
      else
      {
        logout();
      }
}
</script>
<script>
function clearsearch(){
   setJavaCookie("cookie_lastquery", "NORMAL","1");
   deletecookie("SearchWords");
   window.parent.document.getElementById('iframe2').src = "ListEquipment.php";
   setFrame2();
   if(document.getElementById("divCkLate").className=="divCkLate-red"){changeImage();}
	document.getElementById('clearsearch').className = "d-none";
}
</script>
<script>
function toggleNavigation(table)
{

switch(table) { 
  case "tblNavigation":
    if(document.getElementById('tblNavigation') != null){document.getElementById('tblNavigation').style.display = "inline-block";}

    if(document.getElementById('tblTools') != null){document.getElementById('tblTools').style.display = "none";}

    if(document.getElementById('menuNavigation') != null){document.getElementById('menuNavigation').style.display = "inline-block";}
    break;
  case "tblTools":
    if(document.getElementById('tblNavigation') != null){document.getElementById('tblNavigation').style.display = "none";}

    if(document.getElementById('tblTools') != null){document.getElementById('tblTools').style.display = "inline-block";}

    if(document.getElementById('menuTools') != null){document.getElementById('menuTools').style.display = "inline-block";}
    break;
  }
}
</script>
<script>
screen.orientation.onchange = function (){
setFrame2();
}
</script>

<script>
function setFrame2()
{
  var orient = "landscape";
  if(screen.availHeight > screen.availWidth)
    {
      orient = "portrait";
    }
var LoggedIn = false;
if(document.getElementById("txtLoggedIn").value == "true")
{
    LoggedIn = true;
}
if(LoggedIn == true && isMobileDevice() == false)
{
    adjustIframe("iframe1", "0", "125px");
    adjustIframe("iframe2", "0", "2300px");    
    
}

if(LoggedIn == true && isMobileDevice() == true && orient == "landscape")
{
    adjustIframe("iframe1", "0", "75px");
    adjustIframe("iframe2", "0", "2300px");    
    if(document.getElementById("tblNavigation") != null){document.getElementById("tblNavigation").style.display="inline-block";}

    if(document.getElementById("menuNavigation") != null){document.getElementById("menuNavigation").style.display="inline-block";}

    if(document.getElementById("menuNavigation") != null){document.getElementById("menuNavigation").style.display="inline-block";}

    if(document.getElementById("menuTools") != null){document.getElementById("menuTools").style.display="none";}

    if(document.getElementById("tblTools") != null){document.getElementById("tblTools").style.display="none";}
}

if(LoggedIn == true && isMobileDevice() == true && orient == "portrait")
    {
      adjustIframe("iframe1", "0", "72px");
      adjustIframe("iframe2", "0", "2300px");
      if(document.getElementById("tblNavigation") != null){document.getElementById("tblNavigation").style.display="inline-block";}

      if(document.getElementById("menuNavigation") != null){document.getElementById("menuNavigation").style.display="inline-block";}

      if(document.getElementById("menuTools") != null){document.getElementById("menuTools").style.display="inline-block";}
    }

if(LoggedIn == false && isMobileDevice() == true && orient == "landscape")
  {
    adjustIframe("iframe1", "0", "80px");
    adjustIframe("iframe2", "0", "4300px");
    if(document.getElementById("tblNavigation") != null){
      document.getElementById("tblNavigation").style.display="none";}

    if(document.getElementById("menuNavigation") != null){document.getElementById("menuNavigation").style.display="none";}

    if(document.getElementById("menuTools") != null){document.getElementById("menuTools").style.display="none";}

    if(document.getElementById("tblTools") != null){document.getElementById("tblTools").style.display="none";}

    if(document.getElementById("tblLogin") != null){document.getElementById("tblLogin").style.display="table";}
  }

if(LoggedIn == false && isMobileDevice() == true && orient == "portrait")
    {
      adjustIframe("iframe1", "0", "90px");
      adjustIframe("iframe2", "0", "1600px");
      if(document.getElementById("tblNavigation") != null){document.getElementById("tblNavigation").style.display="none";}

      if(document.getElementById("menuNavigation") != null){document.getElementById("menuNavigation").style.display="none";}

      if(document.getElementById("menuTools") != null){document.getElementById("menuTools").style.display="none";}

      if(document.getElementById("tblTools") != null){document.getElementById("tblTools").style.display="none";}

      if(document.getElementById("tblLogin") != null){document.getElementById("tblLogin").style.display="inline-block";}
    }

myvalue=document.getElementById("divTables").offsetHeight;
// window.parent.document.getElementById('iframe1').height = myvalue;
}
</script>
<script>
function saveElementPositions(){
if(document.getElementById("tblNavigation").style.display=="inline-block"){
setCookie("lasttable=tblNavigation");
}
else
{
setCookie("lasttable=tblTools");
}
}
</script>
</head>

<body onload="DoOnLoadStuff();setFrame2();"  onunload="saveElementPositions();">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<?php
$ua = strtolower($_SERVER["HTTP_USER_AGENT"]); 
$isMob = is_numeric(strpos($ua, "mobile"));
if($isMob == 1){
  $mobile="yes";
}else{
  $mobile="no";
}
$_SESSION["admin"] = "";
$UserNamePassed="";

if(isset($_COOKIE["cookie_username"])) {
 //echo "logging in as".$_COOKIE["cookie_username"];
      $UserName=$_COOKIE["cookie_username"];   
      $file = "table_users.json";
      $jsonString = file_get_contents($file);
      $data = json_decode($jsonString, true);
      $found = false;
      foreach ($data as $user) {
      //echo $user["user_name"] ."=". $UserName;
      if ($user["user_name"] == $UserName) 
        {
      $UserNamePassed = "true"; 
      $_SESSION['user_name'] = $user["user_name"];
      $_SESSION['theme'] = $user["theme"];
      $_SESSION['font_family'] = $user["font_family"];
      $_SESSION['font_size'] = $user["font_size"];
      $_SESSION['admin'] = $user["admin"];
      $_SESSION['field2'] = $user["field2"];
      $_SESSION['field3'] = $user["field3"];
      $_SESSION['backup_folder'] = $user["backup_folder"];  
      //echo "backup=".$user["backup_folder"].$user["admin"];
      //echo "user=".$user["admin"];
      $_SESSION["admin"] = $user["admin"];
      if(isset($_SESSION["theme"]) == "td-DarkPlain")
        {
          $_SESSION["background_color"] = "black";
          $_SESSION["font_color"] = "white";
        }
          else
        {
          $_SESSION["background_color"] = "white";
          $_SESSION["font_color"] = "black";
        } 
        }
      }
}
?>
<script>
sessionStorage.setItem("font_size", "<?php echo $_SESSION['font_size'] ?>");
</script>
<?php

if(strcmp($Action, "login")==0 && $UserNamePassed == "")
{  
  $UserNamePassed = "false";
  if(isset($_POST["username"])) {$UserName = $_POST["username"];}
  if(isset($_POST["password"])) {$Password = $_POST["password"];}
  $file = "table_users.json";
  $data = file_get_contents($file);
  $users = json_decode($data, true);
  $found = false;
  foreach ($users as $user) {
 ////echo $user["backup_folder"]." == ".$UserName;
  if ($user["user_name"] === $UserName && $user["password"] === $Password) 
    {
//echo $user["user_name"] . $UserName. " ". $user['password'] . $Password."<br>";
      $UserNamePassed = "true"; 
      $_SESSION['user_name'] = $user["user_name"];
      $_SESSION['theme'] = $user["theme"];
      $_SESSION['font_family'] = $user["font_family"];
      $_SESSION['font_size'] = $user["font_size"];
      $_SESSION['admin'] = $user["admin"];
      $_SESSION['field2'] = $user["field2"];
      $_SESSION['field3'] = $user["field3"];
      $_SESSION['backup_folder'] = $user["backup_folder"]; 
      $_SESSION["admin"] = $user["admin"];
      if(isset($_SESSION["theme"]) == "td-DarkPlain")
        {
          $_SESSION["background_color"] = "black";
          $_SESSION["font_color"] = "white";
        }
          else
        {
          $_SESSION["background_color"] = "white";
          $_SESSION["font_color"] = "black";
        }
    }
}
}
//echo "UserNamePassed=". $UserNamePassed;
if($UserNamePassed == "true"){
  $arUnits = array();
 $file = 'sites/'.$_SESSION["backup_folder"].'/data.json';
  $data = file_get_contents($file);
  $units = json_decode($data, true);
  $found = false;
  if (isset($units['equipment'])) { // Check if "equipment" key exists
    foreach ($units['equipment'] as $obj) {
    array_push($arUnits, $obj["unit_name"]);
  }
  }
?>
<script>
<?php
$js_array = json_encode($arUnits);
echo "var unitsArray = ". $js_array . ";\n";
?>
</script>
<?php
}
     ?><input type="text" style="display:none;" id="txtLoggedIn" value="<?php echo $UserNamePassed; ?>"> 
    <div id="divTables" style="display:flex;flex-direction:column;">
    <?php 
    if($_SESSION["admin"] == "none"){echo "<div style='height:200px;background-color:black;color:white;text-align:center;width:fit-content;margin-right:auto;margin-left:auto;'>
   You are in admin mode because there are currently no users installed. Click <br><a href='admin.php' target='iframe2'>HERE</a><br> to enter admin control panel and create your
   Filter Manager username and password. <p style='color:red;'>IMPORTANT select ADMIN yes option on next page. You will be responsable for adding
   other users</p></div>";}
   
      if($UserNamePassed == "true" && strlen($UserName) > 0)
         {
            echo "<table id='tblNavigation'>";
         }
            else
         {
            echo "<table id='tblNavigation' style='display: none;'>";
         }
		?>
		<tr id="trButtons">
      <td id="tdMenu"><?php if(PHPIsMobile() == true){?><div class="menu" onclick="toggleNavigation('tblTools');">&nbsp;&nbsp;&nbsp;&nbsp;</div> <?php } ?></td>
      <td>                  
         <div class="dropdown" >
			<button class='myButton' onclick="ckifloggedin('ListEquipment.php');" id='btnUnits' name='btnUnits' class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false title="Go to unit list">Units</button>
         <div class="dropdown-content">
            <button class='myButton2' onclick="ckifloggedin('webAddUnit.php');">Create new unit</button>
            <button class='myButton2' onclick="ckifloggedin('ListEquipment.php');">Goto unit list</button>
         </div>
      </div>
</td>

<?php 
if(isset($_SESSION['admin'])){
if(strcmp($_SESSION['admin'], "yes") == 0){
?>
<td><button class='myButton' onclick="ckifloggedin('admin.php');" id='btnAdmin' data-placement='top' title='For admins only'>Admin</button></td>
<?php
}
}
?>
<td>
		   <button id="btnGoToTasks" name="btnGoTasks" onclick="ckifloggedin('WebTasks.php?action=getalltasks');" class="myButton" data-toggle="tooltip" data-placement="top" title="Go to tasks">Tasks</buttton>	
</td><td>
         <div class="dropdown">
			<button class='myButton' onclick="ckifloggedin('web_update_filters.php');" id='btnFilters' name='btnfilters' data-toggle="tooltip" data-placement="top" title="Go to Filter Inventory Control">Filters</button>
         <div class="dropdown-content">
            <button class='myButton2' onclick="ckifloggedin('web_add_filter.php');">Create new filter</button>
            <button class='myButton2' onclick="ckifloggedin('web_update_filters.php');">View filter inventory</button>
            <button class='myButton2'  onclick="ckifloggedin('order.php');">Create filter order</button>
            <button class='myButton2' onclick="ckifloggedin('ManageFilterTypes.php');">Manage filter types</button>
         </div>
      </div>
</td><td>
         <button class="myButton" style="font-size:.75em;" onclick="ckifloggedin('BugReport.php');" id="btnBugReport"  Value="BugReport" data-placement="top" title="Report a problem with this software">Bug<br>Report</button>
</td><td>
		   <button onclick="window.open('help/HelpIndex.html', '_blank');" class="myButton" data-toggle="tooltip" data-placement="top" title="Help using Filter Manager" id='btnHelp' name='action'>Help</button>
</td><td>
		   <button onclick="logout();" class='myButton' data-toggle="tooltip" data-placement="top" title="Log out of Filter Manager" id='btnLogOut'>Log Out</button>
</td><td>
		   
<a href="#" onclick="ckifloggedin('about.html');"><button class='myButton' title="About this app" id='btnApp'>About</button></a>
</td></tr>
</table>
        
    <?php
     $DisplayType = "";
     
        if($UserNamePassed == "true")
            {
                $DisplayType = "block";
            }
                else
            {
                $DisplayType = "none";
            }

            $ByDate="Order by date";
            if(isset($_POST["bydate"])){$ByDate=$_POST["bydate"];}
    ?>
        <table id='tblTools' style="display:none">
        <tr><td><?php if(PHPIsMobile() == true){?><div class="menu" id="menuTools" onclick="toggleNavigation('tblNavigation');" style="display:none;"></div> <?php } ?></td>
        <td style="margin-top:10px;">

         <form action="ListEquipment.php" method="POST" id="frmcklate" target="iframe2">
        <?php echo $CheckBoxLate ?>

  <input type="checkbox"  style="visibility:hidden;height:0px;width:0px;"  id="ckoverdue" name="ckoverdue" <?php if($ShowOverDue == "true"){echo " checked";} ?>">

</form></td>

<td style="padding-top:0px;vertical-align:top;height:auto;">
<form id="frmSearch" autocomplete="off" action="ListEquipment.php" target="Iframe2" method="post" style="margin: auto;vertical-align:top;">
<input type="hidden" name="showsize" value="search">
<span class="input-group-text border-0 flex-nowrap" id="search-addon" style="vertical-align:top;width:220px;margin-top: 8px">
<div class="autocomplete" style="width:300px;height:auto;background-color:black;color:black;border: 3px solid green;margin-top:10px;box-shadow: 4px 4px black;">
<input type="search" onkeypress="enterKeyPressed(event);" style="margin: auto;vertical-align:top;width:fit-content;max-width:140px;" name="search_words" id="myInput" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon">
<script>
$('#myInput').on('input', function(e) {
  if('' == this.value) {
    clearsearch();
  }
});
</script>
     </div></form><img src="images/search3.png" style="box-shadow: 4px 4px black;height:50px;width:50px;margin-left:5px;border-radius:30px;border:3px solid green;" id="img_search" text-align="middle" onclick="resetElements('search');setLastQuery('search');">
<script>
        $("#myInput").keyup(function (event) {
            if (event.keyCode === 13) {
                $("#img_search").click();
               //document.getElementById('img_search').click();
            }
        });

</script>
<script>
autocomplete(document.getElementById("myInput"), unitsArray);
</script>
    <i class="fas fa-search"></i>
  </span></div>
 </td>
         
		 <td>
        <div id="divOutOfStock" style="border: 3px solid green;margin-top:0px;margin-left:20px;width:fit-content;box-shadow: 4px 4px black;height:fit-content;">
          <div style="white-space: nowrap;background-color:orange;font-size:clamp(min-size, calc(100% - padding), 1.25em);color:white;height:20px;">out of stock</div>    
       <div style="background-color:red;white-space: nowrap;font-size:clamp(min-size, calc(100% - padding), 1.25em);color:white;text-align:center;height:20px;">over due</div>
      </div>
      </td>
       <td><input type="textbox" value="<?php echo $UserName ?>"  style="margin-top: 0px;box-shadow: 3px 3px black;border-radius:50%;text-align:center;background-color:#97D09D;color:black;font-weight:bold;font-size:1em;height:50px;border:3px solid green;width:<?php echo strlen($UserName) * 25 ?>px;" id="UserName"></td>
   <td><a href="#" onclick="ckifloggedin('print.php');"><img src="images/print3.png" title="print unit list" style="border:3px solid green;;height:50px;border-radius:50%;box-shadow: 4px 4px black;margin-top: 0px;"></a></td>
<td><a href="#" title="go to app settings" onclick="ckifloggedin('settings.php');"><div style="background-image: url('images/settings3.png');box-shadow: 4px 4px black;background-size:50px 50px;background-position:center; border:3px solid green;margin-top: 0px;border-radius:50%;height:50px;width:50px;"></div></a>
</td>
<td>
<!-- The overlay -->
<script >
function ckOverLay(){
if(isMobileDevice() == true){
  document.getElementById("divOverLayContent").className = "overlay-mobile";
  }
}
</script>
<div id="myNav" class="overlay">

  <!-- Overlay content -->
  <div class="overlay-content" id="divOverLayContent">
    [<a href="#" onclick="resetElements('bydate');closeNav();document.getElementById('txtByDate').value='oldest';document.getElementById('btnSortBy').innerHTML=this.innerHTML;setLastQuery('bydate');">Newest to oldest</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    [<a href="#" onclick="resetElements('bydate');closeNav();document.getElementById('txtByDate').value='newest';document.getElementById('btnSortBy').innerHTML=this.innerHTML;setLastQuery('bydate');">Oldest to newest</a>]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    [<a href="#" onclick="resetElements('bydate');closeNav();document.getElementById('txtByDate').value='today';document.getElementById('btnSortBy').innerHTML=this.innerHTML;setLastQuery('bydate');">Today</a>]
  &nbsp;&nbsp;
    [<a href="#" onclick="resetElements('bydate');closeNav();document.getElementById('txtByDate').value='normal';document.getElementById('btnSortBy').innerHTML='Sort by ...';setLastQuery('bydate');">Normal</a>]
    <!-- Button to close the overlay navigation -->
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
</div>

</div>
<input type="text" id="txtByDate" style="display:none;">
<button class="myButton" style="color:yellow;margin-top: 0px;" id="btnSortBy" onclick="ckOverLay();openNav()">Sort by ...</button>

         </td>
       <?php 
       if(strcmp($ByDate,"today")==0) {
        $currentDate = new DateTime();
        $TodaysDate = $currentDate->format('Y-m-d');
       echo "<fontsize='20px'>Due today: ".$TodaysDate;}
       if(strcmp($ByDate,"oldest")==0) {echo "<fontsize='20px'>Oldest to newest";} ?></td>
       <td>
      </tr>
      </font>
   </table>
<?php
    $Display = "inline-block";
   if($UserNamePassed == "true" || $_SESSION["admin"] == "none")
    {
      $Display = "none";
      //echo "no admin=".$_SESSION["admin"];
    }
if(strcmp($UserNamePassed, "false")==0)
   {
		$Response="Invalid user name or password";
   }
        echo "<table id='tblLogin' style='margin-left:200px;margin-right:auto;margin-top:20px;display:".$Display.";'>";

		?>
<tr><td>
<div style='font-weight:bold;color:white;font-size:1em;background-color:red;'><?php echo $Response ?></div>
<div style="display:flex;flex-direction:row;">
<form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
<input type="text" id="txtUserName" placeholder="ENTER USER NAME HERE" name="username" value="dj" size="21" maxlength="10" height="40 style="margin-right:auto;margin-left:300px;display:inline-block;" />&nbsp;&nbsp;&nbsp;&nbsp;<input type="password" name="password" value="relays" style="color:black;" id="txtPassword" autocomplete="on" placeholder="ENTER PASSWORD HERE"><img src="images/showpassword.png" style="width:20px;height:auto;" onmouseup="document.getElementById('txtPassword').type='password';" onmousedown="document.getElementById('txtPassword').type='text';">&nbsp;&nbsp;&nbsp;<input type="submit" id="btnLogon" class="myButton" name="action" value="login">
</form><div style="width:fit-content;height:fit-content;background-color:black;color:white;margin:10px;">Use given username and password. Just click log in button.</div></div>
</td><td><a href="login_help.php" title="Password help" target="iframe2"><img src="images/passwordhelp2.png" style="width:40px;box-shadow: 4px 4px black;margin-top: 0px; height:auto;border-radius: 50pc;"></a></td></tr>
</table>

<script defer>
if(document.getElementById("txtLoggedIn").value ==  "true")
{
  setJavaCookie("cookie_username", document.getElementById("UserName").value)
}
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
<script src="jquery.js"></script>        
<script src="bootstrap/js/bootstrap.js"></script>
</body>


          