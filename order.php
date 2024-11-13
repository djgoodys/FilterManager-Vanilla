<?php
if(session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }
   
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)

   $BackUpFolder="";
   if(!isset($_SESSION["backup_folder"])){
      if(isset($_GET["folder"]))
      {
         $BackUpFolder=($_GET["folder"]);
         $_SESSION["backup_folder"] = $_GET["folder"];
      }
      else
      {
      $BackUpFolder = "none";
      }
   }
   else
   {
      $BackUpFolder = $_SESSION["backup_folder"];
   }

   if(isset($_SESSION["backup_folder"]) == 1 && $BackUpFolder == "none")
   {
      header('Location: '."start.php");
   }

include "CustomConfirmBox.css";
//print_r($_POST);
//print_r($_GET);
$Action="new_order";
if(isset($_GET["action"])){$Action=$_GET["action"];}
if(isset($_GET["order_id"]))
{
   $OrderID=$_GET["order_id"];
   $arrOrderInfo = explode("*",$_GET["order_info"]);
   //arInfo[0]=id,arInfo[1]=date
   //print_r($arrOrderInfo);
}
$OrderInfo = "";
if(isset($_GET["order_info"])){
   $arrOrderInfo[] = explode("*",$_GET["order_info"]);
   $OrderID = $arrOrderInfo[0];
   $OrderInfo = $_GET["order_info"];
}

if(isset($_POST["order_info"])){
   $arrOrderInfo = explode("*",$_POST["order_info"]);
   $OrderID = $arrOrderInfo[0];
   $OrderInfo = $_POST["order_info"];
}
if(isset($_POST["update_order"])){
   $arrOrderInfo = explode("*",$_POST["order_info"]);
   $OrderID = $arrOrderInfo[0];
   $OrderInfo = $_POST["order_info"];
}
if(isset($_POST["action"])){$Action=($_POST["action"]);}
if(isset($_POST["qty"])){$Qty=$_POST["qty"];}
if(isset($_POST["uom"])){$UOM= $_POST['uom'];}
if(isset($_POST["partnumber"])){$PartNumber = $_POST['partnumber'];}
if(isset($_POST["description"])){$Description= $_POST['description'];}
if(isset($_POST["price"])){$Price= $_POST['price'];}
if(isset($_POST["totalprice"])){$TotalPrice= $_POST['totalprice'];}
if(isset($_POST["date"])){$OrderDate= $_POST['date'];}
if(isset($_POST["qty"])){$Qty=$_POST["qty"];}
if(isset($_POST["shop"])){$ShopArea=$_POST["shop"];}else{$ShopArea="";}
if(isset($_POST["requestedby"])){$RequestedBy=$_POST["requestedby"];}else{$RequestedBy="";}
if(isset($_POST["cer"])){$CER=$_POST["cer"];}else{$CER="";}
if(isset($_POST["vender"])){$Vender=$_POST["vender"];}else{$Vender="";}
if(isset($_POST["needbydate"])){$NeedByDate=$_POST["needbydate"];}else{$NeedByDate="";}
if(isset($_POST["solevender"])){$SoleVender=$_POST["solevender"];}else{$SoleVender="";}
if(isset($_POST["fsize"])){$FilterSize=$_POST["fsize"];}else{$fsize="";}
if(isset($_POST["vender"])){$SuggestedVender=$_POST["vender"];}else{$SuggestedVender="";}
 if(isset($_POST["Quote"])){$Quote=$_POST["Quote"];}else{$Quote = "";}
$CompanyName = "";
?>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

/* The Close Button */
.close {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}

