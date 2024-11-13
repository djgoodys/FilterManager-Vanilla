<script>
/* Set the width of the side navigation to 250px and the left margin of the page content to 250px and add a black background color to body */
function openNav() {
parent.document.getElementsByTagName( 'frameset' )[ 0 ].rows='710,*';
  document.getElementById("mySidenav").style.width = "250px";
  document.getElementById("divSearch").style.display = "none";
  document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}

/* Set the width of the side navigation to 0 and the left margin of the page content to 0, and the background color of body to white */
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.body.style.backgroundColor = "white";
 document.getElementById("divSearch").style.display = "block";
parent.document.getElementsByTagName( 'frameset' )[ 0 ].rows='20%,*';
}
</script>
<script>
        function doesElemExist(txtsize)
         {
            var elemv = document.getElementById("divSize").children;
            var wasFound = false;
            for (let b = 0; b < elemv.length; b++) 
            {     
                if(elemv[b].value.localeCompare(txtsize) == 0)
                {
                    return true;
                }
            }
            return wasFound;
        }
</script>
<script>
        function doesAmntExist(title)
         {
            var elemv = document.getElementById("divAmount").children;
            var wasFound = false;
            for (let b = 0; b < elemv.length; b++) 
            {     
                if(elemv[b].title == title)
                {
                    wasFound = true;
                    return true;
                    break;
                }
            }
            return wasFound;
        }
</script>
<script>
    function MoveDiv(){
        var divx = document.getElementById('divallAmounts');
        var buttonx = document.getElementById('btnPrint');
        divx.style.position = "absolute";
        divx.style.top = buttonx.style.top + buttonx.style.height; 
        divx.style.left = 0; 
    }
</script>

<script>
    function addAmount()
    {
        var AllFilters = document.getElementById("divAllfilters").children;
        var AllTypes = []; // Create an empty array to store the text boxes
        var FiltersCounted = document.getElementById("divFiltersCounted").children;
         
        for (var i = 0; i < AllFilters.length; i++) {
          var childElement = AllFilters[i];

          if (childElement.tagName === "INPUT" && childElement.name === "ftype") {
            AllTypes.push(childElement); // Add matching text boxes to AllTypes array
          }
        }
        var allAmounts = document.getElementById("divAmount").children;
        const allTypes = document.querySelectorAll("[data-ftype]");
        //console.log("allAmounts.length="+ allAmounts.length);
        //console.log("FiltersCounted.length="+ FiltersCounted.length );
        var totalamount = 0;
        
                        for (let p = 0; p < AllFilters.length; p++) 
                            {  
                             elementedAdded = false;
                                for (let f = 0; f < FiltersCounted.length; f++) 
                                {
                                  
                                    if(AllFilters[p].name=="fsize" && AllFilters[p].getAttribute("data-size") == FiltersCounted[f].getAttribute("data-size") && AllFilters[p].getAttribute("data-ftype") == FiltersCounted[f].getAttribute("data-ftype") || FiltersCounted.length==0)
                                      {
                                        num = parseInt(AllFilters[p].getAttribute("data-amount")) + parseInt(FiltersCounted[f].getAttribute("data-amount"));
                                        FiltersCounted[f].setAttribute("data-amount", num);
                                        FiltersCounted[f].value = "(" + num + ") " + FiltersCounted[f].getAttribute("data-size") + " " +FiltersCounted[f].getAttribute("data-ftype");
                                      elementedAdded = true;
                                      }                            
                            
                                }
                                
                                if(elementedAdded == false && AllFilters[p].name == "fsize")
                                {
                                    var x = document.createElement("INPUT");
                                    x.setAttribute("type", "text");
                                    x.setAttribute("id", "size"+p);
                                    x.setAttribute("name", "fsize");
                                    x.setAttribute("title", AllFilters[p].getAttribute("data-size"));
                                    x.setAttribute("style", "width:300px");
                                    x.setAttribute("value", "(" + AllFilters[p].getAttribute("data-amount") + ") " + AllFilters[p].getAttribute("data-size") + " " + AllFilters[p].getAttribute("data-ftype"));
                                    x.setAttribute("data-ftype", AllFilters[p].getAttribute("data-ftype"));
                                    x.setAttribute("data-size", AllFilters[p].getAttribute("data-size"));
                                    x.setAttribute("data-amount", AllFilters[p].getAttribute("data-amount"));
                                    document.getElementById("divFiltersCounted").appendChild(x); 
                                  }
                    //allAmounts[n].value =+ document.getElementById("type"+n).value;
            //if(allAmounts[n].name=="amnt" && totalamount != 0){allAmounts[n].value = totalamount;}
            totalamount = 0;
        }
       // MoveDiv()
    }
