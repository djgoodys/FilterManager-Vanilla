<?php
include 'dbMirage_connect.php';
$response = array();
// echo var_dump($_POST);
//eecho var_dump($_GET);
if(isset($_GET['_id'])){
    $RecID = $_GET['_id'];
    $UnitName = $_GET['unit_name'];
    $Location = $_GET['location'];
    $AreaServed = $_GET['area_served'];
    $FilterSize = $_GET['filter_size'];
    //$FilterType = $_GET['filter_type'];
    $FiltersDue = $_GET['filters_due'];
    $Rotation = $_GET['rotation'];
    $Belts = $_GET['belts'];
    $Notes = $_GET['notes'];
    $AssignedTo = $_GET['assigned_to'];
}
    //echo "Unit name=" . $_POST['unit_name'];//&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){

//if(isset($_POST['_id'])&&isset($_POST['unit_name'])&&isset($_POST['location'])&&isset($_POST['area_served'])&&isset($_POST['filter_size'])&&isset($_POST['filters_due'])&&isset($_POST['rotation'])){
if(isset($_POST['_id'])){
    $RecID = $_POST['_id'];
    $UnitName = $_POST['unit_name'];
    $Location = $_POST['location'];
    $AreaServed = $_POST['area_served'];
    $FilterSize = $_POST['filter_size'];
    $FilterType = $_POST['filter_type'];
    $FiltersDue = $_POST['filters_due'];
    $Rotation = $_POST['rotation'];
    $Belts = $_POST['belts'];
    $Notes = $_POST['notes'];
    $AssignedTo = $_POST['assigned_to'];
}

	//Query to update a movie
	//$query = "UPDATE equipment SET unit_name=?,area_served=?,filter_size=?,filters_due=?,location=?,filter_rotation=?,belts=?,notes=? WHERE _id='".$UnitName."'";
	$query = "UPDATE equipment SET unit_name='" .$UnitName ."',area_served='".$AreaServed. "',filter_size='".$FilterSize."',filters_due='".$FiltersDue."',filter_type='".$FilterType."',location='".$Location."',filter_rotation='".$Rotation."',belts='".$Belts."',notes='".$Notes."', assigned_to='".$AssignedTo."' WHERE _id=".$RecID;
	if (mysqli_query($con, $query)) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . mysqli_error($con);
}

?>