button {
   box-shadow: 4px 4px black;
}
select {
   box-shadow: 4px 4px black;
}
div {
   box-shadow: 4px 4px black;
}
#divShare {
  box-shadow: none;
}
</style>
<script>
function enableElements(yesno){
const textInputs = document.getElementsByTagName('input');
for (let i = 0; i < textInputs.length; i++) {
  document.getElementById("rdoSoleVenderYes").disabled = false;
  document.getElementById("rdoSoleVenderNo").disabled = false;
  document.getElementById("dtNeedByDate").disabled = false;
  document.getElementById("order_date").disabled = false;
      if (textInputs[i].type === 'text') {
        if(yesno == 'no')
        { 
         textInputs[i].readOnly = true;
        }
        else
        {
         textInputs[i].readOnly = false;
        }
      }
}
}
</script>
<script>
function myConfirmBox(message, frmID) {
    let element = document.createElement("div");
    const list = element.classList;
    list.add("box-background:red;");
    element.innerHTML = "<div class='box' style='width:500px;height:300px;top:5px;background-color:gold;font-size:2em;'>"+ message + "<button id='trueButton' class='btn green'>Yes</button><button id='falseButton' class='btn red'>No</button></div>";
    document.body.appendChild(element);
    return new Promise(function (resolve, reject) {
        document.getElementById("trueButton").addEventListener("click", function () {
            resolve(true);
            document.getElementById(frmID).submit();
            document.body.removeChild(element);
            
        });
        document.getElementById("falseButton").addEventListener("click", function () {
            resolve(false);
            document.body.removeChild(element);
        });
    })
}
</script>
<script>
function IsThere(element) 
{
   if(document.getElementById(element) === null || document.getElementById(element) === undefined)
   {
      return false;
   }
   else
   {
      return true;
   }
}
</script>
<script>
function CanYouShare(Order_Info)  
{                    
   if (IsThere("shareButton") === false) 
      {
         
         var shareButton = document.createElement("img");
         shareButton.setAttribute("id", "shareButton");
         shareButton.setAttribute("style", "margin-left:50px;box-shadow: 5px 5px 10px black;border-radius:50%;");
         shareButton.setAttribute("src", "images/share.jpg");
         shareButton.setAttribute("onclick", "share('"+ Order_Info + "');");
         elem = document.getElementById("divShare");
         elem.appendChild(shareButton);
         shareButton.style.display = "inline-block";
         shareButton.style.width = "45px";
         shareButton.style.height = "auto";
      }
}
</script>
<script>
function share(order_info)
{
   if (navigator.share) 
   {
      //var orderInfo = order_info.split('*');
      //var myArray = order_info;
      //const serializedArray = JSON.stringify(myArray);
      //const encodedArray = encodeURIComponent(serializedArray);

      const shareData = 
         {
            title: "Info for filter order number" + order_info,
            text: "Info brought to you by FilterManager",
            url: "https://filtermanager.atwebpages.com/order.php?action=edit_order&order_info="+ order_info +"&folder=<?php echo $BackUpFolder ?>",};
         

         navigator.share(shareData)
         .then(() => console.log("Content shared successfully!"))
         .catch((error) => console.error("Error sharing:", error));
   } 
   else 
   {
   console.error("Sharing API not supported in this browser.");
   } 
         
}
</script>


<script>
function confirmDeleteOrder() {
console.log(myConfirmBox("Permentantly remove this order?","frmDeleteOrder"));
}
</script>
<script>
function printme(){
      
      //----------------

try {
    // Hide tblControls
    var tblControls = document.getElementById('tblControls');
    if (tblControls) {
        tblControls.style.display = 'none';
        var tblControlsWasThere = true;
    }
    var removeHeader = document.getElementById("thRemove");
    removeHeader.style.display = "none";
   const buttons = document.querySelectorAll('button');

// Hide table cells containing "Remove" buttons
for (const button of buttons) {
  // Check if the button text content includes "Remove" (case-insensitive)
  if (button.textContent.toLowerCase().includes('remove')) {
    // Hide the parent table cell instead of the button itself
    button.parentNode.style.display = 'none';
  }
}

  
    // Hide btnPrint
    var btnPrint = document.getElementById('btnPrint');
    if (btnPrint) {
        btnPrint.style.display = 'none';
        var btnPrintWasThere = true;
    }

    // Hide btnSave
    var btnSave = document.getElementById('btnSave');
    if (btnSave) {
        btnSave.style.display = 'none';
        var btnSaveWasThere = true;
    }

    // Hide slctOrder
    var slctOrder = document.getElementById('slctOrder');
    if (slctOrder) {
        slctOrder.style.display = 'none';
        var slctOrderWasThere = true;
    }
} catch (error) {
    console.error('An error occurred:', error.message);
}
var Pad=document.getElementById("divTotalCost").style.paddingRight;
document.getElementById("divTotalCost").style.paddingRight="40px";
//=======================
      window.print();
      document.getElementById("divTotalCost").style.paddingRight=Pad;
      if(tblControlsWasThere){document.getElementById('tblControls').style.display="block";}
      if(btnPrintWasThere){document.getElementById('btnPrint').style.display="block";}
      if(btnSaveWasThere){document.getElementById('btnSave').style.display="block";}
      if(slctOrderWasThere){document.getElementById('slctOrder').style.display="block";}

    removeHeader.style.display = "block";
   const buttons = document.querySelectorAll('button');


for (const button of buttons) {
  if (button.textContent.toLowerCase().includes('remove')) {
    button.parentNode.style.display = 'block';
  }
}
   }
   </script>
   <script>
   function addTotal($linenumber){
   var price=document.getElementById("pricebox"+$linenumber).value;
   var totalpricebox=document.getElementById("totalpricebox"+$linenumber);
   var amount =document.getElementById("amountbox"+$linenumber).value;
   var myResult = amount * price;
   totalpricebox.value=myResult;
   }
