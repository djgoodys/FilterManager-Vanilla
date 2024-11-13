<?php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

  //or, if you DO want a file to cache, use:
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)

include('dbMirage_connect.php');
//print_r($_POST);
$Action="";
if(isset($_POST["action"]))
   {
      $Action=($_POST["action"]);
   }
?>
<html>
<head>
<style>
table, tr{ 
   border: 1px solid;
   padding: 0;
   vertical-align: middle;
   text-align: left;
   height:40px;
}
table, td, th{
border-collapse:collapse;
}
input[type=text]
{
  height:40px;
  font-size:inherit;
}
body {
background-color: white;
color:black;
}
input[type=text]
   {
      width:58px;
      font-weight:bold;
   }
</style>
<script>
function printme(){
      document.getElementById('tblControls').style.display="none";
      document.getElementById('btnPrint').style.display="none";
      document.getElementById('btnSave').style.display="none";
      document.getElementById('slctOrder').style.display="none";
      window.print();
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
<table width="25%" id="tblControls"><tr><td>
<form action="order.php" method="POST">
<button id='btnPrint' onclick="printme();">Print order</button>
</form>
</td><td>
<button name="save" value="save order" id="btnSave" onclick="document.getElementById('orderform').submit();">save order</button>
</td>
<?php
$sql = "select order_number, order_date from orders;";
$lastorder = "";
echo "<td><FORM action='order.php' method='post'>
<input type='hidden' name='action' value='getorder'>
<SELECT name='ordernumb' id='slctOrder' onchange='this.form.submit();'><option>past orders</option>";
    if ($result = $con->query($sql)) 
      {
         while ($row = $result->fetch_assoc()) 
         	{
         		
         		if(strcmp($row['order_number'], $lastorder)!=0)
         		{
               echo "<OPTION name='ordernum' value='".$row['order_number'] ."'>".$row['order_date'] ."</OPTION>";
               $lastorder = $row['order_number'];
               }
            }
   	}
      echo "</SELECT></form></td></tr></table>";
$FilterSize="";
$FilterCount="";
$Par=0;
$RecID="";
$Task="";
if(isset($_GET["task"])){$Task=$_GET["task"];}
if(isset($_POST["action"])){$Action=$_POST["action"];}
if(isset($_POST["fsize"])){$FilterSize=$_POST["fsize"];
}else{
$fsize="";
}

//CREATE NEW ORDER
 if($Action != "getorder")
   {
   $NumberOfRowsUsed=0;
      $sql = "SELECT _id, filter_count,filter_size, par, notes, date_updated from filters;";
      $result = mysqli_query($con, $sql) or die(mysqli_error());
         //while($row = mysqli_fetch_assoc($result))
 ?>
<form name="orderform1" id="orderform" method="post" action="order.php">
<input type="hidden" name="action" value="saveorder">
<table align="left" width="100%" height="auto" cellpadding="1" frame="border">
  <caption style="border: solid black;">Mirage Engineering Operations Material Order Form</caption>
  <tr>
    <td>Date:<input type="text" name="date" style="width:120px;" /></td>
    <td>Requested Ordered by:<input type="text" name="requestedby" style="width:140px;" /></tr>
    <tr ><td>Shop/Area:<input type="text" name="shop" style="width:140px;" /></td>
    <td>Project or CER (Required for):<input type="text" name="cer" style="width:140px;" /></tr>
  </tr><td>Suggested Vender:<input type="text" name="vender" style="width:140px;" /></td><td></td>
  
  <tr>
    <td>Qoute #<input type="text" name="qoute" style="width:140px;" /></td>
    <td>Need By Date:<input type="text" name="needbydate"style="width:120px;" /></td>
  </tr>
  <tr>
    <td>Sole Vender<input type="radio" name="solevender" id="rdoSoleVenderYes" value="yes" 
    <?php
    if(strcmp($row["solevender"],"yes")==0)
    {
      echo " checked";
    }
    ?>
    ><label for="rdoSoleVenderYes">Yes</label> <input type="radio" name="solevender" id="rdoSoleVenderNo" value="no" 
     <?php
    if(strcmp($row["solevender"],"no")==0)
    {
      echo " checked";
    }
    ?>
    ><label for="rdoSoleVenderYes">No</label></td>
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

         while ($row = $result->fetch_assoc()) 
         {
         if($row["filter_count"] <= $row["par"] && $row["par"] != 0 && $row["filter_count"] != $row["par"])
         
            {
               $OrderAmount = $row["par"] - $row["filter_count"];
               $NumberOfRowsUsed=$NumberOfRowsUsed +1;
               ?>
                   <tr>
                       <td><input type="text" name="qty[]" id="amountbox<?php echo $NumberOfRowsUsed ?>" onchange="addTotal(<?php echo $NumberOfRowsUsed ?>);" value="<?php echo $OrderAmount ?>"></td>
                       <td><input type="text" name="uom[]" size="5" value="each"></td>
                       <td><input type="text" name="partnumber[]" style="width:100px;" size="20"></td>
                       <td><input type="text" name="description[]" style="width:200px;" value="<?php echo $row["filter_size"] ?>"></td>
                       <td><input type="text" name="price[]" id="pricebox<?php echo $NumberOfRowsUsed ?>" size="9" onchange="addTotal(<?php echo $NumberOfRowsUsed ?>);"></td>
                       <td><input type="text" name="totalprice[]" id="totalpricebox<?php echo $NumberOfRowsUsed ?>" size="9"></td>
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
                       <td><input type="text" name"orderamount[]" size="5"></td>
                       <td><input type="text" name="uom[]" size="5"></td>
                       <td><input type="text" name="partnumber[]" style="width:100px;" size="20"></td>
                       <td><input type="text" name="description[]" style="width:550px;" value=""></td>
                       <td><input type="text" name="price[]" size="9"></td>
                       <td><input type="text" name="totalprice[]" size="9"></td>
                   </tr>
            <?php
                   $i++;
            }
            echo "</table>";
            echo "<table width='100%'><tr><td width='90%'>Total Cost of Materials to be Purchased (including Tax & Shipping)</td><td width='10%'></tr></table>";
            echo "<table width='100%'><tr><td width='30%'>Manager Approval:</td><td width='30%'>AD Approval:</td><td width='30%'>Rcv'd by Purch Admin::</td></tr>";
            //echo "<tr><td width='30%' height='80px'></td><td width='30%' height='80px'>AD Approval:</td><td width='30%' height='80px'>Rcv'd by Purch Admin::</td></tr></form>";
            if(isset($_POST["qoute"])){$quote=$_POST["qoute"];}
            if(isset($_POST["needbydate"])){$needbydate=$_POST["needbydate"];}
            if(isset($_POST["solevender"])){$solevender=$_POST["solevender"];}
            
     }

    if(strcmp($Action,"getorder")==0 || strcmp($Task,"pastorders")==0)
   {
      $OrderNumber = $_POST["ordernumb"];
      $sql = "SELECT * FROM orders WHERE order_number=" . $OrderNumber . ";";
      $result = $con->query($sql);
      $OArray=array();
      $bArray=array();
      
      while($row = $result->fetch_assoc()) 
         {
         $OArray = array("qty"=>$row["qty"], "uom"=>$row["uom"], "partnumber"=>$row["part_number"],"description"=>$row["description"],"priceperunit"=>$row["price_per_unit"],"totalprice"=>$row["total_price"]);
            //array_push($oArray,,$row["uom"],$row["part_number"],$row["description"],$row["price_per_unit"],$row["total_price"]);
            array_push($bArray,$OArray);
            $RequestedBy = $row["requested_by"];
            $OrderDate = $row["order_date"];
            $Cer = $row["cer"];
            $Shop = $row["shoparea"];
            $Vender = $row["suggested_vender"];
            $Qoute = $row["quote_number"];
            $NeedByDate = $row["need_by_date"];
            $SoleVender = $row["sole_vender"];
         }
         
         //print_r($bArray);
         ?>
<form name="orderform1" id="orderform" method="post" action="order.php">
<input type="hidden" name="action" value="saveorder">
<table align="left" width="100%" height="auto" cellpadding="1" frame="border">
  <caption style="border: solid black;">Mirage Engineering Operations Material Order Form</caption>
  <tr>
    <td>Date:<input type="text" name="date" style="width:140px;" size="30" value="<?php echo $OrderDate ?>"/></td>
    <td>Requested Ordered by:<input type="text" name="requestedby" style="width:150px;" size="50" value="<?php echo $RequestedBy  ?>"/></tr>
    <tr ><td>Shop/Area:<input type="text" style="width:150px;" name="shop" size="50" value="<?php echo $Shop?>"/></td>
    <td>Project or CER (Required for):<input type="text" style="width:150px;"  name="cer" size="60" value="<?php echo $Cer ?>"/></tr>
  </tr><td>Suggested Vender:<input type="text" name="vender" value="<?php echo $Vender ?>" size="40" /></td>
  
  <tr>
    <td>Qoute #<input type="text" name="qoute" value="<?php echo $Qoute ?>" size="40" /></td>
    <td>Need By Date:<input type="text" name="needbydate" value="<?php echo $NeedByDate ?>" size="40" /></td>
  </tr>
  <tr>
    <td>Sole Vender <input type="radio" name="solevender" id="rdoSoleVenderYes" value="yes" 
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
foreach($bArray as $x => $x_value) {
               $NumberOfRowsUsed=$NumberOfRowsUsed+1;
                   echo "<tr>";
                       echo "<td><input type='text' name='qty[]' value='".$x_value["qty"] ."'></td>";
                       echo "<td><input type='text' name='uom[]' size='5' value='".$x_value["uom"] ."'></td>";
                       
                       echo "<td><input type='text' name='partnumber[]' style='width:200px;' size='20' value='".$x_value["partnumber"] ."'></td>";
                      
                       echo "<td><input type='text' name='description[]' style='width:750px;' value='".$x_value["description"]."'></td>";
                       echo "<td><input type='text' name='price[]' size='9' value='".$x_value["priceperunit"]."'></td>";
                       echo "<td><input type='text' name='totalprice[]' value='".$x_value["totalprice"]."'></td>";
                      
                           echo "</tr>";
                        

               
            }
            
            $LinesToPrint =15-$NumberOfRowsUsed;
            $i=0;
            While($i <= $LinesToPrint)
            {
            ?>
                 <tr>
                       <td><input type="text" name"orderamount[]" size="5"></td>
                       <td><input type="text" name="uom[]" size="5"></td>
                       <td><input type="text" name="partnumber[]" size="20"></td>
                       <td><input type="text" name="description[]" style="width:550px;" value=""></td>
                       <td><input type="text" name="price[]" size="9"></td>
                       <td><input type="text" name="totalprice[]" size="9"></td>
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
      echo "saving order................";
      $ordernum = date("YmdHis");
      echo date("YmdHis");
      echo "ordernumber is :".$ordernum."<br>";
      if(isset($_POST["qty"]))
         {
            $uom= $_POST['uom'];
            $partnum= $_POST['partnumber'];
            $description= $_POST['description'];
            $price= $_POST['price'];
            $totalprice= $_POST['totalprice'];
            $orderdate= $_POST['date'];
            $qty=$_POST["qty"];
            $shoparea=$_POST["shop"]; 
            $requestedby=$_POST["requestedby"]; 
            $cer=$_POST["cer"];
            $vender=$_POST["vender"];
            //$quote=$_POST["qoute"];
            //$needbydate=$_POST["needbydate"];
            //$solevender=$_POST["solevender"];
            //var_dump($order);
             if(isset($_POST["qoute"])){$quote=$_POST["qoute"];}
            if(isset($_POST["needbydate"])){$needbydate=$_POST["needbydate"];}
            if(isset($_POST["solevender"])){$solevender=$_POST["solevender"];}
            foreach( $order as $key => $n ) 
               {
                  //echo "qty=".$n." uom=".$uom[$key]." P#=".$partnum[$key]." description=".$description[$key]."price=".$price[$key]." total price=".$totalprice[$key]."<br>";
                  
                  SaveLine($con,$orderdate,$qty[$key],$uom[$key],$partnum[$key],$description[$key],$price[$key],$totalprice[$key],$ordernum, $shoparea, $requestedby, $cer, $vender, $qoute, $needbydate, $solevender);
               }
         }
   }
   
   
   function SaveLine($Con,$orderdate,  $qty, $uom, $part_number,$description, $price_per_unit, $total_price, $order_number, $shoparea, $requestedby, $cer, $suggestedvender, $qoute, $needbydate, $solevender)
   {
      //0ioecho "date=".$order_date." description=". $description." qty=". $qty." uom=". $uom." p/n=".$part_number." price=". $price_per_unit." total price=". $total_price." order#=". $order_number." shop=". $shoparea.//////order_date, description,               qty,       uom,       part_number,            price_per_unit,            total_price,        order_number,           shoparea,        requested_by,        cer,            sole_vender,        suggested_vender,         quote_number,      need_by_date
      $sql = "INSERT INTO orders (order_date, description, qty, uom, part_number, price_per_unit, total_price, order_number, shoparea, requested_by, cer, sole_vender, suggested_vender, quote_number, need_by_date) VALUES ('".$orderdate."', '".$description."', '". $qty."', '". $uom."', '". $part_number. "', '". $price_per_unit. "', '". $total_price."', '". $order_number."', '". $shoparea."', '". $requestedby."', '". $cer."', '" .$solevender."', '". $suggestedvender . "', '". $qoute."', '". $needbydate."');";
      
      if ($Con->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $Con->error;
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