<?php
include("../config/connection.php");
include("../config/auth.php");
$data = json_decode(file_get_contents("php://input"));
$docu_type =mysql_real_escape_string($data->doctype);
$sender =mysql_real_escape_string($data->sender);
$receiver =mysql_real_escape_string($data->receiver);
$name = mysql_real_escape_string($data->receiver);
$name1 = mysql_real_escape_string($data->sender);
$status ="Delivery on progress";
$created = date("ymdhis");
$datecreate = date("Y-m-d h:i:s");

$trackingNo = $created;
	$sen ="I-Team";
	$rec = getUser1($name1);
	$sub = "Tracking Number";
	$mes = $trackingNo;
	$sub1 = $docu_type;
	$mes1 = "You will receive a ".$docu_type." from  ".$sender." here's the tracking number ".$trackingNo;
	$user = getUser($name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$check = getTry($name);
if($check){
$sql = "INSERT INTO `doxs`(`trackingNo`, `docu_type`, `sender`, `receiver`,`status`,`date_time`)VALUES('".$trackingNo."','".$docu_type."','".$sender."','".$receiver."','".$status."','".$datecreate."')";
//sender's mail
	$sql1 = "INSERT INTO `mail`(`sender`, `receiver`, `office`, `subject`, `message`)VALUES('".$sen."','".$rec."','".$sender."','".$sub."','".$mes."')";
	//receiver's mail
	$sql2= "INSERT INTO `mail`(`sender`, `receiver`, `office`, `subject`, `message`)VALUES('".$sender."','".$user."','".$receiver."','".$sub1."','".$mes1."')";
	
$output = Array();

if ($conn->query($sql) === TRUE) {
	$output['success'] = true;
	$output['barid'] = $trackingNo;
	$output['message'] = "Document has been added.";
} else {
	$output['success'] = false;
	$output['message'] = $conn->error;
}
$conn->query($sql1);

$conn->query($sql2);
}
else {
	$output['fail'] = true;
	$output['message'] = "No user found in this office.";
}


echo json_encode ($output);

$conn->close();

function getTry($name){

	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `username` FROM `users` WHERE `office`= '".$name."'";
	$result = $conn->query($sql);
	$user = Array();
	
	if ($result ->num_rows > 0) {
	    while($row = $result -> fetch_assoc()) {
			$user[] = $row;
	    }
	} 
	


	if( count($user) > 0 ){
		//if name was found, return its id
		$result_id = $user[0]['username'];	
	} 

	return $result_id;
}
function getUser($name){

	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `username` FROM `users` WHERE `office`= '".$name."'";
	$result = $conn->query($sql);
	$user = Array();
	
	if ($result ->num_rows > 0) {
	    while($row = $result -> fetch_assoc()) {
			$user[] = $row;
	    }
	} 
	


	if( count($user) > 0 ){
		//if name was found, return its id
		$result_id = $user[0]['username'];	
	} 

	return $result_id;
}

function getUser1($name1){

	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `username` FROM `users` WHERE `office`= '".$name1."'";
	$result = $conn->query($sql);
	$user = Array();
	
	if ($result ->num_rows > 0) {
	    while($row = $result -> fetch_assoc()) {
			$user[] = $row;
	    }
	} 
	


	if( count($user) > 0 ){
		//if name was found, return its id
		$result_id = $user[0]['username'];	
	} 

	return $result_id;
}
	

?>