</script>
</head>
<body>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
    function removeRow(btn) {
      // Get the table row containing the button
      var row = btn.parentNode.parentNode;

      // Remove the row from the table
      row.parentNode.removeChild(row);
    }
  </script>
<?php
 $jsonString = file_get_contents('sites/'.$BackUpFolder.'/data.json');
$data = json_decode($jsonString, true);
//GET COMPANY NAME FOR ORDER SHEET
foreach ($data["misc"] as &$object) {
   $CompanyName = $object["company_name"];
}

//UPDATE ORDER AFTER EDIT
if($Action == "update_order")
{
   $jsonContents = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json', true);
   $dataArray = json_decode($jsonContents, true);
   $TotalOrderArray=array();

   for($i=0; $i < count($_POST)-1; $i++)
      {
         //CREATE ASSOCIATIVE ARRAY
         //echo "post=".$_POST
         $array = array("qty"=>$_POST["qty"][$i],
         "uom"=>$_POST["uom"][$i],
         "partnumber"=>$_POST["partnumber"][$i],
         "description"=>$_POST["description"][$i],
         "priceeach"=>$_POST["price"][$i],
         "totalprice"=>$_POST["totalprice"][$i]);
         array_push($TotalOrderArray, $array);
      }

   $OrderArrayAsString = json_encode($TotalOrderArray);
   foreach ($dataArray["filter_orders"] as &$order) 
      {
         if ($order['_id'] == $OrderID) 
            {
               $order['order_date'] = $OrderDate;
               $order['order_array'] = $OrderArrayAsString;
               $order['shoparea'] = $ShopArea;
               $order['requested_by'] = $RequestedBy;
               $order['cer'] = $CER;
               $order['sole_vender'] = $SoleVender;
               $order['suggested_vender'] = $Vender;
               $order['quote_number'] = $Quote;
               $order['need_by_date'] = $NeedByDate;
               $order['company_name'] = $CompanyName;
               break; // Stop searching once found
            }
      }
         $updatedJson = json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
         file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $updatedJson);
         ?>
         <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
         <select class="form-select bg-warning text-black" id="slctOrder" style="width:fit-content;margin-right:auto;height: 40px;border-radius:5px;margin-top:15px;" aria-label="Default select example" name="order_info" onchange="this.form.submit();">
         <option selected>View past orders...</option>
         <?php
            $jsonString = file_get_contents('sites/'.$BackUpFolder.'/data.json');
            $data = json_decode($jsonString, true);
               foreach ($data['filter_orders'] as &$row) 
                  {
                     
                        echo "<option value='".$row["_id"] ."*".$row["order_date"]."'>".$row["order_date"] ." [".$row["_id"]."]</option>";
                     
                  }
         ?>
         </select><input type="hidden" name="action" value="get_order"></form><img src='images/UpdateSuccess.jpg' style="width:auto;height:30%;">
         <?php
}


 //SAVE ORDER
