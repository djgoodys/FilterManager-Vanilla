<?php
//to delete cookie
//setcookie("username", "dj", time() - 3600, "/");
setcookie("username", "dj", time() + (86400 * 30), "/"); // 86400 = 1 day

if(isset($_COOKIE["username"])) {
   $UserNamePassed="true";
   $UserName=$_COOKIE["username"];
}
else
{
header("Location: mLogin.php");
die();
}

if(session_id() == ''){
      session_start();
   }
//echo "<br>session started".session_id();
include "checkbox-mobile.css" ;
include '../dbMirage_connect.php';
include '../functions.php';
include '../javafunctions.php';
include '../fm.css';
include "SideNav.css";
$UserNamePassed="";
//print_r($_COOKIE);


//GET SESSION VARIABLES FOR FONT SIZE, FONT FAMILY ETC...
 $query="SELECT * FROM users WHERE user_name = '" . $UserName . "';";
    $result = $con -> query($query);
   $row = $result -> fetch_assoc();      
   $_SESSION['user_name'] = $row["user_name"];
   $_SESSION['theme'] = $row["theme"];
   $_SESSION['font_family'] = $row["font_family"];
    $_SESSION['font_size'] = $row["font_size"];             

//CREATE AN ARRAY OF FILTER TYPES
$sql = "SELECT type FROM filter_types;";
   $filtertypes = array();
   global $con;
    if ($result = $con->query($sql)) 
        {
             while ($row = $result->fetch_assoc()) 
                {
                  array_push($filtertypes,$row["type"]); 
                }
        }


$Field2="due_date";
if(isset($_SESSION["Field2"] )){
$Field2 = $_SESSION["Field2"];
}else{
$_SESSION["Field2"]="filters_due";
}
if (isset($_GET["Field2"])) 
   {
      $Field2 = $_GET["Field2"];
      $_SESSION["Field2"] = $Field2;
   }
//echo "session field2=".$_SESSION["Field2"];
$ShowOverDue="";
$Action="";
$ByDate="no";
   if(isset($_POST['bydate'])){
     switch ($_POST['bydate']) {
        case "oldest":
           $ByDate = "oldest to newest";
            break;
        case "newest":
           $ByDate = "newest to oldest";
            break;
        case "today":
           $ByDate = "due today";
            break;
        case "normal":
           $ByDate = "normal";
           break;
    }
  }
//print_r($result);
if(isset($_POST['ckoverdue']))
   {
      if($_POST['ckoverdue'] == "on")
         {
            $ShowOverDue="checked";
         }
   }

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
function DoOnLoadStuff(){
         if(getJavaCookie("username").value != "")
            {
               console.log("username from mListEquipment="+getJavaCookie("username"));
               document.getElementById("divUserName").innerHTML = getJavaCookie("username");
            }
  
     //JUST PUT HERE SO WOULD HAPPEN ON BODY LOAD-SETS CKLATE CHECKBOX
     //alert(String(getCookie("cookie_ckoverdue")).toLowerCase());
     if (String(getCookie("cookie_ckoverdue")).toLowerCase() == "true")
     {
         document.getElementById("ckoverdue").checked = true;
     }
     else
     {
         document.getElementById("ckoverdue").checked = false;
     }
      //PUTS LAST SEARCH WORDS IN SEARCH BOX and shows clear search X SO A RETURN TO WEB PAGE SHOWS WORDS
         if(getCookie("SearchWords").length > 0){showClearSearch();}
         document.getElementById("myInput").value = getCookie("SearchWords");

}
</script>
<script>
function setCookie2(cookiename, cookievalue){
   var now = new Date();
  var time = now.getTime();
  now.setFullYear(now.getFullYear() +10);//ten years
    //document.cookie = 'myCookie=to_be_deleted; expires=' + now.toUTCString() + ';';
  //document.cookie = "username=John Doe; expires=Thu, 18 Dec 2013 12:00:00 UTC; path=/";
  document.cookie = cookiename+"="+cookievalue+";expires="+now.toUTCString()+";path=/";
  //alert("done setCookie2 cookiename="+cookiename+" cookievalue="+getCookie(cookiename));
}
</script>
<script>
function showinfo($divID, $UnitName, $UnitID) {
  // alert("showinfo"+$divID);
 console.log("showinfo ="+$divID);
         var my_disply = document.getElementById("tblUnitinfo"+$divID).style.display;
         setCookie2("cookie_infoid", $divID);
         document.cookie = "unitid=" + $UnitID;
        if(my_disply == "none"){
              document.getElementById("tblUnitinfo"+$divID).style.display = "block";
               document.getElementById("btnCloseInfo"+$divID).style.className="btn btn-info d-block";
              //document.getElementById($btnID).style.display = "block";
            }else{
        setCookie2("cookie_infoid", "void");
     }
   }
</script>
<script>
function closeinfo($divID) {
        document.getElementById("tblUnitinfo"+$divID).style.display = 'none';
        setCookie2("cookie_infoid", "void");
     }
