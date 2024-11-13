<?php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

  //or, if you DO want a file to cache, use:
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)

include('dbMirage_connect.php');

//print_r($_POST);
$Action="new_order";
$NumberOfRowsUsed ="";
if(isset($_GET["action"])){$Action=$_GET["action"];}
if(isset($_GET["order_id"])){$OrderID=$_GET["order_id"];}
if(isset($_POST["order_id"])){$OrderID=$_POST["order_id"];}
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
if(isset($_POST["requestby"])){$RequestedBy=$_POST["requestedby"];}else{$RequestedBy="";}
if(isset($_POST["cer"])){$CER=$_POST["cer"];}else{$CER="";}
if(isset($_POST["vender"])){$Vender=$_POST["vender"];}else{$Vender="";}
if(isset($_POST["needbydate"])){$NeedByDate=$_POST["needbydate"];}else{$NeedByDate="";}
if(isset($_POST["solevender"])){$SoleVender=$_POST["solevender"];}else{$SoleVender="";}
if(isset($_POST["fsize"])){$FilterSize=$_POST["fsize"];}else{$fsize="";}
if(isset($_POST["vender"])){$SuggestedVender=$_POST["vender"];}else{$SuggestedVender="";}
if(isset($_POST["requestedby"])){$RequestedBy=$_POST["requestby"];}else{$RequestedBy="";}
 if(isset($_POST["Quote"])){$Quote=$_POST["Quote"];}else{$Quote = "";}
?>
<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

<script>
   function HideColumn(){
   const table  = document.getElementById( 'filter_table' )
   const column = table.getElementsByClassName( 'hide' );
   const collection = document.getElementsByClassName("hide");
   for(i=0;i < collection.length;i++ ){
      collection[i].style.display = "none";
   }
}
</script>
<script>AbortController
function printme(){
      document.getElementById('tblControls').style.display="none";
      document.getElementById('btnPrint').style.display="none";
      document.getElementById('btnSave').style.display="none";
      document.getElementById('slctOrder').style.display="none";
      HideColumn();
      window.print();
   }