if(strcmp($Action, "saveorder")==0)
   {
      ?>
      <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" style="display: grid;place-items: center;">
      <select class="form-select bg-warning text-black" id="slctOrder" style="width:fit-content; margin: 0 auto;height: 40px;border-radius:5px;margin-top:15px;" aria-label="Default select example" name="order_info" onchange="this.form.submit();">
      <option selected>View past orders...</option>
      <?php
         $jsonString = file_get_contents('sites/'.$BackUpFolder.'/data.json');
         $data = json_decode($jsonString, true);
            foreach ($data['filter_orders'] as &$row) 
               {
                   
                     echo "<option value='".$row["_id"] ."*".$row["order_date"]."'>".$row["order_date"] ." [".$row["_id"]."]</option>";
                  
            }
      ?>
      </select><input type="hidden" name="action" value="get_order"></form>
      <?php
      $TotalOrderArray=array();
      for($i=0; $i < count($_POST); $i++)
         {
            //echo "qty=".$_POST["qty"][$i]." ";
            //echo "uom=".$_POST["uom"][$i]." ";
            //echo "Part number=".$_POST["partnumber"][$i]." ";
            //echo "description=".$_POST["description"][$i]." ";
            //echo "price each=".$_POST["price"][$i]." ";
            //echo "total price=".$_POST["totalprice"][$i]."<br>";
            //CREATE ASSOCIATIVE ARRAY

            $array = array("qty"=>$_POST["qty"][$i],
            "uom"=>$_POST["uom"][$i],
            "partnumber"=>$_POST["partnumber"][$i],
            "description"=>$_POST["description"][$i],
            "priceeach"=>$_POST["price"][$i],
            "totalprice"=>$_POST["totalprice"][$i]);

            array_push($TotalOrderArray, $array);
         }

      $OrderArrayAsString = json_encode($TotalOrderArray);
      $OrderDate = date("Y-m-d");
      $maxId = 0;
      foreach ($data["filter_orders"] as $object) 
         {
            if ($object['_id'] > $maxId) 
               {
                  $maxId = $object['_id'];
               }
         }
         $maxId = $maxId + 1;
         $newOrder = array(
         '_id' => $maxId,
         'order_date' => $OrderDate,
         'order_array' => $OrderArrayAsString,
         'shoparea' => $ShopArea,
         'requested_by' => $RequestedBy,
         'cer' =>  $CER,  
         'sole_vender' => $SoleVender,
         'suggested_vender' => $Vender,
         'quote_number' => $Quote,
         'need_by_date' => $NeedByDate,
         'company_name' => $CompanyName
      );
      
        $data["filter_orders"][] = $newOrder;
        $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
        if (file_put_contents('sites/' . $BackUpFolder . '/data.json', $updatedJson)) {
            echo "<div style='text-align:center;width:100%;height:100%;color:green;font-size:2em;position: absolute;top: 50;right: 0;'>Order with id ".$maxId." was added successfully!</div><img src='images/order_success.jpg' width='400px' height='400px' style='margin-top:50px;margin-left:410px;'>";
        } else {
            echo "<div style='text-align:center;width:100%;background-color:black;color:red;position: absolute;top: 0;right: 0;'>Failed to save new filter order.</div>";
        }
     ?>
     <script defer>
         var min = 12,
         max = 100,
         select = document.getElementById('slctOrder');
         var opt = document.createElement('option');
         opt.style ="width:fit-content;margin-left:auto;margin-right:auto;";
         opt.value = "<?php echo $maxId. "*" . $OrderDate ?>";
         opt.innerHTML = "<?php echo $OrderDate ." [".$maxId ."]"; ?>"
         select.appendChild(opt);
     
     </script>
     
   <?php         
   }
 //DELETE ORDER
if(strcmp($Action, "delete_order")==0)
   {
      $data = json_decode(file_get_contents('sites/'.$BackUpFolder.'/data.json'), true);
      $indexToRemove = null;
      foreach ($data["filter_orders"] as $key => $order) {
         if ($order["_id"] == $OrderID) {
            $indexToRemove = $key;
            break;
         }
      }
      if ($indexToRemove !== null) {
         unset($data["filter_orders"][$indexToRemove]);
         // Re-index the array for consistency (optional)
         $data["filter_orders"] = array_values($data["filter_orders"]);
         $jsonData = json_encode($data, JSON_PRETTY_PRINT);
         file_put_contents('sites/'.$BackUpFolder.'/data.json', $jsonData);

         echo "Order removed successfully";
      } else {
         echo "Order with _id $OrderID not found";
      }

   }

