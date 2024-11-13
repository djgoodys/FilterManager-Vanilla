<?php
  //set headers to NOT cache a page
  header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
  header("Pragma: no-cache"); //HTTP 1.0
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

  //or, if you DO want a file to cache, use:
  header("Cache-Control: max-age=2592000"); //30days (60sec * 60min * 24hours * 30days)

include 'dbMirage_connect.php';
//$username="filterma_admin";
//$password="relays82!";//"Miragetower3!";
//$database="filterma_mirage";
//$server="www.filtermanager.net";
$query = "SELECT _id, filter_size, filter_type, filter_count, par, notes, date_updated FROM filters";
//try {
//    $conn = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully"; 
 //   } catch(PDOException $e) {    
 //   echo "Connection failed: " . $e->getMessage();
 //   }
$result = array();
$filterArray = array();
$response = array();

if($stmt = $con->prepare($query)){
//if($stmt = $conn->prepare($query)){
	$stmt->execute();
	//Bind the fetched data to $unitId and $unitName

	$stmt->bind_result($RecId,$FilterSize, $FilterType, $FilterCount, $Par, $Notes, $DateUpdated);
	//Fetch 1 row at a time					
	while($stmt->fetch()){
		$filterArray["_id"] = $RecId;
		$filterArray["filter_size"] = $FilterSize;
      $filterArray["filter_type"] = $FilterType;
      $filterArray["filter_count"] = $FilterCount;
	  $filterArray["notes"] = $Notes;
	  $filterArray["par"] = $Par;
	  $filterArray["last_updated"] = $DateUpdated;
		$result[]=$filterArray;
	}
	$stmt->close();
	$response["success"] = 1;
	$response["data"] = $result;
	
 
}else{
	//Some error while fetching data
	$response["success"] = 0;
	$response["message"] = mysqli_error($conn);
		
	
}
echo json_encode($response);