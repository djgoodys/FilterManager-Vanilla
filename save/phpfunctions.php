<?php
include 'dbMirage_connect.php';
//CREATE ARRAY OF ALL FILTER SIZES
$sql = "select filter_size from filters order by substring(filter_size, 1, 2)";
$arFilters = array();
$con = mysqli_connect($_SESSION["server_name"],$_SESSION["database_username"],$_SESSION["database_password"],$_SESSION["database_name"]);
 if ($result = $con->query($sql)) 
     {
          while ($row = $result->fetch_assoc()) 
             {
               array_push($arFilters,$row["filter_size"]); 
             }
     }

    function str_contain($searchin, $word){
        $contains = false;
        echo "strpos=".strpos($searchin, $word);
        if(strpos($searchin, $word)){$contains = true;}
        return $contains;
    }


function ExtractFilters($FiltersUsed, $FilterType) 
{
//echo "FROM EXTRACTFILTERS filter type=". gettype($FilterType)."<BR>";
                              
   $f=$FiltersUsed;
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
          $filterSizeSent = substr($f, $pos+1);  
          $filtersQtyUsed = substr($f,1,$pos-1);
          $x=0;

          UpdateInventory($filterSizeSent, $FilterType, $filtersQtyUsed);

       }
   
    if($numOfSets == 2)
       {
          $lastpos = strrpos($f, ")", 0);
          $pos = strpos($f, "(");
          $filt1 = substr($f, $pos-strlen($f),$lastpos-2 );
          //echo "set1=" . $filt1. "<br>";
          $filt2 = substr(strrchr($f, "("), 0);
          //echo "set2=" . $filt2. "<br>";
          //for First filter set
          $pos = strpos($filt1, ")");
          $filterSizeSent = substr($filt1, $pos+1);  
          $filtersQtyUsed = substr($filt1,1,$pos-1);
 
         UpdateInventory($filterSizeSent, $FilterType, $filtersQtyUsed);
         //echo "set1 updated ". $filterSizeSent.":". $QtyUpdated."<br>";                       
          //for Second filter set
          $pos = strrpos($filt2, ")");
          $filterSizeSent = substr($filt2, $pos+1);  
          $filtersQtyUsed = substr($filt2,1,$pos-1);
          //echo "set2 size=".$filterSizeSent." qt2=".$filtersQtyUsed."<br>";
          $filterSizeSent = mb_convert_encoding($filterSizeSent, "ASCII");
          $filterSizeSent = str_replace ("?", "x", $filterSizeSent);
  
         UpdateInventory($filterSizeSent, $FilterType, $filtersQtyUsed);
       }
}
function ExtractFilterSize($f){
    $arFilterSets = array();
    //$arFilter = array();                  
    //echo "number of filter sets=".substr_count($f, '(')."<br>";
    $numOfSets= substr_count($f,'(');
    //echo "f sent=" . $f . "<br>";
    $pos = strpos($f, ")");
    //echo "found at=". $pos ."<br>";
    $filterSize = substr($f, $pos+1);  
    $filtersQty = substr($f,1,$pos-1);
    //echo "size sent=".$filterSizeSent . " qtySent=". $filtersQtyUsed. "<br>";
    //echo "filterS=".$f."<br>";
 if($numOfSets == 1)
    {
       $pos = strpos($f, ")");
       //echo "found at=". $pos ."<br>";
       $filterSize = substr($f, $pos+1);  
       $filtersQty = substr($f,1,$pos-1);
        //echo "SET 1 filterSize =". $filterSize. " SET1 filtersQty=" . $filtersQty."<BR>";
        $arFilterArray = array("size"=>$filterSize, "count"=>$filtersQty);
        //print_r($arFilter);
        array_push($arFilterSets, $arFilterArray);

    }
 
 if($numOfSets == 2)
    {
        $firstpos = strpos($f, ")", 0);
        $string = substr($f, $firstpos + 1);
        $filt2 = strrchr($string,"(");
        //echo "filt2=".$filt2."<br>";
        $filt1 = substr($f, 0, (strlen($f)-strlen($filt2)));
        //echo "filt1=".$filt1."<br>";
        $firstpos = strpos($string, "(", 0);

       //echo "string2=".substr($string, $firstpos)."<br>";
       //$filt1 = substr($f, $lastpos +1 , $pos);
       //echo "set1=" . $filt1. "<br>";
       $filt2 = substr(strrchr($f, "("), 0);
       //echo "set2=" . $filt2. "<br>";
       //-------FOR FIRST FILTER SET-------------------
       $pos = strpos($filt1, ")");
       $filterSizeSent1 = substr($filt1, $pos+1);  
       $filtersQtyUsed1 = substr($filt1,1,$pos-1);
       //echo "SET1 filterSizeSent =". $filterSizeSent1. "<br>SET1 count=" . $filtersQtyUsed1."<BR>"; 
       $arFilterArray = array("size"=>$filterSizeSent1, "count"=>$filtersQtyUsed1);
       //print_r($arFilter);
       array_push($arFilterSets, $arFilterArray);
        //--------FOR SECONCE FILTER SET----------------------
        $pos = strpos($filt2, ")");
       $filterSizeSent2 = substr($filt2, $pos+1);  
       $filtersQtyUsed2 = substr($filt2,1,$pos-1);
       $arFilterArray= array("size"=>$filterSizeSent2, "count"=>$filtersQtyUsed2);
       //echo "SET2 filterSizeSent =". $filterSizeSent2. "<br>SET2 count=" . $filtersQtyUsed2."<BR>"; 
       array_push($arFilterSets, $arFilterArray);
       //print_r($arFilterSets);
       //echo "<br>".print_r(array_keys($arFilterSets))."<br>";
    }
    return $arFilterSets;
 }

function UpdateInventory($filterSize, $Ftype, $QtyUsed)
{
//echo "fsize ".$filterSize. " type ".$Ftype. " qtyused ".$QtyUsed;
 Global $con;

   $query = "UPDATE filters SET filter_count =  (filter_count - ".$QtyUsed. ") WHERE filter_size='" . $filterSize . "' AND filter_type = '". $Ftype ."';";
if ($con->query($query) === TRUE) {
  //echo "Record updated successfully";
} else {
  echo "<div style='background-color:red;color:white;'>Error updating record: " . $conn->error ."</div>";
}
}

 ?>