</script>
<script>
function countFilters()
{
    var ThisIndex = "";
    var ThisValue = "";
    var elemAllFilters = document.getElementById("divAllfilters").children;
    var elemAllSizes = document.getElementById("divSize").children;
    if(elemAllSizes.length == 0)  
                {
                 
                        //CREATE TEXT BOX FILTER SIZE
                    var x = document.createElement("INPUT");
                    x.setAttribute("type", "text");
                    x.setAttribute("id", "size0");
                    x.setAttribute("name", "size");
                    x.setAttribute("title", elemAllFilters[0].value.trim())
                    x.setAttribute("style", "width:160px");
                    x.setAttribute("value", elemAllFilters[0].getAttribute("data-size"));
                    x.setAttribute("class","FiltersNeeded");
                    x.setAttribute("data-ftype", elemAllFilters[0].getAttribute("data-ftype"));
                     x.setAttribute("data-size", elemAllFilters[0].getAttribute("data-size"));
                      x.setAttribute("data-amount", elemAllFilters[0].getAttribute("data-amount"));
                      //console.log("elemAllFilters(0)="+ elemAllFilters[0].getAttribute("data-amount"));
                    document.getElementById("divSize").appendChild(x); 
                    //document.getElementById("size0").style.width = "fit-content";
                    document.getElementById("divFiltersNeeded").innerHTML="Total filters required for all tasks:";
                    ////console.log("just created txtsize value="+elemAllFilters[0].value);
                   

                    //CREATE TEXT BOX FILTER COUNT
                    var x = document.createElement("INPUT");
                    x.setAttribute("type", "text");
                    x.setAttribute("id", "amnt0");
                    x.setAttribute("name", "amnt");
                    x.setAttribute("style", "background-color:green;width: 150px;display:flex;flex-direction:column;float:right;");
                    x.setAttribute("title", document.getElementById("size0").value);
                    x.setAttribute("value", "0");
                    x.setAttribute("class","FiltersNeeded");
                    x.setAttribute("data-ftype", elemAllFilters[0].getAttribute("data-ftype"));
                     x.setAttribute("data-size", elemAllFilters[0].getAttribute("data-size"));
                      x.setAttribute("data-amount", elemAllFilters[0].getAttribute("data-amount"));
                    document.getElementById("divAmount").appendChild(x); 
                    //document.getElementById("amnt0").style.width = "fit-content";

                    //CREATE TEXT BOX FILTER TYPE
                    var x = document.createElement("INPUT");
                    x.setAttribute("type", "text");
                    x.setAttribute("id", "filtype0");
                    x.setAttribute("name", "filtype");
                    x.setAttribute("style", "width: 150px;display:flex;flex-direction:column;float:right;");
                    x.setAttribute("title", document.getElementById("size0").value);
                    x.setAttribute("value", elemAllFilters[0].getAttribute("data-ftype"));
                    x.setAttribute("class","FiltersNeeded");
                    x.setAttribute("data-ftype", elemAllFilters[0].getAttribute("data-ftype"));
                     x.setAttribute("data-size", elemAllFilters[0].getAttribute("data-size"));
                      x.setAttribute("data-amount", elemAllFilters[0].getAttribute("data-amount"));
                    //document.getElementById("divallAmounts").appendChild(x);
                    document.getElementById("divType").appendChild(x); 
                    //document.getElementById("amnt0").style.width = "fit-content";
                
                }
              
        for (let i = 0; i < elemAllFilters.length; i++) 
            {                 
              if(elemAllFilters[i].name == "ftype"){ftype = elemAllFilters[i].value;}
                if(elemAllFilters[i].name == "fsize")
                    {
                        if(elemAllFilters[i].value != undefined && doesElemExist(elemAllFilters[i].value.trim()) != true)
                            {
                                ThisIndex = i;
                                //CREATE SIZE TEXT BOX
                                var x = document.createElement("INPUT");
                                x.setAttribute("type", "text");
                                x.setAttribute("id", "size"+i);
                                x.setAttribute("style", "width:160px;");
                                x.setAttribute("title", elemAllFilters[i].value.trim());
                                x.setAttribute("value",elemAllFilters[i].getAttribute("data-size"));
                                x.setAttribute("name", "size");
                                x.setAttribute("class","FiltersNeeded");
                                x.setAttribute("data-ftype", elemAllFilters[i].getAttribute("data-ftype"));
                                x.setAttribute("data-amount", elemAllFilters[i].getAttribute("data-amount"));
                                x.setAttribute("data-size", elemAllFilters[i].getAttribute("data-size"));
                                //document.getElementById("divallAmounts").appendChild(x);
                                document.getElementById("divSize").appendChild(x); 
                                //document.getElementById("size"+i).style.width = "fit-content";
                                
                                var mysize = elemAllFilters[i].value.trim();
                                
                            }
                    }
                if(elemAllFilters[i].name == "famount" && mysize != undefined)
                    { 
                      ////console.log("elemAllFilters[i].title="+elemAllFilters[i].title);
                        exists=doesAmntExist(mysize);
                       //console.log("exists="+exists+ " "+elemAllFilters[i].title);
                        if(elemAllFilters[i].value != "" && elemAllFilters[i].value != undefined && exists == false){
                          
                        //CREATE AMOUNT TEXT BOX
                                              
                        var x = document.createElement("INPUT");
                        x.setAttribute("type", "text");
                        x.setAttribute("id", "amnt"+ ThisIndex);
                        x.setAttribute("name", "amnt");
                        x.setAttribute("style", "background-color:green;width: 80px;display:block;float:right;");
                        
                        x.setAttribute("title", elemAllFilters[i].getAttribute("data-size"));
                        x.setAttribute("value",elemAllFilters[i].getAttribute("data-amount"));
                        x.setAttribute("class","FiltersNeeded_Amount");
                        x.setAttribute("data-ftype", elemAllFilters[i].getAttribute("data-ftype"));
                        x.setAttribute("data-size", elemAllFilters[i].getAttribute("data-size"));
                        x.setAttribute("data-amount", elemAllFilters[i].getAttribute("data-amount"));
                        document.getElementById("divAmount").appendChild(x); 
                        //document.getElementById("amnt"+ThisIndex).style.width = "fit-content";
                        }
                    }  

                    if(elemAllFilters[i].name == "ftype" && mysize != undefined)
                    { 
                      ////console.log("elemAllFilters[i].title="+elemAllFilters[i].title);
                        //exists=doesAmntExist(mysize);
                       ////console.log("exists="+exists+ " "+elemAllFilters[i].title);
                        if(elemAllFilters[i].value != "" && elemAllFilters[i].value != undefined && exists == false){
                          
                        //CREATE type TEXT BOX
                                              
                        var x = document.createElement("INPUT");
                        x.setAttribute("type", "text");
                        x.setAttribute("id", "filtype"+ ThisIndex);
                        x.setAttribute("name", "filtype");
                        x.setAttribute("style", "width: 150px;display:block;float:right;background-color:green;");
                        x.setAttribute("title", mysize);
                        x.setAttribute("value",ftype);
                        x.setAttribute("data-ftype", elemAllFilters[i].getAttribute("data-ftype"));
                        x.setAttribute("data-size", elemAllFilters[i].getAttribute("size"));
                        x.setAttribute("amount", elemAllFilters[i].getAttribute("data-amount"));
                        x.setAttribute("class","FiltersNeeded_Amount");
                 
                        document.getElementById("divType").appendChild(x); 
                        //document.getElementById("amnt"+ThisIndex).style.width = "fit-content";
                        }
                    }   
            }
        //k = elemAllFilters.length/2 * document.getElementById(amnt0).style.height;
        //document.getElementById("divSize").style.height = kpx;
        addAmount();
                   
const divSizes = document.getElementById("divSize").children;  
const divAmounts = document.getElementById("divAmount").children;
//console.log("divSizes count="+divSizes.length);
for(i = 0; i < divSizes.length ; i++){
  //divSizes[i].value = divSizes[i].value + divSizes[i].getAttribute("data-ftype");
if (divSizes[i].getAttribute("data-size") == divAmounts[i].getAttribute("data-size") && divSizes[i].getAttribute("data-ftype") == divAmounts[i].getAttribute("data-ftype"))
{

  //console.log("ok");
  divSizes[i].value = "("+ divAmounts[i].getAttribute("data-amount") +") " + divSizes[i].getAttribute("data-size") + " " + divSizes[i].getAttribute("data-ftype");
}

    //divAmount[i].remove();
}
}