</script>
<script>
<?php
$js_array = json_encode($arUnits);
echo "var unitsArray = ". $js_array . ";\n";
?>
</script>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<script>
function setCookie(cookie_string){
   //alert(cookie_string);
   const d = new Date();
               d.setTime(d.getTime() + (1*24*60*60*1000));
               let expires = "expires="+ d.toUTCString();
               document.cookie = cookie_string + ";" + expires + ";path=/";
}
</script>
<script>
function deletecookie(cname){
  // alert("deleting cookie");
   document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
   //alert("qry="+getCookie("cookie_lastquery"));
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
function setLastQuery(element){
   let exdate = new Date(Date.now() + 86400e3);
   exdate = exdate.toUTCString();
   var Selected = document.getElementById("txtByDate").value;
   document.cookie = "cookie_bydate="+Selected+"; expires="+exdate+"; path=/";

   switch(element) {
  case "overdue":
   cklate = document.getElementById("ckoverdue");
   if(cklate.checked){
      document.cookie = "cookie_lastquery=SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment WHERE datediff(CURDATE(),filters_due) > 0; expires="+exdate+"; path=/";
      document.cookie = "cookie_ckoverdue=true; expires="+exdate+"; path=/";
   }
   else
   {
      document.cookie = "cookie_lastquery=SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment; expires="+exdate+"; path=/";
   }
   document.getElementById("frmSearch").submit();
   break;
  case "bydate":

   // var e = document.getElementById("bydate");
   //var text = e.options[e.selectedIndex].value;
   var text = document.getElementById('txtByDate').value;
   var currentdate = new Date();
   var date = currentdate.getDay() + "-" + currentdate.getMonth() + "-" + currentdate.getFullYear();
   var sql="";
//alert(date);
if(text=="newest"){sql="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment ORDER BY filters_due DESC; expires="+exdate+"; path=/";}
if(text=="oldest"){sql="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment ORDER BY filters_due ASC; expires="+exdate+"; path=/";}
if(text=="today"){sql="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment WHERE filters_due ="+date+"; expires="+exdate+"; path=/";}
document.cookie = "cookie_lastquery="+sql+"; expires="+exdate+"; path=/";
//alert("lastquery cookie set");
    break;
case "search":
   SearchWords=document.getElementById("myInput").value;
   setCookie("SearchWords="+SearchWords);
   if(SearchWords.length > 0){
   sql= "SELECT  _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment WHERE location LIKE '%"+SearchWords+"%' OR unit_name LIKE '%"+SearchWords+"%' OR area_served LIKE '%"+SearchWords+"%' OR filter_size LIKE '%"+SearchWords+"%' OR notes LIKE '%"+SearchWords+"%' OR filters_due LIKE '%"+SearchWords+"%' OR belts LIKE '%"+SearchWords+"%' OR assigned_to LIKE '%"+SearchWords+"%' OR filter_type LIKE '%"+SearchWords+"%' OR filters_last_changed LIKE '%"+SearchWords+"%'";
   let encoded = encodeURIComponent(sql);
   document.cookie = "cookie_lastquery="+encoded+"; expires="+exdate+"; path=/";
   document.getElementById("frmSearch").submit();
   }
  default:
    // code block
}
   }
</script>
<script>
function resetElements(element){
//console.log("resetElements "+element);
   switch(element){
      case "ckoverdue":
         if(document.getElementById("ckoverdue").checked == true){
         document.getElementById("dropdownMenuLink").innerHTML = "Sort by date";
         document.getElementById("myInput").value = "";
         document.getElementById("clearsearch").className = "d-none";
         //document.getElementById("clearsearch").innerHTML ="";
         }
         break;
      case "bydate":
         var Selected = document.getElementById("txtByDate").value;
         if(Selected == "today"){myvalue = "Today";}
         if(Selected == "oldest"){myvalue = "Oldest First";}
         if(Selected == "newest"){myvalue = "Newest First";}
         if(Selected == "normal"){myvalue = "Normal";}
         document.getElementById("dropdownMenuLink").innerHTML = myvalue;
         document.getElementById("ckoverdue").checked = false;
         document.getElementById("myInput").value = "";
         document.getElementById("clearsearch").className = "d-none";
         //document.getElementById("clearsearch").innerHTML ="";
      case "search":
         if(document.getElementById("myInput").value != ""){
         //document.getElementById("dropdownMenuLink").innerHTML = "Sort by date";
         document.getElementById("ckoverdue").checked = false;
         }
         break;
   }
}
</script>


<script>
function autocomplete(inp, arr) 
    {
        console.log("autocomplete starting");
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
               a.setAttribute("onclick", "setLastQuery('search');");
                this.parentNode.appendChild(a);
                for (i = 0; i < arr.length; i++) 
                    {
                        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) 
                            {
                                b = document.createElement("DIV");
                                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                                b.innerHTML += arr[i].substr(val.length);
                                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                                b.addEventListener("click", function(e)
                                     {
                                    inp.value = this.getElementsByTagName("input")[0].value;
                                    closeAllLists();
                                    });
                                a.appendChild(b);
                            }
                    }
             });

  inp.addEventListener("keydown", function(e) {
      parent.document.getElementsByTagName( 'frameset' )[ 0 ].rows='510,*';
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
});
}
</script>
<script>
   function enterKeyPressed(event) {
      console.log("searching , keycode pressed="+event.keyCode);
      if (event.keyCode == 13) {
         console.log(event.keyCode);
         resetElements('search');
        setLastQuery('search');
         return true;
      } else {
         return false;
      }
   }
</script>
<script>
      function replaceElement() {
         var newElement = document.getElementById("divSearch");
        
         var oldElement = document.getElementById('dropdownMenuLink');
         oldElement.replaceWith(newElement);
     }
   </script>
