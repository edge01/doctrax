<?php
include("../config/connection.php");
include("../config/auth.php");
$data = json_decode(file_get_contents("php://input"));
$trackingNo = mysql_real_escape_string($data->trackingNo);
$username = mysql_real_escape_string($data->offname);
$stat ="Document Delivered";

// z


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$updated = date("Y-m-d h:i:s");
// $office = getUserIdByName($username);
$try = getL($trackingNo);
$sta = getStat($trackingNo);
$rec = getRec($trackingNo);

if ($try != "0"){
	if ($sta !== $stat && $rec !== $username){
		
$sql = "UPDATE doxs SET  status='".$stat."', date_time='".$updated."', receiver='".$username."' WHERE trackingNo='".$trackingNo."'";

$output = Array();

if ($conn->query($sql) === TRUE) {
	$output['success'] = true;
	$output['message'] = "Tracking Number has been updated.";
	$output['message1'] = $sta;
	$output['message2'] = $rec;

} else {
	$output['success'] = false;
	$output['message'] = $conn->error;
}
}
elseif ($sta === $stat && $rec === $username)
{
	$output['fail'] = true;
	$output['message'] = "This document was already received";
}

	elseif ($sta === $stat && $rec !== $username){
		
$sql1 = "UPDATE doxs SET  status='".$stat."', date_time='".$updated."', receiver='".$username."' WHERE trackingNo='".$trackingNo."'";

$output = Array();

if ($conn->query($sql1) === TRUE) {
	$output['success'] = true;
	$output['message'] = "Tracking Number has been updated1.";
	$output['message1'] = $sta;
	$output['message2'] = $rec;

} else {
	$output['success'] = false;
	$output['message'] = $conn->error;
}
}
elseif ($sta !== $stat && $rec === $username){
		
$sql2 = "UPDATE doxs SET  status='".$stat."', date_time='".$updated."', receiver='".$username."' WHERE trackingNo='".$trackingNo."'";

$output = Array();

if ($conn->query($sql2) === TRUE) {
	$output['success'] = true;
	$output['message'] = "Tracking Number has been updated2.";
	$output['message1'] = $sta;
	$output['message2'] = $rec;

} else {
	$output['success'] = false;
	$output['message'] = $conn->error;
}
}




}
else{
	$output['fail'] = true;
	$output['message'] = "No Tracking Number found!";
}
echo json_encode ($output);


$conn->close();

function getL($name){
		// $name=substr($name , 0 , 60);
	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `trackingNo` FROM `doxs` WHERE `trackingNo`= '".$name."'";
	$result = $conn->query($sql);
	$user = Array();
	
	if ($result ->num_rows > 0) {
	    while($row = $result -> fetch_assoc()) {
			$user[] = $row;
	    }
	} 
	


	if( count($user) > 0 ){
		//if name was found, return its id
		$result_id = $user[0]['trackingNo'];	
	} 
	else{
		$result_id = "0";
	}

	return $result_id;
}	

function getStat($name){

	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `status` FROM `doxs` where `trackingNo`='".$name."'";
	$result = $conn->query($sql);
	$user = Array();
	
	if ($result ->num_rows > 0) {
	    while($row = $result -> fetch_assoc()) {
			$user[] = $row;
	    }
	} 
	


	if( count($user) > 0 ){
		//if name was found, return its id
		$result_id = $user[0]['status'];	
	} 
	else{
		$result_id = "0";
	}

	return $result_id;
}	


function getRec($name){
	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `receiver` FROM `doxs` where  `trackingNo`='".$name."' ";
	$result = $conn->query($sql);
	$user = Array();
	
	if ($result ->num_rows > 0) {
	    while($row = $result -> fetch_assoc()) {
			$user[] = $row;
	    }
	} 
	


	if( count($user) > 0 ){
		//if name was found, return its id
		$result_id = $user[0]['receiver'];	
	} 
	else{
		$result_id = "0";
	}

	return $result_id;
}	





?>