</script>
<script>
function cancelAllTasks()
{
  document.getElementById("slctTasks").style.display="block";
  const NumberOfTasks =document.getElementById("slctTasks").length;
  //const allTasks = document.getElementById("slctTasks");
  for(let i = 0;i < NumberOfTasks;i++)
    {
      x = document.getElementById("slctTasks").options[i].value;
      document.getElementById("lbl"+x).remove();
    }


  allt =document.getElementsByClassName("checkmarkListEquipment");
  //CLOSE ALL CKBOX ON LIST EQUIPMENT LIST
  for(let i=0; i < allt.length; i++)
    {
      allt[i].checked=false;
    }
  document.cookie = "tasklist=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";
  document.getElementById("divTasks").style.display="none";
slctAllTasks=document.getElementById("slctTasks");
var i, L = slctAllTasks.length - 1;
   for(i = L; i >= 0; i--) {
      slctAllTasks.remove(i);
   }
  document.getElementById("slctTasks").style.display="none";
}
</script>
<script>
function addTaskToForm(id, unitname, fromCookie) {
var select = document.getElementById("slctTasks");
var opt = document.createElement('option');
    opt.value = id;
    opt.innerHTML = id;
    select.appendChild(opt);

mytop=document.getElementById("myTable").offsetTop;
document.getElementById("frmsubmittasks").style.display="block";
allTasks=document.getElementById("divTasks").getElementsByTagName("label");
if(allTasks.length > 2){jQuery('#divTasks').css("overflow-y", "scroll");}
var elementExists = document.getElementById(id);
if(document.getElementById(id) != null)
{
   if(document.getElementById('cktask'+id).checked == false)
   {
      document.getElementById(id).remove();
      document.getElementById('lbl'+id).remove();
   }
}
if(fromCookie==true){document.getElementById('cktask'+id).checked = true};
if(document.getElementById('cktask'+id).checked == true)
   {
   //CREATE <label class="container">
   var label = document.createElement("LABEL");
   label.setAttribute("id", "lbl"+id);
   label.setAttribute("style", "margin-top:30px");
   label.htmlFor = id;
   label.innerHTML = "&nbsp;&nbsp;"+unitname;
   //tasklist = tasklist + "," + unitname;
    var x = document.createElement("INPUT");
  x.setAttribute("type", "checkbox");
  x.setAttribute("id", id);
  x.setAttribute("checked", true);
  x.setAttribute("name", "ckBox[]");
  x.setAttribute("class", "checkmarkX");
  x.setAttribute("value", id);
  //x.setAttribute("disabled", true);
  x.setAttribute("onclick", 'uncheck('+id+')');
  
   document.getElementById("frmsubmittasks").appendChild(label);
   document.getElementById("lbl"+id).appendChild(x);

   }
   //CLEAR GREEN FROM IF NO CHECK BOXES LEFT
   var inputs = document.forms["frmsubmittasks"].getElementsByTagName("input");
   var found=false;
   

      
      for(var i = 0; i < inputs.length; i++) 
         {
            if(inputs[i].type.toLowerCase() == 'checkbox') 
               {
                  found=true;
                  document.getElementById("frmsubmittasks").style.display="flex";
                  document.getElementById("tableheader").style.position="static";
                  document.getElementById("divTasks").style.top="0px";
                  document.getElementById("divTasks").style.position="sticky";
                  document.getElementById("divTasks").style.zIndex=4;
                  document.getElementById("divTasks").style.display="block";
               }
         }


      if(found==false){
         document.getElementById("frmsubmittasks").style.display="none";
         document.getElementById("tableheader").style.position="sticky";
         document.getElementById("divTasks").style.display="none";
         document.getElementById("divTasks").style.position="static";
}
      //SAVE THEM TO COOKIE
      saveTasksToCookie()
}