<script>
function clearsearch(){
   deletecookie("cookie_lastquery");
   deletecookie("SearchWords");
   window.src = "<?php echo $_SERVER["SCRIPT_NAME"] ?>";
	document.getElementById("myInput").value="";
   document.getElementById("ckoverdue").checked=false;
	document.getElementById('clearsearch').className = "d-none";

}
function showClearSearch(){
        document.getElementById("clearsearch").className = "btn-danger d-block mr-3 align-items-center";
   }
function deletecookie(cname){
   document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
}
</script>
<style>
.circle{
margin-top:0px;
  width:100%;
height:100%;
padding:0px;
  background:green;
  border-radius:50%;
display:flex;
text-align:center;
vertical-align:top;
color:white;
font-size:1em;
box-shadow: 3px 3px black;
align-items: center; 
  justify-content: center;
}

.autocomplete {
  /*the container must be positioned relative:*/
  position: relative;
  display: inline-block;
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

 @media screen and (width: 600px) {
        body {
          color: red;
        }
      }
</style>

</head>

<body onload="DoOnLoadStuff();" style="width:100vw;">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<?php
echo file_get_contents( "SideNav.php" ); 
$query = "SELECT filter_size  FROM filters WHERE filter_count <= 0;";
$result = mysqli_query($con, $query) or die(mysqli_error());
//while($row = mysqli_fetch_array($result)){
	//echo $row['filter_size']."<br>";
//}
?>

<div style="width:100%;height:10vh;display:flex;border:2px dotted;background-color:green;">
<div style="width:15%;height:10vh;float:left;"><img src="../images/menu.gif" style="display:inline-block;margin-left:0px;width:50px;height:height:10vh;" onclick="openNav()"></div>
<div class="input-group rounded" style="margin-top:0px;margin-left:0px;padding:0px;vertical-align:top;width:70%;height:10vh;border: 3px solid green;background-color:blue;z-index: 2;position: relative;" id="divSearch">
            <form id="frmSearch" autocomplete="off" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" style="margin: auto;vertical-align:top;>
            <input type="hidden" name="showsize" value="search">
            <span id="search-addon" style="display:flex;margin-left:0px;vertical-align:top;width:100%;height:100%;background-color:green;">
               <div class="btn-danger d-none" id="clearsearch" style="box-shadow: 4px 4px black;font-size:6vh;border-radius:3px;font-weight:bold;text-align:top;height:7vh;width: fit-content;color:white;" onclick="clearsearch()">X</div>
                  <div class="autocomplete" style="width:75%;height:5.5vh;background-color:black;color:black;">
                  <input type="search" onkeypress="enterKeyPressed(event);" style="box-shadow: 2px 2px black;margin: auto;vertical-align:top;width:100%;height:7vh;font-size:6vw;" name="search_words" id="myInput" class="form-control rounded" placeholder="Search" aria-label="Search" aria-describedby="search-addon" onkeyup="showClearSearch();">
                  </div>
               </form><img src="../images/search.png" style="padding-left:5px;box-shadow: 4px 4px black;height:40px;width:*;margin-left:15px;border-radius:50px;margin-top:0px;" id="img_search" onclick="resetElements('search');setLastQuery('search');">
               <i class="fas fa-search"></i>
            </span>
               <script>
                     $("#myInput").keyup(function (event) {
                           console.log("key pressed was "+ event.keyCode);
                           if (event.keyCode === 13) {
                              $("#img_search").click();
                              //document.getElementById('img_search').click();
                           }
                     });
               </script>         
               <script>
               var checkbox = document.getElementById("ckoverdue");
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
         </div>

      <div style="width:10%;float:right;display:flex;background-color:green;">
         <div style="float:right;width:55px;height:55px;margin-left:5px;" class="circle" id='divUserName'><?php echo $UserName ?></div>
      </div>
         
            
            <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" target="iframe2" method="POST" id="frmByDate">
            <input type = "text" name="bydate" id="txtByDate" style="display:none;">

</div>
</div>
            <div class="dropdown show" style="float:rigt;box-shadow: 4px 4px;margin-left:0px;height:20px;width:100%;font-size:3em;">
               <a class="btn btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="float:left;height:50px;width:100%;font-size:.5em;">
               <?php if(isset($_POST["bydate"])){echo $ByDate;} else { echo "Sort by date";} ?>
               </a>
               <div class="dropdown-menu bg-success text-info" aria-labelledby="dropdownMenuLink" style="height:fit-content;width:100vw;">
                  <a class="dropdown-item text-white text-white" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Newest first'; document.getElementById('txtByDate').value='newest';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');" style="font-size:4em;">Newest first</a>
                  <a class="dropdown-item text-white" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Oldest first'; document.getElementById('txtByDate').value='oldest';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');" style="font-size:4em;">Oldest first</a>
                  <a class="dropdown-item text-white" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Today'; document.getElementById('txtByDate').value='today';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');" style="font-size:4em;">Today</a>
                  <a class="dropdown-item text-white" href="#" onclick="document.getElementById('dropdownMenuLink').innerHTML='Normal'; document.getElementById('txtByDate').value='normal';resetElements('bydate');setLastQuery('bydate');document.getElementById('frmByDate').submit();resetElements('bydate');" style="font-size:4em;">Normal</a>
               </div>
            </div>
            </FORM>