window.onafterprint = function(){
      document.getElementById('tblControls').style.display="block";
      document.getElementById('btnPrint').style.display="block";
      document.getElementById('btnSave').style.display="block";
      document.getElementById('slctOrder').style.display="block";
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

<table width="25%" id="tblControls" styole="display:inline-block;"><tr><td>
<button id='btnPrint' onclick="printme();" class="btn btn-success">Print order</button>
</td><td>
<button class="btn btn-success" name="save" value="save order" id="btnSave" onclick="document.getElementById('orderform').submit();">Save order</button>
</td>
<td>

<form action="<?php echo $_SERVER["SCRIPT_NAME"] ?>" method="post" style="margin-top:15px;">
<input type="hidden" name="action" value="getorder">
<select class="form-select bg-success text-white" id="slctOrder" style="height: 40px;border-radius:5px; style="margin-top:15px;" aria-label="Default select example" name="order_id" onchange="this.form.submit();">
  <option selected>View past orders...</option>
<?php
//$con = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
$sql = "select * from filter_orders ORDER BY order_date ASC;";
$lastorder = "";
    if ($result = $con->query($sql)) 
      {
         while ($row = $result->fetch_assoc()) 
         	{
               echo "<option value='".$row["_id"] ."'>".$row["order_date"] ."</option>";
            }
   	}
      ?>
</select></form></td></tr></table>

<?php



$FilterSize="";
$FilterCount="";
$Par=0;
$RecID="";

//CREATE NEW ORDER
 if(strcmp($Action, "new_order")==0)
   {
      echo "starting new order";
//GET COMPANY NAME FOR ORDER SHEET
$sql = "select company_name from misc LIMIT 1;";
    if ($result = $con->query($sql)) 
      {
         while ($row = $result->fetch_assoc()) 
         	{
      $CompanyName = $row["company_name"];
            }
      }
      $NumberOfRowsUsed=0; 
      $sql = "SELECT _id, filter_count,filter_size, filter_type, par, notes, date_updated from filters;";
      $result = mysqli_query($con, $sql) or die(mysqli_error());
      echo "num rows".mysqli_num_rows($result);
 ?>
<form name="orderform1" id="orderform" method="post" action="order.php">
<input type="hidden" name="action" value="saveorder">
<table align="left" width="100%" height="auto" cellpadding="1" frame="border">
  <caption style="border: solid black;"><?php echo $CompanyName ?> Material Order Form</caption>
  <tr>
    <td>Date:<input type="Date" name="date" value="<?php echo date("Y-m-d") ?>" style="width:120px;" /></td>
    <td>Requested Ordered by:<input type="text" readonly name="requestedby" style="width:140px;" /></tr>
    <tr ><td>Shop/Area:<input type="text" readonly name="shop" style="width:140px;" /></td>
    <td>Project or CER (Required for):<input type="text" readonly name="cer" style="width:140px;" /></tr>
  </tr><td>Suggested Vender:<input type="text" readonly name="vender" style="width:140px;" /></td><td></td>
  
  <tr>
    <td>Quote #<input type="text" readonly name="Quote" style="width:140px;" /></td>
    <td>Need By Date:<input type="date" name="needbydate"style="width:120px;" /></td>
  </tr>
  <tr>
    <td>Sole Vender<input type="radio" name="solevender" id="rdoSoleVenderYes" value="yes">
    <label for="rdoSoleVenderYes">Yes</label> 
    <input type="radio" name="solevender" id="rdoSoleVenderNo" value="no" checked><label for="rdoSoleVenderYes">No</label></td>
    <td></td>
  </tr>
</table>
<table width="100%">
 <tr><td>Stock Item  /  Repair Item  /  Replacement Item  /  New Item to Add to Stock</td</tr>
 </table>            
<?php echo "Action=". $Action; ?>
 <table width="100%" id="filter_table">
   <tr style="display: flex;flex-direction: row;">
   <th id="trRemove" class="hide">Remove</th>
    <th style="width:50px;">Qty</th>
    <th style="width:100px;">UOM</th>
    <th style="width:100px;">Part #</th>
    <th style="width:600px;">Description</th>
    <th style="width:200px;">Price/Unit</th>
    <th style="width:200px;">Total Price</th>
    </tr>
<?php

         while ($row = $result->fetch_assoc()) 
         {
         if($row["filter_count"] <= $row["par"] && $row["par"] != 0 && $row["filter_count"] != $row["par"])
         
            {
               $OrderAmount = $row["par"] - $row["filter_count"];
               $NumberOfRowsUsed=$NumberOfRowsUsed +1;
               ?>
                   <tr id="line<?php echo $NumberOfRowsUsed; ?>" style="display: flex;flex-direction: row;">
                         <td class="hide"><a href="#" onclick="document.getElementById('line<?php echo $NumberOfRowsUsed ?>').style.display='none';">remove</a></td>                       
                       <td style="width:50px;"><input type="text" readonly name="qty[]" id="amountbox<?php echo $NumberOfRowsUsed ?>" onchange="addTotal(<?php echo $NumberOfRowsUsed ?>);" value="<?php echo $OrderAmount ?>"></td>
                       <td><input type="text" readonly name="uom[]" size="5" value="each" style="width:100px;"></td>
                       <td><input type="text" readonly name="partnumber[]" style="width:100px;" size="20"></td>
                       <td><input type="text" readonly name="description[]" style="width:600px;" value="<?php echo $row["filter_size"] ." ". $row["filter_type"]; ?>"></td>
                       <td><input type="text" readonly name="price[]" style="width:200px;" id="pricebox<?php echo $NumberOfRowsUsed ?>" size="9" onchange="addTotal(<?php echo $NumberOfRowsUsed ?>);"></td>
                       <td><input type="text" readonly name="totalprice[]" style="width:200px;" id="totalpricebox<?php echo $NumberOfRowsUsed ?>" size="9"></td>
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
                       <td><input type="text" readonly name="orderamount[]" size="5"></td>
                       <td><input type="text" readonly name="uom[]" size="5"></td>
                       <td><input type="text" readonly name="partnumber[]" style="width:100px;" size="20"></td>
                       <td><input type="text" readonly name="description[]" style="width:550px;" value=""></td>
                       <td><input type="text" readonly name="price[]" size="9"></td>
                       <td><input type="text" readonly name="totalprice[]" size="9"></td>
                   </tr>
            <?php
                   $i++;
            }
            echo "</table>";
            echo "<table width='100%'><tr><td width='90%'>Total Cost of Materials to be Purchased (including Tax & Shipping)</td><td width='10%'></tr></table>";
            echo "<table width='100%'><tr><td width='30%'>Manager Approval:</td><td width='30%'>AD Approval:</td><td width='30%'>Rcv'd by Purch Admin::</td></tr>";
            //echo "<tr><td width='30%' height='80px'></td><td width='30%' height='80px'>AD Approval:</td><td width='30%' height='80px'>Rcv'd by Purch Admin::</td></tr></form>";
            if(isset($_POST["Quote"])){$Quote=$_POST["Quote"];}
            if(isset($_POST["needbydate"])){$needbydate=$_POST["needbydate"];}
            if(isset($_POST["solevender"])){$SoleVender=$_POST["solevender"];}

     }

    if(strcmp($Action,"getorder")==0)
   {
      $sql = "SELECT * FROM filter_orders WHERE _id=" . $OrderID . ";";
      $result = $con->query($sql);
      
      while($row = $result->fetch_assoc()) 
         {
         //$OArray = array("qty"=>$row["qty"], "uom"=>$row["uom"], "partnumber"=>$row["part_number"],"description"=>$row["description"],"priceperunit"=>$row["price_per_unit"],"totalprice"=>$row["total_price"]);
            //array_push($oArray,,$row["uom"],$row["part_number"],$row["description"],$row["price_per_unit"],$row["total_price"]);
           // array_push($bArray,$OArray);
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
//echo $qty." ". $uom. " ".$description . " ". $price. " ". $totalprice."<br>";

$obj =  json_decode($OrderArrayAsString, true);

         ?>
<table align="left" width="100%" height="auto" cellpadding="1" frame="border">
  <caption style="border: solid black;"><?php echo $CompanyName ?> Material Order Form</caption>
  <tr>
    <td>Date:<input type="text" readonly readonly name="date" style="width:140px;" size="30" value="<?php echo $OrderDate ?>"/></td>
    <td>Requested Ordered by:<input type="text" readonly name="requestedby" style="width:150px;" size="50" value="<?php echo $RequestedBy  ?>"/></tr>
    <tr ><td>Shop/Area:<input type="text" readonly style="width:150px;" name="shop" size="50" value="<?php echo $Shop?>"/></td>
    <td>Project or CER (Required for):<input type="text" readonly style="width:150px;"  name="cer" size="60" value="<?php echo $CER ?>"/></tr>
  </tr><td>Suggested Vender:<input type="text" readonly name="vender" value="<?php echo $Vender ?>" size="40" /></td>
  
  <tr>
    <td>Quote #<input type="text" readonly name="Quote" value="<?php echo $Quote ?>" size="40" /></td>
    <td>Need By Date:<input type="date" disabled name="needbydate" value="<?php echo $NeedByDate ?>" size="40" /></td>
  </tr>
  <tr>
    <td>Sole Vender <input type="radio" disabled name="solevender" id="rdoSoleVenderYes" value="yes" 
    <?php
    if(strcmp($SoleVender,"yes")==0)
    {
      echo " checked";
    }
    ?>
    ><label for="rdoSoleVenderYes">Yes</label> 
    <input type="radio" name="solevender" id="rdoSoleVenderNo" value="no" 
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
<table width="100%">
 <tr><td>Stock Item  /  Repair Item  /  Replacement Item  /  New Item to Add to Stock</td</tr>
 </table>
 <table width="100%" id="filter_table"><tr>
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
         echo "<td><input type'text' readonly name='qty[]' value='".$qty ."'></td>";
         echo "<td><input type'text' readonly name='uom[]' size='5' value='".$uom."'></td>";
          echo "<td><input type'text' readonly name='partnumber[]' style='width:200px;' size='20' value='".$partnumber ."'></td>";
          echo "<td><input type'text' readonly name='description[]' style='width:750px;' value='".$description."'></td>";
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
                       <td><input type="text" readonly name="partnumber[]" size="20"></td>
                       <td><input type="text" readonly name="description[]" style="width:550px;" value=""></td>
                       <td><input type="text" readonly name="price[]" size="9"></td>
                       <td><input type="text" readonly name="totalprice[]" size="9"></td>
                   </tr>
            <?php
                   $i++;
            }
            echo "</table>";
            echo "<table width='100%'><tr><td width='90%'>Total Cost of Materials to be Purchased (including Tax & Shipping)</td><td width='10%'></tr></table>";
            echo "<table width='100%'><tr><td width='30%'>Manager Approval:</td><td width='30%'>AD Approval:</td><td width='30%'>Rcv'd by Purch Admin:</td></tr>";
            echo "<tr><td width='30%' height='80px'></td><td width='30%' height='80px'>AD Approval:</td><td width='30%' height='80px'>Rcv'd by Purch Admin::</td></tr></form>";
   }
if(strcmp($Action, "saveorder")==0)
   {
      $TotalOrderArray=array();
      //print_r($_POST["qty"]);
      //print_r(array_keys($_POST["description"]));
      //echo "number of post vars=".count($_POST["description"])."<br>";
      ECHO "<BR>--------------------------<BR>";
      for($i=0; $i < count($_POST); $i++){
      //echo "qty=".$_POST["qty"][$i]." ";
      //echo "uom=".$_POST["uom"][$i]." ";
      //echo "Part number=".$_POST["partnumber"][$i]." ";
      //echo "description=".$_POST["description"][$i]." ";
      //echo "price each=".$_POST["price"][$i]." ";
      //echo "total price=".$_POST["totalprice"][$i]."<br>";
      //CREATE ASSOCIATIVE ARRAY

      $array = array("qty"=>$_POST["qty"][$i],"uom"=>$_POST["uom"][$i],"partnumber"=>$_POST["partnumber"][$i],"description"=>$_POST["description"][$i],"priceeach"=>$_POST["price"][$i],"totalprice"=>$_POST["totalprice"][$i]);

         array_push($TotalOrderArray,$array);
      }

      $OrderArrayAsString = json_encode($TotalOrderArray);
   echo "Length of ORDER ARRay ".strlen($OrderArrayAsString)."<br>";
      echo "this is json order".$OrderArrayAsString."<br>";
      $OrderDate = date("Y-m-d");
      echo "Order date=".$OrderDate;
      //echo "ordernumber is :".$OrderID."<br>";
            $sql = "INSERT INTO filter_orders (order_date, order_array, shoparea, requested_by, cer, sole_vender, suggested_vender, quote_number, need_by_date) VALUES ('".$OrderDate."', '".$OrderArrayAsString. "', '". $ShopArea."', '". $RequestedBy."', '". $CER."', '" .$SoleVender."', '". $SuggestedVender . "', '". $Quote."', '". $NeedByDate."');";
      echo "<br>".$sql."<br>";
      if ($con->query($sql) === TRUE) {
            echo "Filter order was saved.";
         } 
      else 
         {
            echo "Error saving filter order: " . $sql . "<br>" . $Con->error;
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