</script>
<script>
function saveScrollPosition()
{
   var scrollPos = window.scrollY || window.scrollTop || document.getElementsByTagName("html")[0].scrollTop;
      var timeToAdd = 1000 * 60 * 60 * 24 * 7 * 4 * 6;
      var date = new Date();
      var expiryTime = parseInt(date.getTime()) + timeToAdd;
      date.setTime(expiryTime);
      var utcTime = date.toUTCString();
      document.cookie = "scrollPos="+scrollPos+"; expires=" + utcTime + ";path=/";
}
</script>
<script>
function saveTasksToCookie(){
tasklist = "";
let exdate = new Date(Date.now() + 86400e3);
exdate = exdate.toUTCString();
var ckboxs = document.forms['frmsubmittasks'].getElementsByClassName("checkmarkX");
let jsonTask = '{ "tasks" : [';
    for (var i = 0; i < ckboxs.length; i++) 
    {
      if(ckboxs[i].type == "checkbox")
         {
            m=ckboxs[i].getAttribute("id");
            UnitName = document.getElementById("lbl"+ m).innerText;
            if(i+1 < ckboxs.length)
              {
                jsonTask=jsonTask + '{"unitname":"'+UnitName+'", "id":"'+m+'"},';}
              else
              {
                jsonTask=jsonTask + '{"unitname":"'+UnitName+'", "id":"'+m+'"}';}
              }
          
    }
jsonTask=jsonTask + "]}";
document.cookie = "tasklist=" + jsonTask + "; expires=" + exdate + "; path=/";
}
</script>
<script>
   function setScrollPosition(){
    try{
      x=getCookie('scrollPos');
      window.scrollTo(0, x);
    } catch (error) {
        //console.log("error with setScrollPosition");
    }
   }