<div id="session_vars" style="display:none;"><?php echo json_encode($_SESSION) ?></div>
<script>
        text = document.getElementById('session_vars').innerHTML;
        const jSESSION = JSON.parse(text);
</script>
<div id="divTasks">
 <form action="ListEquipment.php" method="post" id="frmsubmittasks" title="Add units to tasks list" style="background-color:green;color:white;height:100px;width:100%;display:none;"><button class="btn btn-primary rounded-9 btn-sm" style="width: fit-content;height:60px;white-space: normal;border-radius:10px;" onclick="submittasks('<?php echo $UserName ?>');">Submit Tasks</button>
   <div id="lblsubmittasks" style="display:none;"></div><br>
 <input type="hidden" name="action" value="addalltasks">
   <input type="hidden" name="username" id="username" value="<?php echo $UserName ?>">
   </form></div>
<table style="width:100%" id="myTable" border="1" style="background-color:yellow;">
<tr style="top:0px;position:sticky;z-index:4;" id="tableheader">
<td class="<?php echo $TDstyle ?>"></td><td class="<?php echo $TDstyle ?>"></td>
    <tr class="myTable">
        <th style="background-color:#07ff10;text-align:center;">
      <label style="width:90px;height:60px;text-align:center;font-size:1em;color:red;font-weight:bold;"  name="checkbox" id="lblCheckBox" title="display overdue filters only" >Over Due
               <input type="checkbox" style="margin-top:10px;width:20px;height:20px;" id="ckoverdue" name="ckoverdue" onChange="setCookie('cookie_ckoverdue='+ this.checked);resetElements('ckoverdue');setLastQuery('overdue');" <?php echo $ShowOverDue ?>>
               <!--<span class="checkmark"></span>-->
            </label>
        </th>
         <th style="background-color:#07ff10;font-size:<?php echo $FontSize ?>;color:black;" id="thFiltersDone">Filters<br>Done
		</th>  
        <th style="background-color:#07ff10;font-size:<?php echo $FontSize ?>;color:black;text-align: center;" id="thunitname">Unit Name
		</th>       
        <th style="background-color:#07ff10">

 <div class="dropdown show" style="height:100px;text-align: left;z-index: 0;position: relative;">
  <button class="btn btn-secondary dropdown-toggle" style="margin-left:0px;margin-top:25px;background-color:#07ff10;color:black;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
    <?php   
   if($Field2 == 'location'){echo "Location";} 
   if($Field2 == 'filters_due'){echo "Due Date";} 
   if($Field2 == 'area_served'){echo "Area Served";} 
   if($Field2 == 'filtersize'){echo "Filter Size";} 
   if($Field2 == 'notes'){echo "Notes";} 
?>
  </button>
<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?Field2=filters_due">Due Date</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?Field2=area_served">Area Served</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?Field2=location">Location</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?Field2=filtersize">Filter Size</a>
         <a class="dropdown-item" href="<?php echo $_SERVER["SCRIPT_NAME"] ?>?Field2=notes">Notes</a>
  </div>
</div>
</th>
    </tr>
   <form action="mListEquipment.php" method="post" id="frmsubmittasks">
   <input type="hidden" name="action" value="addalltasks">
   <input type="hidden" name="username" id="username" value="<?php echo $UserName ?>">
<?php
      //GET LAST QUERY IN CASE PAGE WAS TEMPERARALY CHANGED
   $LastQuery ="";
    //lAST QUERY IS SAVED WITH JAVASCRIPT WHEN ELEMENTS ARE CLICK USING FUNCTION setLastQuery()
   if(isset($_COOKIE["cookie_lastquery"])){
      $LastQuery=$_COOKIE["cookie_lastquery"];
      //echo "last qry=".$_COOKIE["cookie_lastquery"];
      }
 