$FilterSize="";
$FilterCount="";
$Par=0;
$RecID="";
$NumberOfRowsUsed=0; 
$CompanyName="";



//CREATE NEW ORDER
if(strcmp($Action, "new_order")==0)
   {      
      ?>
      <table width="60%" id="tblControls" styole="display:inline-block;"><tr><td>
      <button style="margin-left:10px;" id='btnPrint' onclick="printme();" class="btn btn-success">Print order</button>
      </td><td>
      <button class="btn btn-primary" name="save" value="save order" id="btnSave" onclick="document.getElementById('orderform').submit();">Save order</button>
      </td>
      <td>

      <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
      <select class="form-select bg-warning text-black" id="slctOrder" style="height: 40px;border-radius:5px;margin-top:15px;" aria-label="Default select example" name="order_info" onchange="this.form.submit();">
      <option selected>View past orders...</option>
      <?php
         $jsonString = file_get_contents('sites/'.$BackUpFolder.'/data.json');
         $data = json_decode($jsonString, true);
            foreach ($data['filter_orders'] as &$row) 
               {
                 
                     echo "<option value='".$row["_id"] ."*".$row["order_date"]."'>".$row["order_date"] ." [".$row["_id"]."]</option>";
                  
            }
      ?>
      </select><input type="hidden" name="action" value="get_order"></form></td>
     
</tr></table>
<form name="orderform1" id="orderform" method="post" action="order.php">
<input type="hidden" name="action" value="saveorder">
<table style="margin-left:10px;float:left;width:97%;height:auto;padding:1;" frame="border">
  <caption style="border: solid black;"><?php echo $CompanyName ?> Material Order Form</caption>
  <tr>
    <td>Date:<input type="Date" name="date" value="<?php echo date("Y-m-d") ?>" style="width:120px;" /></td>
    <td>Requested Ordered by:<input type="text"  name="requestedby" style="width:140px;" /></tr>
    <tr ><td>Shop/Area:<input type="text"  name="shop" style="width:140px;" /></td>
    <td>Project or CER (Required for):<input type="text"  name="cer" style="width:140px;" /></tr>
  </tr><td>Suggested Vender:<input type="text"  name="vender" style="width:140px;" /></td><td></td>
  
  <tr>
    <td>Quote #<input type="text"  name="Quote" style="width:140px;" /></td>
    <td>Need By Date:<input type="date" id="dtNeedByDate" name="needbydate" style="width:120px;"/><input type="date" name="date" id="date" onchange="document.getElementById('txtDate').value = this.value;"></td>
  </tr>
  <tr>
    <td>Sole Vender<input type="radio" name="solevender" id="rdoSoleVenderYes" value="yes">
    <label for="rdoSoleVenderYes">Yes</label> 
    <input type="radio" name="solevender" id="rdoSoleVenderNo" value="no" checked><label for="rdoSoleVenderYes">No</label></td>
    <td></td>
  </tr>
</table>
<table style="width:100%;margin-left: 10px;">
 <tr><td>Stock Item  /  Repair Item  /  Replacement Item  /  New Item to Add to Stock</td</tr>
 </table>            
  <!--CREATE NEW ORDER-->
 <table width="97%" id="filter_table" style="margin-left: 10px;"><tr>
 <th id="thRemove" style="display:block;">Remove</th>
    <th>Qty</th>
    <th>UOM</th>
    <th>Part #</th>
    <th>Description</th>
    <th>Price/Unit</th>
    <th>Total Price</th>
    </tr>
<?php
 foreach ($data["filters"] as &$row) 
      {
         if($row["filter_count"] <= $row["par"] && $row["par"] != 0 && $row["filter_count"] != $row["par"])
            {
               $OrderAmount = $row["par"] - $row["filter_count"];
               $totalprice = $OrderAmount;// *  $row["price"];
               $NumberOfRowsUsed=$NumberOfRowsUsed +1;
               ?>
                   <tr >
                       <td><button onclick="removeRow(this)">Remove</button></td><td><input type="text" style="width:80px;" name="qty[]" id="amountbox<?php echo $NumberOfRowsUsed ?>" onchange="addTotal(<?php echo $NumberOfRowsUsed ?>);" value="<?php echo $OrderAmount ?>"></td>
                       <td><input type="text"  name="uom[]" size="5" value="each"></td>
                       <td><input type="text"  name="partnumber[]" style="width:200px;" size="40"></td>
                       <td><input type="text"  name="description[]" style="width:400px;" value="<?php echo $row["filter_size"] ." ". $row["filter_type"]; ?>"></td>
                       <td><input type="text"  name="price[]" id="pricebox<?php echo $NumberOfRowsUsed ?>" size="9" onchange="addTotal(<?php echo $NumberOfRowsUsed ?>);"></td>
                       <td><input type="text"  name="totalprice[]" id="totalpricebox<?php echo $NumberOfRowsUsed ?>" size="9"></td>
                   </tr>
               <?php
            }
         }
            $LinesToPrint =15-$NumberOfRowsUsed;
            $i=0;
            While($i <= $LinesToPrint)
            {
            ?>
                 <tr>
                       <td><input type="text"  name="orderamount[]" size="5"></td>
                       <td><input type="text"  name="uom[]" size="5"></td>
                       <td><input type="text"  name="partnumber[]" style="width:100px;" size="20"></td>
                       <td><input type="text"  name="description[]" style="width:550px;" value=""></td>
                       <td><input type="text"  name="price[]" size="9"></td>
                       <td><input type="text"  name="totalprice[]" size="9"></td>
                   </tr>
            <?php
                   $i++;
            }
            echo "</table>";
            echo "<div style='width:96%;text-align: right; box-shadow: none;display:block;padding-right:0px;' id='divTotalCost'>Total Cost of Materials to be Purchased (including Tax & Shipping)&nbsp;<input type='text' style='width:110px;' id='totalorderprice' name='totalorderprice' value'".$totalprice."'></div>";
            echo "<table width='100%' style='margin-left:10px;'><tr><td width='30%'>Manager Approval:<input type=text></td><td width='30%'>AD Approval:</td><td width='30%'>Rcv'd by Purch Admin::</td></tr>";
            //echo "<tr><td width='30%' height='80px'></td><td width='30%' height='80px'>AD Approval:</td><td width='30%' height='80px'>Rcv'd by Purch Admin::</td></tr></form>";
            if(isset($_POST["Quote"])){$Quote=$_POST["Quote"];}
            if(isset($_POST["needbydate"])){$needbydate=$_POST["needbydate"];}
            if(isset($_POST["solevender"])){$SoleVender=$_POST["solevender"];}

     }
   
