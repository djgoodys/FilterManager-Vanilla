<?php
if(session_id() == '' || !isset($_SESSION) || session_status() == PHP_SESSION_NONE) {
   //echo "no session";
     session_start();
   }
   if(!isset($_SESSION["backup_folder"]))
   {
      header('Location: '."start.php");
   }
function getNextDueDate($FRotation){   
$monthsToAdd = $FRotation;
$currentDate = new DateTime();
$currentDate->add(new DateInterval('P' . $monthsToAdd . 'M'));
//$currentDate->format('Y-m-d');
$NewDueDate = $currentDate->format('Y-m-d');
return $NewDueDate;
  }

function getUniqueID($TableName){
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
$objArray = $data[$TableName];
$highestId = 0;
foreach ($objArray as $item) {
    $id = (int) $item["_id"]; // Ensure it's an integer
    $highestId = max($highestId, $id);
}
$newId = $highestId + 1;
return $newId;
}

function searchInJSONFile($TableName, $FieldName, $SearchWords) {
    $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
    $data = json_decode($jsonString, true);
    $tableArray = $data[$TableName]; // Adjust if the table is nested
    foreach ($tableArray as $object) {
        if (strpos(strtolower($object[$FieldName]), strtolower($SearchWords)) !== false) {
            // Found! Return the entire object
            return true;
        }
    }

    // Not found
    return false;
}

function arrayFromDataJson($TableName, $FieldName){
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
$arrayToMake = [];
if(is_array($data[$TableName])){
foreach ($data[$TableName] as $obj) {
    $arrayToMake[] = $obj[$FieldName];
}
return $arrayToMake;
}
}
function Search($TableName, $searchWords){
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
$row = array();
foreach ($data[$TableName] as $key => $value) {
    foreach ($value as $k => $v) {
        if (stripos($v, $searchWords) !== false) {
            $row[] = $value;
            break;
        }
    }
}
return $row;
// Output the results
//print_r($row);

}

function Search2($tablename, $fields, $searchWord){
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
$searchResults = [];
foreach ($data[$fields] as $filterObject) {
    // Check for matches in the specified fields
    if (
        strpos($filterObject["_id"], $searchWord) !== false ||
        strpos($filterObject["filter_size"], $searchWord) !== false ||
        strpos($filterObject["filter_type"], $searchWord) !== false ||
        strpos($filterObject["par"], $searchWord) !== false ||
        strpos($filterObject["notes"], $searchWord) !== false ||
        strpos($filterObject["pn"], $searchWord) !== false ||
        strpos($filterObject["storage"], $searchWord) !== false
    ) {
        // Add the matching object to the results array
        $searchResults[] = $filterObject;
    }
}
return $searchResults;
}


function getData($TableName){
$jsonData = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonData, true);
if (isset($data[$TableName])) {
    $targetArray = $data[$TableName]; // Create the array from the target data    
    return $targetArray;
} else {
    echo "Key $TableName not found in the JSON data.\n";
}
}

 
    function str_contain($searchin, $word){
        $contains = false;
        //echo "strpos=".strpos($searchin, $word);
        if(strpos($searchin, $word)){$contains = true;}
        return $contains;
    }


function ExtractFilters($FiltersUsed, $FilterType) 
{                             
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
            $jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
            $data = json_decode($jsonString, true);
            foreach ($data['filters'] as &$object) {
               if ($object['filter_size'] == $filterSizeSent && $object['filter_type'] == $FilterType) {
               }
            }
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
        //--------FOR Second FILTER SET----------------------
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
//echo "starting UpdateInventory<br> UpdateInventory values passed=".$filterSize." ". $Ftype." ". $QtyUsed."<br>";
$jsonString = file_get_contents('sites/'.$_SESSION["backup_folder"].'/data.json');
$data = json_decode($jsonString, true);
foreach ($data['filters'] as &$object) {
    if ($object['filter_size'] == $filterSize && $object['filter_type'] == $Ftype) {
        //echo "was found with id=".$object['_id']." count before =".$object['filter_count']."<br>";
        $object['filter_count'] -= $QtyUsed;
    }
}
$jsonString = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('sites/'.$_SESSION["backup_folder"].'/data.json', $jsonString);
}

 ?>