if(strcmp($Action,"clearsearch")==0){
      $query= "SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment;";
   }
            if(strlen($LastQuery) == 0)
            {
               $query="SELECT _id,unit_name,location,area_served,filter_size,filters_due,filter_type,belts,notes,filter_rotation,filters_last_changed, assigned_to, image FROM equipment;";
            }
            else
            {
               $query = $LastQuery;
            }
   //echo "<br>Query=".$query;
    if ($stmt = $con->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($unitId, $UnitName, $location, $AreaServed, $FilterSize, $FiltersDue, $FilterType, $belts, $notes, $FilterRotation, $FiltersLastChanged, $AssignedTo, $Image );
?>
    
    <?php
          $X=0;
          //$TDstyle="";
          $EquipmentList="";
        while ($stmt->fetch()) {
        $EquipmentList= $EquipmentList . "{\"units\":[  {\"_id\":\"".$unitId."\", \"unit_name\":\"".$UnitName."\",\"location\":\"".$location."\"}]}";
         //echo $unitId ."vbcrlf";
         $X=$X+1;
        
         $today = date('Y-m-d');
        $someDate = new DateTime($FiltersDue);
        $daysoverdue= s_datediff("d",$today,$someDate,true);
       // $daysoverdue=  $diff = date_diff("d",$today, $someDate);
       
	   

        //echo "<br>TDstyle=".$TDstyle."fontcolor=".$FontColor;
            ?>
            <tr class="myTable">
			<?php 
					if(getFilterCount($FilterSize, $result)=="outofstock")
					{
						$outofstock="outofstock";
					}
					else
					{
						$outofstock="";
					}
					
			$myCss = GetCss($Theme,$daysoverdue,$outofstock); 
			?>
                    
                        <td style='<?php echo $myCss ?>;text-align:center;'>
					<?php 
                        if($AssignedTo == "")
                           {
                                 ?>
                                 <label class="container"><input type="checkbox" class="checkmark"  id="cktask<?php echo $unitId ?>" onchange="addTaskToForm('<?php echo $unitId ?>','<?php echo $UnitName ?>');" value="<?php echo $unitId ?>">
                                 <span class="checkmark"></span>
                                 </label>
                                 <?php 
                           }
                           else
                           { 
                              ?>
                              <a onclick='showSelectUsers(<?php echo $unitId ?>);'><?php echo $AssignedTo ?></a><div id='divSelectUser<?php echo $unitId ?>' style='display:none';>
                              <div class="dropdown show">
                              <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background-color:#07ff10;color:black;font-weight:bold;">Re-assin too
                              </a>      
                              <div class="dropdown-menu" aria-labelledby="dropdownMenuLink" >
                              <?php
                              foreach ($Users as $value) 
                                 {
                                    echo "<a class='dropdown-item' href='".$_SERVER['SCRIPT_NAME']."?action=edit_task&assignedto=".$UserName."&reassignto=".$value."&id=".$unitId."'>".$value."</a>";
                                 }
                              echo "</div></div></td>";
                           }
                           ?>
                  <td style='<?php echo $myCss ?>;text-align:center;'>
                        <label class="switch">
                        <input type="checkbox" id="ckShowFilterTypes<?php echo $unitId ?>" onChange="showFilterTypes('<?php echo $unitId ?>');">
                        <span class="slider round"></span>
                        </label></checkbox>
                        <?php $myURL = "mListEquipment.php?action=unitdone&filter_rotation=".$FilterRotation."&unit_id=".$unitId."&unit_name=".$UnitName."&filter_size=".$FilterSize."&filters_used=".$FilterSize."&filters_due=".$FiltersDue; ?>
                        <br>
                        <div class="dropdown d-none" id="slctFilterTypes<?php echo $unitId ?>">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           select filter used
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                        foreach ($filtertypes as $value) {
                           echo "<a class='dropdown-item' href='".$myURL."&filter_type=".$value."'>".$value."</a>";
                        }
                        ?>
                        <a class='dropdown-item' href='<?php echo $myURL ?>&filter_type=no_filters_used'>No filters used</a>
                        </div>
                        </div>
                   <A style="display:none;" id="ahref<?php echo $unitId ?>" href="ListEquipment.php?action=unitdone&username=<?php echo $UserName ?>&filter_rotation=<?php echo $FilterRotation ?>&unit_id=<?php echo $unitId ?>&unit_name=<?php echo $UnitName ?>&filter_size=<?php echo $FilterSize ?>&filters_used=<?php echo $FilterSize ?>&filters_due=<?php echo $FiltersDue ?>" style="font-weight: bold;font-size: 50px;color:white;background-color:gold;height:170px;width:100%;"><div style="font-weight: bold;font-size: 50px;background-color:white;height:50px;">SUBMIT</div></A>
               </div> </td>
					<td style="<?php echo $myCss ?>" id='info'>
                    <a href="#" onclick="showinfo('<?php echo $unitId ?>', '<?php echo $UnitName ?>','<?php echo $unitId ?>');"> <div style="<?php echo $myCss ?>" id='mydiv<?php echo $unitId ?>'>
					<?php echo $UnitName ?></div></a>               
                 <table style="display:none;" id="tblUnitinfo<?php echo $unitId ?>">
                  <tr><td><button id="btnCloseInfo<?php echo $unitId ?>"  type="button" class="btn btn-info " onclick="closeinfo('<?php echo $unitId ?>');">CLOSE</button></td><td></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Unit name</td><td class="tdUnitInfo2"><?php echo $UnitName ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Assigned too</td><td class="tdUnitInfo2"><?php echo $AssignedTo ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Location</td><td class="tdUnitInfo2"><?php echo $location ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Area served</td><td class="tdUnitInfo2"><?php echo  $AreaServed ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Filter size</td><td class="tdUnitInfo2"><?php echo $FilterSize ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Filter type</td><td class="tdUnitInfo2"><?php echo $FilterType ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Filters due</td><td class="tdUnitInfo2"><?php echo $FiltersDue ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Filters last changed</td><td class="tdUnitInfo2"><?php echo $FiltersLastChanged ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Rotation</td><td class="tdUnitInfo2"><?php echo $FilterRotation ?></td></tr>
                  <tr id="trinfo"><td class="tdUnitInfo">Belts</td><td class="tdUnitInfo2"> <?php echo $belts ?></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">Notes</td><td class="tdUnitInfo2"><textarea class="mytextarea"><?php echo $notes ?></textarea></td></tr>
                   <tr id="trinfo"><td class="tdUnitInfo">
                   <a href="mwebEditUnit.php?_id=<?php echo $unitId ?>&username=<?php echo $UserName ?>" class="success" style="background-color:white;color:green;width:100%;height:170px;font-size:30px;font-weight: bold;">EDIT UNIT</a></font>
                   <!--<br>FILTERS DONE<BR>
                       <label class="switch">
                        <input type="checkbox" id="ckShowFilterTypes<?php echo $unitId ?>" onChange="showFilterTypes('<?php echo $unitId ?>');">
                        <?php $myURL = "mListEquipment.php?action=unitdone&filter_type=". $FilterType ."&username=". $UserName ."&filter_rotation=". $FilterRotation ."&unit_id=".$unitId."&unit_name=".$UnitName ."&filter_size=".$FilterSize."&filters_due=". $FiltersDue ?>
                        <span class="slider round"></span>
                        </label><br>
                        <div class="dropdown d-none" id="slctFilterTypes<?php echo $unitId ?>">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                           select filter used
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <?php
                        foreach ($filtertypes as $value) {
                           echo "<a class='dropdown-item' href='".$myURL."&filter_type=".$value."'>".$value."</a>";
                        }
                        ?>
                        <a class='dropdown-item' href='<?php echo $myURL ?>&filter_type=no_filters_used'>No filters used</a>-->
                        </div>
                        </div>      
                   </td><td class="tdUnitInfo">
                   <?php 
                   if ($Image != null)
                     {
                        echo "<a href='" . $Image ."' target='_blank'><img src='".$Image."' alt='". $UnitName ."' style='width:100px;height:100px;'></a>";
                     }
                     ?>
                     </td></tr>
                </table>
                </td>

                  <?php
                  if(strcmp($Field2, "filters_due")==0 || strcmp($Field2, "filters_due")==0)
                  {
					      $setField2 = "filters_due";
                     echo "<td style='".$myCss."'>". $FiltersDue."</td>";
                  }
                  if(strcmp($Field2, "location")==0 || strcmp($Field2, "select_one2")==0)
                  {
					      $setField2 = "location";
                     echo "<td style='".$myCss."'>". $location."</td>";
                  }
                if(strcmp($Field2, "area_served")==0)
                  {
					      $setField2 = "area served";
                     echo "<td style='".$myCss."'>". $AreaServed ."</td>";
                  }
                  if(strcmp($Field2, "notes")==0)
                  {
				         $setField2 = "notes";
                     echo "<td style='".$myCss."'>". $notes."</td>";
                  }
                  if(strcmp($Field2, "filtersize")==0)
                  {
                        
				         $setField2 = "filter size";
                      $outofstock = getFilterCount($FilterSize,$result);
						   //$outofstock=getFilterCount($fs, $result);
                     $myCss=getCss($Theme,$daysoverdue,$outofstock);
                     $myCss ."font-family:".$_SESSION["font_family"];
                     $AlCss=GetAlinkCss($Theme,$daysoverdue,$outofstock);
                     $AlCss = $AlCss . ";font-family:".$_SESSION["font_family"];
                     //REMOVE AMOUNT FROM FILTERSIZE FOR BOOKMARK HYPERLINK TO FILTERS PAGE
                     $NumberOfFilters = substr_count($FilterSize,"(");
                     $B=0;
                     if($NumberOfFilters == 1)
                      {
                     $myfilter = $FilterSize;
                     $x = strpos($myfilter, ")",0);
                     $myfilter = substr($myfilter, $x + 1, strlen($myfilter)- $x);
                     $myfilter = $myfilter . $FilterType;
                     $myfilter = str_replace(" ","",$myfilter);
                      echo "<td style='".$myCss."'><a href='web_update_filters.php#".$myfilter ."' style='".$AlCss."';>". $FilterSize . "</a></td>";
                    }
                    else
                    {
                      $arfilters = explode(" ", $FilterSize);
                        
                      echo "<td style='".$myCss."'>";
                     foreach ($arfilters as $value) 
                        {
                           $B=$B+1;
                           if($B <= 2){
                           $myfilter = $value;
                           $x = strpos($myfilter, ")",0);
                           $myfilteronly = substr($myfilter, $x + 1, strlen($myfilter)- $x);
                           echo "<a href='web_update_filters.php#".$myfilteronly .$FilterType."' style='".$AlCss."';>". $myfilter . "</a>&nbsp;&nbsp;";
                           }
                        }
                     echo "</td>";
                    }                    
                  }
?>
                </tr>

            <?php

        }
    }
    
    //$stmt->close();
    
     ?>
    </form>