//OPEN ORDER FOR EDIT
    if(strcmp($Action,"get_order") == 0 || strcmp($Action, "edit_order")==0)
   {
      ?>
      <table width="100%" id="tblControls" style="display:inline-block;margin-left:10px;"><tr><td>
      <button id='btnPrint' onclick="printme();" class="btn btn-success">Print order</button>
      </td>
      <td>
      <form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post">
      <select class="form-select bg-warning text-black" id="slctOrder" style="height: 40px;border-radius:5px;margin-top:15px;" aria-label="Default select example" name="order_info" onchange="this.form.submit();">
      <option selected>View past orders...</option>
      <?php
      $jsonString = file_get_contents('sites/'.$BackUpFolder.'/data.json');
      $data = json_decode($jsonString, true);
        foreach ($data['filter_orders'] as &$row) 
         {
            
               echo "<option value='".$row["_id"] ."*".$row["order_date"]."'>".$row["order_date"] ." [".$row["_id"]."]</option>";
            
   	}
      ?>
      </select><input type="hidden" name="action" value="get_order"></form></td><td><button type="button" id="btnUpdate" class="btn btn-info" onclick="if(this.innerText == 'Edit'){enableElements('yes');this.innerText = 'Update';}else{document.getElementById('frmUpdateOrder').submit();}">Edit</button></td><td>
      <?php 
   if(isset($OrderInfo))
      {
         $arrOrderInfo = explode("*",$OrderInfo);
         echo "<td><div id='divMessage' style='height:45px;text-align:center;border-radius:5px;line-height:45px;padding-left:10px;background-color:#6a2573;color:white;'>Viewing Order # ".$arrOrderInfo[0].". Saved : ".$arrOrderInfo[1]."</div></td><td><form id='frmDeleteOrder' action='".$_SERVER["SCRIPT_NAME"]."' method='post'><button type='button' class='btn btn-danger' name='btndelete_order' style='height:44px;width:fit-content;margin-top:16px;margin-left:20px;' onclick='confirmDeleteOrder();'>Delete Order</button><input type='hidden' name='order_id' value='".$arrOrderInfo[0]."'><input type='hidden' name='action' value='delete_order'></form></td><td><div id='divShare'></div></td>";
         echo "<script defer>CanYouShare('". $OrderInfo ."');</script>";
      }
      ?>
   </td></tr></table>
   <?php
      $jsonString = file_get_contents('sites/'.$BackUpFolder.'/data.json');
      $data = json_decode($jsonString, true);    
      foreach($data["filter_orders"] as $row) 
         {
            if($row["_id"] == $arrOrderInfo[0])
               {
                  $RequestedBy = $row["requested_by"];
                  $OrderDate = $row["order_date"];
                  $CER = $row["cer"];
                  $Shop = $row["shoparea"];
                  $Vender = $row["suggested_vender"];
                  $Quote = $row["quote_number"];
                  $NeedByDate = $row["need_by_date"];
                  $SoleVender = $row["sole_vender"];
                  $CompanyName = $row["company_name"];
                  $OrderArrayAsString = $row["order_array"];   
               } 
         }    
//echo "count=".count($OrderArrayAsString);
   $obj =  json_decode($OrderArrayAsString, true);

         ?>
   <form id="frmUpdateOrder" onkeydown = "if(document.getElementById('btnUpdate').innerText == 'Edit'){document.getElementById('myModal').style.display = 'block';}" action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post"><div style="box-shadow: none;">
   <input type="hidden" name="action" value="update_order">
   <input type="hidden" name="order_info" value="<?php echo $OrderInfo ?>">
   <table style="margin-left:10px;float:left;width:100%;height:auto;padding:1;" frame="border">
  <caption style="border: solid black;width:99%;"><?php echo $CompanyName ?> Material Order Form</caption>
  <tr>
    <td>Date:<input type="date" disabled onmouseover = "if(document.getElementById('btnUpdate').innerText == 'Edit'){document.getElementById('myModal').style.display = 'block';}" id="order_date" name="date" style="width:140px;" size="30" value="<?php echo $OrderDate ?>"/></td>
    <td>Requested Ordered by:<input type="text" readonly name="requestedby" style="width:150px;" size="50" value="<?php echo $RequestedBy  ?>"/></tr>
    <tr ><td>Shop/Area:<input type="text" readonly style="width:150px;" name="shop" size="50" value="<?php echo $Shop?>"/></td>
    <td>Project or CER (Required for):<input type="text" readonly style="width:150px;"  name="cer" size="60" value="<?php echo $CER ?>"/></tr>
  </tr><td>Suggested Vender:<input type="text" readonly name="vender" value="<?php echo $Vender ?>" size="40" /></td>
  
  <tr>
    <td>Quote #<input type="text" readonly name="Quote" value="<?php echo $Quote ?>" size="40" /></td>
    <td>Need By Date:<input type="date" disabled onmouseover = "if(document.getElementById('btnUpdate').innerText == 'Edit'){document.getElementById('myModal').style.display = 'block';}"  id="dtNeedByDate" name="needbydate" value="<?php echo $NeedByDate ?>" size="40" /></td>
  </tr>
  <tr>
    <td>Sole Vender <input type="radio" disabled onmouseover = "if(document.getElementById('btnUpdate').innerText == 'Edit'){document.getElementById('myModal').style.display = 'block';}" name="solevender" id="rdoSoleVenderYes" value="yes" 
    <?php
    if(strcmp($SoleVender,"yes")==0)
    {
      echo " checked";
    }
    ?>
    ><label for="rdoSoleVenderYes">Yes</label> 
    <input type="radio" name="solevender" disabled onmouseover = "if(document.getElementById('btnUpdate').innerText == 'Edit'){document.getElementById('myModal').style.display = 'block';}" id="rdoSoleVenderNo" value="no" 
     <?php
    if(strcmp($SoleVender,"no")==0)
    {
      echo " checked";
    }
    ?>
    ><label for="rdoSoleVenderNo">No</label></td>
    <td></td>
  </tr>
   </table>
   <table width="100%" style="margin-left: 10px;>
   <tr><td>Stock Item  /  Repair Item  /  Replacement Item  /  New Item to Add to Stock</td</tr>
   </table>
   <!--VIEWING SAVED ORDER-->
 <table id="filter_table" style="margin-left: 10px;width:100%"><tr>
    <th>Qty</th>
    <th>UOM</th>
    <th>Part #</th>
    <th>Description</th>
    <th>Price/Unit</th>
    <th>Total Price</th>
    </tr>
   <?php
      //echo $sql ."<br>";
      $NumberOfRowsUsed=0;
      $i=0;
      $v=0;
   foreach($obj as $item) 
      { 
         $uom = $item['uom'];
         $qty = $item['qty'];
         $partnumber = $item['partnumber'];
         $description = $item['description']; //etc
         $price = $item['priceeach']; //etc
         $totalprice = $item['totalprice'];
         $NumberOfRowsUsed=$NumberOfRowsUsed+1;
         echo "<tr>";
         echo "<td><input type'text' readonly size='5' name='qty[]' value='".$qty ."'></td>";
         echo "<td><input type'text' style='width:50px;' readonly name='uom[]' value='".$uom."'></td>";
          echo "<td><input type'text' readonly name='partnumber[]' style='width:300px;' size='20' value='".$partnumber ."'></td>";
          echo "<td><input type'text' readonly name='description[]' style='width:450px;' value='".$description."'></td>";
          echo "<td><input type'text' readonly name='price[]' size='9' value='".$price."'></td>";
          echo "<td><input type'text' readonly name='totalprice[]' value='".$totalprice."'></td>";
          echo "</tr>";  
      }
            $LinesToPrint =15-$NumberOfRowsUsed;
            $i=0;
            While($i <= $LinesToPrint)
            {
            ?>
                 <tr>
                       <td><input type="text" readonly name="orderamount[]" size="5"></td>
                       <td><input type="text" readonly name="uom[]" size="5"></td>
                       <td><input type="text" readonly name="partnumber[]" size='20' style='width:200px;'></td>
                       <td><input type="text" readonly name="description[]" style='width:750px;' value=""></td>
                       <td><input type="text" readonly name="price[]" size='9'></td>
                       <td><input type="text" readonly name="totalorderprice"></td>
                   </tr>
            <?php
                   $i++;
            }
            echo "</table>";
            echo "<div style='width:100%;text-align: right; box-shadow: none;display:block;margin-right:40px;padding-right: 3px;' id='divTotalCost'>Total Cost of Materials to be Purchased (including Tax & Shipping)&nbsp;<input type='text' style='width:187px;' id='totalorderprice' name='totalorderprice' value'".$totalprice."'></div>";
            echo "<table width='100%'><tr><td width='30%'>Manager Approval:</td><td width='30%'>AD Approval:</td><td width='30%'>Rcv'd by Purch Admin:</td></tr></table></form>";
            ?>
            <!-- The Modal -->
            <div id="myModal" class="modal">

            <!-- Modal content -->
            <div class="modal-content">
               <span class="close" onclick = "document.getElementById('myModal').style.display = 'none';">&times;</span>
               <p>To begin editing order press the Edit button above. Press Update button when done to save your edit.</p>
            </div>
            <?php
            if(strcmp($Action, "edit_order")==0){
               ?>
               <script defer>
               enableElements('no');
               </script>
            <?php
            }
           
   }


 function getFilterCount($fsize, &$result){
   foreach($result as $side=>$direc) 
      {
      //echo $fsize . "=" . $direc["filter_size"]."<br>";
         if(strpos($fsize, $direc["filter_size"]) != false)
            {
               return $direc["filter_count"];
            }
      }
      }
?>
