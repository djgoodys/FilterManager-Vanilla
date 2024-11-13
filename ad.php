<?php
include "javafunctions.php"
?>
<!DOCTYPE html> 
<html></html>
<title>A Filter Manager Software</title>
<Head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=AW-16542837705">
</script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-16542837705');
</script>
<style>
.div5 {
    background-color:black;
    color:white;
    text-align:center;
    width:100%;
}
.div5forPhone {
    background-color:black;
    color:white;
    text-align:center;
    width:100%";
    font-size:3em;
}
ul {
  /* Style the unordered list (optional) */
  list-style-type: disc; /* Change bullet point style (disc, circle, square, etc.) */
  margin: 0; /* Remove default margin */
  padding: 0; /* Remove default padding */
}

li {
  /* Style the list items */
  margin-bottom: 10px; /* Add spacing between items */
}
.dotted-list {
  display: inline-block; /* Ensures the dots appear before the content */
  leader-chars: "."; /* Defines the leader character as a dot */
  leader-space: 1em; /* Controls the spacing between the dot and the content */
  /* Optional styles for the content */
  color: black;
}
</style>
  <script src="https://js.stripe.com/v3/"></script>
  <script>
  function submitForm() {
  var form = document.getElementById("myForm");
  form.submit();
}
  </script>
  <!-- Google tag (gtag.js) --> <script async src="https://www.googletagmanager.com/gtag/js?id=AW-16542837705"></script> <script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'AW-16542837705'); </script>

</Head>
<body style="background-color:green;color:white;">
<div id="div1" style="display: flex;flex-direction:row;";><img id="img1" src="https://www.filtermanager.net/images/fm1.jpg" style="width:200px;height:200px;margin:0 auto;"><div id="div3" style="margin:0 auto;width:400px;">WELCOME TO FILTERMANAGER.NET. This app organizes everything needed to change out air conditioning filters at large or small facilities. It will alert when filters are over do to be replaced, track your filter inventory and create filter order sheats in seconds. It has a full search engine to locate units or filters. Can be accessed from any web browser on any device. Computers, tablets or cell phones. You will be wondering how you got along with out it. This product is available right now with a free 180 day trial offer(cancel anytime, no credit card or payment needed during trial period).</div><div id="div2" style="border-radius:25%;background-color:gold;color:black;font-size:2em;width:300px;height:180px;padding-top:50px;padding-left:20px;font-weight: bold;">
<form action="cust_signup.php" method="POST" id="myForm">
<!-- Add a hidden field with the lookup_key of your Price -->
<input type="hidden" name="lookup_key" value="{{PRICE_LOOKUP_KEY}}" />
<a href="javascript:void(0);" onclick="submitForm()"> CLICK HERE TO START YOUR 180 DAY FREE TRIAL NOW</a></div></form></div></div>
<table style="color:#5ff707;margin:50px;" id="tbl1">
<tr><td>$ave on power!</td><td>$ave on costly air conditioning repairs!</td>
<td>Quicky see which filters are past due!</td>
<td>Automatically updates filter inventory!</td>
<td>Create order sheets in seconds!</td>
<td>Track hundreds of air conditioning units!</td></tr></table>
<div>SCREEN SHOT</div>
<img src="https://www.filtermanager.net/images/screen-shot.png" width="100%" height="400px">
<div>SCREEN SHOT</div>
<img src="https://www.filtermanager.net/images/screen-shot2.png" width="100%" height="500px">
<div>SCREEN SHOT</div>
<img src="https://www.filtermanager.net/images/screen-shot3.png" width="100%" height="500px">
<div>SCREEN SHOT</div>
<img src="https://www.filtermanager.net/images/screen-shot4.png" width="100%" height="500px">
<divstyle="font-size:2em;"><a href="https://www.filtermanager.net/cust_signup.php">Try free for 180 days</a>.No credit or debit card needed to sign up and use during the 180 day trial. Cancel anytime.</div>
</body>
<script defer>
if (isMobileDevice()) {
   document.getElementById('div1').style="display: flex;flex-direction:column;justify-content: center;position:relative;";
   document.getElementById('div2').style="margin:0 auto;margin-top:50px;justify-content: center;border-radius:25%;background-color:gold;color:black;font-size:2em;width:400px;height:350px;display: flex;align-items: center;font-size:3em;padding:20px;font-weight: bold;";
   document.getElementById('div3').style="margin:0 auto;width:80%;font-size:2em;margin-top:50px;";
   document.getElementById('img1').style="width:80%;height:auto;margin:0 auto;";
const tableElement = document.getElementById("tbl1");

if (tableElement) {
  tableElement.style.fontSize = "3em";
}
if (tableElement) {
  const tableRows = tableElement.getElementsByTagName("tr");
  for (let i = 0; i < tableRows.length; i++) {
    const row = tableRows[i];
    row.querySelectorAll("td").forEach(function(tableCell) {
      tableCell.style.display = "block";
    });
  }
}
}
</script>
</html>