<?php
    $ByDate="no";
    if (isset($_GET['unit_name'])) {$UnitName=$_GET['unit_name'];}
    if(isset($_POST['_id'])){$UnitID = ($_POST['_id']);}
    if(isset($_POST['bydate'])){$ByDate = ($_POST['bydate']);}
    if(isset($_GET['bydate'])){$ByDate = ($_GET['bydate']);}
    if(isset($_GET['ckoverdue'])){$overdue = $_GET['ckoverdue'];}
    if(isset($_POST['ckoverdue'])){$overdue = $_POST['ckoverdue'];}
?>
</table>
<div style="display:none;" id="equipmentlist" style="back-color:white;color:black;"><font color="white"><?php echo $EquipmentList ?></div>
<?php 
echo  $X . " units in returned by query."; 

function getFilterCount($fsize, &$result)
{
//echo "from function fsize=".$fsize."<br>";
   foreach($result as $side=>$direc)
      {
      //echo $fsize . "=" . $direc["filter_size"]."<br>";
         if(strpos($fsize, $direc["filter_size"]) > 0)
            {
               return "outofstock";
            }
      }
}
function AddTask2($UserName, $unitid,$UnitName,$filters, $filterrotation, $filtersdue)
{
    $link = mysqli_connect("MYSQL5013.site4now.net","a3f5da_lobby","relays82","db_a743b0_cannery");
     echo "AddTask2<br>";
    // Check connection
    if($link === false)
    {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }
 
    // Prepare an insert statement
    $sql = "INSERT INTO tasks2 (_id, employee_name, unit_name, task_date_created, filters_needed, unit_id, filter_rotation, filters_due) VALUES (?,?,?,?,?,?,?,?)";
    $date_created=date("Y-m-d");
    if($stmt = mysqli_prepare($link, $sql))
       {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssssss",$_id, $employee_name, $unitid, $task_date_created, $filters, $UnitName, $filterrotation, $filtersdue);
    
            // Set parameters
            $_id='';
            $employee_name=$UserName;
            $unit_name=$unitid;
            $task_date_created=$date_created;
            $filters_needed=$filters;
            $unit_id = $unitid;
            $filter_rotation=$filterrotation;
            $filters_due=$filters;
    
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt))
                {
                    //echo "Affected rows: " . mysqli_affected_rows($link)."<br>";
                    echo "Records inserted successfully.";
                } 
                else
                {
                echo "ERROR: Could not execute query: $sql. " . mysqli_error($link);
                }
        } 
        else
        {
            echo "ERROR: Could not prepare query: $sql. " . mysqli_error($link);
        }
    // Close statement
    mysqli_stmt_close($stmt);
    // Close connection
    mysqli_close($link);
}