</script>
<script>
function expireJavaCookie(name) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
}
</script>
<script>
function setJavaCookie(name,value,days) {
 if (days) {
 var date = new Date();
 date.setTime(date.getTime()+(days*24*60*60*1000));
 var expires = "; expires="+date.toGMTString();
 }
 else var expires = "";
 document.cookie = name+"="+value+expires+"; path=/";
}
</script>

<script>
function deletecookie(cname){
   document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;"
   document.cookie = cname+"=; expires=Thu, 01 Jan 1970 00:00:00 UTC;"
}
</script>

<script>
   function getJavaCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) 
    {
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
<script type="text/javascript">
   function isMobileDevice()
    {
      const userAgent = navigator.userAgent.toLowerCase();
      var isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
      return isMobile;
    }
  </script>
<script>
/* div below is needed to use callAlert function
<div class="toast" role="alert" id="toastAlert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
  <div class="toast-header"><button>close</button>
    <img src="..." class="rounded mr-2" alt="...">
    <strong class="mr-auto">Bootstrap</strong>
    <small>11 mins ago</small>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="toast-body">
    Use numeral inputs only.
  </div>
</div> */

function callAlert(message){
var x = document.getElementById("toastAlert");
        x.innerHTML = message;
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 9000);
}
</script>
<script>
function autocomplete(inp, arr) {
  /*the autocomplete function takes two arguments,
  the text field element and an array of possible autocompleted values:*/
  var currentFocus;
  /*execute a function when someone writes in the text field:*/
  inp.addEventListener("input", function(e) {
      var a, b, i, val = this.value;
      /*close any already open lists of autocompleted values*/
      closeAllLists();
      if (!val) { return false;}
      currentFocus = -1;
      /*create a DIV element that will contain the items (values):*/
      a = document.createElement("DIV");
      a.setAttribute("id", this.id + "autocomplete-list");
      a.setAttribute("onclick", "submitSearch();")
      a.setAttribute("class", "autocomplete-items");
      /*append the DIV element as a child of the autocomplete container:*/
      this.parentNode.appendChild(a);
      /*for each item in the array...*/
      for (i = 0; i < arr.length; i++) {
        /*check if the item starts with the same letters as the text field value:*/
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
          /*create a DIV element for each matching element:*/
          b = document.createElement("DIV");
          /*make the matching letters bold:*/
          b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
          b.innerHTML += arr[i].substr(val.length);
          /*insert a input field that will hold the current array item's value:*/
          b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
          /*execute a function when someone clicks on the item value (DIV element):*/
              b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              setJavaCookie("SearchWords",inp.value ,1);
              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
          });
          a.appendChild(b);
        }
      }
  });
  /*execute a function presses a key on the keyboard:*/
  inp.addEventListener("keydown", function(e) {
      var x = document.getElementById(this.id + "autocomplete-list");
      if (x) x = x.getElementsByTagName("div");
      if (e.keyCode == 40) {
        /*If the arrow DOWN key is pressed,
        increase the currentFocus variable:*/
        currentFocus++;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 38) { //up
        /*If the arrow UP key is pressed,
        decrease the currentFocus variable:*/
        currentFocus--;
        /*and and make the current item more visible:*/
        addActive(x);
      } else if (e.keyCode == 13) {
        /*If the ENTER key is pressed, prevent the form from being submitted,*/
        e.preventDefault();
        if (currentFocus > -1) {
          /*and simulate a click on the "active" item:*/
          if (x) x[currentFocus].click();
        }
      }
  });
  function addActive(x) {
    /*a function to classify an item as "active":*/
    if (!x) return false;
    /*start by removing the "active" class on all items:*/
    removeActive(x);
    if (currentFocus >= x.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = (x.length - 1);
    /*add class "autocomplete-active":*/
    x[currentFocus].classList.add("autocomplete-active");
  }
  function removeActive(x) {
    /*a function to remove the "active" class from all autocomplete items:*/
    for (var i = 0; i < x.length; i++) {
      x[i].classList.remove("autocomplete-active");
    }
  }
  function closeAllLists(elmnt) {
    /*close all autocomplete lists in the document,
    except the one passed as an argument:*/
    var x = document.getElementsByClassName("autocomplete-items");
    for (var i = 0; i < x.length; i++) {
      if (elmnt != x[i] && elmnt != inp) {
      x[i].parentNode.removeChild(x[i]);
    }
  }
}
/*execute a function when someone clicks in the document:*/
document.addEventListener("click", function (e) {
    closeAllLists(e.target);
});
}
</script>
<script>
function isOnlyDigits(string) {
   //for (let i = 0; i < string.length; i++) {
      var ascii = string.charCodeAt(string);
      if (ascii < 48 || ascii > 57) {
         return false;
      }
   //}
   return true;
}

