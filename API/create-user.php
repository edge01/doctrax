<?php
include("../config/connection.php");
include("../config/auth.php");
$data = json_decode(file_get_contents("php://input"));
$username = mysql_real_escape_string($data->uname);
$password = md5(mysql_real_escape_string($data->pword));
$office = mysql_real_escape_string($data->ofice);
$sname=mysql_real_escape_string($data->user);
$aname="admin";
function getUserAdmin($name){
		$name=substr($name , 0 , 60);
	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `username` FROM `users` WHERE `username`= '".$name."'";
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
		$name=substr($name , 0 , 60);
	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `username` FROM `users` WHERE `username`= '".$name."'";
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


function getUserIdByUsername($username){
		// $name=substr($name , 0 , 60);
	global $conn;

	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	} 

	$sql = "SELECT `user_id` FROM `users` WHERE `username`= '".$username."'";
	$result = $conn->query($sql);
	$user = Array();
	
	if ($result ->num_rows > 0) {
	    while($row = $result -> fetch_assoc()) {
			$user[] = $row;
	    }
	} 
	


	if( count($user) > 0 ){
		//if name was found, return its id
		$result_id = $user[0]['user_id'];	
	} 

	return $result_id;
}	


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$checkUsername = getUserIdByUsername($username);
$checkAdmin = getUser($sname);
$check = getUserAdmin($aname);
if (stripos($checkAdmin, "admin") !== FALSE){

	if(!$checkUsername){

	$sql = "INSERT INTO `users`(`username`, `password`, `office`)VALUES('".$username."','".$password."','".$office."')";
	$output = Array();

	if ($conn->query($sql) === TRUE) {

		$last_id = $conn->insert_id;

		$access_token = md5("thisisasecrettoken" . $password);

		$update_sql = "UPDATE users SET access_token='" . $access_token . "' WHERE user_id='" . $last_id . "'";

		if ($conn->query($update_sql) === TRUE) {
			//echo 'updated';
		}

		$output['success'] = true;
		$output['message'] = "User has been added.";
	} else {
		$output['success'] = false;
		$output['message'] = $conn->error;
	}
} else {
	$output['message'] = 'Existing User';
	$output['success1'] = true;
	// $output['message_code'] = '2'; 
}

} else {
	$output['message'] = 'User restricted';
	// $output['message'] = $conn->error;
	$output['success2'] = true;
}
echo json_encode ($output);

$conn->close();
?>