function getCss($theme,$daysoverdue,$outofstock)
{
	//echo "theme=".$theme." days=".$daysoverdue." oos=".$outofstock."<br>";
	if(strcmp($theme,"Light-tdPlain")==0)
	{
		if ($daysoverdue > 0)//FILTERS ARE NOT OVERDUE
				{
					  if(strcmp($outofstock, "outofstock")==0)//FILTERS OUT OF STOCK
					  {
							   $nCss = "background-color:orange;color:black;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
					  else
					  {			//FILTERS NOT OUT OF STOCK
								$nCss="background-color:white;color:black;text-align: left;font-weight:bold;";	
								//echo "nCss=".$nCss."<br>";
					  }
			   }
				if ($daysoverdue <= 0)//FILTERS ARE OVERDUE
				{
					     if(strcmp($outofstock, "outofstock")==0)
					  {
							   $nCss="background-color:orange;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $nCss="background-color:red;color:white;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	else
	{
	//DARK Theme
		if ($daysoverdue > 0)//FILTERS ARE NOT OVERDUE
				{
					  if(strcmp($outofstock, "outofstock")==0)//FILTERS OUT OF STOCK
					  {
							   $nCss = "background-color:orange;color:white;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
					  else
					  {			//FILTERS NOT OUT OF STOCK
								$nCss="background-color:black;color:white;text-align: left;font-weight:bold;";	
								//echo "nCss=".$nCss."<br>";
					  }
			   }
				if ($daysoverdue <= 0)//FILTERS ARE OVERDUE
				{
					     if(strcmp($outofstock, "outofstock")==0)
					  {
							   $nCss="background-color:orange;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $nCss="background-color:black;color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	return $nCss;
}

function getAlinkCss($theme,$daysoverdue,$outofstock)
{
	if(strcmp($theme,"Light-tdPlain")==0)
	{
		if ($daysoverdue > 0)//FILTERS ARE NOT OVERDUE
				{
					  if(strcmp($outofstock, "outofstock")==0)//FILTERS OUT OF STOCK
					  {
							   $AlinkCss = "color:black;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
					  else
					  {			//FILTERS NOT OUT OF STOCK
								$AlinkCss="color:black;text-align: left;font-weight:bold;";	
								//echo "nCss=".$nCss."<br>";
					  }
			   }
				if ($daysoverdue <= 0)//FILTERS ARE OVERDUE
				{
					     if(strcmp($outofstock, "outofstock")==0)
					  {
							   $AlinkCss="color:red;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					 
					  }
					  else
					  {
							   $AlinkCss="color:black;text-align: left;font-weight:bold;";
							   //echo "nCss=".$nCss."<br>";
					  }
			   }
	}
	return $AlinkCss;
}


function AddTask($con, $user, $unitid,$UnitLocation,$UnitName,$filters, $filterrotation, $filtersdue)
    {
        //echo var_dump($_POST);
	    //echo "AddTask<br>";
        if (isset($_POST['action'])=="addtask") 
            {
                if (isset($_COOKIE["user"])) 
                    {
                      $UserName = $_COOKIE["user"];
                      //echo "COOKIE username=". $UserName."<BR>";
                    }
                    $servername = "localhost";
                    $username = "4094059_mirage";
                    $password = "relays82";
                    $database = "4094059_mirage";
               $con2 = new mysqli("$servername","$username","$password","$database");
               // Check connection
               if ($con2->connect_error) 
                    {
                        die("Connection failed: " . $con2->connect_error);
                    }
                $TodaysDate = date("Y-m-d");  
                                            
                $sql = "INSERT INTO tasks(employee_name,unit_id,unit_name,unit_location,filters_needed,task_date_created,filter_rotation,action) VALUES ('" . $user . "','" . $unitid . "','" . $UnitName . "','" . $UnitLocation. "','" . $filters . "','" . $TodaysDate . "','" . $filterrotation . "', 'new')";
                //$sql = "INSERT INTO tasks(employee_name, unit_name, task_date_created, filters_needed, unit_id, filter_rotation, filters_due) VALUES ('" . $UserName . "','" . $UnitName. "','". $TodaysDate . "','" . $filters . "','" . $unitid . "','" . $filterrotation . "','" . $filtersdue . "')";
                //echo $sql."<br>";
                //mysqli_select_db($con2, 'db_a743b0_cannery');
                if ($con->query($sql) === TRUE) 
                    {
                        echo "insert success tasks created<br>";
                    }
                    else
                    {
                        echo "Error: " . $sql . "<br>" . $con2->error;
                        //die('Could not enter data: ' . mysqli_error($con2));
                    }
                if(isset($_POST['_id'])){$UnitID = ($_POST['_id']);}
                $sql="UPDATE equipment  SET assigned_to ='".$user."' WHERE _id='". $unitid ."';";
                 //mysqli_select_db($con, 'db_a743b0_cannery');
                if ($retval=mysqli_query($con2, $sql))
                    {
                        echo "<br>" .$UnitName . " was added to your tasks<br>";
                    }
                mysqli_close($con2);
            }
}

function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false)
{

       if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
       if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);

       $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
       
       switch( $str_interval){
           case "y": 
               $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
           case "m":
               $total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
               break;
           case "d":
               $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
               break;
           case "h": 
               $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
               break;
           case "i": 
               $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
               break;
           case "s": 
               $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
               break;
          }
       if($diff->invert)
       {
            return -1 * $total;
       }else
       {
            return $total;
       }
}

function ExtractFilterSize($f){
                              
   //echo "number of filter sets=".substr_count($f, '(')."<br>";
   $numOfSets= substr_count($f,'(');
   //echo "f sent=" . $f . "<br>";
   $pos = strpos($f, ")");
   //echo "found at=". $pos ."<br>";
   $filterSizeSent = substr($f, $pos+1);  
   $filtersQtyUsed = substr($f,1,$pos-1);
   //echo "size sent=".$filterSizeSent . " qtySent=". $filtersQtyUsed. "<br>";

if($numOfSets == 1)
   {
      $pos = strpos($f, ")");
      //echo "found at=". $pos ."<br>";
      $filterSizeSent = substr($f, $pos+1);  
      $filtersQtyUsed = substr($f,1,$pos-1);
	 // echo "filterSizeSent=".$filterSizeSent."<br>";
	  if(getfiltercount($filterSizeSent,$result) == "outofstock"){return "outofstock";}
   }

if($numOfSets == 2)
   {
      $lastpos = strpos($f, ")", 0);
      $pos = strpos($f, "(");
      $filt1 = substr($f, $pos-strlen($f),$lastpos-2 );
      echo "set1=" . $filt1. "<br>";
      $filt2 = substr(strrchr($f, "("), 0);
	  //if(getfiltercount($filt1, $result) == "outofstock"){return "outofstock";}
	  //if(getfiltercount($filt2, $result) == "outofstock"){return "outofstock";}
      echo "set2=" . $filt2. "<br>";
      //for First filter set
      $pos = strpos($filt1, ")");
      $filterSizeSent = substr($filt1, $pos+1);  
	  if(getfiltercount($filterSizeSent, $result) == "outofstock"){return "outofstock";}
      $filtersQtyUsed = substr($filt1,1,$pos-1);
   }
      //return $filterSizeSent;
}
?>
<script>
// When the user clicks on <div>, open the popup
function showPOPUP() {
alert("showPOPUP");
  var popup = document.getElementById("myPopup");
  popup.classList.toggle("show");
}


function setOverDue(){
   if(document.getElementById('ckoverdue').checked)
      {
         document.cookie = "ckoverdue=true";
         //alert("seting to false");
      }
      else
      {
         document.cookie = "ckoverdue=false";
         //alert("settting to true");
      }
      //$line=readCookie("ckoverdue");
      //alert("cookie="+$line);
   document.getElementById("cklate").submit();
}  
</script>
<script>
function readCookie(name) {
 var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  return parts.pop().split(";").shift();
}
</script>
<script>

function showsettings(){
   document.getElementById("selFontSize").style.width = "115px";
   document.getElementById("selTheme").style.width = "115px";
	tdSettings = document.getElementById("tdSettings");
	tdSettings.style.visibility="visible";
}
</script>
<script>
function fillOptions(){
		searchby=document.getElementById("selSearch");
		x = document.getElementById("selField2");
		var option = document.createElement("option");
		  option.text = x.options[x.selectedIndex].text;
		  searchby.add(option);
}
</script>

<script>
    function myFunction() {    
	     
        var input, filter, table, tr, td, i, txtValue;
        var $n="1";
        input = document.getElementById("myInput");
        $lastsearch = document.getElementById("myInput").value
        document.cookie = "search=" + $lastsearch;
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
     
        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[$n];
        if (td) {
        txtValue = td.textContent || td.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
        
        tr[i].style.display = "";
		tr[i].height= "300px";
        td.style.height = "20px";
        } else {
		 td.style.height = "20px";
		 	if(tr[i].id != "trinfo"){
		  tr[i].style.display = "none";
		  }
        }
	
        }
		}
    }
</script>

</body>
</html>