function checkDataType(textboxid){
isNumber = true;
var elementType = document.getElementById(textboxid).nodeName;
if(elementType == "INPUT")
    {
        str=document.getElementById(textboxid).value;
    }
    else
    {
        str=document.getElementById(textboxid).innerHTML;
    }

if(isNaN(str) != false)
{
        isNumber = false;
      var x = document.getElementById("toastAlert");
        x.innerHTML = "Use numerical inputs only";
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        document.getElementById(textboxid).value="";
}
        return isNumber;
}
</script>
<script>
function checkForm(formID){
        formpass = true;
        //CHECK UNIT NAME 
        if(document.getElementById("unit_name").value == "")
            {
                formpass = false;
                elementx = document.getElementById("unit_name");
                errorcode = "Unit name can not be blank";
            }
        //CHECK THAT FILTER SIZE FOR #1 WAS SELECTED
        var select1 = document.getElementById("slctSize1");
        var $f1 = select1.options[select1.selectedIndex].text;
        if($f1.toLowerCase() == "select size")
        {
            formpass = false;
            elementx = document.getElementById("slctSize1");
            errorcode = "Please input a filter size for filter size #1."
        }
        //CHECK THAT FILTER AMOUNT IS NOT BLANK FOR #1
        if(document.getElementById("amount1").value == "")
        {
            formpass = false;
            elementx = document.getElementById("amount1");
            errorcode = "Please input AMOUNT of filters for filter size #1."
        }


        //CHECK THAT FILTER SIZE FOR #2 WAS SELECTED IF AMOUNT2 WAS FILLED IN
        var select2 = document.getElementById("slctSize2");
        var amount2 = document.getElementById("amount2").value;
        var $f2 = select2.options[select2.selectedIndex].text;
        if(document.getElementById("btnShowSize2").className == "container m-4" && document.getElementById("filtersize2").value == "")
        {
            formpass = false;
            elementx = document.getElementById("amount2");
            errorcode = "Please input a filter size for filter #2."
        }
        //CHECK THAT FILTER AMOUNT IS NOT BLANK FOR #2
        if(document.getElementById("amount2").value == "" && document.getElementById("filtersize2").className == "container m-4")
        {
            formpass = false;
            elementx = document.getElementById("amount2");
            errorcode = "Please input AMOUNT of filters for filter size #2."
        }



        //CHECK THAT ROTATION IS NOT BLANK
        if(document.getElementById("filter_rotation").value == "")
        {
            formpass = false;
            elementx = document.getElementById("filter_rotation");
            errorcode = "Please input a rotation number.(amount of months between filter changes)."
        }
        if(formpass == false)
        {
            var x = document.getElementById("snackbar");
            x.innerHTML = errorcode;
            elementx.className="bg-danger text-white w-50";
            x.className = "show";
            setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
        }
        else
        {
            document.getElementById(formID).submit();
        }
    }
</script>

